<?php get_header(); ?>

<section class="error-404">
    <div>
        <div class="error-404__code">404</div>
        <h1 class="error-404__title"><?php echo charity_t( 'Trang không tìm thấy', 'Page Not Found' ); ?></h1>
        <p class="error-404__text"><?php echo charity_t( 'Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc tạm thời không khả dụng.', 'The page you are looking for may have been removed, renamed, or is temporarily unavailable.' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
            ← <?php echo charity_t( 'Về trang chủ', 'Back to Home' ); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>
