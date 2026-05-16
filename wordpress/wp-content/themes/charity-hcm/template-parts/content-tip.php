<?php
/**
 * Template Part: Tip Card
 * Used by category.php for bi-kip and the-gioi-quanh-ta category pages.
 * Compact layout: category label, title, short excerpt, read-more link.
 */
defined( 'ABSPATH' ) || exit;

$post_cats = get_the_category();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'tip-card' ); ?>>
    <div class="tip-card__inner">
        <div class="tip-card__body">
            <?php if ( $post_cats ) : ?>
            <a href="<?php echo esc_url( get_category_link( $post_cats[0]->term_id ) ); ?>" class="tip-card__cat">
                <?php echo esc_html( $post_cats[0]->name ); ?>
            </a>
            <?php endif; ?>
            <h3 class="tip-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <p class="tip-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
            <a href="<?php the_permalink(); ?>" class="tip-card__read-more">
                <?php echo charity_t( 'Đọc thêm', 'Read more' ); ?> &rarr;
            </a>
        </div>
    </div>
</article>
