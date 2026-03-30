<?php
// Reusable post card — used in archive, load-more AJAX, and news grid
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-card' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" class="news-card__img-wrap">
        <?php the_post_thumbnail( 'card-thumb', [ 'class' => 'news-card__img', 'alt' => get_the_title() ] ); ?>
    </a>
    <?php endif; ?>

    <div class="news-card__body">
        <div class="news-card__meta">
            <?php
            $cats = get_the_category();
            if ( $cats ) :
            ?>
            <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="news-card__cat">
                <?php echo esc_html( $cats[0]->name ); ?>
            </a>
            <?php endif; ?>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="news-card__date">
                <?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?>
            </time>
        </div>

        <h3 class="news-card__title news-card__title--sm">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <p class="news-card__excerpt"><?php the_excerpt(); ?></p>

        <a href="<?php the_permalink(); ?>" class="btn btn--xs">Xem thêm →</a>
    </div>

</article>
