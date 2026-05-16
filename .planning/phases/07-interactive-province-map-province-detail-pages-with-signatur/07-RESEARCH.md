# Phase 07 Research: Interactive Province Map — Province Detail Pages

**Date:** 2026-05-16
**Phase:** 07 — Interactive Province Map - Province Detail Pages with Signature Photos and Member Contacts
**Requirements:** MAP-DETAIL-01, MAP-DETAIL-02, MAP-DETAIL-03 (TBD — to be formalized in ROADMAP)

---

## Scope Summary

Phase 6 built an interactive SVG choropleth map with hover tooltips and a 34/63-province toggle.
Phase 7 extends that by: clicking a highlighted province navigates to a dedicated detail page.
The detail page shows (1) a signature JPG photo of the province and (2) a contact table of Đông Du members there.

---

## Province Data Inventory

The existing `data/contact-dongdu.json` has **34 provinces with member data** (codes p11–p44):

| Code | Province | Members |
|------|----------|---------|
| p11 | Hà Nội | 3 |
| p12 | TP. Hồ Chí Minh | 3 |
| p13 | Đà Nẵng | 3 |
| p14 | Hải Phòng | 2 |
| p15 | Cần Thơ | 3 |
| p16 | Huế | 2 |
| p17 | An Giang | 3 |
| p18 | Bắc Ninh | 2 |
| p19 | Cà Mau | 3 |
| p20 | Cao Bằng | 2 |
| p21 | Đắk Lắk | 3 |
| p22 | Điện Biên | 2 |
| p23 | Đồng Nai | 3 |
| p24 | Đồng Tháp | 2 |
| p25 | Gia Lai | 3 |
| p26 | Hà Tĩnh | 2 |
| p27 | Hưng Yên | 3 |
| p28 | Khánh Hòa | 2 |
| p29 | Lai Châu | 2 |
| p30 | Lâm Đồng | 3 |
| p31 | Lạng Sơn | 2 |
| p32 | Lào Cai | 3 |
| p33 | Nghệ An | 2 |
| p34 | Ninh Bình | 3 |
| p35 | Phú Thọ | 2 |
| p36 | Quảng Ngãi | 3 |
| p37 | Quảng Ninh | 2 |
| p38 | Quảng Trị | 3 |
| p39 | Sơn La | 2 |
| p40 | Tây Ninh | 3 |
| p41 | Thái Nguyên | 2 |
| p42 | Thanh Hóa | 3 |
| p43 | Tuyên Quang | 2 |
| p44 | Vĩnh Long | 3 |

All 34 provinces need a detail page and a signature photo.

---

## Architecture Decision: Province Page Routing

### Option A — Custom Post Type (CPT) "province"
- Create CPT via `register_post_type('province', ['slug' => 'tinh', ...])`
- Create 34 WP pages via WP admin or programmatically in `functions.php`
- Template: `single-province.php`
- **Cons**: Requires 34 manually created/seeded WP posts; poor fit for data already in JSON.

### Option B — Custom Rewrite Rule + Virtual Page (RECOMMENDED)
- Register a rewrite rule: `/tinh/{province-slug}/` → `index.php?province_slug=$matches[1]`
- Register a custom query var `province_slug`
- In `template_redirect`, detect the query var and load `page-tinh.php`
- Data comes from `contact-dongdu.json` — no database rows needed
- **Pros**: Zero WP admin setup, data stays in JSON, no DB dependency, clean URL
- **Cons**: Requires `flush_rewrite_rules()` on theme activation (already done in theme)

```php
// In functions.php:
add_action('init', function() {
    add_rewrite_rule('^tinh/([^/]+)/?$', 'index.php?province_slug=$matches[1]', 'top');
});
add_filter('query_vars', function($vars) {
    $vars[] = 'province_slug';
    return $vars;
});
add_action('template_redirect', function() {
    $slug = get_query_var('province_slug');
    if ($slug) {
        include get_template_directory() . '/page-tinh.php';
        exit;
    }
});
```

### Option C — Single WordPress Page with a Query Parameter
- Create one WP page `/tinh/` and use `?p=ha-noi` URL params
- **Cons**: Not clean URLs, SEO-unfriendly, requires creating a WP page.

**Decision: Option B** — Virtual page with rewrite rule. Clean URLs, no DB dependency.

---

## URL Slug Map (Province → URL Slug)

These slugs are used in URLs (`/tinh/{slug}/`) and photo filenames (`{slug}.jpg`):

