# RiseUp Scholarship Website

<p align="center">
  <img src="wordpress/wp-content/themes/charity-hcm/assets/img/dong-du-logo.jpg" alt="Dong Du Study Encouragement Fund Logo" width="320" />
</p>

WordPress project for **Học Bổng Vươn Lên (RiseUp Scholarship)** under **Quỹ Khuyến Học Đông Du**.

This repository contains a full WordPress setup and the custom theme `charity-hcm` (v2.2.0), including bilingual content (VI/EN), 5 structured content sections, announcement feed, frontend post submission, contact page, and responsive UI.

## 5 Content Sections (Chuyên mục)

The site is organized around 5 main content pillars defined in `Y_Tuong_De_Muc_Website_HBVL.md`:

| # | Section | Slug | Subsections |
|---|---------|------|-------------|
| 1 | **TIN TỨC** — News | `tin-tuc` | Chuyện Vươn Lên · Gương mặt Vươn Lên · Tiếp nối |
| 2 | **ĐÔNG DU KÝ** — Dong Du Journeys | `dong-du-ky` | Bản đồ Vươn Lên · Nhật ký chuyến đi · Giới thiệu địa điểm |
| 3 | **SỔ TAY KIẾN THỨC** — Knowledge Handbook | `so-tay-kien-thuc` | Bí kíp · Thế giới quanh ta |
| 4 | **GÓC SÁCH HAY** — Book Corner | `goc-sach-hay` | Tóm tắt sách · Viết cảm nhận sách |
| 5 | **SINH HOẠT** — Community Activities | `sinh-hoat` | Trò chơi/sinh hoạt tập thể · Tổng hợp bài hát |

All sections and subsections are auto-created as WordPress categories at theme init via `charity_content_groups()` in `functions.php`.

## Highlights

- **5 structured content sections** from the HBVL proposal — auto-creating WP categories, navigation menu, and category archive pages
- Bilingual switch VI/EN with cookie-based translator helper (`charity_t()`)
- Custom scholarship theme (`charity-hcm`) with responsive layout
- **Bản đồ Vươn Lên** — Vietnam map concept (visual mockup with location pins)
- Story feed with reactions, likes, and comments
- Frontend post submission flow (pending moderation)
- Announcement and contact page templates
- Local and production config templates
- GSD planning docs in `.planning/` (roadmap, requirements, codebase map)

## Unified Repository Structure

```text
RiseUp/
├── Y_Tuong_De_Muc_Website_HBVL.md   ← Content section proposal (source of truth)
├── config/
│   ├── content-pillars.json          ← JSON reference for content groups
│   ├── wp-config.local.php
│   └── wp-config.prod.php
├── database/
│   └── sampleweb_wp_export.sql
├── docs/
│   ├── LOCAL_SETUP.md
│   ├── DEPLOY_GUIDE.md
│   ├── DEVELOPMENT_GUIDE.md
│   ├── TRANSLATION_GUIDE.md
│   ├── PLAN.md
│   └── screenshots/
├── .planning/                        ← GSD project planning docs
│   ├── PROJECT.md
│   ├── REQUIREMENTS.md
│   ├── ROADMAP.md
│   ├── STATE.md
│   ├── config.json
│   └── codebase/                     ← Architecture & stack docs
└── wordpress/
    └── wp-content/themes/charity-hcm/  ← Active custom theme (v2.2.0)
```

> [!NOTE]
> Redundant local copies were removed. This root structure is now the single source of truth.

## Localhost Preview

### Homepage
![Homepage](docs/screenshots/localhost-home.jpg)

### Announcements
![Announcements](docs/screenshots/localhost-announcements.jpg)

### Contact
![Contact](docs/screenshots/localhost-contact.jpg)

## Quick Start (Local)

1. Copy `config/wp-config.local.php` to `wordpress/wp-config.php`.
2. Create database `sampleweb_wp`.
3. Import `database/sampleweb_wp_export.sql`.
4. Start Apache + MySQL.
5. Open `http://localhost/sampleweb/wordpress`.

Detailed setup: [docs/LOCAL_SETUP.md](docs/LOCAL_SETUP.md)

## Deployment

Production deployment guide: [docs/DEPLOY_GUIDE.md](docs/DEPLOY_GUIDE.md)

## Development Docs

- [docs/DEVELOPMENT_GUIDE.md](docs/DEVELOPMENT_GUIDE.md)
- [docs/TRANSLATION_GUIDE.md](docs/TRANSLATION_GUIDE.md)
- [docs/PLAN.md](docs/PLAN.md)
- [.planning/ROADMAP.md](.planning/ROADMAP.md)
- [.planning/REQUIREMENTS.md](.planning/REQUIREMENTS.md)

## Content Architecture

All 5 content sections are defined in a single PHP function:

```php
// wordpress/wp-content/themes/charity-hcm/functions.php
charity_content_groups()
```

This drives: navigation menu, auto-created WP categories, front-page section display, and category archive pages. To add or rename a section, update this function only.

## Tech Stack

- WordPress 6.x
- PHP 8+
- MySQL / MariaDB
- Custom PHP theme (`charity-hcm` v2.2.0) + vanilla CSS/JS
- No npm, no build tool, no CSS preprocessor
