<?php
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
    wp_safe_redirect( charity_portal_url( 'member' ) );
    exit;
}

$state = sanitize_key( wp_unslash( $_GET['feedback'] ?? '' ) );
get_header();
?>

<main class="feedback-page" id="main-content">
    <div class="feedback-page__shell">
        <section class="feedback-page__intro">
            <span class="section-label"><?php echo esc_html( charity_t( 'Kênh nội bộ', 'Private channel' ) ); ?></span>
            <h1><?php echo esc_html( charity_t( 'Gửi ý kiến', 'Send feedback' ) ); ?></h1>
            <p><?php echo esc_html( charity_t(
                'Ý kiến của bạn chỉ hiển thị trong khu vực quản lý để cộng tác viên và quản trị viên xem.',
                'Your feedback is visible only to collaborators and administrators.'
            ) ); ?></p>
        </section>

        <section class="feedback-card">
            <?php if ( 'success' === $state ) : ?>
                <div class="auth-message auth-message--success"><?php echo esc_html( charity_t( 'Ý kiến đã được gửi thành công.', 'Your feedback was sent.' ) ); ?></div>
            <?php elseif ( in_array( $state, [ 'invalid', 'error' ], true ) ) : ?>
                <div class="auth-message auth-message--error" role="alert"><?php echo esc_html( charity_t( 'Không thể gửi ý kiến. Hãy kiểm tra nội dung và thử lại.', 'Feedback could not be sent. Check the form and try again.' ) ); ?></div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="feedback-form">
                <input type="hidden" name="action" value="charity_submit_feedback">
                <?php wp_nonce_field( 'charity_submit_feedback', 'feedback_nonce' ); ?>
                <input class="auth-honeypot" type="text" name="feedback_website" tabindex="-1" autocomplete="off">

                <label for="feedback-subject"><?php echo esc_html( charity_t( 'Tiêu đề', 'Subject' ) ); ?></label>
                <input id="feedback-subject" name="feedback_subject" type="text" required maxlength="160">

                <label for="feedback-message"><?php echo esc_html( charity_t( 'Nội dung ý kiến', 'Feedback' ) ); ?></label>
                <textarea id="feedback-message" name="feedback_message" rows="8" required maxlength="5000"></textarea>

                <button class="btn btn--primary" type="submit"><?php echo esc_html( charity_t( 'Gửi cho ban biên tập', 'Send to editorial team' ) ); ?></button>
            </form>
        </section>
    </div>
</main>

<?php get_footer(); ?>
