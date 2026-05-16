# CONCERNS.md — Technical Concerns & Debt

## High Priority

### 1. External Map Image URL (Hard-coded)
- **File:** `functions.php` → `charity_vietnam_map_image_url()`
- **Issue:** Returns an external CDN URL (`meeymap.com`). This can break without warning, and is not self-hosted.
- **Fix:** Download and serve locally from `assets/img/`; or replace with an SVG map.

### 2. CSS Duplication (~200+ lines repeated)
- **File:** `main.css`
- **Issue:** Several CSS blocks appear to be duplicated (e.g., `.nav-menu`, `.hero`, `.btn` sections appear more than once). This inflates the file and can cause specificity conflicts.
- **Fix:** Audit and deduplicate CSS. Consider splitting into component files.

### 3. Vietnam Map (Bản đồ Vươn Lên) — Placeholder Only
- **File:** `front-page.php`, `category.php`
- **Issue:** The map feature is a static image with CSS-positioned decorative pins. Not interactive. The idea document describes an interactive map with member locations.
- **Fix:** Phase 2 feature — implement with Leaflet.js + custom WP user meta for coordinates.

### 4. Social Links Not Wired
- **File:** `header.php`
- **Issue:** Facebook, YouTube, Instagram links are all `href="#"`.
- **Fix:** Add to WordPress Customizer options or theme settings.

### 5. No Input Validation on Frontend Submit Form
- **File:** `page-submit-post.php`
- **Issue:** Frontend post submission — validation completeness needs review (file upload, nonce, rate limiting).
- **Fix:** Audit the submission handler for completeness.

## Medium Priority

### 6. functions.php Monolith
- **File:** `functions.php` (~450+ lines)
- **Issue:** All logic in one file: setup, CPTs, nav, AJAX, helpers, hooks. Makes maintenance harder.
- **Mitigation:** Acceptable for a small WordPress theme; refactor into `inc/` subdirectory when it grows further.

### 7. No Structured Data / SEO Meta
- **Issue:** No Open Graph, Twitter Card, or schema.org markup.
- **Fix:** Add basic meta tags in `header.php` or install Yoast/Rank Math.

### 8. Cookie-based Like System
- **File:** `functions.php` → `charity_ajax_toggle_like()`
- **Issue:** Cookie-based reaction tracking is easily circumvented (one per browser session, not per user). Fine for MVP but not robust for a real community site.

## Low Priority

### 9. No Loading State on Language Switch
- **Issue:** Language switch via URL param causes full page reload without visual transition.

### 10. No 404 Redirect for Old Blog URL
- **File:** `functions.php`
- **Note:** A `template_redirect` hook catches `/blog` 404s and redirects to `tin-tuc`. Good. But may need coverage for more legacy URL patterns.

### 11. Image Alt Text on Map
- **File:** `front-page.php`, `category.php`
- **Issue:** `<img src="..." alt="">` — map image has empty alt text; should describe the map for accessibility.
