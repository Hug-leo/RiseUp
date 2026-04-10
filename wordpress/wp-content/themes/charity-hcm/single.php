<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="page-banner">
    <div class="page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a>
            <span>/</span>
            <?php
            $cats = get_the_category();
            if ( $cats ) :
            ?>
            <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>">
                <?php echo esc_html( $cats[0]->name ); ?>
            </a>
            <span>/</span>
            <?php endif; ?>
            <span><?php the_title(); ?></span>
        </div>
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
    </div>
</div>

<div class="content-wrap">
    <div class="container">
        <main id="main" class="site-main" role="main">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <div class="entry-meta">
                        <?php if ( $cats ) : ?>
                        <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="entry-cat">
                            <?php echo esc_html( $cats[0]->name ); ?>
                        </a>
                        <?php endif; ?>
                        <span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <?php echo esc_html( get_the_date() ); ?>
                        </span>
                        <span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <?php the_author(); ?>
                        </span>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                <img
                    src="<?php the_post_thumbnail_url( 'large' ); ?>"
                    alt="<?php the_title_attribute(); ?>"
                    class="entry-thumbnail"
                    loading="eager"
                >
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) :
                    ?>
                    <div class="entry-tags">
                        <span class="entry-tags__label">Tags:</span>
                        <?php foreach ( $tags as $tag ) : ?>
                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="entry-tag">
                            #<?php echo esc_html( $tag->name ); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </footer>

                <!-- Reactions -->
                <?php $likes = (int) get_post_meta( get_the_ID(), '_post_likes', true ); ?>
                <div class="post-reactions">
                    <button class="reaction-btn" data-post-id="<?php the_ID(); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        <span class="reaction-count"><?php echo $likes; ?></span>
                        <?php echo charity_t( 'Thích', 'Like' ); ?>
                    </button>
                </div>

            </article>

            <!-- Post Navigation -->
            <nav class="post-nav">
                <?php
                $prev = get_previous_post();
                $next = get_next_post();
                if ( $prev ) :
                ?>
                <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
                    <span class="post-nav__label">&larr; <?php echo charity_t( 'Bài trước', 'Previous' ); ?></span>
                    <p class="post-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></p>
                </a>
                <?php else : ?><div></div><?php endif; ?>

                <?php if ( $next ) : ?>
                <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" style="text-align:right;">
                    <span class="post-nav__label"><?php echo charity_t( 'Bài tiếp theo', 'Next' ); ?> &rarr;</span>
                    <p class="post-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></p>
                </a>
                <?php endif; ?>
            </nav>

        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<!-- Comments -->
<?php if ( comments_open() || get_comments_number() ) : ?>
<div style="max-width:var(--container-wide);margin:0 auto;padding:0 20px 64px;">
    <?php comments_template(); ?>
</div>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>