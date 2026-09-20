# Gửi bài và duyệt bài

## Thành viên

1. Đăng nhập, chọn **Đăng bài** hoặc mở `/gui-bai/`.
2. Nhập tiêu đề, chuyên mục, nội dung; chọn ảnh đại diện nếu cần (JPEG/PNG/WebP, tối đa 5 MB).
3. Bấm **Lưu nháp** để viết tiếp, hoặc **Gửi duyệt** khi hoàn tất.
4. Theo dõi tại `/bai-cua-toi/` hoặc **Tài khoản → Gửi bài / Bài của tôi**.

Trạng thái: Nháp → Chờ duyệt → Đã đăng; nếu cần bổ sung: Cần sửa → sửa và Gửi duyệt lại. Admin có thể Từ chối kèm lý do.

Chỉ sửa được bài của mình ở trạng thái Nháp/Cần sửa. Bài Chờ duyệt, Đã đăng, Từ chối được khóa với người gửi. Không tự động lưu: cần bấm Lưu nháp trước khi rời trang. Khi lỗi ảnh, nội dung giữ trong form nhưng chưa lưu; chọn lại ảnh rồi gửi. Ảnh tải lên có URL công khai như thư viện Media WordPress, không dùng cho tài liệu riêng tư.

## Cộng tác viên

Vào **wp-admin → Bài viết → Chờ xét duyệt**. Mở bài, chỉnh nội dung/chuyên mục/ảnh và lưu. Bài gửi từ website dùng trình soạn thảo cổ điển có sẵn của WordPress.

Muốn người gửi bổ sung: ở hộp **Phản hồi người gửi**, nhập lý do, bấm **Yêu cầu sửa**. Lưu chỉnh sửa nội dung trước khi bấm nút này. Người gửi thấy lý do trong Bài của tôi.

Cộng tác viên không tự xuất bản, không chỉnh bài đã đăng và không xóa bài người khác. Quyền duyệt ý kiến thành viên vẫn giữ nguyên.

## Admin

Vào **Bài viết → Chờ xét duyệt**, mở bài và bấm **Đăng / Publish** để xuất bản. Có thể yêu cầu sửa; hoặc nhập lý do và bấm **Từ chối** trong hộp Phản hồi người gửi.

Nếu WordPress báo người khác đang chỉnh bài, phối hợp để họ lưu và rời bài; admin có thể bấm **Take over / Tiếp quản** khi cần tiếp nhận việc biên tập.

Google Drive không còn là luồng gửi bài chính. Không cần SMTP: trạng thái và phản hồi được đọc ngay trên website; chưa gửi thông báo email.

## Kiểm tra

```powershell
node scripts/test_submissions.cjs <duong-dan-goi-playwright>
& C:\xampp\php\php.exe scripts/test_submission_rules.php
```

Bài kiểm tra tạo và xóa tài khoản, bài viết, ảnh thử của chính nó. Bài thử được xuất bản trong thời gian ngắn để kiểm tra luồng duyệt; chỉ chạy trên môi trường local/test.
