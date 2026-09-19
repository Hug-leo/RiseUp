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
                            <button class="ann-card__action reaction-like-btn" data-post-id="<?php the_ID(); ?>" aria-label="<?php echo esc_attr( charity_t( 'Thích', 'Like' ) ); ?>">
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

                <div id="ann-empty-state" class="ann-empty-state" style="display:none;">
                    <h3><?php echo esc_html( charity_t( 'Không có thông báo nào', 'No announcements found' ) ); ?></h3>
                    <p><?php echo esc_html( charity_t( 'Chưa có bài viết nào trong danh mục đã chọn.', 'There are no announcements in the selected category.' ) ); ?></p>
                </div>
            <?php else : ?>
                <p class="no-content"><?php echo charity_t( 'Chưa có thông báo nào.', 'No announcements yet.' ); ?></p>
            <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
(function () {
    'use strict';

    // Category filter — client-side for instant filtering
    var filterBtns = document.querySelectorAll('.ann-filter__btn');
    var cards = document.querySelectorAll('.ann-card');
    var emptyState = document.getElementById('ann-empty-state');

    if (filterBtns.length && cards.length) {
        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');

                var catId = btn.dataset.cat;
                var matchCount = 0;

                cards.forEach(function (card) {
                    if (catId === '0') {
                        card.style.display = '';
                        matchCount++;
                    } else {
                        var cardCats = (card.dataset.cats || '').split(',');
                        var match = cardCats.indexOf(catId) !== -1;
                        card.style.display = match ? '' : 'none';
                        if (match) {
                            matchCount++;
                        }
                    }
                });

                if (emptyState) {
                    emptyState.style.display = matchCount === 0 ? 'block' : 'none';
                }
            });
        });
    }

    // Re-init like buttons for this page
    if (typeof window.initLikeButtons === 'function') {
        window.initLikeButtons();
    }
})();
</script>

<?php get_footer(); ?>
