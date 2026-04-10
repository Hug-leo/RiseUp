# RiseUp — Charity Community WordPress Site

A bilingual (Vietnamese / English) WordPress website for a youth volunteer organization in Ho Chi Minh City. Built with a custom theme featuring a **vertical scroll feed** layout inspired by [Reedsy](https://reedsy.com/short-stories/), with post reactions, comments, and fully responsive mobile design.

## Features

- **Vertical Scroll Feed** — Stories displayed as stacked cards with author info, excerpts, likes, and comment counts (Reedsy-inspired layout)
- **Post Reactions** — Cookie-based like/heart system with AJAX (no login required)
- **Comments System** — Threaded comments with avatars, reply support, and custom styling
- **Custom Theme** (`charity-hcm`) — Responsive design with Google Fonts (Be Vietnam Pro, Playfair Display)
- **Bilingual Support** — Built-in VI/EN language switcher via cookies (no plugin required)
- **Custom Post Types** — Events (`su-kien`) and Programs (`chuong-trinh`)
- **Category Tabs** — Filter posts by category on the homepage without page reload
- **AJAX Load More** — Paginated posts loaded dynamically
- **Fully Responsive** — Optimized for mobile, tablet, and desktop
- **Clean URLs** — SEO-friendly permalinks for all content

## Homepage Sections

1. **Hero** — Gradient banner with site name, description, and CTA buttons
2. **About** — Organization info with animated stat counters
3. **Stories Feed** — Vertical card feed with category tabs, likes, comments, and "Read story" links
4. **Events Grid** — Responsive grid of upcoming events
5. **Donate CTA** — Call-to-action section for sponsorship
6. **Programs** — Grid of programs and activities
7. **Our Journey** — Full-width motivational section

## Project Structure

```
RiseUp/
├── config/                             # Environment-specific configs
│   ├── wp-config.local.php             #   → Local dev (XAMPP)
│   └── wp-config.prod.php              #   → Production hosting
├── database/
│   └── sampleweb_wp_export.sql         # Database dump (UTF-8, ~1.3 MB)
├── docs/
│   ├── LOCAL_SETUP.md                  # Step-by-step local XAMPP setup
│   └── DEPLOY_GUIDE.md                 # Step-by-step production deploy
├── wordpress/                          # Full WordPress installation
│   ├── wp-config.php                   # Active config
│   ├── fix-urls.php                    # URL repair tool
│   └── wp-content/
│       └── themes/
│           └── charity-hcm/            # Custom theme
│               ├── style.css           # Theme metadata
│               ├── functions.php       # Theme setup, CPTs, AJAX, reactions, bilingual
│               ├── header.php          # Topbar + sticky navigation
│               ├── footer.php          # 4-column footer
│               ├── front-page.php      # Homepage: hero + feed + events + programs
│               ├── single.php          # Single post with reactions + comments
│               ├── index.php           # Blog archive (vertical card list)
│               ├── archive.php         # Custom post type archives
│               ├── page.php            # Static page template
│               ├── 404.php             # Error page
│               ├── comments.php        # Threaded comments template
│               ├── sidebar.php         # Sidebar with recent posts + categories
│               ├── template-parts/
│               │   └── content-card.php # Reusable story card component
│               └── assets/
│                   ├── css/main.css    # Full responsive stylesheet
│                   └── js/main.js      # Reactions, animations, AJAX, category tabs
└── README.md                           # This file
```

## Requirements

- **PHP 8.0+**
- **MySQL 5.7+** or **MariaDB 10.3+**
- Apache with `mod_rewrite` enabled

---

## Quick Start — Local Development

> Full guide: [docs/LOCAL_SETUP.md](docs/LOCAL_SETUP.md)

1. Install and start **XAMPP** (Apache + MySQL)
2. Enable `mod_rewrite` in Apache's `httpd.conf`
3. Copy this folder to `C:\xampp\htdocs\sampleweb\`
4. Copy `config/wp-config.local.php` → `wordpress/wp-config.php`
5. Create database `sampleweb_wp` in phpMyAdmin (collation: `utf8mb4_unicode_ci`)
6. Import `database/sampleweb_wp_export.sql`
7. Visit `http://localhost/sampleweb/wordpress`
8. Log into wp-admin → **Settings → Permalinks → Save Changes**

## Quick Start — Production Deploy

> Full guide: [docs/DEPLOY_GUIDE.md](docs/DEPLOY_GUIDE.md)

1. Create a MySQL database on your hosting (cPanel / Plesk)
2. Import `database/sampleweb_wp_export.sql` via phpMyAdmin
3. Copy `config/wp-config.prod.php` → `wordpress/wp-config.php`
4. Fill in your hosting's DB credentials and generate new security keys
5. Upload `wordpress/` contents to `public_html/` via FTP or File Manager
6. Visit `https://yourdomain.com/fix-urls.php` to update all URLs
7. Save Permalinks, change admin password, delete `fix-urls.php`

---

## Admin Credentials

| Field    | Value                |
|----------|----------------------|
| Username | `Qm1719`            |
| Password | `#52RXeWH%q#E4gikWC`|

> **Change these immediately** after first login on production.

## Troubleshooting

| Problem | Solution |
|---------|----------|
| "Error establishing a database connection" | Check DB credentials in `wp-config.php` |
| Subpages return 404 | Save Permalinks in wp-admin |
| No CSS / broken images | Run `fix-urls.php` then save Permalinks again |
| Vietnamese text garbled | Re-import the SQL from `database/` — it must be UTF-8 |
| White screen | Check `wp-content/debug.log`; ensure PHP >= 8.0 |
| "Too many redirects" (production) | Check SSL + `FORCE_SSL_ADMIN` setting |
| Likes not saving | Ensure cookies are enabled; check AJAX nonce |

## Tech Stack

- WordPress 6.x
- Custom PHP theme (no page builder)
- Vanilla CSS + JS (no jQuery dependency for frontend)
- Google Fonts: Be Vietnam Pro, Playfair Display
- MariaDB / MySQL with `utf8mb4` charset
- Cookie-based post reactions (AJAX)

## License

WordPress is licensed under the [GPLv2](https://www.gnu.org/licenses/gpl-2.0.html).
The Charity HCM theme is bundled for educational/demonstration purposes.