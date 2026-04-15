# Development Guide — Rise Up Scholarship

## Prerequisites

- PHP 8.0+ with `mbstring`, `mysqli` extensions
- MySQL 5.7+ / MariaDB 10.3+
- WordPress 6.x
- A local web server (PHP built-in, MAMP, or similar)

---

## Theme Architecture

### Template Hierarchy

| File | Purpose |
|------|---------|
| `functions.php` | Theme setup, CPTs, AJAX handlers, bilingual system |
| `front-page.php` | Homepage — hero + content pillars + latest posts |
| `page-contact.php` | Contact page (Template Name: Contact) |
| `page-announcements.php` | Announcement feed (Template Name: Announcements Feed) |
| `page-submit-post.php` | Frontend submission (Template Name: Submit Post) |
| `single.php` | Single post view |
| `page.php` | Generic page template |
| `archive.php` | Archive pages |
| `404.php` | Not found page |
| `template-parts/content-card.php` | Reusable post card component |

### CSS Architecture

All styles live in `assets/css/main.css`, organized by section:

1. **Variables** (`:root` custom properties)
2. **Reset & Base** (box-sizing, typography)
3. **Layout** (topbar, header, footer)
4. **Components** (buttons, cards, forms)
5. **Sections** (hero, feed, events, programs)
6. **Pages** (single post, comments, sidebar)
7. **Responsive** (1024px, 768px, 480px breakpoints)
8. **Animations** (fade-in, scroll-reveal)

**CSS custom properties** use semantic naming:

```css
--primary, --primary-dark, --primary-light, --primary-bg  /* brand blue */
--gold, --gold-dark                                        /* accent */
--text, --text-secondary, --text-muted                     /* text hierarchy */
--bg, --bg-light, --bg-dark                                /* backgrounds */
```

### JavaScript

`assets/js/main.js` handles:

- Sticky header shadow on scroll
- Mobile nav toggle + sub-menu accordion
- Category tab filtering (AJAX fetch)
- Load more posts (AJAX pagination)
- Like/reaction buttons (toggle with animation)
- Scroll-reveal animations (IntersectionObserver)
- Back-to-top button visibility

No build tools needed — vanilla ES6+, runs directly in modern browsers.

---

## Common Tasks

### Adding a Content Group to Homepage

Edit `front-page.php`, add to the `$content_groups` array:

```php
[
    'title_vi' => 'TIÊU ĐỀ TIẾNG VIỆT',
    'title_en' => 'ENGLISH TITLE',
    'items'    => [
        [
            'vi'      => 'Tên mục tiếng Việt',
            'en'      => 'Item Name English',
            'desc_vi' => 'Mô tả tiếng Việt.',
            'desc_en' => 'English description.',
        ],
        // ...more items (recommend 3 per group)
    ],
],
```

The CSS grid auto-adjusts. No CSS changes needed for standard content groups.

### Adding a New Page Template

1. Create `page-{slug}.php` in the theme root
2. Add the header comment:
   ```php
   <?php
   /**
    * Template Name: My Page
    */
   get_header();
   ?>
   ```
3. Use `get_header()` / `get_footer()` for consistent layout
4. Wrap all user-facing text with `charity_t('VI text', 'EN text')`
5. Create a WordPress page with that slug, and select the template in Page Attributes

### Adding Bilingual Text

```php
<!-- Simple text -->
<?php echo charity_t( 'Tiếng Việt', 'English text' ); ?>

<!-- In HTML attributes (always escape!) -->
<input placeholder="<?php echo esc_attr( charity_t( 'Nhập...', 'Enter...' ) ); ?>">
```

See [TRANSLATION_GUIDE.md](TRANSLATION_GUIDE.md) for the full guide.

### Adding a Custom Post Type

Add a `register_post_type()` call inside the `init` action in `functions.php`:

```php
register_post_type( 'my_cpt', [
    'labels'       => [
        'name'          => __( 'My Items', 'charity-hcm' ),
        'singular_name' => __( 'My Item', 'charity-hcm' ),
    ],
    'public'       => true,
    'has_archive'  => true,
    'menu_icon'    => 'dashicons-admin-generic',
    'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
    'rewrite'      => [ 'slug' => 'my-items' ],
    'show_in_rest' => true,
] );
```

Existing CPTs:
- **Events** (`event`) — slug: `/su-kien/`
- **Programs** (`program`) — slug: `/chuong-trinh/`

### Modifying CSS

1. Find the relevant section in `assets/css/main.css` (sections are labeled with comment headers)
2. Use existing CSS custom properties (`var(--primary)`, `var(--radius)`, etc.)
3. Follow BEM-like naming: `.block__element--modifier`
4. Add responsive overrides in the existing `@media` blocks at the bottom

---

## AJAX Endpoints

| Action | Handler | Nonce | Purpose |
|--------|---------|-------|---------|
| `load_more_posts` | `charity_ajax_load_more()` | `charity_load_more` | Paginated post loading |
| `toggle_post_like` | `charity_ajax_toggle_like()` | `charity_load_more` | Like/unlike a post |
| `vuonlen_submit_post` | `vuonlen_handle_submit_post()` | `vuonlen_submit_post` | Frontend post submission |

All endpoints verify nonces via `check_ajax_referer()`. The `charityHCM` JS object (localized via `wp_localize_script`) provides `ajaxurl` and `nonce` to the frontend.

---

## Deployment

1. Copy `config/wp-config.prod.php` → `wordpress/wp-config.php`
2. Replace all `YOUR_*` placeholder values with real credentials
3. Generate **new** security keys at https://api.wordpress.org/secret-key/1.1/salt/
4. Upload `wordpress/` to hosting via FTP or cPanel
5. Ensure `charity-hcm` is in `wp-content/themes/`
6. Activate the theme in WordPress Admin
7. After activation, visit Settings → Permalinks and click "Save" to flush rewrite rules

---

## Coding Conventions

| Area | Convention |
|------|-----------|
| PHP | WordPress Coding Standards (WPCS) |
| CSS | BEM-like naming (`.block__element--modifier`) |
| JS | Vanilla ES6+, strict mode, IIFE wrapper |
| Indentation | Tabs for PHP, 2 spaces for CSS/JS |
| Strings | All user-facing text through `charity_t()` |
| Security | Escape output (`esc_html`, `esc_attr`, `esc_url`), verify nonces, sanitize input |
| Commits | Conventional format: `feat:`, `fix:`, `docs:`, `style:` |
