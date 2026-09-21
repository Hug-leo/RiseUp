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
BOUNDS = {"min_x": 102, "max_x": 118.4, "min_y": 6.8, "max_y": 23.6}


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
    data = json.loads((DATA / "vietnam-provinces.json").read_text(encoding="utf-8"))
    geometry = data["modes"]["34"]
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

    islands = []
    for island in data["islands"]:
        x, y = project(island["position"])
        dx, dy = island["labelOffset"]
        archipelago = island.get("archipelago", False)
        anchor = "middle" if archipelago else "end" if dx < 0 else "start"
        dots = [] if archipelago else [(0, 0)]
        markers = "".join(f'<circle cx="{cx}" cy="{cy}" r="3"/>' for cx, cy in dots)
        country = f'<text y="{dy + 17}" text-anchor="middle">VIỆT NAM</text>' if archipelago else ""
        islands.append(
            f'<g class="island{" archipelago" if archipelago else ""}" transform="translate({x:.2f},{y:.2f})">'
            f'<title>{escape(island["name"])}</title>{markers}'
            f'<text x="{dx}" y="{dy}" text-anchor="{anchor}">{escape(island["name"])}</text>{country}</g>'
        )

    svg = f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {VIEW_WIDTH} {VIEW_HEIGHT}" role="img" aria-labelledby="map-title map-desc">
  <title id="map-title">Bản đồ 34 tỉnh, thành hiện hành của Việt Nam</title>
  <desc id="map-desc">Bản đồ tỉnh, thành có thành viên HBVL, cùng các đảo và quần đảo Hoàng Sa, Trường Sa của Việt Nam. Đảo và quần đảo được thể hiện bằng ký hiệu vị trí.</desc>
  <style>
    .province {{ stroke:#63839a;stroke-width:.9;vector-effect:non-scaling-stroke; }}
    .province--empty {{ fill:#e7eef2; }}
    .province--members {{ fill:#a7cce3; }}
    .province--members-high {{ fill:#5f9fc8; }}
    .island circle {{ fill:#0d47a1;stroke:#fff;stroke-width:1; }}
    .island text {{ fill:#17365d;font:600 12px system-ui,sans-serif;paint-order:stroke;stroke:#f7fbff;stroke-width:3px;stroke-linejoin:round; }}
    .archipelago circle, .archipelago text {{ fill:#b71c1c; }}
  </style>
  <g>{''.join(paths)}</g>
  <g aria-label="Các đảo và quần đảo Việt Nam">{''.join(islands)}</g>
</svg>'''
    OUTPUT.write_text(svg, encoding="utf-8")
    print(f"Built homepage preview: 34 units, {len(members)} members, {OUTPUT.stat().st_size} bytes")


if __name__ == "__main__":
    main()
