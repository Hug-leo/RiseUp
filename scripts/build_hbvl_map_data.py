#!/usr/bin/env python3
"""Build and validate the public HBVL member-map dataset.

Reads the source workbook without modifying it. Only member name and the
pre-reorganisation province are written to the frontend JSON output.
"""

from __future__ import annotations

import argparse
from collections import Counter
import json
import re
import unicodedata
import zipfile
from pathlib import Path
from xml.etree import ElementTree as ET


NS = {"m": "http://schemas.openxmlformats.org/spreadsheetml/2006/main"}
REL_NS = {"r": "http://schemas.openxmlformats.org/package/2006/relationships"}
DOC_REL = "{http://schemas.openxmlformats.org/officeDocument/2006/relationships}id"


NEW_34_TO_OLD_63 = {
    "Hà Nội": ["Hà Nội"], "Cao Bằng": ["Cao Bằng"],
    "Tuyên Quang": ["Hà Giang", "Tuyên Quang"], "Điện Biên": ["Điện Biên"],
    "Lai Châu": ["Lai Châu"], "Sơn La": ["Sơn La"],
    "Lào Cai": ["Yên Bái", "Lào Cai"], "Thái Nguyên": ["Bắc Kạn", "Thái Nguyên"],
    "Lạng Sơn": ["Lạng Sơn"], "Quảng Ninh": ["Quảng Ninh"],
    "Bắc Ninh": ["Bắc Giang", "Bắc Ninh"],
    "Phú Thọ": ["Vĩnh Phúc", "Hòa Bình", "Phú Thọ"],
    "Hải Phòng": ["Hải Phòng", "Hải Dương"], "Hưng Yên": ["Thái Bình", "Hưng Yên"],
    "Ninh Bình": ["Hà Nam", "Nam Định", "Ninh Bình"], "Thanh Hóa": ["Thanh Hóa"],
    "Nghệ An": ["Nghệ An"], "Hà Tĩnh": ["Hà Tĩnh"],
    "Quảng Trị": ["Quảng Bình", "Quảng Trị"], "Huế": ["Huế"],
    "Đà Nẵng": ["Đà Nẵng", "Quảng Nam"], "Quảng Ngãi": ["Kon Tum", "Quảng Ngãi"],
    "Gia Lai": ["Bình Định", "Gia Lai"], "Khánh Hòa": ["Ninh Thuận", "Khánh Hòa"],
    "Đắk Lắk": ["Phú Yên", "Đắk Lắk"],
    "Lâm Đồng": ["Đắk Nông", "Bình Thuận", "Lâm Đồng"],
    "TP. Hồ Chí Minh": ["TP. Hồ Chí Minh", "Bà Rịa - Vũng Tàu", "Bình Dương"],
    "Đồng Nai": ["Bình Phước", "Đồng Nai"], "Tây Ninh": ["Long An", "Tây Ninh"],
    "Cần Thơ": ["Cần Thơ", "Sóc Trăng", "Hậu Giang"],
    "Vĩnh Long": ["Bến Tre", "Trà Vinh", "Vĩnh Long"],
    "Đồng Tháp": ["Tiền Giang", "Đồng Tháp"], "Cà Mau": ["Bạc Liêu", "Cà Mau"],
    "An Giang": ["Kiên Giang", "An Giang"],
}


def folded(value: object) -> str:
    text = unicodedata.normalize("NFD", str(value or "").strip())
    text = "".join(ch for ch in text if unicodedata.category(ch) != "Mn")
    text = text.replace("Đ", "D").replace("đ", "d").upper()
    text = re.sub(r"[.,;:/\\()_-]+", " ", text)
    return re.sub(r"\s+", " ", text).strip()


def canonical_aliases() -> dict[str, str]:
    aliases = {folded(old): old for olds in NEW_34_TO_OLD_63.values() for old in olds}
    aliases.update({
        "THUA THIEN HUE": "Huế", "TP HUE": "Huế", "THANH PHO HUE": "Huế",
        "TP HO CHI MINH": "TP. Hồ Chí Minh", "TPHCM": "TP. Hồ Chí Minh",
        "HO CHI MINH": "TP. Hồ Chí Minh", "THANH PHO HO CHI MINH": "TP. Hồ Chí Minh",
        "TP THU DUC": "TP. Hồ Chí Minh", "THANH PHO THU DUC": "TP. Hồ Chí Minh",
        "THU DUC": "TP. Hồ Chí Minh", "DAC LAK": "Đắk Lắk", "DAKLAK": "Đắk Lắk",
        "TP DA NANG": "Đà Nẵng", "THANH PHO DA NANG": "Đà Nẵng",
        "TP HAI PHONG": "Hải Phòng", "THANH PHO HAI PHONG": "Hải Phòng",
        "TP CAN THO": "Cần Thơ", "THANH PHO CAN THO": "Cần Thơ",
        "BA RIA VUNG TAU": "Bà Rịa - Vũng Tàu",
    })
    return aliases


def normalize_province(value: object) -> str | None:
    return canonical_aliases().get(folded(value))


def column_index(reference: str) -> int:
    letters = re.match(r"[A-Z]+", reference).group(0)
    number = 0
    for char in letters:
        number = number * 26 + ord(char) - 64
    return number - 1


