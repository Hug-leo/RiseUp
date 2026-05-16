# Plan 06-02 Summary: Interactive 63-Province Student Map

**Phase:** 06-contact-and-student-map
**Plan:** 02
**Status:** Complete
**Commit:** 1bbbbd6

## What Was Done

Replaced the static map demo on the `ban-do-vuon-len` category page with a fully interactive SVG choropleth map.

### Files Created

- `assets/img/vietnam-63-provinces.svg` — Self-hosted simplified Vietnam 63-province SVG map with ISO 3166-2:VN province `id` attributes (`VN-SG`, `VN-HN`, `VN-DN`, `VN-CT`, `VN-HP`, etc.). `viewBox`-only sizing, no fixed dimensions.
- `assets/js/student-map.js` — IIFE choropleth logic: reads `window.vuonlenMap` from PHP, applies `province--low/mid/high` CSS classes by student count tiers (1-3/4-7/8+), binds mouse/touch hover tooltips, positioned relative to `.student-map__wrap`.

### Files Modified

- `category.php` — Replaced `<section class="category-map-demo">` with `<section class="student-map-section">` containing inline SVG via `file_get_contents()` for JS path access.
- `assets/css/main.css` — Appended `/* Student Map (Phase 6) */` CSS block: section layout, province fill tiers (low=`#f4c4b2`, mid=`#e07a5f`, high=`#c0392b`), tooltip positioning, mobile breakpoint (280px max-width at 768px).
- `functions.php` — Added conditional `wp_enqueue_script('charity-student-map')` inside `wp_enqueue_scripts` hook, active only when `is_category()` AND `$queried->slug === 'ban-do-vuon-len'`. Sample `vuonlenMap` data: 12 provinces (VN-SG=12, VN-HN=8, VN-DN=5, …).

## Decisions

- Used `file_get_contents()` to inline SVG so JS can `querySelector('#VN-SG')` on path elements
- `instanceof WP_Term` check prevents fatal error if `get_queried_object()` returns null
- SVG does not include `<script>` elements (XSS mitigation per T-06-02-02)
- Student data is sample/illustrative only — no real PII

## Verification

- `php -l functions.php` → No syntax errors
- `php -l category.php` → No syntax errors
- `student-map.js` exists
- `vuonlenMap` appears in `functions.php`
- `.student-map-section` CSS rules appended to `main.css`
