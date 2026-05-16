---
plan: 03-01
phase: 03-section-specific-category-page-templates
status: complete
commit: c789236
---

# Plan 03-01 Summary — Section-Specific Template Parts

## What Was Built

Three new WordPress template parts for section-specific post card layouts:

1. **`template-parts/content-song.php`** — Song card with musical note SVG icon, title link, trimmed excerpt (20 words), and "Xem bài hát / View Song" CTA. Used by the `tong-hop-bai-hat` category loop.

2. **`template-parts/content-profile.php`** — Profile card with optional post thumbnail (falls back gracefully if absent), member name link, summary excerpt (25 words), and "Xem hồ sơ / View Profile" CTA. Used by the `guong-mat-vuon-len` category loop.

3. **`template-parts/content-tip.php`** — Compact tip card with first-category label link, title, short excerpt (18 words), and "Đọc thêm / Read more" CTA. Used by `bi-kip` and `the-gioi-quanh-ta` loops.

## Security

- All dynamic output escaped via `esc_html()`, `esc_url()`, `esc_attr()`
- `defined('ABSPATH') || exit` guard at top of each file
- No raw `$_POST`/`$_GET` access

## Conventions

- BEM class naming: `.{type}-card`, `.{type}-card__inner`, `.{type}-card__body`, `.{type}-card__title`
- Bilingual strings via `charity_t( $vi, $en )`
- Pattern mirrors `content-card.php` reference

## Artifacts Created

| File | BEM Block | Wired By |
|------|-----------|----------|
| `template-parts/content-song.php` | `.song-card` | `category.php` via `get_template_part('template-parts/content', 'song')` |
| `template-parts/content-profile.php` | `.profile-card` | `category.php` via `get_template_part('template-parts/content', 'profile')` |
| `template-parts/content-tip.php` | `.tip-card` | `category.php` via `get_template_part('template-parts/content', 'tip')` |
