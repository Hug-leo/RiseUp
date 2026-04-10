<?php
/**
 * Template Name: Contact
 *
 * Contact page for Dong Du Study Encouragement Fund. Displays contact info cards,
 * embedded Google Map, and a contact form that sends via wp_mail().
 */
get_header();

$form_sent  = false;
$form_error = '';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['contact_nonce'] ) ) {
    if ( ! wp_verify_nonce( $_POST['contact_nonce'], 'vuonlen_contact_form' ) ) {
        $form_error = charity_t( 'Yêu cầu không hợp lệ.', 'Invalid request.' );
    } else {
        $name    = sanitize_text_field( wp_unslash( $_POST['contact_name'] ?? '' ) );
        $email   = sanitize_email( wp_unslash( $_POST['contact_email'] ?? '' ) );
        $subject = sanitize_text_field( wp_unslash( $_POST['contact_subject'] ?? '' ) );
        $message = sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ?? '' ) );

        if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
            $form_error = charity_t(
                'Vui lòng điền đầy đủ các trường bắt buộc.',
                'Please fill in all required fields.'
            );
        } elseif ( ! is_email( $email ) ) {
            $form_error = charity_t( 'Email không hợp lệ.', 'Invalid email address.' );
        } else {
            $to      = 'quykhuyenhocdongdu@gmail.com';
            $subject = ! empty( $subject ) ? $subject : charity_t( 'Liên hệ từ website', 'Contact from website' );
            $body    = sprintf(
                "%s: %s\n%s: %s\n\n%s",
                charity_t( 'Tên', 'Name' ), $name,
                'Email', $email,
                $message
            );
            $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$name} <{$email}>" ];

            $sent = wp_mail( $to, $subject, $body, $headers );
            if ( $sent ) {
                $form_sent = true;
            } else {
                $form_error = charity_t(
                    'Gửi thất bại. Vui lòng thử lại hoặc liên hệ trực tiếp.',
                    'Send failed. Please try again or contact us directly.'
                );
            }
        }
    }
}
?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a>
            <span>/</span>
            <span><?php echo charity_t( 'Liên hệ', 'Contact' ); ?></span>
        </div>
        <h1 class="page-banner__title"><?php echo charity_t( 'Liên Hệ Với Chúng Tôi', 'Contact Us' ); ?></h1>
    </div>
</div>

