# Phase 04 — Plan 01 Summary

**Plan:** 04-01 — Social Links Customizer
**Completed:** 2026-05-16
**Commit:** c4ca270

## What Was Built

### functions.php — Customizer registration
Added a `customize_register` hook that registers:
- **Section** `charity_social_links` — "Mạng xã hội / Social Links" (priority 120)
- **Settings:** `charity_social_facebook`, `charity_social_youtube`, `charity_social_instagram`
  - All use `sanitize_callback: 'esc_url_raw'`
- **Controls:** URL-type inputs in the social links section

### header.php — Social link wiring
Replaced three hardcoded `href="#"` topbar social links with dynamic `get_theme_mod()` calls:
- `esc_url( get_theme_mod( 'charity_social_facebook', '#' ) )`
- `esc_url( get_theme_mod( 'charity_social_youtube', '#' ) )`
- `esc_url( get_theme_mod( 'charity_social_instagram', '#' ) )`
Added `target="_blank" rel="noopener noreferrer"` to all three links.

## Verification
- `php -l functions.php` → No syntax errors
- `php -l header.php` → No syntax errors
- 3 `get_theme_mod.*charity_social` matches in header.php
- `esc_url_raw` sanitize callback present

## Requirements Covered
- **NAV-03** ✓ — Social links wired to real URLs via WordPress Customizer
