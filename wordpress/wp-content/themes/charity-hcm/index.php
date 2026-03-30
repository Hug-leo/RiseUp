<?php get_header(); ?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
            <span>/</span>
            <span>Tin tức</span>
        </div>
        <h1 class="page-banner__title">Tin Tức &amp; Hoạt Động</h1>
    </div>
</div>

<div class="content-wrap no-sidebar">
    <div class="container">
        <main id="main" class="site-main" role="main">
            <?php if ( have_posts() ) : ?>

            <div class="archive-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'card' ); ?>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination( [
                'prev_text' => '&larr; Trước',
                'next_text' => 'Tiếp &rarr;',
                'before_page_number' => '<span class="meta-nav screen-reader-text">Page </span>',
            ] );
            ?>

            <?php else : ?>
            <p class="no-content">Chưa có bài viết nào.</p>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