<div class="content-wrap no-sidebar">
    <div class="container">
        <main id="main" class="site-main" role="main">

            <!-- Contact info cards -->
            <div class="contact-cards">
                <div class="contact-card animate-in">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <h3><?php echo charity_t( 'Địa chỉ', 'Address' ); ?></h3>
                    <p>43D/46 Hồ Văn Huê<br><?php echo charity_t( 'Phú Nhuận, TP. Hồ Chí Minh', 'Phu Nhuan, Ho Chi Minh City' ); ?></p>
                </div>
                <div class="contact-card animate-in">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.09 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 11.91a16 16 0 0 0 6 6l2.27-2.27a2 2 0 0 1 2.11-.45c.907.339 1.85.574 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <h3><?php echo charity_t( 'Điện thoại', 'Phone' ); ?></h3>
                    <p>084 3214 142<br>(Thanh Vẹn)</p>
                </div>
                <div class="contact-card animate-in">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <h3>Email</h3>
                    <p>quykhuyenhocdongdu<br>@gmail.com</p>
                </div>
                <div class="contact-card animate-in">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h3><?php echo charity_t( 'Giờ làm việc', 'Office Hours' ); ?></h3>
                    <p><?php echo charity_t(
                        'Thứ 2 — Thứ 6: 8:00 — 17:00<br>Thứ 7: 8:00 — 11:30',
                        'Mon — Fri: 8:00 — 17:00<br>Sat: 8:00 — 11:30'
                    ); ?></p>
                </div>
            </div>

            <!-- Map + Form -->
            <div class="contact-grid">
                <div class="contact-map animate-in">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.3!2d106.68!3d10.8!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDQ4JzAwLjAiTiAxMDbCsDQwJzQ4LjAiRQ!5e0!3m2!1svi!2s!4v1"
                        width="100%" height="100%" style="border:0;border-radius:var(--radius-lg);min-height:380px"
                        allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="<?php echo esc_attr( charity_t( 'Bản đồ văn phòng', 'Office location map' ) ); ?>">
                    </iframe>
                </div>

                <div class="contact-form-wrap animate-in">
                    <?php if ( $form_sent ) : ?>
                    <div class="contact-success">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <h3><?php echo charity_t( 'Gửi thành công!', 'Sent successfully!' ); ?></h3>
                        <p><?php echo charity_t(
                            'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.',
                            'Thank you for contacting us. We will respond as soon as possible.'
                        ); ?></p>
                    </div>
                    <?php else : ?>
                    <h2 class="contact-form-title"><?php echo charity_t( 'Gửi tin nhắn', 'Send a Message' ); ?></h2>
                    <p class="contact-form-desc"><?php echo charity_t(
                        'Điền thông tin bên dưới, chúng tôi sẽ liên lạc sớm nhất có thể.',
                        'Fill in the form below, we will get back to you as soon as possible.'
                    ); ?></p>

                    <?php if ( $form_error ) : ?>
                    <div class="contact-error"><?php echo esc_html( $form_error ); ?></div>
                    <?php endif; ?>

                    <form method="POST" class="contact-form">
                        <?php wp_nonce_field( 'vuonlen_contact_form', 'contact_nonce' ); ?>

                        <div class="cf-row">
                            <div class="cf-field">
                                <label for="cf-name"><?php echo charity_t( 'Họ và tên', 'Full Name' ); ?> *</label>
                                <input type="text" id="cf-name" name="contact_name" required
                                       placeholder="<?php echo esc_attr( charity_t( 'Nguyễn Văn A', 'Your name' ) ); ?>" autocomplete="name">
                            </div>
                            <div class="cf-field">
                                <label for="cf-email">Email *</label>
                                <input type="email" id="cf-email" name="contact_email" required
                                       placeholder="email@example.com" autocomplete="email">
                            </div>
                        </div>

                        <div class="cf-field">
                            <label for="cf-subject"><?php echo charity_t( 'Tiêu đề', 'Subject' ); ?></label>
                            <input type="text" id="cf-subject" name="contact_subject"
                                   placeholder="<?php echo esc_attr( charity_t( 'Chủ đề liên hệ', 'What is this about?' ) ); ?>">
                        </div>

                        <div class="cf-field">
                            <label for="cf-message"><?php echo charity_t( 'Nội dung', 'Message' ); ?> *</label>
                            <textarea id="cf-message" name="contact_message" rows="6" required
                                      placeholder="<?php echo esc_attr( charity_t( 'Viết nội dung tin nhắn...', 'Write your message...' ) ); ?>"></textarea>
                        </div>

                        <button type="submit" class="btn btn--primary cf-submit">
                            <?php echo charity_t( 'Gửi tin nhắn', 'Send Message' ); ?>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>
</div>

<style>
/* ===== Contact Page Styles ===== */
.contact-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}
.contact-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 24px;
    text-align: center;
    transition: box-shadow .25s, transform .25s;
}
.contact-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
}
.contact-card__icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 14px;
    background: var(--red-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--red);
}
.contact-card h3 {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 6px;
}
.contact-card p {
    font-size: 13px;
    color: var(--text-secondary);
    line-height: 1.6;
}
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
}
.contact-form-wrap {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 32px;
}
.contact-form-title {
    font-family: var(--font-serif);
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 6px;
}
.contact-form-desc {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 24px;
}
.contact-error {
    background: #fce4ec;
    border: 1px solid #ef9a9a;
    color: #c62828;
    padding: 10px 14px;
    border-radius: var(--radius);
    font-size: 13px;
    margin-bottom: 16px;
}
.contact-success {
    text-align: center;
    padding: 40px 20px;
}
.contact-success h3 {
    font-size: 20px;
    font-weight: 700;
    color: #2e7d32;
    margin-bottom: 8px;
}
.contact-success p {
    font-size: 14px;
    color: var(--text-secondary);
}
.cf-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.cf-field {
    margin-bottom: 16px;
}
.cf-field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--text);
}
.cf-field input,
.cf-field textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 14px;
    font-family: var(--font-sans);
    color: var(--text);
    transition: border-color var(--transition);
}
.cf-field input:focus,
.cf-field textarea:focus {
    outline: none;
    border-color: var(--red);
    box-shadow: 0 0 0 3px var(--red-bg);
}
.cf-field textarea { resize: vertical; }
.cf-submit {
    width: 100%;
    padding: 12px;
    font-size: 15px;
    font-weight: 600;
    margin-top: 8px;
}

@media (max-width: 768px) {
    .contact-cards { grid-template-columns: 1fr 1fr; }
    .contact-grid { grid-template-columns: 1fr; }
    .cf-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .contact-cards { grid-template-columns: 1fr; }
    .contact-form-wrap { padding: 20px; }
}
</style>

<?php get_footer(); ?>
