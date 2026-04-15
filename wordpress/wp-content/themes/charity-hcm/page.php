<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a>
            <span>/</span>
            <span><?php the_title(); ?></span>
        </div>
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
    </div>
</div>

<div class="content-wrap no-sidebar">
    <div class="container">
        <main id="main" class="site-main" role="main">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                <img src="<?php the_post_thumbnail_url( 'large' ); ?>"
                     alt="<?php the_title_attribute(); ?>"
                     class="entry-thumbnail">
                <?php endif; ?>
                <div class="entry-content"><?php the_content(); ?></div>
            </article>
        </main>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
