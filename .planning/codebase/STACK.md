# STACK.md — Technology Stack

## Runtime & Language

- **PHP 8+** — server-side templating via WordPress template hierarchy
- **MySQL / MariaDB** — database backend (WordPress tables + custom meta)
- **JavaScript (vanilla ES6)** — browser-side interactions, no build tool
- **CSS (vanilla)** — 2,309-line `main.css` using CSS custom properties (variables)

## CMS & Framework

- **WordPress 6.x** — full WP install under `wordpress/`
- **Custom theme: `charity-hcm`** (v2.2.0) under `wordpress/wp-content/themes/charity-hcm/`
- No page builder (Gutenberg blocks not used in theme templates)
- No npm / package.json — pure PHP + vanilla CSS/JS

## Custom Post Types (registered in `functions.php`)

| CPT | Slug | Purpose |
|-----|------|---------|
| Event | `su-kien` | Fund events |
| Scholarship Program | `chuong-trinh` | Scholarship programs |

## Typography

- **Be Vietnam Pro** (300–800) via Google Fonts — primary UI font
- **Noto Sans** (400, 500, 700) — fallback / multilingual support
- **Playfair Display** (700) — serif headings / hero titles

## CSS Architecture

- CSS variables via `:root` in `main.css`
- BEM-ish naming: `.story-card__body`, `.nav-menu > li`, `.cp-group-card`
- `cp-*` prefix for content-pillar-specific components
- No preprocessor (plain CSS)

## Key Files

| File | Purpose |
|------|---------|
| `wordpress/wp-content/themes/charity-hcm/functions.php` | Theme setup, CPTs, nav, AJAX handlers, `charity_content_groups()` |
| `wordpress/wp-content/themes/charity-hcm/assets/css/main.css` | All styles (~2309 lines) |
| `wordpress/wp-content/themes/charity-hcm/assets/js/main.js` | Sticky header, mobile nav, feed tabs, load-more, reactions |
| `config/content-pillars.json` | JSON reference for content group structure |
| `config/wp-config.local.php` | Local DB credentials template |
| `config/wp-config.prod.php` | Production DB credentials template |
