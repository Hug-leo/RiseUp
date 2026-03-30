<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
<aside id="secondary" class="widget-area" role="complementary">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
<?php else : ?>
<aside id="secondary" class="widget-area" role="complementary">
    <!-- Recent Posts widget fallback -->
    <div class="widget">
        <h3 class="widget-title">Bài viết gần đây</h3>
        <?php
        $recent = new WP_Query( [ 'posts_per_page' => 5, 'post_status' => 'publish' ] );
        if ( $recent->have_posts() ) :
        ?>
        <ul style="display:flex;flex-direction:column;gap:14px;">
            <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
            <li style="display:flex;gap:12px;align-items:flex-start;">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" style="flex-shrink:0;">
                    <?php the_post_thumbnail( [ 60, 60 ], [ 'style' => 'border-radius:6px;object-fit:cover;width:60px;height:60px;' ] ); ?>
                </a>
                <?php endif; ?>
                <div>
                    <a href="<?php the_permalink(); ?>" style="font-size:13px;font-weight:600;line-height:1.4;color:var(--text);display:block;margin-bottom:4px;"><?php the_title(); ?></a>
                    <span style="font-size:11px;color:var(--text-light);"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></span>
                </div>
            </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>
        <?php endif; ?>
    </div>

    <!-- Categories widget fallback -->
    <div class="widget">
        <h3 class="widget-title">Danh mục</h3>
        <ul style="display:flex;flex-direction:column;gap:8px;">
            <?php
            $cats = get_categories( [ 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ] );
            foreach ( $cats as $cat ) :
            ?>
            <li>
                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                   style="display:flex;justify-content:space-between;font-size:13.5px;color:var(--text-muted);transition:color .2s;"
                   onmouseover="this.style.color='var(--red)'"
                   onmouseout="this.style.color='var(--text-muted)'">
                    <span><?php echo esc_html( $cat->name ); ?></span>
                    <span style="background:var(--bg-light);padding:1px 8px;border-radius:100px;font-size:12px;"><?php echo esc_html( $cat->count ); ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</aside>
<?php endif; ?>
