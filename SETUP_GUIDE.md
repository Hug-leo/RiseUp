# 🚀 Charity HCM Website — Setup Guide
> Follow these steps to run the site on your own machine using XAMPP.

---

## What's in the ZIP

```
sampleweb/
├── wordpress/                     ← full WordPress installation + custom theme
│   ├── wp-content/
│   │   └── themes/charity-hcm/   ← the custom theme
│   ├── wp-config.php              ← database config (pre-filled)
│   └── fix-urls.php               ← URL repair tool (run if CSS is broken)
├── sampleweb_wp_export.sql        ← full database dump (~2.5 MB)
└── SETUP_GUIDE.md                 ← this file
```

**What the site contains:**
- Homepage with hero, news grid, events carousel, donate CTA, programs, and footer
- 3 published blog posts (charity series)
- 16 pages: Giới thiệu, Chuyên trang, Liên hệ, Hỗ trợ and all their subpages
- Primary navigation menu with dropdowns + footer menu
- Blog listing at `/blog/`, clean URLs for all pages and posts

---

## Step 1 — Install XAMPP

1. Install XAMPP (can also download from **https://www.apachefriends.org**)
   - Pick the version with **PHP 8.0 or higher** and **MySQL/MariaDB**
2. Install it to the default path: `C:\xampp`
3. Open **XAMPP Control Panel** and start both services:
   - ✅ **Apache**
   - ✅ **MySQL**

---

## Step 2 — Enable mod_rewrite in Apache

> This is required for clean URLs (`/blog/`, `/gioi-thieu/`, etc.) to work.
> Skip this step only if you've done it before on this XAMPP install.

1. In XAMPP Control Panel, click **Config** next to Apache → open **httpd.conf**
2. Find this line and remove the `#` at the start to uncomment it:
   ```
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```
   After editing:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. In the same file, find the section `<Directory "C:/xampp/htdocs">` and change:
   ```
   AllowOverride None
   ```
   to:
   ```
   AllowOverride All
   ```
4. Save the file and **restart Apache** in XAMPP Control Panel

---

## Step 3 — Copy the website files

1. Extract the ZIP (if you received one)
2. Copy the entire **`sampleweb`** folder into XAMPP's web root:
   ```
   C:\xampp\htdocs\sampleweb\
   ```
3. Double-check the structure looks like this:
   ```
   C:\xampp\htdocs\sampleweb\wordpress\
   C:\xampp\htdocs\sampleweb\sampleweb_wp_export.sql
   ```

---

## Step 4 — Create the database

1. Open your browser and go to:
   ```
   http://localhost/phpmyadmin
   ```
2. Click **"New"** in the left sidebar
3. Enter the database name — must be **exactly**:
   ```
   sampleweb_wp
   ```
4. Set collation to: **`utf8mb4_unicode_ci`**
5. Click **Create**

---

## Step 5 — Import the database

1. In phpMyAdmin, click **`sampleweb_wp`** in the left sidebar
2. Click the **Import** tab at the top
3. Click **Choose File** and select:
   ```
   C:\xampp\htdocs\sampleweb\sampleweb_wp_export.sql
   ```
4. Leave all settings as default, scroll down and click **Import**
5. Wait for the green ✅ success message (~2.5 MB, takes a few seconds)

---

## Step 6 — Check the config file

Open this file in Notepad or any text editor:
```
C:\xampp\htdocs\sampleweb\wordpress\wp-config.php
```

Verify these 4 lines — they should already be correct for a default XAMPP install:
```php
define( 'DB_NAME',     'sampleweb_wp' );  // must match Step 4
define( 'DB_USER',     'root' );           // XAMPP default
define( 'DB_PASSWORD', '' );              // XAMPP default — blank
define( 'DB_HOST',     'localhost' );      // keep as-is
```

> ⚠️ If you set a custom MySQL password in XAMPP, update `DB_PASSWORD` to match.

---

## Step 7 — Open the website

Visit in your browser:
```
http://localhost/sampleweb/wordpress
```

You should see the **Charity HCM homepage**. 🎉

---

## Step 8 — Log in and save Permalinks *(required)*

1. Go to the admin panel:
   ```
   http://localhost/sampleweb/wordpress/wp-admin
   ```
2. Log in with the username and password:
Qm1719
#52RXeWH%q#E4gikWC
This can be edit or add new user with whatever admin rights later.
3. Go to **Settings → Permalinks** and click **Save Changes**
   > ⚠️ This step is **not optional** — without it, all subpages and blog posts
   > will return 404 errors even though the database imported correctly.

---

## Step 9 — Fix URLs if CSS looks broken *(only if needed)*

If the homepage loads but looks unstyled or links point to the wrong address:

1. Visit:
   ```
   http://localhost/sampleweb/wordpress/fix-urls.php
   ```
2. The page will auto-detect the old URL and show you the difference
3. Click **Run URL Fix**
4. Then go back to **Settings → Permalinks → Save Changes** once more

> ⚠️ Delete `fix-urls.php` from the `wordpress/` folder after you're done with it.

---

## ❓ Troubleshooting

### "Error establishing a database connection"
- Make sure **MySQL is running** in XAMPP Control Panel
- Check `DB_NAME`, `DB_USER`, `DB_PASSWORD` in `wp-config.php` (Step 6)
- Confirm the database is named exactly `sampleweb_wp` (case-sensitive)

### Subpages or blog posts return 404
- Go to **wp-admin → Settings → Permalinks → Save Changes** (Step 8)
- If that doesn't help, re-check that `mod_rewrite` is enabled (Step 2)

### Site loads but has no CSS / images are broken
- Run `fix-urls.php` as described in Step 9

### White screen or PHP error
- Check the error log at:
  ```
  C:\xampp\htdocs\sampleweb\wordpress\wp-content\debug.log
  ```
- Most common cause: PHP version below 8.0
  → Check in XAMPP Control Panel → Apache → Config → php.ini (look for PHP version)

### phpMyAdmin won't open
- Make sure both **Apache** and **MySQL** are running (not just one)
- Try: `http://localhost:80/phpmyadmin` if the default URL doesn't work

---

## 📋 Final Checklist

- [ ] XAMPP installed, Apache + MySQL running
- [ ] `mod_rewrite` enabled in Apache config (Step 2)
- [ ] `sampleweb` folder copied to `C:\xampp\htdocs\`
- [ ] Database `sampleweb_wp` created in phpMyAdmin
- [ ] SQL file imported successfully
- [ ] `wp-config.php` credentials verified
- [ ] Homepage loads at `http://localhost/sampleweb/wordpress`
- [ ] Logged into wp-admin and saved Permalinks
- [ ] Blog works at `http://localhost/sampleweb/wordpress/blog/`
- [ ] Subpage works e.g. `http://localhost/sampleweb/wordpress/gioi-thieu/`
- [ ] `fix-urls.php` deleted after use (if you ran it)

---

*Built with WordPress + Charity HCM custom theme.*
