# Translation Guide — Bilingual System (VI / EN)

## How It Works

The site uses a lightweight cookie-based bilingual system defined in `functions.php`. No WordPress plugin needed.

### Core Functions

```php
// Get current language — returns 'vi' or 'en'
charity_get_lang()

// Return the correct string based on current language
charity_t( $vietnamese_text, $english_text )

// Generate a URL that switches language
charity_lang_url( 'en' )   // or 'vi'
```

### User Flow

1. User clicks **VI** or **EN** in the topbar language switcher
2. The link adds `?lang=vi` or `?lang=en` to the current URL
3. `charity_get_lang()` reads the `lang` query param, sets a cookie (`charity_lang`), and returns the language code
4. On subsequent page loads, the cookie is read — no query param needed
5. Cookie persists for 1 year

---

## Usage

### In PHP Templates

```php
<!-- Simple text -->
<?php echo charity_t( 'Trang chủ', 'Home' ); ?>

<!-- In HTML attributes (always escape!) -->
<a href="..." aria-label="<?php echo esc_attr( charity_t( 'Đọc thêm', 'Read more' ) ); ?>">

<!-- Multi-line or HTML content -->
<p><?php echo charity_t(
    'Dòng đầu tiên.<br>Dòng thứ hai.',
    'First line.<br>Second line.'
); ?></p>

<!-- In PHP arrays (e.g. content groups in front-page.php) -->
$groups = [
    [
        'title_vi' => 'VIẾT VÀ ĐI',
        'title_en' => 'WRITE & TRAVEL',
    ],
];
echo charity_t( $group['title_vi'], $group['title_en'] );
```

### In JavaScript (via wp_localize_script)

PHP side (`functions.php`):
```php
wp_localize_script( 'charity-hcm-main', 'charityHCM', [
    'loadMoreText' => charity_t( 'Xem thêm bài viết', 'Load more stories' ),
] );
```

JS side (`main.js`):
```javascript
button.textContent = charityHCM.loadMoreText;
```

---

## Where Translatable Strings Are

| File | What's Translated |
|------|-------------------|
| `header.php` | Site name, tagline, nav items, topbar location |
| `footer.php` | Brand name, description, quick links, copyright |
| `front-page.php` | Hero text, content pillar titles & descriptions, section headers |
| `page-contact.php` | Contact info labels, form labels, error/success messages |
| `page-announcements.php` | Filter labels, breadcrumbs, page title |
| `page-submit-post.php` | Form labels, placeholders, validation messages |
| `functions.php` | AJAX response messages, localized JS strings |

---

## Adding New Translatable Strings

1. Find the text you want to make bilingual
2. Replace it with `charity_t()`:

```php
// Before (hardcoded Vietnamese)
<h2>Tin tức mới</h2>

// After (bilingual)
<h2><?php echo charity_t( 'Tin tức mới', 'Latest News' ); ?></h2>
```

3. Always escape output when inside HTML attributes or untrusted contexts

---

## Common Pitfalls

### 1. Don't use `bloginfo()` for translatable fields

`bloginfo('name')` and `bloginfo('description')` read from the WordPress database, which is language-agnostic. They always return the same value regardless of the active language.

```php
// BAD — always shows DB value, ignores language switch
<?php bloginfo( 'name' ); ?>

// GOOD — switches with language
<?php echo charity_t( 'Vươn Lên', 'Rise Up' ); ?>
```

### 2. Always escape in HTML attributes

```php
// BAD — XSS risk if the translated string contains special characters
<input placeholder="<?php echo charity_t( 'Tìm...', 'Search...' ); ?>">

// GOOD — properly escaped
<input placeholder="<?php echo esc_attr( charity_t( 'Tìm...', 'Search...' ) ); ?>">
```

### 3. WordPress category names aren't auto-translated

Category names stored in the database are single-language. If you need bilingual category display, options include:
- Use term meta to store the English name
- Use a naming convention like `"Thơ / Poetry"` in the category name
- Map category slugs to translated names in the theme code

### 4. Language persists via cookie, not URL

After the user clicks the language switcher, the `charity_lang` cookie is set. All subsequent page loads use the cookie. You don't need to append `?lang=` to every link — the language follows the user automatically.

### 5. `charity_t()` always returns a string

It never echoes directly. You must use `echo` explicitly:

```php
// BAD — outputs nothing
<?php charity_t( 'Xin chào', 'Hello' ); ?>

// GOOD
<?php echo charity_t( 'Xin chào', 'Hello' ); ?>
```

---

## Testing Language Switch

1. Open the site in your browser
2. Click **EN** in the topbar → all sections should switch to English
3. Navigate to other pages → language should persist (cookie)
4. Click **VI** to switch back → all sections return to Vietnamese
5. Open DevTools → Application → Cookies → look for `charity_lang` = `en` or `vi`
6. Clear the cookie → language resets to default (Vietnamese)

### Quick Test Checklist

- [ ] Hero section (site name, subtitle, buttons)
- [ ] Navigation menu items
- [ ] Content pillar titles and descriptions
- [ ] Footer (brand, links, copyright)
- [ ] Contact page (labels, form placeholders)
- [ ] Announcements page (filter, breadcrumbs)
- [ ] Submit Post page (form labels)
- [ ] Topbar location text
