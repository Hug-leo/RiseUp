<?php
/**
 * Template Name: Contact
 *
 * Contact page for Dong Du Study Encouragement Fund. Displays contact info cards,
 * embedded Google Map, and a contact form backed by a private inbox.
 */
$form_sent  = false;
$form_error = '';
$values = [ 'contact_name' => '', 'contact_email' => '', 'contact_subject' => '', 'contact_message' => '' ];
if ( is_user_logged_in() ) {
    $values['contact_name'] = wp_get_current_user()->display_name;
    $values['contact_email'] = wp_get_current_user()->user_email;
}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $input = wp_unslash( $_POST );
    foreach ( $values as $key => $value ) {
        $values[ $key ] = isset( $input[ $key ] ) && is_string( $input[ $key ] ) ? $input[ $key ] : '';
    }
    $result = charity_receive_contact( $input );
    if ( is_wp_error( $result ) ) {
        $form_error = $result->get_error_message();
    } else {
        wp_safe_redirect( add_query_arg( 'contact_received', $result, get_permalink() ) . '#contact-result', 303 );
        exit;
    }
}
$receipt = isset( $_GET['contact_received'] ) && is_scalar( $_GET['contact_received'] ) ? get_post( absint( $_GET['contact_received'] ) ) : null;
$form_sent = is_user_logged_in() && $receipt && 'riseup_contact' === $receipt->post_type && (int) $receipt->post_author === get_current_user_id();
get_header();
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
                    <p><?php echo charity_t( '43D/46 Hồ Văn Huê, P. Đức Nhuận, TP. HCM', '43D/46 Ho Van Hue, Duc Nhuan Ward, HCMC' ); ?></p>
                </div>
                <div class="contact-card animate-in">
                    <div class="contact-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.09 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 11.91a16 16 0 0 0 6 6l2.27-2.27a2 2 0 0 1 2.11-.45c.907.339 1.85.574 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <h3><?php echo charity_t( 'Điện thoại', 'Phone' ); ?></h3>
                    <p>084.3214.142<br>(Thanh Vẹn)</p>
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
                    <div class="contact-success" id="contact-result" role="status">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <h3><?php echo charity_t( 'Đã tiếp nhận tin nhắn!', 'Message received!' ); ?></h3>
                        <p><?php echo esc_html( charity_t( 'Mã liên hệ: #', 'Reference: #' ) . $receipt->ID ); ?></p>
                        <p><?php echo charity_t(
                            'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.',
                            'Thank you for contacting us. We will respond as soon as possible.'
                        ); ?></p>
                    </div>
                    <?php elseif ( ! is_user_logged_in() ) : ?>
                    <div class="contact-login-required">
                        <h2 class="contact-form-title"><?php echo esc_html( charity_t( 'Đăng nhập để gửi tin nhắn', 'Sign in to send a message' ) ); ?></h2>
                        <p class="contact-form-desc"><?php echo esc_html( charity_t(
                            'Khách chưa đăng nhập chỉ có thể xem nội dung. Thành viên có thể gửi tin nhắn và ý kiến sau khi đăng nhập.',
                            'Guests can view content only. Members can send messages and feedback after signing in.'
                        ) ); ?></p>
                        <a class="btn btn--primary" href="<?php echo esc_url( charity_portal_url( 'member' ) ); ?>"><?php echo esc_html( charity_t( 'Đăng nhập thành viên', 'Member sign in' ) ); ?></a>
                    </div>
                    <?php else : ?>
                    <h2 class="contact-form-title"><?php echo charity_t( 'Gửi tin nhắn', 'Send a Message' ); ?></h2>
                    <p class="contact-form-desc"><?php echo charity_t(
                        'Điền thông tin bên dưới, chúng tôi sẽ liên lạc sớm nhất có thể.',
                        'Fill in the form below, we will get back to you as soon as possible.'
                    ); ?></p>

                    <?php if ( $form_error ) : ?>
                    <div class="contact-error" role="alert"><?php echo esc_html( $form_error ); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo esc_url( get_permalink() ); ?>" class="contact-form">
                        <?php wp_nonce_field( 'vuonlen_contact_form', 'contact_nonce' ); ?>

                        <div class="cf-row">
                            <div class="cf-field">
                                <label for="cf-name"><?php echo charity_t( 'Họ và tên', 'Full Name' ); ?> *</label>
                                <input type="text" id="cf-name" name="contact_name" required maxlength="120" value="<?php echo esc_attr( $values['contact_name'] ); ?>"
                                       placeholder="<?php echo esc_attr( charity_t( 'Nguyễn Văn A', 'Your name' ) ); ?>" autocomplete="name">
                            </div>
                            <div class="cf-field">
                                <label for="cf-email">Email *</label>
                                <input type="email" id="cf-email" name="contact_email" required maxlength="254" value="<?php echo esc_attr( $values['contact_email'] ); ?>"
                                       placeholder="email@example.com" autocomplete="email">
                            </div>
                        </div>

                        <div class="cf-field">
                            <label for="cf-subject"><?php echo charity_t( 'Tiêu đề', 'Subject' ); ?></label>
                            <input type="text" id="cf-subject" name="contact_subject" maxlength="200" value="<?php echo esc_attr( $values['contact_subject'] ); ?>"
                                   placeholder="<?php echo esc_attr( charity_t( 'Chủ đề liên hệ', 'What is this about?' ) ); ?>">
                        </div>

                        <div class="cf-field">
                            <label for="cf-message"><?php echo charity_t( 'Nội dung', 'Message' ); ?> *</label>
                            <textarea id="cf-message" name="contact_message" rows="6" required maxlength="5000"
                                      placeholder="<?php echo esc_attr( charity_t( 'Viết nội dung tin nhắn...', 'Write your message...' ) ); ?>"><?php echo esc_textarea( $values['contact_message'] ); ?></textarea>
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

<?php get_footer(); ?>
