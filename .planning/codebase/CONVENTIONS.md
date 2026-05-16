# CONVENTIONS.md — Code Conventions

## PHP

- **Security:** All output escaped with `esc_html()`, `esc_url()`, `esc_attr()`
- **ABSPATH check:** `defined( 'ABSPATH' ) || exit;` at top of `functions.php`
- **Nonce verification:** All AJAX handlers call `check_ajax_referer()` before processing
- **Input sanitization:** `absint()` for integer POST params in AJAX handlers
- **No raw `$_POST`/`$_GET`** directly used — always sanitized
- Arrow functions used for simple closures: `fn() => 25`

## CSS

- CSS variables defined in `:root` at top of `main.css`
- BEM-ish naming: `.block`, `.block__element`, `.block--modifier`
- `cp-*` prefix for content-pillar components (e.g., `.cp-group-card`, `.cp-hero`)
- `clamp()` for responsive typography (e.g., `font-size: clamp(1.5rem, 3vw, 2rem)`)
- Transitions use `var(--transition)` = `0.2s ease`
- No utility classes (no Tailwind/Bootstrap)

## JavaScript

- IIFE pattern: `(function() { 'use strict'; ... })()`
- Vanilla ES6: `const`, `let`, arrow functions, template literals, `fetch`
- DOM ready assumption: script loaded at footer (`true` in `wp_enqueue_script`)
- FormData for AJAX POST to `admin-ajax.php`
- Cookie-based: like state, language preference

## Bilingual Pattern

```php
// Pattern: charity_t( $vi_string, $en_string )
echo charity_t( 'Trang chủ', 'Home' );
echo charity_t( $group['title_vi'], $group['title_en'] );
```

## Content Group Data Pattern

```php
// All sections defined in one place:
function charity_content_groups() {
    return [
        [
            'slug'       => 'tin-tuc',
            'title_vi'   => 'TIN TỨC',
            'title_en'   => 'NEWS',
            'summary_vi' => '...',
            'summary_en' => '...',
            'items'      => [
                [ 'slug' => 'chuyen-vuon-len', 'vi' => '...', 'en' => '...', 'desc_vi' => '...', 'desc_en' => '...' ],
            ],
        ],
    ];
}
```

## Template Part Pattern

```php
get_template_part( 'template-parts/content', 'card' );
```

## Error Handling (AJAX)

```php
wp_send_json_error( [ 'message' => 'no_more' ] );
wp_send_json_success( [ 'html' => $html, 'hasMore' => $bool ] );
```
