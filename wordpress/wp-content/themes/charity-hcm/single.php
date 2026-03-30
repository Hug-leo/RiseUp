<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
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
                            <?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?>
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

                <footer class="entry-footer" style="margin-top:32px;padding-top:20px;border-top:1px solid var(--border);">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) :
                    ?>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span style="font-size:13px;font-weight:600;color:var(--text-muted);">Tags:</span>
                        <?php foreach ( $tags as $tag ) : ?>
                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                           style="font-size:12px;background:var(--bg-light);padding:4px 12px;border-radius:100px;color:var(--text-muted);border:1px solid var(--border);transition:all .2s;">
                            #<?php echo esc_html( $tag->name ); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </footer>

            </article>

            <!-- Adjacent posts nav -->
            <nav class="post-nav" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:48px;">
                <?php
                $prev = get_previous_post();
                $next = get_next_post();
                if ( $prev ) :
                ?>
                <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>"
                   style="background:var(--bg-light);border-radius:var(--radius);padding:18px 20px;border:1px solid var(--border);transition:all .25s;"
                   onmouseover="this.style.borderColor='var(--red)'"
                   onmouseout="this.style.borderColor='var(--border)'">
                    <span style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);font-weight:600;">← Bài trước</span>
                    <p style="font-weight:700;margin-top:6px;font-size:14px;"><?php echo esc_html( get_the_title( $prev ) ); ?></p>
                </a>
                <?php else : ?><div></div><?php endif; ?>

                <?php if ( $next ) : ?>
                <a href="<?php echo esc_url( get_permalink( $next ) ); ?>"
                   style="background:var(--bg-light);border-radius:var(--radius);padding:18px 20px;border:1px solid var(--border);text-align:right;transition:all .25s;"
                   onmouseover="this.style.borderColor='var(--red)'"
                   onmouseout="this.style.borderColor='var(--border)'">
                    <span style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);font-weight:600;">Bài tiếp theo →</span>
                    <p style="font-weight:700;margin-top:6px;font-size:14px;"><?php echo esc_html( get_the_title( $next ) ); ?></p>
                </a>
                <?php endif; ?>
            </nav>

        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<!-- Comments -->
<?php if ( comments_open() || get_comments_number() ) : ?>
<div class="comments-wrap">
    <div class="container comments-container">
        <?php comments_template(); ?>
    </div>
</div>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
