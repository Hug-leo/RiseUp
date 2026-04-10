<?php
// Story card — used in archive, AJAX load-more, and front-page feed
$post_cats     = get_the_category();
$likes         = (int) get_post_meta( get_the_ID(), '_post_likes', true );
$comment_count = get_comments_number();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card' . ( ! has_post_thumbnail() ? ' story-card--no-img' : '' ) ); ?>>
    <div class="story-card__inner">
        <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" class="story-card__img-wrap">
            <?php the_post_thumbnail( 'card-thumb', [ 'alt' => get_the_title() ] ); ?>
        </a>
        <?php endif; ?>

        <div class="story-card__body">
            <div class="story-card__author">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', get_the_author(), [ 'class' => 'story-card__avatar' ] ); ?>
                <div class="story-card__author-info">
                    <span class="story-card__author-name"><?php the_author(); ?></span>
                    <time class="story-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                        <?php echo esc_html( get_the_date() ); ?>
                    </time>
                </div>
            </div>

            <?php if ( $post_cats ) : ?>
            <a href="<?php echo esc_url( get_category_link( $post_cats[0]->term_id ) ); ?>" class="story-card__cat">
                <?php echo esc_html( $post_cats[0]->name ); ?>
            </a>
            <?php endif; ?>

            <h3 class="story-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <p class="story-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 30 ); ?></p>

            <div class="story-card__footer">
                <button class="story-card__action reaction-like-btn" data-post-id="<?php the_ID(); ?>" aria-label="Like">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    <span class="like-count"><?php echo $likes > 0 ? esc_html( $likes ) : ''; ?></span>
                </button>

                <a href="<?php the_permalink(); ?>#comments" class="story-card__action">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    <?php if ( $comment_count > 0 ) : ?>
                    <span><?php echo esc_html( $comment_count ); ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?php the_permalink(); ?>" class="story-card__read-more">
                    <?php echo charity_t( 'Đọc tiếp', 'Read story' ); ?> &rarr;
                </a>
            </div>
        </div>
    </div>
</article>