| Code | Province | URL Slug |
|------|----------|----------|
| p11 | Hà Nội | ha-noi |
| p12 | TP. Hồ Chí Minh | ho-chi-minh |
| p13 | Đà Nẵng | da-nang |
| p14 | Hải Phòng | hai-phong |
| p15 | Cần Thơ | can-tho |
| p16 | Huế | hue |
| p17 | An Giang | an-giang |
| p18 | Bắc Ninh | bac-ninh |
| p19 | Cà Mau | ca-mau |
| p20 | Cao Bằng | cao-bang |
| p21 | Đắk Lắk | dak-lak |
| p22 | Điện Biên | dien-bien |
| p23 | Đồng Nai | dong-nai |
| p24 | Đồng Tháp | dong-thap |
| p25 | Gia Lai | gia-lai |
| p26 | Hà Tĩnh | ha-tinh |
| p27 | Hưng Yên | hung-yen |
| p28 | Khánh Hòa | khanh-hoa |
| p29 | Lai Châu | lai-chau |
| p30 | Lâm Đồng | lam-dong |
| p31 | Lạng Sơn | lang-son |
| p32 | Lào Cai | lao-cai |
| p33 | Nghệ An | nghe-an |
| p34 | Ninh Bình | ninh-binh |
| p35 | Phú Thọ | phu-tho |
| p36 | Quảng Ngãi | quang-ngai |
| p37 | Quảng Ninh | quang-ninh |
| p38 | Quảng Trị | quang-tri |
| p39 | Sơn La | son-la |
| p40 | Tây Ninh | tay-ninh |
| p41 | Thái Nguyên | thai-nguyen |
| p42 | Thanh Hóa | thanh-hoa |
| p43 | Tuyên Quang | tuyen-quang |
| p44 | Vĩnh Long | vinh-long |

---

## Province Photos

### Source: Wikimedia Commons
Wikimedia Commons has freely licensed (CC BY-SA or Public Domain) high-quality photos for every Vietnamese province. Download URL pattern:
```
https://upload.wikimedia.org/wikipedia/commons/{path}
```

Key representative images per province (iconic landmarks/landscapes):

| Province | Recommended Image | Wikimedia filename |
|----------|------------------|--------------------|
| Hà Nội | Hoan Kiem Lake + Turtle Tower | `Hoan_Kiem_lake_Hanoi.jpg` |
| TP. HCM | Ben Thanh Market | `Ben_Thanh_market.jpg` |
| Đà Nẵng | Dragon Bridge | `Dragon_Bridge_Da_Nang.jpg` |
| Hải Phòng | Hải Phòng Opera House | `Hai_Phong_Opera_House.jpg` |
| Cần Thơ | Cái Răng Floating Market | `Cai_Rang_floating_market.jpg` |
| Huế | Imperial Citadel | `Hue_Citadel_Noon_Gate.jpg` |
| An Giang | Sam Mountain | `Nui_Sam_An_Giang.jpg` |
| Bắc Ninh | Bút Tháp Pagoda | `But_Thap_Pagoda_Bac_Ninh.jpg` |
| Cà Mau | Cà Mau Cape | `Ca_Mau_Cape.jpg` |
| Cao Bằng | Bản Giốc Waterfall | `Ban_Gioc_waterfall.jpg` |
| Đắk Lắk | Lak Lake | `Lak_Lake_Dak_Lak.jpg` |
| Điện Biên | Điện Biên Phủ battlefield | `Dien_Bien_Phu_monument.jpg` |
| Đồng Nai | Trị An Lake | `Tri_An_Lake_Dong_Nai.jpg` |
| Đồng Tháp | Tháp Mười Tower | `Dong_Thap_Muoi.jpg` |
| Gia Lai | Pleiku plateau | `Bien_Ho_Gia_Lai.jpg` |
| Hà Tĩnh | Thiên Cầm beach | `Thien_Cam_beach_Ha_Tinh.jpg` |
| Hưng Yên | Phố Hiến old town | `Pho_Hien_Hung_Yen.jpg` |
| Khánh Hòa | Nha Trang Bay | `Nha_Trang_Bay.jpg` |
| Lai Châu | Sìn Hồ highlands | `Sin_Ho_Lai_Chau.jpg` |
| Lâm Đồng | Dalat Flower Garden | `Da_Lat_flower_garden.jpg` |
| Lạng Sơn | Đồng Đăng border gate | `Dong_Dang_Lang_Son.jpg` |
| Lào Cai | Fansipan / Sa Pa | `Sa_Pa_Lao_Cai.jpg` |
| Nghệ An | Cửa Lò beach | `Cua_Lo_beach_Nghe_An.jpg` |
| Ninh Bình | Tràng An limestone karst | `Trang_An_Ninh_Binh.jpg` |
| Phú Thọ | Đền Hùng Temple | `Den_Hung_Phu_Tho.jpg` |
| Quảng Ngãi | Lý Sơn Island | `Ly_Son_Island_Quang_Ngai.jpg` |
| Quảng Ninh | Hạ Long Bay | `Ha_Long_Bay.jpg` |
| Quảng Trị | Hiền Lương Bridge (Ben Hai) | `Hien_Luong_Bridge_Quang_Tri.jpg` |
| Sơn La | Mộc Châu plateau | `Moc_Chau_Son_La.jpg` |
| Tây Ninh | Núi Bà Đen | `Nui_Ba_Den_Tay_Ninh.jpg` |
| Thái Nguyên | Hồ Núi Cốc Lake | `Ho_Nui_Coc_Thai_Nguyen.jpg` |
| Thanh Hóa | Sầm Sơn beach | `Sam_Son_beach_Thanh_Hoa.jpg` |
| Tuyên Quang | Na Hang hydroelectric lake | `Na_Hang_Tuyen_Quang.jpg` |
| Vĩnh Long | Cồn Phụng (Mekong island) | `Con_Phung_Vinh_Long.jpg` |

