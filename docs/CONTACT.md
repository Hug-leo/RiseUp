# Sử dụng tính năng Liên hệ

## Khác nhau giữa Gửi ý kiến và Liên hệ

| | Gửi ý kiến | Liên hệ |
| --- | --- | --- |
| Mục đích | Góp ý nội bộ cho ban biên tập | Gửi yêu cầu cần phản hồi qua email |
| Người gửi | Tài khoản đã đăng nhập | Tài khoản đã đăng nhập |
| Thông tin | Tiêu đề, nội dung; gắn tài khoản người gửi | Họ tên, email, chủ đề, nội dung |
| Người xem hộp thư | Cộng tác viên và quản trị viên | Chỉ quản trị viên |
| Nơi nhận trong wp-admin | **Ý kiến thành viên** | **Liên hệ** |
| Email thông báo | Không gửi; không cần SMTP | Có thử gửi sau khi lưu; cần cấu hình mail |
| Xử lý | Đọc ý kiến trong quản trị | Trả lời qua ứng dụng email, đánh dấu đã xử lý |

Hai loại đều lưu riêng tư trong database, không xuất hiện như bài viết công khai.

### Cách gửi và đọc ý kiến

1. Đăng nhập, chọn **Gửi ý kiến** ở thanh trên cùng hoặc mở `http://localhost/sampleweb/wordpress/gui-y-kien/`.
2. Nhập tiêu đề (tối đa 160 ký tự), nội dung (tối đa 5.000 ký tự), bấm **Gửi cho ban biên tập**.
3. Trang báo gửi thành công. Đợi ít nhất một phút nếu muốn gửi ý kiến tiếp; tải lại trang không gửi lại.
4. Cộng tác viên hoặc quản trị viên vào **wp-admin → Ý kiến thành viên** để xem người gửi và mở nội dung.

Gửi ý kiến chưa có chức năng phản hồi hai chiều hoặc trạng thái đã xử lý. Nếu cần trả lời qua email, dùng **Liên hệ**. Khi form báo nội dung không hợp lệ, cần nhập lại; tính năng này chưa lưu bản nháp.

Kiểm tra trình duyệt tự động (cần Playwright và XAMPP đang chạy):

```powershell
node scripts/test_feedback.cjs <duong-dan-goi-playwright>
```

Bài kiểm tra tạo rồi xóa tài khoản và ý kiến thử, kiểm tra bốn vai trò, dữ liệu không hợp lệ, nonce, gửi lặp, nội dung lưu và quyền truy cập hộp ý kiến. Không gửi email.

## Người gửi

1. Mở `http://localhost/sampleweb/wordpress/lien-he/`.
2. Đăng nhập tài khoản thành viên (quản trị viên đang đăng nhập cũng gửi được), rồi mở lại **Liên hệ**.
3. Nhập họ tên, email nhận phản hồi, chủ đề và nội dung. Chủ đề không bắt buộc; nội dung tối đa 5.000 ký tự.
4. Bấm **Gửi tin nhắn**. Trang báo **Đã tiếp nhận tin nhắn!** và mã liên hệ nghĩa là tin đã được lưu trong website.

Gửi cách nhau ít nhất một phút. Nếu nhập sai, nội dung vẫn giữ lại để sửa. Tải lại trang thành công không gửi thêm tin.

## Quản trị viên

1. Mở `http://localhost/sampleweb/wordpress/wp-admin/edit.php?post_type=riseup_contact`, hoặc chọn **Liên hệ** trong menu quản trị.
2. Mở tin nhắn để xem họ tên, email và nội dung. Hộp thư chỉ dành cho quản trị viên; thành viên và cộng tác viên không đọc được.
3. Bấm **Trả lời qua email** để mở ứng dụng email đã cài, hoặc sao chép địa chỉ người gửi vào Gmail và soạn phản hồi.
4. Sau khi trả lời, tích **Đã xử lý**, bấm **Cập nhật**.

Nút trả lời chỉ mở ứng dụng email, không tự gửi thư. Website chưa có hội thoại hai chiều hoặc trang theo dõi phản hồi cho thành viên.

## Email thông báo hoạt động thế nào?

Website lưu tin nhắn riêng tư vào database trước, sau đó gọi `wp_mail()` để thông báo tới `quykhuyenhocdongdu@gmail.com`. Header `Reply-To` dùng email người gửi.

- **Đã chuyển cho hệ thống mail**: WordPress chấp nhận gửi, chưa đảm bảo Gmail đã nhận; kiểm tra cả thư rác.
- **Email chưa gửi được; tin nhắn đã lưu**: quản trị viên vẫn đọc được trong hộp thư. Không có tự động gửi lại email.

XAMPP hiện dùng SMTP `localhost`, cổng `25`. Muốn nhận email thật cần cấu hình dịch vụ SMTP cho WordPress bằng tài khoản gửi được cấp quyền: máy chủ, cổng, mã hóa và thông tin xác thực do nhà cung cấp cấp. Không đưa mật khẩu vào mã nguồn. Sau cấu hình, gửi một thư thử được cho phép và kiểm tra hộp thư đích. Không cần SMTP để nhận và xử lý tin trong quản trị.

## Kiểm tra lại

Từ thư mục dự án, chạy:

```powershell
& C:\xampp\php\php.exe scripts/test_contact.php
```

Bài kiểm tra dùng database WordPress local, tạo rồi xóa tài khoản/tin nhắn thử; mô phỏng mail thành công và thất bại, không gửi email thật. Bao gồm kiểm tra đăng nhập, nonce, email sai, độ dài, quyền riêng tư, giới hạn gửi và đánh dấu đã xử lý.
