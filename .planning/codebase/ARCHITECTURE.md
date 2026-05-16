# ARCHITECTURE.md — Architecture Overview

## Pattern

**WordPress theme architecture** — standard WP template hierarchy, no MVC abstraction layer.

- Theme acts as the presentation layer
- WordPress core handles routing, ORM (WP_Query), and user management
- No REST API usage (all data via PHP template tags and AJAX)

## Template Hierarchy

```
wordpress/wp-content/themes/charity-hcm/
├── front-page.php       ← Homepage (hero + content groups + map + posts)
├── header.php           ← Topbar + sticky nav + mobile toggle
├── footer.php           ← Dark footer with 4-column grid + back-to-top
├── category.php         ← Section/subsection archive pages with banner + breadcrumb
├── single.php           ← Single post with reactions + comments
├── page.php             ← Generic page template
├── archive.php          ← Generic archive fallback
├── 404.php              ← Not found page
├── index.php            ← Fallback
├── page-announcements.php  ← Announcements page template
├── page-contact.php     ← Contact form page
├── page-submit-post.php ← Frontend submission form
├── sidebar.php          ← Widget area sidebar
├── comments.php         ← Comment list + reply form
├── template-parts/
│   └── content-card.php ← Reusable post card partial (used by AJAX load-more)
└── functions.php        ← All theme logic (setup, CPTs, nav, AJAX, helpers)
```

## Content Groups System

The `charity_content_groups()` function in `functions.php` is the single source of truth for all 5 content sections:

| Group | Slug | Subsections |
|-------|------|-------------|
| TIN TỨC | `tin-tuc` | Chuyện Vươn Lên, Gương mặt Vươn Lên, Tiếp nối |
| ĐÔNG DU KÝ | `dong-du-ky` | Bản đồ Vươn Lên, Nhật ký chuyến đi, Giới thiệu địa điểm |
| SỔ TAY KIẾN THỨC | `so-tay-kien-thuc` | Bí kíp, Thế giới quanh ta |
| GÓC SÁCH HAY | `goc-sach-hay` | Tóm tắt sách, Viết cảm nhận sách |
| SINH HOẠT | `sinh-hoat` | Trò chơi/sinh hoạt tập thể, Tổng hợp bài hát |

This data drives: navigation menu, category auto-creation, front-page display, category.php banner, and submit-post form.

## Data Flow

```
WordPress DB (wp_categories, wp_posts)
       ↓
functions.php (charity_content_groups + WP_Query)
       ↓
PHP templates (front-page.php, category.php, single.php)
       ↓
HTML + CSS (main.css) + JS (main.js)
       ↓
Browser (AJAX for load-more and likes)
```

## Bilingual System

- `charity_t( $vi, $en )` helper function returns Vietnamese or English string based on `?lang=en` query param or cookie
- Language cookie persisted via JS; URLs use `?lang=en` or `?lang=vi`
- All user-facing strings in templates pass through `charity_t()`

## Key Entry Points

- `wordpress/index.php` → `wp-blog-header.php` → WordPress bootstrap
- `wordpress/wp-config.php` → database + salts (not committed)
- Front page: `front-page.php` (WordPress uses this when a static front page is set)
