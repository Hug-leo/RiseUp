# Phase 06 Research: Contact Section Update & Interactive Student Map

**Date:** 2026-05-16
**Phase:** 06 — Contact Section Update & Interactive Student Map
**Requirements:** CONTACT-01, MAP-01, MAP-02, MAP-03

---

## Current State Audit

### Contact Section (`page-contact.php`)

The contact page already exists with well-structured PHP. The address card currently contains:
```php
<p>43D/46 Hồ Văn Huê<br>Phú Nhuận, TP. Hồ Chí Minh</p>
```
Target per ROADMAP: `43D/46 Hồ Văn Huê, P. Đức Nhuận, TP. HCM`

Phone card:
```php
<p>084 3214 142<br>(Thanh Vẹn)</p>
```
Target: `084.3214.142` (format change, keep the name note)

The address and phone strings are **hardcoded HTML** — not using `charity_t()` for the values themselves (only the labels like "Address" are translated). Since these are proper nouns (address literals), `charity_t()` is still correct for the label wrapper. However, the address content string itself should be updated.

**Footer contact check**: `footer.php` only links to `/lien-he/` — no hardcoded address/phone there. Change scope: only `page-contact.php` needs updating.

---

### Interactive Map — Current State

The `category.php` already has a `ban-do-vuon-len` conditional block at line 90:

```php
<?php if ( $current_item && $current_item['slug'] === 'ban-do-vuon-len' ) : ?>
    <section class="category-map-demo">
        <div class="category-map-demo__map" aria-hidden="true">
            <img src="..." alt="">
            <span class="cp-map-pin cp-map-pin--north"></span>
            ...
        </div>
        <div class="category-map-demo__content">
            <p>A later phase can replace this mockup with an interactive map...</p>
        </div>
    </section>
```

**This is the exact section Phase 6 replaces.** The comment even says "A later phase can replace this mockup."

The front-page (`front-page.php`) also has a `cp-map-feature` section with the static map — this is a **separate homepage teaser**, not the interactive map. Phase 6 only replaces the `category.php` section.

---

## Technology Decision: SVG Choropleth vs Leaflet.js

### Stack constraint
> WordPress 6.x + PHP 8 + vanilla CSS/JS. No npm, no build tools, no new frameworks.

### Leaflet.js analysis
- **Pros**: Industry standard, rich pin/popup API, GeoJSON support
- **Cons**: ~147KB minified library + GeoJSON files (~500KB for Vietnam provinces) = ~650KB total. Requires self-hosting both Leaflet JS/CSS and GeoJSON. Heavier than needed for sample-data choropleth.
- **Requirement MAP-01** cites Leaflet.js, but that requirement is v2 ("real member coordinates + user accounts"). Phase 6 is a scoped-down demo with sample data.

### SVG Choropleth analysis
- **Pros**: 0 external dependencies, pure CSS/JS, ~30-80KB total (compressed SVG + small JS), inline-manipulable with vanilla JS, mobile-responsive via `viewBox`, perfect for sample-data demo
- **Cons**: Manual SVG sourcing, less familiar API for future devs

### **Recommendation: SVG Choropleth**

For this phase (sample hardcoded data, visual polish, toggle demo), an inline SVG approach fits the stack perfectly. If/when Phase 2 milestone delivers real member accounts, Leaflet can replace it with a proper backend.

---

## Vietnam Province SVG Sources

### 63-province SVG
The 63-province Vietnam administrative map is widely available:
- **GADM (gadm.org)**: Official GeoJSON → can convert to SVG. Level 1 = provinces.
- **vietnam-geojson on GitHub** (e.g., `duyvuleo/vietnam-geojson`): Ready-made province GeoJSON
- **Simplified SVG**: Pre-simplified province SVG files optimized for web (search "vietnam 63 provinces svg viewbox")
- Self-host as `assets/img/vietnam-63-provinces.svg`

### 34-province SVG (new 2025)
Vietnam's National Assembly Resolution 76/2025/QH15 merged provinces effective 2025-07-01, reducing from 63 → 34 provinces/cities (pending updates, some sources say 34, check current). Key mergers:
- Hà Nội absorbed several surrounding provinces (Hoà Bình, Hà Nam, Vĩnh Phúc, etc.)
- TP.HCM absorbed Bình Dương, Bà Rịa–Vũng Tàu area
- Full list: 34 merged units including 6 centrally-administered cities
- **Source**: The official GeoJSON for new boundaries is being published by Vietnamese GIS agencies. A practical approach: start from the 63-province SVG and build a province-to-new-unit mapping, grouping `<path>` elements under `<g>` tags for each new province.
- Self-host as `assets/img/vietnam-34-provinces.svg`

**Plan recommendation**: Include both SVG files in `assets/img/`. The 63-province SVG can be sourced from GADM/GitHub and simplified. The 34-province variant can use the same paths with a JS mapping that groups old province IDs → new province IDs.

---

## Implementation Architecture

