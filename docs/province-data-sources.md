# HBVL province data sources

Accessed 4 September 2026. Runtime files are vendored in the theme; the public map does not call third-party services.

## Administrative authority

- [Nghị quyết 202/2025/QH15](https://vanban.chinhphu.vn/?classid=1&docid=213930&orggroupid=1&pageid=27160), National Assembly / Government legal-document portal. Used to verify the 2025 provincial reorganisation and canonical 63-to-34 composition.
- [Quyết định 19/2025/QĐ-TTg](https://vanban.chinhphu.vn/?classid=1&docid=214409&orggroupid=3&pageid=27160), Prime Minister / Government legal-document portal. Used to verify the current administrative codes and names from 1 July 2025.
- [Official list and codes for 34 units](https://xaydungchinhsach.chinhphu.vn/bang-danh-muc-va-ma-so-cua-34-tinh-thanh-moi-cac-don-vi-hanh-chinh-cap-xa-moi-11925070418263625.htm), Government policy portal. Used as a readable cross-check of Decision 19.
- [Administrative map for the 34-unit system](https://dosmvn.mae.gov.vn/ban-do-hanh-chinh-chi-tiet-34-tinh-thanh-moi-cua-viet-nam-2110.htm), Department of Survey, Mapping and Geographic Information Vietnam. Used to check coastline, orientation, provincial arrangement and official map availability.

The current system is described internally and publicly as 34 provincial-level units: 28 provinces and 6 centrally governed cities. The historical legal state immediately before the reorganisation uses `Huế`; `Thừa Thiên Huế` is accepted only as a source alias.

## Geometry and frontend data

- Current 34-unit polygons were built from the MIT-licensed [Vietnamese Provinces Database](https://github.com/ThangLeQuoc/vietnamese-provinces-database). Its GIS documentation attributes geometry to the Vietnam Administrative Units Reference Map published by the Vietnam Natural Resources, Environment and Cartography Publishing House. The local build validates all 34 canonical names.
- Historical 63-unit polygons were built from the locally vendored [Highcharts Vietnam map data](https://code.highcharts.com/mapdata/countries/vn/vn-all.geo.json), then normalized to the canonical legal names and validated as exactly 63 distinct units.
- `data-source/DANH SÁCH HBVL 26 - 27.xlsx` is read-only. The build extracts only member name and old province, derives the current province from the verified mapping, and writes the sanitized theme JSON. No workbook field is imported into WordPress or a database.

Hoàng Sa and Trường Sa appear as reference labels and are not counted as extra provincial-level units. Any geometry-associated island features remain part of their parent unit.
