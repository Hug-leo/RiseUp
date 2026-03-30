<?php get_header(); ?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
            <span>/</span>
            <span><?php the_archive_title(); ?></span>
        </div>
        <h1 class="page-banner__title"><?php the_archive_title(); ?></h1>
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
            ] );
            ?>
            <?php else : ?>
            <p class="no-content">Chưa có nội dung nào.</p>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
