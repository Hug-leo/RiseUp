# RiseUp Checklist

## Local Run

- [ ] Cài XAMPP hoặc môi trường có Apache, MySQL, PHP 8.0+
- [ ] Bật `Apache` và `MySQL`
- [ ] Copy [config/wp-config.local.php](../config/wp-config.local.php) sang [wordpress/wp-config.php](../wordpress/wp-config.php)
- [ ] Tạo database tên `sampleweb_wp`
- [ ] Import [database/sampleweb_wp_export.sql](../database/sampleweb_wp_export.sql)
- [ ] Mở site tại `http://localhost/sampleweb/wordpress`
- [ ] Vào `wp-admin` và bấm lưu `Permalinks`
- [ ] Nếu CSS / link sai, chạy `fix-urls.php`
- [ ] Xóa `fix-urls.php` sau khi sửa xong

## Production Deploy

- [ ] Có hosting PHP 8.0+ và MySQL/MariaDB
- [ ] Tạo database + user mới trên hosting
- [ ] Import [database/sampleweb_wp_export.sql](../database/sampleweb_wp_export.sql)
- [ ] Copy [config/wp-config.prod.php](../config/wp-config.prod.php) sang [wordpress/wp-config.php](../wordpress/wp-config.php)
- [ ] Thay toàn bộ giá trị `YOUR_*` bằng thông tin thật
- [ ] Tạo security keys mới từ WordPress salt generator
- [ ] Upload toàn bộ nội dung thư mục `wordpress/` lên hosting
- [ ] Cập nhật `siteurl` và `home` theo domain thật
- [ ] Vào `wp-admin` và bấm lưu `Permalinks`
- [ ] Kiểm tra trang chủ, bài viết, trang con, đổi ngôn ngữ VI/EN
- [ ] Xóa `fix-urls.php` khỏi server nếu đã dùng
