<?php
/**
 * Template Name: Announcements Feed
 *
 * Reedsy-style scrollable announcement board. Pulls posts from
 * the "Thong bao" category (or all posts if that category doesn't exist).
 * Features: category filter tabs, scroll-reveal cards, lazy images,
 * like/comment counts, and responsive layout.
 */
get_header();

// Try to find announcement-related categories
$announcement_cats = get_categories( [
    'hide_empty' => false,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
] );
?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a>
            <span>/</span>
            <span><?php echo charity_t( 'Thông báo', 'Announcements' ); ?></span>
        </div>
        <h1 class="page-banner__title"><?php echo charity_t( 'Thông Báo & Tin Tức', 'Announcements & News' ); ?></h1>
        <p class="page-banner__desc"><?php echo charity_t(
            'Cập nhật thông tin mới nhất từ Quỹ Khuyến Học Đông Du',
            'Latest updates from the Dong Du Study Encouragement Fund'
        ); ?></p>
    </div>
</div>

<!-- Filter tabs -->
<?php if ( $announcement_cats ) : ?>
<div class="ann-filter">
    <div class="ann-filter__inner container">
        <button class="ann-filter__btn active" data-cat="0"><?php echo charity_t( 'Tất cả', 'All' ); ?></button>
        <?php foreach ( $announcement_cats as $cat ) : ?>
        <button class="ann-filter__btn" data-cat="<?php echo esc_attr( $cat->term_id ); ?>">
            <?php echo esc_html( $cat->name ); ?>
        </button>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="content-wrap no-sidebar">
    <div class="container">
        <main id="main" class="site-main" role="main">
            <div class="ann-feed" id="ann-feed">
            <?php
            $ann_query = new WP_Query( [
                'post_type'      => 'post',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ] );
            ?>

            <?php if ( $ann_query->have_posts() ) : ?>
                <?php while ( $ann_query->have_posts() ) : $ann_query->the_post(); ?>
                <?php
                $post_cats     = get_the_category();
                $likes         = (int) get_post_meta( get_the_ID(), '_post_likes', true );
                $comment_count = get_comments_number();
                $cat_ids       = wp_list_pluck( $post_cats, 'term_id' );
                ?>
                <article class="ann-card animate-in" data-cats="<?php echo esc_attr( implode( ',', $cat_ids ) ); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="ann-card__img-wrap">
                        <?php the_post_thumbnail( 'card-thumb', [ 'alt' => get_the_title(), 'loading' => 'lazy' ] ); ?>
                    </a>
                    <?php endif; ?>

                    <div class="ann-card__body">
                        <div class="ann-card__meta">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', get_the_author(), [ 'class' => 'ann-card__avatar' ] ); ?>
                            <span class="ann-card__author"><?php the_author(); ?></span>
                            <time class="ann-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                <?php echo esc_html( get_the_date() ); ?>
                            </time>
                        </div>

                        <?php if ( $post_cats ) : ?>
                        <div class="ann-card__cats">
                            <?php foreach ( array_slice( $post_cats, 0, 2 ) as $pc ) : ?>
                            <a href="<?php echo esc_url( get_category_link( $pc->term_id ) ); ?>" class="ann-card__cat-badge">
                                <?php echo esc_html( $pc->name ); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <h3 class="ann-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <p class="ann-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25 ); ?></p>

                        <div class="ann-card__footer">
                            <button class="ann-card__action reaction-like-btn" data-post-id="<?php the_ID(); ?>" aria-label="Like">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                <span class="like-count"><?php echo $likes > 0 ? esc_html( $likes ) : ''; ?></span>
                            </button>
                            <a href="<?php the_permalink(); ?>#comments" class="ann-card__action">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                                <?php if ( $comment_count > 0 ) : ?>
                                <span><?php echo esc_html( $comment_count ); ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="<?php the_permalink(); ?>" class="ann-card__read">
                                <?php echo charity_t( 'Đọc tiếp', 'Read more' ); ?> &rarr;
                            </a>
                        </div>
                    </div>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="no-content"><?php echo charity_t( 'Chưa có thông báo nào.', 'No announcements yet.' ); ?></p>
            <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<style>
/* ===== Announcement Feed Styles ===== */
.page-banner__desc {
    font-size: 15px;
    color: rgba(255,255,255,.8);
    margin-top: 8px;
}
.ann-filter {
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: var(--header-h);
    z-index: 50;
}
.ann-filter__inner {
    display: flex;
    gap: 6px;
    padding-top: 10px;
    padding-bottom: 10px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.ann-filter__btn {
    padding: 6px 16px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 20px;
    background: var(--bg-light);
    color: var(--text-muted);
    white-space: nowrap;
    transition: all var(--transition);
}
.ann-filter__btn:hover { color: var(--text); }
.ann-filter__btn.active {
    background: var(--red);
    color: #fff;
}
.ann-feed {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 24px 0;
}
.ann-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: box-shadow .25s, transform .25s;
}
.ann-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.ann-card__img-wrap img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}
.ann-card__body { padding: 20px; }
.ann-card__meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 10px;
}
.ann-card__avatar { border-radius: 50%; width: 28px; height: 28px; }
.ann-card__author { font-weight: 600; color: var(--text); }
.ann-card__date::before { content: '·'; margin: 0 6px; }
.ann-card__cats {
    display: flex;
    gap: 6px;
    margin-bottom: 8px;
}
.ann-card__cat-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    background: var(--red-bg);
    color: var(--red);
}
.ann-card__title {
    font-family: var(--font-serif);
    font-size: 20px;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 8px;
}
.ann-card__title a:hover { color: var(--red); }
.ann-card__excerpt {
    font-size: 14px;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 14px;
}
.ann-card__footer {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 13px;
    color: var(--text-muted);
}
.ann-card__action {
    display: flex;
    align-items: center;
    gap: 4px;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    transition: color var(--transition);
}
.ann-card__action:hover { color: var(--red); }
.ann-card__action.liked { color: var(--red); }
.ann-card__action.liked svg { fill: var(--red); }
.ann-card__read {
    margin-left: auto;
    font-weight: 600;
    color: var(--red);
    font-size: 13px;
}
.ann-card__read:hover { opacity: .7; }

@media (max-width: 600px) {
    .ann-card__img-wrap img { height: 160px; }
    .ann-card__body { padding: 16px; }
    .ann-card__title { font-size: 17px; }
}
</style>

<script>
(function () {
    'use strict';

    // Category filter — client-side for instant filtering
    var filterBtns = document.querySelectorAll('.ann-filter__btn');
    var cards = document.querySelectorAll('.ann-card');

    if (filterBtns.length && cards.length) {
        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');

                var catId = btn.dataset.cat;
                cards.forEach(function (card) {
                    if (catId === '0') {
                        card.style.display = '';
                    } else {
                        var cardCats = (card.dataset.cats || '').split(',');
                        card.style.display = cardCats.indexOf(catId) !== -1 ? '' : 'none';
                    }
                });
            });
        });
    }

    // Re-init like buttons for this page
    if (typeof initLikeButtons === 'function') {
        initLikeButtons();
    }
})();
</script>

<?php get_footer(); ?>
