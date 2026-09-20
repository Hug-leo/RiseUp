# Kiểm tra logic website local

**Cập nhật luồng gửi bài:** đã thay luồng Google Drive bằng form gửi bài, lưu nháp, ảnh đại diện và Bài của tôi. Cộng tác viên biên tập/yêu cầu sửa; admin duyệt đăng hoặc từ chối. Xem [SUBMISSIONS.md](SUBMISSIONS.md). Các ghi chú Google Drive bên dưới mô tả thời điểm rà soát trước thay đổi này.

## Đã sửa

- Chuyển ngôn ngữ: ghi cookie một lần ở `init`, trước khi xuất HTML; giá trị cookie ngoài `vi`/`en` dùng tiếng Việt.
- Menu responsive: đóng menu và bỏ khóa cuộn khi đi qua điểm chuyển mobile/desktop; hỗ trợ Escape.
- Đăng nhập/đăng ký: từ chối trường dạng mảng không hợp lệ; không biến email nhập sai thành email khác bằng cách tự xóa ký tự trước khi kiểm tra.
- API thích bài: chỉ chấp nhận bài viết đã công khai và không bị khóa mật khẩu với người đang xem; không sửa số lượt thích của liên hệ, ý kiến hoặc bài riêng tư.
- API gửi bài: kiểm tra kiểu dữ liệu, giữ dấu gạch chéo ngược, từ chối nội dung chỉ có khoảng trắng, kiểm tra quyền tải tệp và báo lỗi upload. Nếu upload bị từ chối, xóa bài vừa tạo trong chính yêu cầu đó để tránh lưu bài dở và gửi trùng khi thử lại.
- Tăng phiên bản asset để trình duyệt tải JavaScript mới.

## Đã kiểm tra

- 28 liên kết nội bộ lấy trực tiếp từ trang chủ: không lỗi HTTP 4xx/5xx.
- Đăng ký qua form thật tạo đúng vai trò `riseup_member`, tự đăng nhập; đăng xuất trở về trang chủ.
- Đăng nhập cộng tác viên qua form thật vào danh sách bài viết.
- Góp ý: gửi và đọc với bốn vai trò; nonce, dữ liệu lỗi, gửi lặp, bảo toàn nội dung, hạn chế quyền vào hộp ý kiến.
- Liên hệ: lưu riêng tư, email lỗi không mất tin, phân quyền và đánh dấu xử lý.
- Bình luận: quy tắc tự duyệt cho vai trò website, giữ quyết định spam, cộng tác viên không sửa bình luận admin, thành viên không có quyền kiểm duyệt.
- Tìm kiếm và trang tỉnh hợp lệ trả 200; tỉnh không tồn tại trả 404.
- Ngôn ngữ lưu qua lần tải trang; menu mở/đóng và chuyển viewport không khóa cuộn.
- API gửi bài giữ trạng thái chờ duyệt; upload sai không tạo thêm bài.
- 25 file PHP của theme qua kiểm tra cú pháp; JavaScript chính qua `node --check`.
- Dữ liệu bản đồ: 34 tỉnh hiện tại, 63 tỉnh lịch sử, 31 thành viên duy nhất, không bản ghi không khớp.

## Chạy lại

Từ thư mục dự án, với XAMPP Apache/MySQL đang chạy:

```powershell
node scripts/test_site.cjs <duong-dan-goi-playwright>
node scripts/test_feedback.cjs <duong-dan-goi-playwright>
& C:\xampp\php\php.exe scripts/test_contact.php
& C:\xampp\php\php.exe scripts/test_comment_permissions.php
python scripts/validate_hbvl_map.py
```

Các bài kiểm tra tạo rồi xóa dữ liệu riêng của chúng. Không gửi email thử ra ngoài.

## Giới hạn xác minh

Đây là kiểm tra luồng ứng dụng local, không phải chứng nhận website không còn lỗi. Chưa kiểm tra tải lớn, mọi trình duyệt hoặc hạ tầng production. Email gửi thật và khôi phục mật khẩu qua email cần SMTP; chưa kiểm tra giao thư. Trang Đăng bài hiện dẫn tới Google Drive: chưa tải tài liệu ra dịch vụ ngoài hoặc kiểm tra quyền thư mục Drive. Kiểm tra bình luận ở mức quy tắc xử lý/phân quyền, chưa thử gửi bình luận công khai qua HTTP. Lượt thích hiện còn dựa trên cookie trình duyệt, chưa đồng bộ theo tài khoản giữa thiết bị.
