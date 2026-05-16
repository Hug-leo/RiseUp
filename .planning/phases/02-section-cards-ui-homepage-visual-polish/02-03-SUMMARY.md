# Plan 02-03 Summary: Self-host Vietnam Map + Animated Pins

## Status: Completed

## What was built
- Downloaded Vietnam map image (453KB) from external CDN to `wordpress/wp-content/themes/charity-hcm/assets/img/vietnam-map.jpg`
- Updated `charity_vietnam_map_image_url()` in `functions.php` to return `get_template_directory_uri() . '/assets/img/vietnam-map.jpg'` — no external dependency
- Added `@keyframes cp-pin-pulse` animation (box-shadow pulse effect) to `main.css`
- Added `animation: cp-pin-pulse 2s ease-out infinite` to `.cp-map-pin`
- Added staggered `animation-delay` to the 3 pins: north 0s, central 0.66s, south 1.33s

## Files modified
- `wordpress/wp-content/themes/charity-hcm/functions.php` (already done in 02-01)
- `wordpress/wp-content/themes/charity-hcm/assets/img/vietnam-map.jpg` (new file, 453KB)
- `wordpress/wp-content/themes/charity-hcm/assets/css/main.css`

## Self-Check: PASSED
