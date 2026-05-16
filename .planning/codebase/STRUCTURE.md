# STRUCTURE.md — Directory Structure

## Repository Root

```
RiseUp/
├── README.md                    ← Project overview (needs updating for new sections)
├── Y_Tuong_De_Muc_Website_HBVL.md  ← Content proposal document (source of truth for sections)
├── config/
│   ├── content-pillars.json     ← JSON reference for content groups (mirrors functions.php)
│   ├── wp-config.local.php      ← Local DB credentials template
│   └── wp-config.prod.php       ← Production DB credentials template
├── database/
│   ├── sampleweb_wp_export.sql  ← Database dump for local setup
│   └── *.bak                    ← Backup (original UTF-16LE export)
├── docs/
│   ├── CHECKLIST.md
│   ├── DEPLOY_GUIDE.md
│   ├── DEVELOPMENT_GUIDE.md
│   ├── LOCAL_SETUP.md
│   ├── PLAN.md
│   ├── TRANSLATION_GUIDE.md
│   └── screenshots/             ← localhost screenshots
└── wordpress/                   ← Full WordPress install
    ├── wp-config.php            ← Active config (gitignored, use config/ templates)
    ├── wp-content/
    │   ├── themes/
    │   │   ├── charity-hcm/     ← ACTIVE custom theme
    │   │   ├── twentytwentyfive/
    │   │   ├── twentytwentyfour/
    │   │   ├── twentytwentythree/
    │   │   └── nook/
    │   ├── plugins/
    │   └── uploads/
    └── wp-admin/, wp-includes/  ← WordPress core (unmodified)
```

## Theme Structure (`charity-hcm/`)

```
charity-hcm/
├── style.css                    ← Theme metadata header only
├── functions.php                ← ALL theme logic (~450+ lines)
├── assets/
│   ├── css/main.css             ← All styles (~2309 lines, no preprocessor)
│   ├── js/main.js               ← All client-side JS (~200+ lines)
│   └── img/
│       ├── dong-du-logo.jpg     ← Fund logo
│       └── hero-bg.jpg          ← Hero background
├── template-parts/
│   └── content-card.php         ← Reusable post card (AJAX + loop)
├── front-page.php               ← Homepage sections
├── header.php                   ← Topbar + sticky nav
├── footer.php                   ← Dark footer
├── category.php                 ← Section archive pages
├── single.php                   ← Post detail
├── page.php, archive.php, index.php, 404.php
├── page-announcements.php
├── page-contact.php
└── page-submit-post.php
```

## Naming Conventions

- PHP templates: `kebab-case.php` (WordPress convention)
- CSS classes: BEM-inspired with `cp-*` prefix for content-pillar components
- PHP functions: `charity_` prefix (e.g., `charity_t()`, `charity_content_groups()`)
- WordPress hooks/filters: standard WP `add_action`/`add_filter` pattern
- Category slugs: Vietnamese romanized with hyphens (e.g., `tin-tuc`, `dong-du-ky`)
