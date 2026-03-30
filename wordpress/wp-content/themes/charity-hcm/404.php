<?php get_header(); ?>

<section class="error-404">
    <div>
        <div class="error-404__code">404</div>
        <h1 class="error-404__title">Trang không tìm thấy</h1>
        <p class="error-404__text">Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc tạm thời không khả dụng.</p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
            ← Về trang chủ
        </a>
    </div>
</section>

<?php get_footer(); ?>
