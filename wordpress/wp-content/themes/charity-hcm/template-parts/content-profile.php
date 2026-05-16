<?php
/**
 * Template Part: Profile Card
 * Used by category.php for the guong-mat-vuon-len category page.
 */
defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'profile-card' ); ?>>
    <div class="profile-card__inner">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="profile-card__photo">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'thumbnail', [ 'alt' => esc_attr( get_the_title() ) ] ); ?>
            </a>
        </div>
        <?php endif; ?>
        <div class="profile-card__body">
            <h3 class="profile-card__name">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <p class="profile-card__summary"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?></p>
            <a href="<?php the_permalink(); ?>" class="profile-card__link">
                <?php echo charity_t( 'Xem hồ sơ', 'View Profile' ); ?> &rarr;
            </a>
        </div>
    </div>
</article>