### Download Strategy
Use `curl` to download from Wikimedia Commons, then compress with macOS `sips`:
```bash
# Download (example)
curl -L "https://upload.wikimedia.org/wikipedia/commons/thumb/{hash}/{filename}/1200px-{filename}" \
     -o "assets/img/provinces/ha-noi.jpg"

# Compress to target ≤ 150KB
sips -Z 1200 --setProperty format jpeg --setProperty formatOptions 75 ha-noi.jpg --out ha-noi.jpg
```

Alternative compression tool: `convert` (ImageMagick) if available:
```bash
convert ha-noi.jpg -quality 75 -resize "1200x>" ha-noi.jpg
```

Target dimensions: 1200×800px maximum, JPEG quality 70-80, file size ≤ 150KB.

### Practical Download Approach
Rather than exact Wikimedia paths (which change with file hashes), use the Wikimedia Commons API:
```
https://commons.wikimedia.org/wiki/Special:FilePath/{Filename}
```
This URL redirects to the current canonical path regardless of hash. Example:
```
curl -L "https://commons.wikimedia.org/wiki/Special:FilePath/Hoan_Kiem_lake_(Hanoi).jpg" -o ha-noi.jpg
```

For reliable downloads without exact hashes, curl with `-L` (follow redirects) handles this well.

---

## `page-tinh.php` Template Architecture

```php
<?php
/**
 * Template: Province Detail Page
 * URL: /tinh/{province-slug}/
 */
$province_slug = get_query_var('province_slug');

// Load JSON data
$contacts_file = get_template_directory() . '/data/contact-dongdu.json';
$all_provinces = json_decode(file_get_contents($contacts_file), true);

// Find province by slug
$province_data = null;
foreach ($all_provinces as $code => $prov) {
    if (isset($prov['slug']) && $prov['slug'] === $province_slug) {
        $province_data = $prov;
        $province_data['code'] = $code;
        break;
    }
}

if (!$province_data) {
    // 404
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('404');
    exit;
}

$photo_url   = CHARITY_HCM_URI . '/assets/img/provinces/' . $province_slug . '.jpg';
$back_url    = get_category_link( get_cat_ID('ban-do-vuon-len') );
$province_name = $province_data['name'];
$members     = $province_data['members'] ?? [];

get_header();
?>
<main class="province-detail">
  <div class="province-detail__hero">
    <img src="<?php echo esc_url($photo_url); ?>" 
         alt="<?php echo esc_attr($province_name); ?>" 
         class="province-detail__photo">
    <div class="province-detail__title-wrap">
      <h1 class="province-detail__title"><?php echo esc_html($province_name); ?></h1>
      <a href="<?php echo esc_url($back_url); ?>" class="province-detail__back">
        ← <?php echo charity_t('Về bản đồ', 'Back to map'); ?>
      </a>
    </div>
  </div>
  <div class="province-detail__body container">
    <h2><?php echo charity_t('Thành viên Đông Du', 'Đông Du Members'); ?></h2>
    <?php if ($members): ?>
    <table class="province-detail__table">
      <thead>
        <tr>
          <th><?php echo charity_t('Tên', 'Name'); ?></th>
          <th><?php echo charity_t('Vai trò', 'Role'); ?></th>
          <th><?php echo charity_t('Liên hệ', 'Contact'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($members as $m): ?>
        <tr>
          <td><?php echo esc_html($m['name']); ?></td>
          <td><?php echo esc_html($m['role']); ?></td>
          <td><a href="<?php echo esc_url($m['contact']); ?>">
            <?php echo charity_t('Liên hệ', 'Contact'); ?>
          </a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <p><?php echo charity_t('Chưa có thành viên trong tỉnh này.', 'No members listed for this province yet.'); ?></p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
```

