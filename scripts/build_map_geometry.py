#!/usr/bin/env python3
"""Build compact, validated 34/63 province GeoJSON for the frontend map."""

from __future__ import annotations

import argparse
import base64
import json
import math
from pathlib import Path

from build_hbvl_map_data import NEW_34_TO_OLD_63, folded


CURRENT_CODE_NAMES = {
    "01_ha_noi": "Hà Nội", "04_cao_bang": "Cao Bằng", "08_tuyen_quang": "Tuyên Quang",
    "11_dien_bien": "Điện Biên", "12_lai_chau": "Lai Châu", "14_son_la": "Sơn La",
    "15_lao_cai": "Lào Cai", "19_thai_nguyen": "Thái Nguyên", "20_lang_son": "Lạng Sơn",
    "22_quang_ninh": "Quảng Ninh", "24_bac_ninh": "Bắc Ninh", "25_phu_tho": "Phú Thọ",
    "31_hai_phong": "Hải Phòng", "33_hung_yen": "Hưng Yên", "37_ninh_binh": "Ninh Bình",
    "38_thanh_hoa": "Thanh Hóa", "40_nghe_an": "Nghệ An", "42_ha_tinh": "Hà Tĩnh",
    "44_quang_tri": "Quảng Trị", "46_hue": "Huế", "48_da_nang": "Đà Nẵng",
    "51_quang_ngai": "Quảng Ngãi", "52_gia_lai": "Gia Lai", "56_khanh_hoa": "Khánh Hòa",
    "66_dak_lak": "Đắk Lắk", "68_lam_dong": "Lâm Đồng", "75_dong_nai": "Đồng Nai",
    "79_ho_chi_minh": "TP. Hồ Chí Minh", "80_tay_ninh": "Tây Ninh", "82_dong_thap": "Đồng Tháp",
    "86_vinh_long": "Vĩnh Long", "91_an_giang": "An Giang", "92_can_tho": "Cần Thơ",
    "96_ca_mau": "Cà Mau",
}


OLD_NAME_ALIASES = {
    "HO CHI MINH CITY": "TP. Hồ Chí Minh", "HO CHI MINH": "TP. Hồ Chí Minh",
    "HAIPHONG": "Hải Phòng", "HAI PHONG": "Hải Phòng", "CAN THO": "Cần Thơ",
    "DA NANG": "Đà Nẵng", "THUA THIEN HUE": "Huế", "THUA THIEN HUE CITY": "Huế",
    "THURA THIEN HUE": "Huế", "DAC NONG": "Đắk Nông", "DAK NONG": "Đắk Nông",
    "DAC LAK": "Đắk Lắk", "DAK LAK": "Đắk Lắk", "SOUTHEAST": "Đồng Nai",
}


def point_segment_distance(point, start, end):
    if start == end:
        return math.dist(point, start)
    x, y = point
    x1, y1 = start
    x2, y2 = end
    t = max(0.0, min(1.0, ((x - x1) * (x2 - x1) + (y - y1) * (y2 - y1)) / ((x2 - x1) ** 2 + (y2 - y1) ** 2)))
    return math.dist(point, (x1 + t * (x2 - x1), y1 + t * (y2 - y1)))


def simplify_line(points, tolerance):
    if len(points) <= 2:
        return points
    maximum = 0.0
    index = 0
    for i in range(1, len(points) - 1):
        distance = point_segment_distance(points[i], points[0], points[-1])
        if distance > maximum:
            maximum, index = distance, i
    if maximum <= tolerance:
        return [points[0], points[-1]]
    left = simplify_line(points[: index + 1], tolerance)
    right = simplify_line(points[index:], tolerance)
    return left[:-1] + right


def simplify_ring(ring, tolerance):
    if len(ring) < 5:
        return ring
    open_ring = ring[:-1] if ring[0] == ring[-1] else ring
    simplified = simplify_line(open_ring + [open_ring[0]], tolerance)
    if len(simplified) < 4:
        return ring
    if simplified[0] != simplified[-1]:
        simplified.append(simplified[0])
    return [[round(value, 5) for value in point] for point in simplified]


def simplify_geometry(geometry, tolerance):
    kind = geometry["type"]
    coordinates = geometry["coordinates"]
    if kind == "Polygon":
        simplified = [simplify_ring(ring, tolerance) for ring in coordinates]
    elif kind == "MultiPolygon":
        simplified = [[simplify_ring(ring, tolerance) for ring in polygon] for polygon in coordinates]
    else:
        raise ValueError(f"Unsupported geometry type: {kind}")
    return {"type": kind, "coordinates": simplified}


def decode_api_file(path: Path):
    envelope = json.loads(path.read_text(encoding="utf-8"))
    raw = base64.b64decode("".join(envelope["content"].split()))
    return json.loads(raw.decode("utf-8"))


def build_current(source_dir: Path, tolerance: float):
    features = []
    for stem, name in CURRENT_CODE_NAMES.items():
        collection = decode_api_file(source_dir / f"{stem}.json")
        if len(collection.get("features", [])) != 1:
            raise ValueError(f"Expected one feature for {stem}")
        source = collection["features"][0]
        features.append({
            "type": "Feature", "id": stem[:2],
            "properties": {"name": name, "constituents": NEW_34_TO_OLD_63[name]},
            "geometry": simplify_geometry(source["geometry"], tolerance),
        })
    return features


def resolve_old_name(properties, canonical_by_fold):
    candidates = [properties.get("alt-name"), properties.get("woe-name"), properties.get("name"), properties.get("woe-label")]
    for candidate in candidates:
        if not candidate:
            continue
        for part in str(candidate).split("|"):
            key = folded(part.replace(", VN, Vietnam", ""))
            if key in OLD_NAME_ALIASES:
                return OLD_NAME_ALIASES[key]
            if key in canonical_by_fold:
                return canonical_by_fold[key]
    raise ValueError(f"Unmatched 63-mode geometry: {properties}")


def build_old(source_path: Path):
    collection = json.loads(source_path.read_text(encoding="utf-8-sig"))
    old_names = [old for olds in NEW_34_TO_OLD_63.values() for old in olds]
    canonical_by_fold = {folded(name): name for name in old_names}
    features = []
    for source in collection.get("features", []):
        name = resolve_old_name(source.get("properties", {}), canonical_by_fold)
        features.append({
            "type": "Feature", "id": source.get("properties", {}).get("hc-key", name),
            "properties": {"name": name}, "geometry": source["geometry"],
        })
    names = [feature["properties"]["name"] for feature in features]
    if len(features) != 63 or set(names) != set(old_names) or len(names) != len(set(names)):
        raise ValueError("63-mode geometry does not match the canonical 63-unit set")
    return features


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--current-source", type=Path, required=True)
    parser.add_argument("--old-source", type=Path, required=True)
    parser.add_argument("--output", type=Path, required=True)
    parser.add_argument("--tolerance", type=float, default=0.003)
    args = parser.parse_args()
    current = build_current(args.current_source, args.tolerance)
    old = build_old(args.old_source)
    if len(current) != 34 or {f["properties"]["name"] for f in current} != set(NEW_34_TO_OLD_63):
        raise ValueError("34-mode geometry does not match the canonical 34-unit set")
    payload = {"type": "HBVLProvinceGeometry", "modes": {"34": current, "63": old}}
    args.output.parent.mkdir(parents=True, exist_ok=True)
    args.output.write_text(json.dumps(payload, ensure_ascii=False, separators=(",", ":")), encoding="utf-8")
    print(f"Validated geometry: {len(current)} current units, {len(old)} old units; {args.output.stat().st_size} bytes")


if __name__ == "__main__":
    main()
