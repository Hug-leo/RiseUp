---
phase: 07-province-detail-pages
plan: 02
subsystem: api
tags: [wordpress, php, rewrite-rules, template, routing]

requires:
  - phase: 07-01
    provides: contact-dongdu.json with slug fields; province photo assets
provides:
  - WordPress rewrite rule for /tinh/{slug}/ URLs
  - province_slug query var registered
  - page-tinh.php template rendering province detail pages
affects: [07-03, student-map]

tech-stack:
  added: []
  patterns: [WordPress rewrite rule + template_redirect pattern for virtual pages]

key-files:
  created:
    - wordpress/wp-content/themes/charity-hcm/page-tinh.php
  modified:
    - wordpress/wp-content/themes/charity-hcm/functions.php

key-decisions:
  - "Routing via add_rewrite_rule + template_redirect (not a custom post type)"
  - "sanitize_title() applied to province_slug before all use (path traversal prevention)"
  - "404 served via global $wp_query->set_404() for unknown slugs"
  - "All user-visible strings use charity_t() for bilingual support"

patterns-established:
  - "Virtual page routing: add_rewrite_rule('init') + query_vars filter + template_redirect include"
  - "Province template reads JSON with file_get_contents(CHARITY_HCM_DIR . '/data/...')"

requirements-completed:
  - MAP-DETAIL-01
  - MAP-DETAIL-02

duration: 10min
completed: 2026-05-16
---

# Plan 07-02 Summary

**WordPress province routing and page-tinh.php template registered — /tinh/{slug}/ URLs now resolve to province detail pages.**

## Performance

- **Duration:** ~10 min
- **Completed:** 2026-05-16
- **Tasks:** 2
- **Files modified:** 2

## Accomplishments

1. **functions.php**: Added province routing block (lines 645–666):
   - `add_rewrite_rule` on `init` hook for `^tinh/([^/]+)/?$`
   - `query_vars` filter registers `province_slug`
   - `template_redirect` hook loads `page-tinh.php` when slug is set

2. **page-tinh.php**: Created province detail template:
   - Reads `contact-dongdu.json` via `file_get_contents(CHARITY_HCM_DIR . '/data/...')`
   - Finds province by `slug` field
   - Serves 404 for unknown slugs via `$wp_query->set_404()`
   - Renders hero image, province name `<h1>`, member contact table
   - All output escaped (`esc_html`, `esc_url`, `sanitize_title`)
   - All user-visible text uses `charity_t()` for bilingual support

## Verification

- `php -l functions.php` → No syntax errors ✓
- `php -l page-tinh.php` → No syntax errors ✓
- `grep "province_slug" functions.php` → 3 matches (rewrite rule, query_vars, template_redirect) ✓
- `grep -c "esc_html|esc_url|sanitize_title" page-tinh.php` → 7 matches ✓
- `grep -c "charity_t" page-tinh.php` → 8 matches ✓

## Next step

Visit `/wp-admin/options-permalink.php` and click "Save Changes" to flush rewrite rules so `/tinh/{slug}/` URLs resolve properly.