---

## student-map.js Click Extension

Phase 6's student-map.js handles `click` events on province `<path>` elements. Currently it shows a tooltip popup. Phase 7 extends this:

```js
// In student-map.js — extend click handler
// Before: click shows tooltip
// After:  click navigates to /tinh/{slug}/ if province has a detail URL
//         otherwise shows tooltip (non-member provinces)

// vuonlenMap.contacts structure after Phase 7:
// {
//   p11: { name: "Hà Nội", slug: "ha-noi", members: [...] },
//   p12: { name: "TP. Hồ Chí Minh", slug: "ho-chi-minh", members: [...] },
//   ...
// }

// In click handler:
path.addEventListener('click', function(e) {
    const code = this.id;  // e.g. "p11"
    const contact = vuonlenMap.contacts[code];
    if (contact && contact.slug) {
        window.location.href = '/tinh/' + contact.slug + '/';
    }
    // else: show tooltip (existing behavior for non-member provinces)
});
```

The `slug` field is added to `contact-dongdu.json` and exposed via `wp_localize_script`.
The cursor changes to `pointer` for member provinces (already done via CSS `.province-active`).

---

## CSS for Province Detail Page

Add a new section `/* ── Province Detail Page ─── */` in `main.css`:

```css
.province-detail__hero {
    position: relative;
    height: 380px;
    overflow: hidden;
}
.province-detail__photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.province-detail__title-wrap {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    padding: 2rem;
    color: #fff;
}
.province-detail__title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
}
.province-detail__back {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    font-size: 0.9rem;
}
.province-detail__back:hover { color: #fff; }

.province-detail__body {
    padding: 2rem 1rem;
    max-width: 800px;
    margin: 0 auto;
}
.province-detail__table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}
.province-detail__table th,
.province-detail__table td {
    border: 1px solid var(--color-border, #e2e8f0);
    padding: 0.75rem 1rem;
    text-align: left;
}
.province-detail__table th {
    background: var(--color-gray-light, #f7fafc);
    font-weight: 600;
}
@media (max-width: 768px) {
    .province-detail__hero { height: 220px; }
    .province-detail__title { font-size: 1.4rem; }
}
```

---

## Plan Breakdown

### Plan 01 — Data + Photos (Wave 1)
- Extend `contact-dongdu.json` to add `slug` field per province
- Create `assets/img/provinces/` folder
- Download all 34 province signature JPG photos from Wikimedia Commons using curl
- Compress each to ≤ 150KB using `sips` (macOS built-in)

### Plan 02 — Routing + Template (Wave 1, parallel)
- Add rewrite rule + query var to `functions.php`
- Create `page-tinh.php` province detail template
- Flush rewrite rules via `flush_rewrite_rules()` or theme activation hook

### Plan 03 — JS click handler + CSS (Wave 2, depends Plan 01 + Plan 02)
- Extend `student-map.js` to navigate on province click when `contacts[code].slug` exists
- Add province detail CSS section to `main.css`
- Update `wp_localize_script` in `functions.php` to expose slug via contacts data

---

## Security Considerations

- `get_query_var('province_slug')` output must be escaped with `esc_html()` / `esc_attr()` / `esc_url()` before rendering (XSS prevention)
- JSON data file is read from theme directory (server-side only), not from user input
- `file_get_contents()` is used on a hardcoded path — no user-controlled file paths
- All member data is static/mock data — no user-submitted content displayed

---

## Validation Architecture

### Dimension 8: Automated Test Coverage
- Verify rewrite rule routes correctly: `curl -I http://127.0.0.1:8080/tinh/ha-noi/` returns 200
- Verify 404 for unknown slugs: `curl -I http://127.0.0.1:8080/tinh/invalid-slug/` returns 404
- Verify photos exist: `ls assets/img/provinces/*.jpg | wc -l` returns 34
- Verify photo sizes: `find assets/img/provinces -name "*.jpg" -size +150k` returns 0
