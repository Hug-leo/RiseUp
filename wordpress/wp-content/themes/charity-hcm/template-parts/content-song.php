<?php
/**
 * Template Part: Song Card
 * Used by category.php for the tong-hop-bai-hat category page.
 */
defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'song-card' ); ?>>
    <div class="song-card__inner">
        <div class="song-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
        </div>
        <div class="song-card__body">
            <h3 class="song-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <p class="song-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
            <a href="<?php the_permalink(); ?>" class="song-card__link">
                <?php echo charity_t( 'Xem bài hát', 'View Song' ); ?> &rarr;
            </a>
        </div>
    </div>
</article>
