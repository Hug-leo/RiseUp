<?php
/**
 * Template Name: Submit Post
 *
 * Frontend post submission form. Anyone can submit a post that
 * goes into "pending" status for admin review. Supports title,
 * content, category, optional featured image, and guest author info.
 */
get_header();
?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a>
            <span>/</span>
            <span><?php echo charity_t( 'Viết bài', 'Submit Post' ); ?></span>
        </div>
        <h1 class="page-banner__title"><?php echo charity_t( 'Chia sẻ câu chuyện của bạn', 'Share Your Story' ); ?></h1>
    </div>
</div>

<div class="content-wrap no-sidebar">
    <div class="container">
        <main id="main" class="site-main" role="main">

            <div class="submit-post-intro">
                <p><?php echo charity_t(
                    'Bạn có câu chuyện, ý kiến hoặc thông tin muốn chia sẻ? Hãy viết bài và gửi cho chúng tôi. Bài viết sẽ được quản trị viên duyệt trước khi đăng.',
                    'Have a story, opinion, or information to share? Write and submit your post. It will be reviewed by an admin before publishing.'
                ); ?></p>
            </div>

            <form id="submit-post-form" class="submit-post-form" enctype="multipart/form-data">
                <?php wp_nonce_field( 'vuonlen_submit_post', 'submit_post_nonce' ); ?>

                <div class="form-field">
                    <label for="sp-name"><?php echo charity_t( 'Họ và tên', 'Full Name' ); ?> <span class="required">*</span></label>
                    <input type="text" id="sp-name" name="author_name" required
                           placeholder="<?php echo esc_attr( charity_t( 'Nguyễn Văn A', 'Your name' ) ); ?>"
                           maxlength="100" autocomplete="name">
                </div>

                <div class="form-field">
                    <label for="sp-email"><?php echo charity_t( 'Email', 'Email' ); ?></label>
                    <input type="email" id="sp-email" name="author_email"
                           placeholder="email@example.com" autocomplete="email">
                </div>

                <div class="form-field">
                    <label for="sp-title"><?php echo charity_t( 'Tiêu đề bài viết', 'Post Title' ); ?> <span class="required">*</span></label>
                    <input type="text" id="sp-title" name="post_title" required
                           placeholder="<?php echo esc_attr( charity_t( 'Nhập tiêu đề...', 'Enter title...' ) ); ?>"
                           maxlength="200">
                </div>

                <div class="form-field">
                    <label for="sp-category"><?php echo charity_t( 'Danh mục', 'Category' ); ?></label>
                    <select id="sp-category" name="post_category">
                        <option value="0"><?php echo charity_t( '-- Chọn danh mục --', '-- Select category --' ); ?></option>
                        <?php
                        $cats = get_categories( [ 'hide_empty' => false ] );
                        foreach ( $cats as $cat ) :
                        ?>
                        <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label for="sp-content"><?php echo charity_t( 'Nội dung', 'Content' ); ?> <span class="required">*</span></label>
                    <textarea id="sp-content" name="post_content" rows="12" required
                              placeholder="<?php echo esc_attr( charity_t( 'Viết nội dung bài viết của bạn...', 'Write your post content...' ) ); ?>"></textarea>
                </div>

                <div class="form-field">
                    <label for="sp-image"><?php echo charity_t( 'Ảnh đại diện (tùy chọn)', 'Featured Image (optional)' ); ?></label>
                    <input type="file" id="sp-image" name="post_image" accept="image/*">
                    <small class="form-hint"><?php echo charity_t( 'Chấp nhận JPG, PNG, GIF. Tối đa 2MB.', 'Accepts JPG, PNG, GIF. Max 2MB.' ); ?></small>
                </div>

                <div class="form-field form-actions">
                    <button type="submit" class="btn btn--primary submit-post-btn">
                        <?php echo charity_t( 'Gửi bài viết', 'Submit Post' ); ?>
                    </button>
                </div>

                <div id="submit-post-message" class="submit-post-message" style="display:none;"></div>
            </form>

        </main>
    </div>
