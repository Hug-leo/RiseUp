---
phase: 07-province-detail-pages
plan: 03
subsystem: ui
tags: [javascript, css, map, navigation, responsive]

requires:
  - phase: 07-01
    provides: contact-dongdu.json with slug fields; province photo assets
  - phase: 07-02
    provides: page-tinh.php template; /tinh/{slug}/ WordPress routing

provides:
  - student-map.js click handler navigates to /tinh/{slug}/ for member provinces
  - province detail page CSS (hero, table, mobile responsive, clickable cursor)
affects: [province-detail-pages, student-map]

tech-stack:
  added: []
  patterns: [mapData.contacts slug check before navigation; CSS BEM province-detail__ block]

key-files:
  created: []
  modified:
    - wordpress/wp-content/themes/charity-hcm/assets/js/student-map.js
    - wordpress/wp-content/themes/charity-hcm/assets/css/main.css

key-decisions:
  - "Navigation added at START of click handler — takes priority over popup for member provinces"
  - "Non-member provinces fall through to existing openPopup() behavior unchanged"
  - "Touch (touchend) also navigates for member provinces"
  - "province-clickable CSS class added during bindEvents() loop for slug-having provinces"
  - "CSS variables used: --primary, --bg-light, --border, --text-muted, --text"

patterns-established:
  - "Map click navigation pattern: check mapData.contacts[code].slug before routing"

requirements-completed:
  - MAP-DETAIL-01
  - MAP-DETAIL-02

duration: 15min
completed: 2026-05-17
---

# Plan 07-03 Summary

**Province map now navigates to detail pages on click; detail pages styled with hero image overlay and member contact table.**

## Performance

- **Duration:** ~15 min
- **Completed:** 2026-05-17
- **Tasks:** 2 + human verification checkpoint
- **Files modified:** 2

## Accomplishments

1. **student-map.js**: Extended `bindEvents()` click and touchend handlers:
   - Before tooltip/popup logic: checks `mapData.contacts[code].slug`
   - If slug exists → `window.location.href = '/tinh/' + slug + '/'` and returns
   - If no slug → falls through to existing `openPopup()` (tooltip behavior preserved)
   - `province-clickable` CSS class added per-path during init loop

2. **main.css**: Appended province detail block styles (~140 lines):
   - `.province-detail__hero` — 380px hero container with overflow hidden
   - `.province-detail__photo` — `object-fit: cover` full-width image
   - `.province-detail__title-wrap` — absolute bottom overlay with gradient
   - `.province-detail__table` — bordered table with hover row states
   - `.province-clickable` — `cursor: pointer` on map province paths
   - `@media (max-width: 768px)` — hero 220px, reduced font sizes and padding

## Verification

- `grep "window.location.href.*tinh" student-map.js` → 2 matches (click + touchend) ✓
- `grep "province-clickable" student-map.js` → 1 match ✓
- `grep -c "province-detail" main.css` → 9+ rules ✓
- `grep "province-clickable" main.css` → cursor: pointer rule ✓
- Human checkpoint: approved ✓