### File structure
```
assets/
  img/
    vietnam-63-provinces.svg   ← self-hosted SVG map (simplified for web)
    vietnam-34-provinces.svg   ← self-hosted SVG map (new 2025 admin divisions)
  js/
    student-map.js             ← vanilla JS choropleth + toggle logic
  css/
    main.css                   ← append student-map CSS section
```

### PHP template change
In `category.php`, the `ban-do-vuon-len` section replaces the current `<section class="category-map-demo">` with a new `<section class="student-map-section">` that:
1. Renders toggle buttons (34 / 63 provinces)
2. Renders two SVG containers (one active, one hidden)
3. Renders a tooltip div

### JS architecture (`student-map.js`)
```js
// Passed from PHP via wp_localize_script
// window.vuonlenMap = { students: [{province_code, count, label}], ... }

(function() {
  // 1. On DOMContentLoaded, read active map (default: 63)
  // 2. For each province path with data, apply fill class + data-count attr
  // 3. Toggle buttons: swap active SVG container, re-apply coloring
  // 4. Hover: show tooltip at mouse position
  // 5. Mobile: touch-friendly tap tooltip
})();
```

### Sample student data (hardcoded via `wp_localize_script`)
15–20 provinces with student origin counts:
```php
$student_data_63 = [
  ['code' => 'VN-SG',  'label_vi' => 'TP. Hồ Chí Minh', 'label_en' => 'Ho Chi Minh City', 'count' => 12],
  ['code' => 'VN-HN',  'label_vi' => 'Hà Nội',           'label_en' => 'Hanoi',            'count' => 8],
  ['code' => 'VN-DN',  'label_vi' => 'Đà Nẵng',          'label_en' => 'Da Nang',          'count' => 5],
  ['code' => 'VN-CT',  'label_vi' => 'Cần Thơ',          'label_en' => 'Can Tho',          'count' => 4],
  ['code' => 'VN-HP',  'label_vi' => 'Hải Phòng',        'label_en' => 'Hai Phong',        'count' => 3],
  // ... ~15 total
];
```

### CSS strategy
Add new section `/* ── Student Map ─────── */` to `main.css`:
- `.student-map-section`: container, padding
- `.student-map__toggle`: flex row, styled toggle buttons
- `.student-map__svg-wrap`: relative container for SVG
- `path.province-active`: fill color (theme red tint, opacity by count tier)
- `path.province-hover`: darker fill + cursor pointer
- `.student-map__tooltip`: absolute positioned, CSS-animated fade-in
- Mobile: stacked layout, tooltip below map

### `wp_enqueue_script` registration
Conditionally load `student-map.js` only on the ban-do-vuon-len category page:
```php
// In functions.php enqueue hook
if ( is_category() ) {
    $cat = get_queried_object();
    if ( $cat && $cat->slug === 'ban-do-vuon-len' ) {
        wp_enqueue_script(
            'charity-student-map',
            CHARITY_HCM_URI . '/assets/js/student-map.js',
            [],
            CHARITY_HCM_VERSION,
            true
        );
        wp_localize_script( 'charity-student-map', 'vuonlenMap', [
            'lang'     => charity_get_lang(),
            'students' => $student_data_63, // or derive from PHP array
        ] );
    }
}
```

---

## Wave Structure

| Plan | Content | Wave | Parallel with |
|------|---------|------|---------------|
| 06-01 | Contact section update (page-contact.php) | 1 | 06-02 |
| 06-02 | SVG assets + JS choropleth base + CSS (63-province only) | 1 | 06-01 |
| 06-03 | 34-province toggle + mobile polish | 2 | — |

---

## Common Pitfalls

1. **SVG path IDs must match province codes** used in JS data — use ISO 3166-2:VN codes (e.g., `VN-SG`, `VN-HN`) for consistency
2. **SVG must have `viewBox` not fixed width/height** — enables responsive scaling via CSS
3. **Inline SVG vs `<img>`**: Must use inline SVG (or `<object>`) for JS path manipulation — `<img>` tags can't be DOM-queried
4. **Toggle hides/shows `<div>` wrappers**, not the SVG files themselves — both SVGs stay in DOM, JS toggles `.active` class
5. **`wp_localize_script` must match handle** registered in `wp_enqueue_script` — use `charity-student-map` consistently
6. **`charity_t()` for all new UI strings** — toggle button labels, tooltip text, section heading
7. **`aria-hidden="true"` on SVG** — the map is decorative/supplementary, screen readers should skip it; add visible text alternative

---

## Validation Architecture

**Critical paths to verify:**
1. Contact page shows correct address + phone at `/lien-he/`
2. `ban-do-vuon-len` category page shows interactive SVG map (not static img)
3. Province paths in sample data receive fill color class
4. Toggle button switches between 63-province and 34-province SVG views
5. Tooltip appears on hover/tap with province name + student count
6. Map is responsive at 375px viewport width
7. All new strings use `charity_t()` bilingual helper

**Manual test steps:**
- Visit `http://127.0.0.1:8080/?cat={ban-do-vuon-len-id}` and verify map renders
- Resize to 375px and confirm stacked layout
- Click toggle buttons and confirm SVG swap
- Hover provinces with data (HCM, HN, etc.) and confirm tooltip
