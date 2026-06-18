<?php
/**
 * Template Name: Submit Post
 *
 * Sends article submissions to the shared Google Drive upload folder.
 */
get_header();

$drive_upload_url = function_exists( 'charity_drive_upload_url' ) ? charity_drive_upload_url() : home_url( '/' );
?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a>
            <span>/</span>
            <span><?php echo charity_t( 'Gửi bài', 'Submit' ); ?></span>
        </div>
        <h1 class="page-banner__title"><?php echo charity_t( 'Gửi bài viết', 'Submit Your Article' ); ?></h1>
    </div>
</div>

<div class="content-wrap no-sidebar">
    <div class="container">
        <main id="main" class="site-main" role="main">
            <div class="submit-post-drive-card">
                <span class="submit-post-drive-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <path d="M12 3v12"></path>
                        <path d="m7 10 5 5 5-5"></path>
                        <path d="M5 21h14"></path>
                    </svg>
                </span>
                <h2><?php echo esc_html( charity_t( 'Gửi bài qua Google Drive', 'Submit via Google Drive' ) ); ?></h2>
                <p><?php echo charity_t(
                    'Bạn muốn gửi bài viết đến ban tác giả? Hãy gửi bài của bạn thông qua liên kết bên dưới.',
                    'Want to submit your article to the editorial team? Please upload your submission using the link below.'
                ); ?></p>
                <a class="btn btn--primary submit-post-drive-btn" href="<?php echo esc_url( $drive_upload_url ); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo charity_t( 'Gửi bài qua Google Drive', 'Submit via Google Drive' ); ?>
                </a>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>
