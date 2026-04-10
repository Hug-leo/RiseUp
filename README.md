# Vuon Len Scholarship — Dong Du Study Encouragement Fund

A bilingual (Vietnamese / English) WordPress website for **Hoc Bong Vuon Len** (Vuon Len Scholarship), one of three scholarship programs under the **Dong Du Study Encouragement Fund** (Quỹ Khuyến Học Đông Du), founded by teacher Nguyen Duc Hoe — Principal of Dong Du Japanese Language School in Ho Chi Minh City.

Built with a custom WordPress theme featuring a **vertical scroll feed** layout inspired by [Reedsy](https://reedsy.com/short-stories/), with post reactions, comments, frontend post submission, and fully responsive mobile design.

## Table of Contents

- [Features](#features)
- [Homepage Sections](#homepage-sections)
- [Page Templates](#page-templates)
- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Quick Start — Local Development](#quick-start--local-development)
- [Quick Start — Production Deploy](#quick-start--production-deploy)
- [Admin Credentials](#admin-credentials)
- [Organization Info](#organization-info)
- [Troubleshooting](#troubleshooting)
- [Tech Stack](#tech-stack)
- [License](#license)
- [Standalone Pages (Legacy)](#standalone-pages-legacy)

## Features

- **Vertical Scroll Feed** — Stories displayed as stacked cards with author info, excerpts, likes, and comment counts (Reedsy-inspired layout)
- **Frontend Post Submission** — Anyone can submit a post via a clean form; posts go to "pending" for admin review
- **Post Reactions** — Cookie-based like/heart system with AJAX (no login required)
- **Comments System** — Threaded comments with avatars, reply support, and custom styling
- **Announcements Board** — Dedicated page template with category filter tabs and scroll-reveal cards
- **Contact Page** — Contact form with `wp_mail()`, Google Maps embed, and info cards
- **Custom Theme** (`charity-hcm`) — Responsive design with Google Fonts (Be Vietnam Pro, Playfair Display)
- **Bilingual Support** — Built-in VI/EN language switcher via cookies (no plugin required)
- **Custom Post Types** — Events (`su-kien`) and Scholarship Programs (`chuong-trinh`)
- **Category Tabs** — Filter posts by category on the homepage without page reload
- **AJAX Load More** — Paginated posts loaded dynamically
- **Fully Responsive** — Optimized for mobile, tablet, and desktop
- **Clean URLs** — SEO-friendly permalinks for all content
- **Education Blue Color Scheme** — Blue (#1565C0) primary + Gold (#F9A825) accent

## Homepage Sections

1. **Hero** — Gradient banner with site name, description, and CTA buttons
2. **About** — Organization info with animated stat counters (30 scholarships/year, 9M VND/scholarship, 8+ years, 3 funds)
3. **Stories Feed** — Vertical card feed with category tabs, likes, comments, "Submit Post" CTA, and "Read story" links
4. **Events Grid** — Responsive grid of upcoming events
5. **Support CTA** — Call-to-action section for fund donations
6. **Scholarship Programs** — Grid of programs (La Xanh, Mai Vang, Vuon Len, and more)
7. **Our Journey** — Full-width motivational section with scholarship stories

## Page Templates

| Template | Slug | Description |
|----------|------|-------------|
| Submit Post | `viet-bai` | Frontend post submission form (pending review) |
| Announcements Feed | `thong-bao` | Reedsy-style announcement board with filter tabs |
| Contact | `lien-he` | Contact form + Google Maps + info cards |

## Project Structure

```
RiseUp/
├── config/                             # Environment-specific configs
│   ├── wp-config.local.php             #   → Local dev (XAMPP/Homebrew)
│   └── wp-config.prod.php              #   → Production hosting
├── database/
│   └── sampleweb_wp_export.sql         # Database dump (UTF-8, ~1.3 MB)
├── docs/
│   ├── LOCAL_SETUP.md                  # Step-by-step local setup
│   └── DEPLOY_GUIDE.md                 # Step-by-step production deploy
├── wordpress/                          # Full WordPress installation
│   ├── wp-config.php                   # Active config
│   ├── fix-urls.php                    # URL repair tool
│   └── wp-content/
│       └── themes/
│           └── charity-hcm/            # Custom theme
│               ├── style.css           # Theme metadata
│               ├── functions.php       # Theme setup, CPTs, AJAX, reactions, bilingual, post submission
│               ├── header.php          # Topbar + sticky navigation
│               ├── footer.php          # 4-column footer
│               ├── front-page.php      # Homepage: hero + feed + events + programs
│               ├── single.php          # Single post with reactions + comments
│               ├── index.php           # Blog archive (vertical card list)
│               ├── archive.php         # Custom post type archives
│               ├── page.php            # Static page template
│               ├── page-submit-post.php    # Frontend post submission form
│               ├── page-announcements.php  # Announcements feed (Reedsy-style)
│               ├── page-contact.php        # Contact page with form + map
│               ├── 404.php             # Error page (bilingual)
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
8. Log into wp-admin → **Settings → General** → Set:
   - **Site Title**: `Học Bổng Vươn Lên`
   - **Tagline**: `Quỹ Khuyến Học Đông Du`
9. Go to **Settings → Permalinks → Save Changes**
10. Create the following pages in wp-admin:
    - **Viết bài** (slug: `viet-bai`) → Template: "Submit Post"
    - **Thông báo** (slug: `thong-bao`) → Template: "Announcements Feed"
    - **Liên hệ** (slug: `lien-he`) → Template: "Contact"

## Quick Start — Production Deploy

> Full guide: [docs/DEPLOY_GUIDE.md](docs/DEPLOY_GUIDE.md)

1. Create a MySQL database on your hosting (cPanel / Plesk)
2. Import `database/sampleweb_wp_export.sql` via phpMyAdmin
3. Copy `config/wp-config.prod.php` → `wordpress/wp-config.php`
4. Fill in your hosting's DB credentials and generate new security keys
5. Upload `wordpress/` contents to `public_html/` via FTP or File Manager
6. Visit `https://yourdomain.com/fix-urls.php` to update all URLs
7. Update Site Title and Tagline in Settings → General
8. Create the three page templates (Submit Post, Announcements, Contact)
9. Save Permalinks, change admin password, delete `fix-urls.php`

---

## Admin Credentials

| Field    | Value                |
|----------|----------------------|
| Username | `Qm1719`            |
| Password | `#52RXeWH%q#E4gikWC`|

> **Change these immediately** after first login on production.

## Organization Info

| Field | Value |
|-------|-------|
| Organization | Quỹ Khuyến Học Đông Du |
| Scholarship | Học Bổng Vươn Lên |
| Founded by | Thầy Nguyễn Đức Hoè |
| Since | 2018 |
| Scholarships/year | 30 |
| Amount | 9,000,000 VND/year (1,000,000/month × 9 months) |
| Address | 43D/46 Hồ Văn Huê, Phú Nhuận, TP. HCM |
| Phone | 084 3214 142 (Thanh Vẹn) |
| Email | quykhuyenhocdongdu@gmail.com |

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
| Submit Post not working | Ensure page exists with "Submit Post" template assigned |
| Contact form not sending | Check wp_mail() config; may need SMTP plugin on some hosts |

## Tech Stack

- WordPress 6.x
- Custom PHP theme (no page builder)
- Vanilla CSS + JS (no jQuery dependency for frontend)
- Google Fonts: Be Vietnam Pro, Playfair Display
- MariaDB / MySQL with `utf8mb4` charset
- Cookie-based post reactions (AJAX)
- Frontend post submission (AJAX + `wp_insert_post`)
- Contact form via `wp_mail()`

## License

WordPress is licensed under the [GPLv2](https://www.gnu.org/licenses/gpl-2.0.html).
The theme is bundled for educational/demonstration purposes.

---

## Standalone Pages (Legacy)

The `wordpress/` folder also contains standalone PHP pages (`home.php`, `events.php`,
`contact.php`, `announcement-feed.php`, `announcement-feed.html`) that work without
WordPress. These have been superseded by the WordPress theme and page templates above.
They are kept for reference but are not required for the WordPress deployment.