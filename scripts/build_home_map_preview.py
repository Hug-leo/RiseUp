#!/usr/bin/env python3
"""Build the homepage SVG preview from the validated frontend geometry and members."""

from __future__ import annotations

from collections import Counter
from html import escape
import json
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
DATA = ROOT / "wordpress/wp-content/themes/charity-hcm/assets/data"
OUTPUT = ROOT / "wordpress/wp-content/themes/charity-hcm/assets/img/vietnam-34-provinces.svg"
VIEW_WIDTH = 620
VIEW_HEIGHT = 760
PADDING = 34
BOUNDS = {"min_x": 102, "max_x": 110.8, "min_y": 8, "max_y": 23.6}


def project(point: list[float]) -> tuple[float, float]:
    width = BOUNDS["max_x"] - BOUNDS["min_x"]
    height = BOUNDS["max_y"] - BOUNDS["min_y"]
    scale = min((VIEW_WIDTH - PADDING * 2) / width, (VIEW_HEIGHT - PADDING * 2) / height)
    offset_x = (VIEW_WIDTH - width * scale) / 2
    offset_y = (VIEW_HEIGHT - height * scale) / 2
    x = offset_x + (point[0] - BOUNDS["min_x"]) * scale
    y = VIEW_HEIGHT - offset_y - (point[1] - BOUNDS["min_y"]) * scale
    return x, y


def geometry_path(geometry: dict) -> str:
    polygons = [geometry["coordinates"]] if geometry["type"] == "Polygon" else geometry["coordinates"]
    paths = []
    for polygon in polygons:
        for ring in polygon:
            commands = []
            for index, point in enumerate(ring):
                x, y = project(point)
                commands.append(f"{'M' if index == 0 else 'L'}{x:.2f},{y:.2f}")
            paths.append(" ".join(commands) + " Z")
    return " ".join(paths)


def main() -> None:
    geometry = json.loads((DATA / "vietnam-provinces.json").read_text(encoding="utf-8"))["modes"]["34"]
    members = json.loads((DATA / "hbvl-members.json").read_text(encoding="utf-8"))["members"]
    counts = Counter(member["currentProvince"] for member in members)
    assert len(geometry) == 34

    paths = []
    for feature in geometry:
        name = feature["properties"]["name"]
        count = counts[name]
        level = "members-high" if count >= 3 else "members" if count else "empty"
        paths.append(
            f'<path class="province province--{level}" data-name="{escape(name)}" '
            f'd="{geometry_path(feature["geometry"])}"><title>{escape(name)}: {count} thành viên HBVL</title></path>'
        )

    svg = f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {VIEW_WIDTH} {VIEW_HEIGHT}" role="img" aria-labelledby="map-title map-desc">
  <title id="map-title">Bản đồ 34 tỉnh, thành hiện hành của Việt Nam</title>
  <desc id="map-desc">Bản đồ địa lý dùng màu xanh để thể hiện tỉnh, thành có thành viên HBVL.</desc>
  <style>
    .province {{ stroke:#63839a;stroke-width:.9;vector-effect:non-scaling-stroke; }}
    .province--empty {{ fill:#e7eef2; }}
    .province--members {{ fill:#a7cce3; }}
    .province--members-high {{ fill:#5f9fc8; }}
    .reference {{ fill:#4f6474;font:600 13px system-ui,sans-serif;letter-spacing:.04em; }}
    .reference-line {{ stroke:#8da5b6;stroke-width:1;stroke-dasharray:3 4; }}
  </style>
  <g>{''.join(paths)}</g>
  <g aria-hidden="true">
    <line class="reference-line" x1="435" y1="445" x2="535" y2="445"/>
    <text class="reference" x="435" y="468">HOÀNG SA · TRƯỜNG SA</text>
    <text class="reference" x="435" y="488" style="font-size:10px;font-weight:500">Nhãn tham chiếu</text>
  </g>
</svg>'''
    OUTPUT.write_text(svg, encoding="utf-8")
    print(f"Built homepage preview: 34 units, {len(members)} members, {OUTPUT.stat().st_size} bytes")


if __name__ == "__main__":
    main()