</div>

<style>
/* ===== Submit Post Form Styles ===== */
.submit-post-intro {
    max-width: 700px;
    margin: 0 auto 32px;
    text-align: center;
    color: var(--text-secondary);
    font-size: 15px;
    line-height: 1.7;
}
.submit-post-form {
    max-width: 700px;
    margin: 0 auto;
    padding: 36px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}
.submit-post-form .form-field {
    margin-bottom: 20px;
}
.submit-post-form label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text);
}
.submit-post-form .required { color: var(--red); }
.submit-post-form input[type="text"],
.submit-post-form input[type="email"],
.submit-post-form select,
.submit-post-form textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 14px;
    font-family: var(--font-sans);
    line-height: 1.6;
    color: var(--text);
    background: var(--bg);
    transition: border-color var(--transition), box-shadow var(--transition);
}
.submit-post-form input:focus,
.submit-post-form select:focus,
.submit-post-form textarea:focus {
    outline: none;
    border-color: var(--red);
    box-shadow: 0 0 0 3px var(--red-bg);
}
.submit-post-form textarea {
    resize: vertical;
    min-height: 200px;
}
.submit-post-form input[type="file"] {
    font-size: 13px;
    padding: 8px 0;
}
.form-hint {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}
.form-actions {
    text-align: center;
    margin-top: 28px;
}
.submit-post-btn {
    min-width: 200px;
    padding: 12px 32px;
    font-size: 15px;
    font-weight: 600;
}
.submit-post-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.submit-post-message {
    margin-top: 20px;
    padding: 14px 18px;
    border-radius: var(--radius);
    font-size: 14px;
    line-height: 1.5;
}
.submit-post-message.success {
    background: #e8f5e9;
    border: 1px solid #a5d6a7;
    color: #2e7d32;
}
.submit-post-message.error {
    background: #fce4ec;
    border: 1px solid #ef9a9a;
    color: #c62828;
}
@media (max-width: 600px) {
    .submit-post-form { padding: 20px; }
}
</style>

<script>
(function () {
    'use strict';

    var form = document.getElementById('submit-post-form');
    if (!form || typeof charityHCM === 'undefined') return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var btn = form.querySelector('.submit-post-btn');
        var msgEl = document.getElementById('submit-post-message');
        var origText = btn.textContent;

        // Validate file size (max 2MB)
        var fileInput = form.querySelector('input[type="file"]');
        if (fileInput && fileInput.files.length > 0) {
            if (fileInput.files[0].size > 2 * 1024 * 1024) {
                msgEl.textContent = '<?php echo esc_js( charity_t( "Ảnh quá lớn. Tối đa 2MB.", "Image too large. Max 2MB." ) ); ?>';
                msgEl.className = 'submit-post-message error';
                msgEl.style.display = 'block';
                return;
            }
        }

        btn.disabled = true;
        btn.textContent = '<?php echo esc_js( charity_t( "Đang gửi...", "Submitting..." ) ); ?>';

        var fd = new FormData(form);
        fd.append('action', 'vuonlen_submit_post');
        fd.append('nonce', fd.get('submit_post_nonce'));

        fetch(charityHCM.ajaxurl, { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                msgEl.style.display = 'block';
                if (data.success) {
                    msgEl.textContent = data.data.message;
                    msgEl.className = 'submit-post-message success';
                    form.reset();
                } else {
                    msgEl.textContent = data.data ? data.data.message : 'Error';
                    msgEl.className = 'submit-post-message error';
                }
            })
            .catch(function () {
                msgEl.textContent = '<?php echo esc_js( charity_t( "Lỗi kết nối. Vui lòng thử lại.", "Connection error. Please try again." ) ); ?>';
                msgEl.className = 'submit-post-message error';
                msgEl.style.display = 'block';
            })
            .finally(function () {
                btn.disabled = false;
                btn.textContent = origText;
            });
    });
})();
</script>

<?php get_footer(); ?>
