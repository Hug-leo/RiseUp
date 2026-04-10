# Deploy Guide — Production Hosting

> How to deploy the Charity HCM website to a real hosting provider (cPanel, Plesk, etc.)

---

## Prerequisites

- A hosting plan with **PHP 8.0+** and **MySQL 5.7+ / MariaDB 10.3+**
- A registered domain (e.g. `yourdomain.com`)
- FTP client (FileZilla) or cPanel File Manager access
- phpMyAdmin or similar database tool on the hosting

---

## Step 1 — Create the Database

1. Log into your hosting control panel (cPanel / Plesk / DirectAdmin)
2. Go to **MySQL Databases**
3. Create a new database — note the name (e.g. `username_charityhcm`)
4. Create a new database user with a **strong password**
5. Add the user to the database with **All Privileges**
6. Write down these 3 values:
   - Database name
   - Database user
   - Database password

---

## Step 2 — Import the Database

1. Open **phpMyAdmin** from your hosting panel
2. Select the database you just created
3. Click **Import** → choose `database/sampleweb_wp_export.sql`
4. Click **Go** and wait for the success message

---

## Step 3 — Configure wp-config.php

1. Copy `config/wp-config.prod.php` → `wordpress/wp-config.php`
2. Open it and replace the placeholder values:

```php
define( 'DB_NAME',     'username_charityhcm' );   // from Step 1
define( 'DB_USER',     'username_dbuser' );        // from Step 1
define( 'DB_PASSWORD', 'your_strong_password' );   // from Step 1
define( 'DB_HOST',     'localhost' );               // usually localhost
```

3. **Generate new security keys** — visit:
   ```
   https://api.wordpress.org/secret-key/1.1/salt/
   ```
   Copy the output and replace all 8 `GENERATE_NEW_KEY` lines.

4. After setting up SSL, uncomment this line:
   ```php
   define( 'FORCE_SSL_ADMIN', true );
   ```

---

## Step 4 — Upload Files

### Option A: cPanel File Manager
1. Zip the entire `wordpress/` folder
2. Upload the zip to `public_html/` (or a subdirectory)
3. Extract it on the server

### Option B: FTP (FileZilla)
1. Connect with FTP credentials from your hosting
2. Upload the `wordpress/` folder contents to `public_html/`
   - Upload the **contents** of `wordpress/`, not the folder itself
   - So `wp-config.php`, `wp-admin/`, `wp-content/`, etc. should be directly inside `public_html/`

### Folder structure on hosting:
```
public_html/
├── wp-config.php        ← production config
├── wp-admin/
├── wp-content/
│   └── themes/
│       └── charity-hcm/
├── wp-includes/
├── index.php
└── ...
```

---

## Step 5 — Fix URLs

The database still has `http://localhost/sampleweb/wordpress` as the site URL.
You need to update it to your real domain.

### Method A: Using fix-urls.php (recommended)
1. Visit `https://yourdomain.com/fix-urls.php`
2. Log in with admin credentials
3. Enter the old URL: `http://localhost/sampleweb/wordpress`
4. Click **Run URL Fix**
5. **Delete `fix-urls.php`** from the server after use (security risk!)

### Method B: Using phpMyAdmin (manual)
Run these SQL queries (replace `yourdomain.com` with your actual domain):

```sql
UPDATE wp_options SET option_value = 'https://yourdomain.com'
WHERE option_name IN ('siteurl', 'home');
```

Then go to wp-admin → **Settings → Permalinks → Save Changes**.

---

## Step 6 — Save Permalinks

1. Go to `https://yourdomain.com/wp-admin`
2. Log in (username: `Qm1719`, password: `#52RXeWH%q#E4gikWC`)
3. Go to **Settings → Permalinks → Save Changes**
4. **Change the admin password immediately** under Users → Your Profile

---

## Step 7 — Enable SSL (HTTPS)

Most hosting providers offer free SSL via Let's Encrypt:
1. Go to your hosting control panel → **SSL/TLS** or **Let's Encrypt**
2. Enable SSL for your domain
3. In `wp-config.php`, uncomment: `define( 'FORCE_SSL_ADMIN', true );`
4. In wp-admin → **Settings → General**, change both URLs to use `https://`

---

## Step 8 — Post-Deploy Checklist

- [ ] Site loads at `https://yourdomain.com`
- [ ] Vietnamese text displays correctly (UTF-8)
- [ ] All pages work: Giới thiệu, Chuyên trang, Liên hệ, Hỗ trợ
- [ ] Blog posts load correctly
- [ ] Language switcher (VI/EN) works
- [ ] Images and CSS load properly
- [ ] Admin password changed from default
- [ ] `fix-urls.php` deleted from server
- [ ] `WP_DEBUG` is set to `false`
- [ ] SSL certificate active (green padlock)

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| "Error establishing a database connection" | Double-check DB_NAME, DB_USER, DB_PASSWORD in wp-config.php |
| Site loads but no CSS/images | Run fix-urls.php or manually update siteurl/home in wp_options |
| 404 on all pages except homepage | Save Permalinks; check if hosting supports mod_rewrite |
| White screen | Enable WP_DEBUG temporarily, check wp-content/debug.log |
| Vietnamese text garbled | Ensure database collation is `utf8mb4_unicode_ci` |
| "Too many redirects" | Check FORCE_SSL_ADMIN setting; make sure SSL is working first |

---

## Hosting Recommendations (Vietnam)

| Provider | Starting Price | Notes |
|----------|---------------|-------|
| [Tinohost](https://tinohost.com) | ~50k VND/month | Vietnamese support, cPanel |
| [SUSPENDED](https://azdigi.com) | ~60k VND/month | Good performance, cPanel |
| [Hostinger](https://hostinger.vn) | ~45k VND/month | Cheap, hPanel |
| [Namecheap](https://namecheap.com) | ~$2/month | International, cPanel |

All of these support WordPress, PHP 8.0+, MySQL, and free SSL.
