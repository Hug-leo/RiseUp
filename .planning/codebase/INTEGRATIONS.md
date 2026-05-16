# INTEGRATIONS.md — External Integrations

## Database

- **MySQL / MariaDB** — WordPress default tables + `_post_likes` custom post meta
- Connection: `wordpress/wp-config.php` (gitignored); templates in `config/`

## Google Fonts (CDN)

- Enqueued via `wp_enqueue_style` in `functions.php`
- Families: Be Vietnam Pro, Noto Sans, Playfair Display
- No local font hosting — requires internet access

## Vietnam Map Image (External CDN)

- `charity_vietnam_map_image_url()` returns a hardcoded external URL:
  `https://meeymap.com/tin-tuc/wp-content/uploads/2025/06/Ban-do-34-tinh-thanh-Viet-Nam-sau-sat-nhap.jpg`
- **Risk:** External dependency; URL may break. Should be self-hosted.

## WordPress AJAX

- `admin-ajax.php` used for:
  - `load_more_posts` — paginated story feed
  - `toggle_post_like` — cookie-based like/unlike reactions
- Nonce: `charity_load_more` (generated per page load, localized via `wp_localize_script`)

## Social Links (Placeholder)

- Facebook, YouTube, Instagram links in `header.php` topbar — currently `href="#"` (not wired)

## Email / Contact

- `wordpress/contact.php` custom page template — form handling not yet visible in theme (likely WP native or plugin)
- `wordpress/page-submit-post.php` — frontend post submission (pending moderation)

## No Third-Party Plugins Observed

- No plugin dependencies identified in theme code
- No payment gateway, analytics, or newsletter integrations currently wired
