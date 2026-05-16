# Plan 06-03 Summary: 34-Province Toggle Map

**Phase:** 06-contact-and-student-map
**Plan:** 03
**Status:** Complete
**Commit:** 43bdd48

## What Was Done

Extended the student map from Plan 02 with a 34-province toggle for Vietnam's 2025 administrative restructuring.

### Files Created

- `assets/img/vietnam-34-provinces.svg` — Self-hosted simplified SVG for Vietnam's 34-province 2025 administrative map (merged units per Resolution 76/2025/QH15). IDs use `VN34-` prefix (e.g. `VN34-SG`, `VN34-HN`, `VN34-CT`). `viewBox`-only, no fixed dimensions.

### Files Modified

- `category.php` — Added toggle button group (`<div class="student-map__toggle">`) with "63 tỉnh thành / Cũ" and "34 tỉnh thành / Mới 2025" buttons (bilingual via `charity_t()`). Added `#student-map-34` SVG container inside `.student-map__wrap` (hidden by default).
- `assets/js/student-map.js` — Replaced with full toggle-enabled version: `containers` object for both maps, `switchMap(toKey)` function (validates key before acting, swaps `.active` classes, swaps `aria-hidden`), `applyData()` + `bindEvents()` called for both maps on init, toggle button click handlers wired.
- `assets/css/main.css` — Appended toggle button CSS: `.student-map__toggle`, `.map-toggle-btn`, `.map-toggle-btn__note`, active/hover states (red background), mobile breakpoint (480px: smaller padding).
- `functions.php` — Added `$student_data_34` array (8 provinces) and updated `wp_localize_script` to include `'students_34' => $student_data_34`.

## Key Decisions

- `switchMap()` validates key exists in `containers` object before acting — prevents unexpected DOM manipulation (T-06-03-01 mitigation)
- Both maps are initialized (data applied + events bound) at page load — no lazy init needed
- Toggle state managed via `activeMap` var + CSS classes, no hidden/show logic that could cause FOUC

## Verification

- `php -l functions.php` → No syntax errors
- `php -l category.php` → No syntax errors
- `student-map.js` contains `switchMap`, `students_34`, `.map-toggle-btn`
- `functions.php` contains `students_34`
- `vietnam-34-provinces.svg` exists