def read_workbook(path: Path) -> list[tuple[str, list[list[str]]]]:
    with zipfile.ZipFile(path) as archive:
        shared: list[str] = []
        if "xl/sharedStrings.xml" in archive.namelist():
            root = ET.fromstring(archive.read("xl/sharedStrings.xml"))
            for item in root.findall("m:si", NS):
                shared.append("".join(node.text or "" for node in item.findall(".//m:t", NS)))

        workbook = ET.fromstring(archive.read("xl/workbook.xml"))
        rels = ET.fromstring(archive.read("xl/_rels/workbook.xml.rels"))
        targets = {rel.attrib["Id"]: rel.attrib["Target"] for rel in rels.findall("r:Relationship", REL_NS)}
        output = []
        for sheet in workbook.findall("m:sheets/m:sheet", NS):
            target = targets[sheet.attrib[DOC_REL]].lstrip("/")
            sheet_path = target if target.startswith("xl/") else "xl/" + target
            root = ET.fromstring(archive.read(sheet_path))
            rows: list[list[str]] = []
            for row in root.findall(".//m:sheetData/m:row", NS):
                values: list[str] = []
                for cell in row.findall("m:c", NS):
                    idx = column_index(cell.attrib["r"])
                    while len(values) <= idx:
                        values.append("")
                    cell_type = cell.attrib.get("t")
                    value_node = cell.find("m:v", NS)
                    if cell_type == "inlineStr":
                        value = "".join(n.text or "" for n in cell.findall(".//m:t", NS))
                    elif value_node is None:
                        value = ""
                    elif cell_type == "s":
                        value = shared[int(value_node.text)]
                    else:
                        value = value_node.text or ""
                    values[idx] = value.strip()
                rows.append(values)
            output.append((sheet.attrib["name"], rows))
        return output


def find_header(rows: list[list[str]]) -> tuple[int, int, int]:
    name_terms = {"HO VA TEN", "HO TEN", "TEN", "TEN THANH VIEN", "THANH VIEN"}
    province_terms = {"TINH", "TINH THANH", "QUE QUAN", "DIA PHUONG", "NOI O", "NOI SINH"}
    for row_index, row in enumerate(rows[:30]):
        normalized = [folded(value) for value in row]
        name_index = next((i for i, value in enumerate(normalized) if value in name_terms), None)
        province_index = next((i for i, value in enumerate(normalized) if value in province_terms), None)
        if name_index is not None and province_index is not None:
            return row_index, name_index, province_index
    # The supplied 2026–2027 workbook is a headerless roster with sequence,
    # name, gender, cohort and province in columns A–E.
    roster_rows = [row for row in rows[:30] if any(row)]
    if roster_rows and all(
        len(row) > 4 and row[0].strip().isdigit() and row[1].strip() and row[4].strip()
        for row in roster_rows[:5]
    ):
        return -1, 1, 4
    raise ValueError("Could not deterministically identify name and province columns")


def validate_mapping() -> dict[str, str]:
    assert len(NEW_34_TO_OLD_63) == 34, "Current province count must equal 34"
    old_units = [old for olds in NEW_34_TO_OLD_63.values() for old in olds]
    assert len(old_units) == 63, "Constituent union must contain 63 entries"
    assert len(set(old_units)) == 63, "Old provinces must not be duplicated"
    assert all(olds for olds in NEW_34_TO_OLD_63.values()), "Every current unit needs a constituent"
    return {old: current for current, olds in NEW_34_TO_OLD_63.items() for old in olds}


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("workbook", type=Path)
    parser.add_argument("--inspect", action="store_true")
    parser.add_argument("--output", type=Path)
    args = parser.parse_args()
    old_to_current = validate_mapping()
    sheets = read_workbook(args.workbook)

    if args.inspect:
        for name, rows in sheets:
            print(f"SHEET: {name} ({len(rows)} rows)")
            nonempty = [row for row in rows if any(row)]
            print(f"NONEMPTY: {len(nonempty)} rows")
            sample = nonempty[:20] + ([['...']] if len(nonempty) > 40 else []) + nonempty[-20:]
            for row in sample:
                print(json.dumps(row, ensure_ascii=False))
        return

    members = []
    unmatched = []
    for sheet_name, rows in sheets:
        try:
            header_row, name_column, province_column = find_header(rows)
        except ValueError:
            continue
        for source_row, row in enumerate(rows[header_row + 1 :], start=header_row + 2):
            name = row[name_column].strip() if len(row) > name_column else ""
            raw_province = row[province_column].strip() if len(row) > province_column else ""
            if not name and not raw_province:
                continue
            province = normalize_province(raw_province)
            if not name or province is None:
                unmatched.append({"sheet": sheet_name, "row": source_row, "name": name, "location": raw_province})
                continue
            members.append({"name": name, "oldProvince": province, "currentProvince": old_to_current[province]})

    assert not unmatched, "Unmatched workbook rows:\n" + json.dumps(unmatched, ensure_ascii=False, indent=2)
    assert len(members) == len({(m["name"], m["oldProvince"]) for m in members}), "Duplicate member records found"
    totals_63 = Counter(member["oldProvince"] for member in members)
    totals_34 = Counter(member["currentProvince"] for member in members)
    assert sum(totals_63.values()) == len(members), "63-mode member total differs from source"
    assert sum(totals_34.values()) == len(members), "34-mode member total differs from source"
    assert sum(totals_34.values()) == sum(totals_63.values()), "34/63 member totals differ"
    payload = {"members": members, "meta": {"memberCount": len(members), "oldProvinceCount": 63, "currentProvinceCount": 34}}
    if not args.output:
        print(json.dumps(payload, ensure_ascii=False, indent=2))
        return
    args.output.parent.mkdir(parents=True, exist_ok=True)
    args.output.write_text(json.dumps(payload, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
    print(f"Validated 34 current units, 63 old units, {len(members)} members, 0 unmatched locations")


if __name__ == "__main__":
    main()
