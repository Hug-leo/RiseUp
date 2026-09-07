#!/usr/bin/env python3
"""Validate all frontend member, administrative mapping, and geometry invariants."""

from collections import Counter
import json
from pathlib import Path

from build_hbvl_map_data import NEW_34_TO_OLD_63, validate_mapping

ROOT = Path(__file__).resolve().parents[1]
DATA = ROOT / "wordpress/wp-content/themes/charity-hcm/assets/data"


def main() -> None:
    old_to_current = validate_mapping()
    members = json.loads((DATA / "hbvl-members.json").read_text(encoding="utf-8"))["members"]
    geometry = json.loads((DATA / "vietnam-provinces.json").read_text(encoding="utf-8"))["modes"]
    current_names = set(NEW_34_TO_OLD_63)
    old_names = set(old_to_current)

    assert len(current_names) == 34
    assert len(old_names) == 63
    assert {feature["properties"]["name"] for feature in geometry["34"]} == current_names
    assert {feature["properties"]["name"] for feature in geometry["63"]} == old_names
    assert len(geometry["34"]) == 34 and len(geometry["63"]) == 63
    assert all(NEW_34_TO_OLD_63[name] for name in current_names)
    assert all(member["oldProvince"] in old_names for member in members)
    assert all(old_to_current[member["oldProvince"]] == member["currentProvince"] for member in members)
    assert len(members) == len({(member["name"], member["oldProvince"]) for member in members})

    totals_63 = Counter(member["oldProvince"] for member in members)
    totals_34 = Counter(member["currentProvince"] for member in members)
    assert sum(totals_63.values()) == sum(totals_34.values()) == len(members)
    assert all(totals_34[name] == sum(totals_63[old] for old in old_names_list)
               for name, old_names_list in NEW_34_TO_OLD_63.items())

    zero_34 = next(name for name in current_names if totals_34[name] == 0)
    zero_63 = next(name for name in old_names if totals_63[name] == 0)
    assert list(member for member in members if member["currentProvince"] == zero_34) == []
    assert list(member for member in members if member["oldProvince"] == zero_63) == []

    print(
        f"PASS: 34 current, 63 historical, {len(members)} unique members, "
        f"0 unmatched; totals equal; zero states safe ({zero_34}, {zero_63})."
    )


if __name__ == "__main__":
    main()
