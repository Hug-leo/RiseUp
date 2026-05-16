# Phase 7: Interactive Province Map — Province Detail Pages — Context

**Gathered:** 2026-05-16
**Status:** Ready for planning
**Source:** Direct user specification in conversation

<domain>
## Phase Boundary

When a user clicks a province on the interactive Vietnam map (built in Phase 6),
they are navigated to a dedicated province detail page. The detail page shows:

1. A signature JPG photo representing/identifying that province visually
2. A contact table listing Đông Du members currently in that province (name, role, contact info)

Images are researched online, downloaded to a local folder in the theme, and compressed to JPG.
The map click handler (student-map.js from Phase 6) is extended to route to the province page.

This phase is entirely within the existing WordPress theme — no external plugins, no new CPTs required.
Province pages can be standard WordPress pages with a dedicated page template, or handled via a
custom CPT. No React, no Vue — pure PHP templates + vanilla JS.

</domain>

<decisions>
## Implementation Decisions

### D-01: Province page navigation
- Locked: Clicking a province on the SVG map navigates to a dedicated URL for that province (e.g., `/tinh/ha-noi/` or `/tinh/ha-noi` or a WP page slug)
- Navigation replaces tooltip-only behavior; the tooltip (from Phase 6) can remain but the click must route to the detail page

### D-02: Province detail page content
- Locked: Each province detail page MUST display:
  1. Province signature JPG (a visually representative/iconic photo of the province)
  2. Contact table of Đông Du members in that province (columns: Name, Role/Position, Contact info)
- Optional: Province name header, short description blurb

### D-03: Province photos
- Locked: Photos are JPG format (not PNG, not WebP)
- Locked: Photos are downloaded to local theme folder (not external CDN links)
- Locked: Photos are compressed (smaller file size — target < 150KB per image)
- Locked: A dedicated folder is created for province photos (e.g., `assets/img/provinces/`)
- Approach: Research and download free-license representative photos for each of the 63 provinces
- Priority provinces: Those with Đông Du members defined in the sample data from Phase 6

### D-04: Member contact table
- Locked: Each province page shows a table of Đông Du members for that province
- Data source: Sample/hardcoded data in PHP template or JSON file (no database backend required for v1)
- Table columns: Name, Role, Contact (email or phone)
- Design: Clean table consistent with the theme's existing typography and color system

### D-05: Province page template approach
- Locked: WordPress template, pure PHP — no JS framework
- Preferred: A single PHP template file `page-province.php` with province slug as page identifier
- OR: A custom page template assigned to province pages
- Data: Province-to-member mapping defined in `functions.php` or a JSON config file

### D-06: Only provinces with members need full detail pages
- Locked: Only create detail pages for provinces that have at least one Đông Du member in the sample data
- Non-member provinces: Clicking them shows a tooltip only (no navigation)
- This reduces the scope of pages needed (likely 5–15 pages, not 63)

### D-07: Scope of province photos
- Must download photos for all provinces WITH members (per D-06)
- Photos folder MUST be created: `wordpress/wp-content/themes/charity-hcm/assets/img/provinces/`
- Naming convention: `{province-slug}.jpg` (e.g., `ha-noi.jpg`, `ho-chi-minh.jpg`)

### Claude's Discretion
- Exact URL structure for province pages (slug format)
- Whether to use WP custom pages or a custom post type
- Exact PHP template structure
- Number of sample members per province (suggest 3–8 for demo)
- Contact table visual style (keep consistent with theme)
- Whether to add a "back to map" button on province pages

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Phase 6 — Interactive Map (prerequisite output)
- `.planning/phases/06-cap-nhat-section-lien-he-va-ban-do-dong-du-voi-ban-do-tuong-/06-02-PLAN.md` — SVG map architecture, student-map.js click/hover logic, province ID scheme (ISO 3166-2:VN), vuonlenMap data structure
- `.planning/phases/06-cap-nhat-section-lien-he-va-ban-do-dong-du-voi-ban-do-tuong-/06-03-PLAN.md` — Province toggle (34 vs 63), mobile polish

### Theme source of truth
- `wordpress/wp-content/themes/charity-hcm/functions.php` — charity_content_groups(), wp_localize_script pattern, template registration
- `wordpress/wp-content/themes/charity-hcm/assets/js/student-map.js` — current click handler to extend (built in Phase 6)
- `wordpress/wp-content/themes/charity-hcm/assets/css/main.css` — CSS variables and typography to match

### Data reference
- `config/content-pillars.json` — Section structure reference
- `.planning/codebase/STACK.md` — No npm, pure PHP+JS+CSS, charity_t() for bilingual strings

</canonical_refs>

<specifics>
## Specific Ideas from User

- "ấn vào từng tỉnh, nó sẽ qua một page khác" → province-level routing on click
- "có ảnh jpg signature của tỉnh đó" → one visually iconic JPG per province
- "bảng contact của các thành viên đông du trong đó" → member contact table
- "mày research trên mạng, tải về local này và tạo thư mục nha" → download + create assets/img/provinces/
- "ưu tiên .jpg để giảm dung lượng lưu trữ, compress" → JPG only, compress all images

</specifics>

<deferred>
## Deferred Ideas

- Real member data from WordPress user database (deferred — sample data is sufficient for Phase 7)
- Members uploading their own province assignments via profile (deferred — backend feature)
- Photo gallery per province (deferred — one signature photo is sufficient for v1)
- Province statistics (population, area, etc.) — not requested

</deferred>

---

*Phase: 07-interactive-province-map-province-detail-pages-with-signatur*
*Context gathered: 2026-05-16 via direct user conversation*
