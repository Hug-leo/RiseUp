<?php get_header(); ?>

<?php
$content_groups = function_exists( 'charity_content_groups' ) ? charity_content_groups() : [];
$submit_url     = function_exists( 'charity_submit_post_url' ) ? charity_submit_post_url() : home_url( '/gui-bai-viet/' );
$featured_query = new WP_Query( [
    'post_type'           => 'post',
    'posts_per_page'      => 5,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
] );
$featured_posts = $featured_query->posts;
$featured_ids   = wp_list_pluck( $featured_posts, 'ID' );

global $post;

$category_label = static function ( $category ) use ( $content_groups ) {
    if ( ! $category instanceof WP_Term ) {
        return charity_t( 'Tin HBVL', 'HBVL' );
    }

    foreach ( $content_groups as $group ) {
        if ( ! empty( $group['slug'] ) && $group['slug'] === $category->slug ) {
            return charity_t( $group['title_vi'], $group['title_en'] );
        }

        foreach ( $group['items'] ?? [] as $item ) {
            if ( ! empty( $item['slug'] ) && $item['slug'] === $category->slug ) {
                return charity_t( $item['vi'], $item['en'] );
            }
        }
    }

    return $category->name;
};

$render_home_card = static function ( $variant = 'standard' ) use ( $category_label ) {
    $post_categories = get_the_category();
    $primary_cat     = $post_categories[0] ?? null;
    $category_name   = $category_label( $primary_cat );
    $category_url    = $primary_cat instanceof WP_Term ? get_category_link( $primary_cat ) : '';
    $image_size      = 'lead' === $variant ? 'card-wide' : 'card-thumb';
    ?>
    <article <?php post_class( 'vl-news-card vl-news-card--' . sanitize_html_class( $variant ) ); ?>>
        <a class="vl-news-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( $image_size, [ 'alt' => get_the_title(), 'loading' => 'lead' === $variant ? 'eager' : 'lazy' ] ); ?>
            <?php else : ?>
                <span class="vl-news-card__placeholder" aria-hidden="true">
                    <span>HBVL</span>
                </span>
            <?php endif; ?>
        </a>

        <div class="vl-news-card__body">
            <?php if ( $category_url ) : ?>
                <a class="vl-news-card__badge" href="<?php echo esc_url( $category_url ); ?>"><?php echo esc_html( $category_name ); ?></a>
            <?php else : ?>
                <span class="vl-news-card__badge"><?php echo esc_html( $category_name ); ?></span>
            <?php endif; ?>

            <h3 class="vl-news-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <p class="vl-news-card__excerpt">
                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 'lead' === $variant ? 34 : 18 ) ); ?>
            </p>

            <div class="vl-news-card__meta">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                <a href="<?php the_permalink(); ?>"><?php echo esc_html( charity_t( 'Đọc tiếp', 'Read More' ) ); ?></a>
            </div>
        </div>
    </article>
    <?php
};
?>

<section class="cp-hero" id="home">
    <div class="cp-hero__pattern"></div>
    <div class="container cp-hero__inner">
        <img class="cp-hero__logo" src="<?php echo esc_url( CHARITY_HCM_URI . '/assets/img/dong-du-logo.jpg' ); ?>" alt="Dong Du logo">
        <p class="cp-hero__eyebrow"><?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?></p>
        <h1><?php echo charity_t( 'Học Bổng Vươn Lên', 'Rise Up Scholarship' ); ?></h1>
        <p class="cp-hero__subtitle"><?php echo charity_t( 'Một không gian lưu giữ câu chuyện học bổng, kết nối thành viên và lan tỏa tinh thần vươn lên.', 'A home for scholarship stories, member connection, and the spirit of rising through education.' ); ?></p>
        <div class="cp-hero__actions">
            <a class="btn btn--primary" href="#featured-stories"><?php echo charity_t( 'Đọc tin mới', 'Read Stories' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( $submit_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo charity_t( 'Gửi bài viết', 'Submit a Story' ); ?></a>
        </div>
    </div>
</section>

<section class="section vl-featured" id="featured-stories">
    <div class="container container--wide">
        <div class="vl-editorial-header">
            <div>
                <span class="section-label"><?php echo esc_html( charity_t( 'Dòng tin Vươn Lên', 'Rise Up Newsroom' ) ); ?></span>
                <h2 class="section-title"><?php echo esc_html( charity_t( 'Tin nổi bật', 'Featured Stories' ) ); ?></h2>
            </div>
            <a class="vl-section-link" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">
                <?php echo esc_html( charity_t( 'Xem tất cả', 'View All' ) ); ?>
            </a>
        </div>

        <?php if ( ! empty( $featured_posts ) ) : ?>
            <div class="vl-featured-grid">
                <?php
                $lead_post = array_shift( $featured_posts );
                $post      = $lead_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                setup_postdata( $post );
                $render_home_card( 'lead' );
                wp_reset_postdata();
                ?>

                <?php if ( ! empty( $featured_posts ) ) : ?>
                    <div class="vl-secondary-grid">
                        <?php foreach ( $featured_posts as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                            <?php setup_postdata( $post ); ?>
                            <?php $render_home_card( 'secondary' ); ?>
                        <?php endforeach; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="vl-news-empty">
                <h3><?php echo esc_html( charity_t( 'Chưa có bài viết nào.', 'No stories yet.' ) ); ?></h3>
                <p><?php echo esc_html( charity_t( 'Khi có bài viết mới, các tin nổi bật sẽ xuất hiện ngay tại khu vực này.', 'Newly published stories will appear in this featured area.' ) ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section vl-category-news">
    <div class="container container--wide">
        <div class="vl-editorial-header">
            <div>
                <span class="section-label"><?php echo esc_html( charity_t( 'Theo chuyên mục', 'By Section' ) ); ?></span>
                <h2 class="section-title"><?php echo esc_html( charity_t( 'Cập nhật mới nhất', 'Latest Updates' ) ); ?></h2>
            </div>
        </div>

        <div class="vl-category-list">
            <?php
            $rendered_rows = 0;
            foreach ( $content_groups as $group ) :
                if ( $rendered_rows >= 5 || empty( $group['slug'] ) ) {
                    break;
                }

                $group_term = get_category_by_slug( $group['slug'] );
                if ( ! $group_term ) {
                    continue;
                }

                $row_query = new WP_Query( [
                    'cat'                 => $group_term->term_id,
                    'post_type'           => 'post',
                    'posts_per_page'      => 3,
                    'post_status'         => 'publish',
                    'orderby'             => 'date',
                    'order'               => 'DESC',
                    'post__not_in'        => $featured_ids,
                    'ignore_sticky_posts' => true,
                    'no_found_rows'       => true,
                ] );

                if ( ! $row_query->have_posts() ) {
                    wp_reset_postdata();
                    continue;
                }

                $rendered_rows++;
                ?>
                <section class="vl-category-block">
                    <div class="vl-category-block__head">
                        <h3><?php echo esc_html( charity_t( $group['title_vi'], $group['title_en'] ) ); ?></h3>
                        <a href="<?php echo esc_url( get_category_link( $group_term ) ); ?>"><?php echo esc_html( charity_t( 'Xem thêm', 'More' ) ); ?></a>
                    </div>

                    <div class="vl-category-block__grid">
                        <?php while ( $row_query->have_posts() ) : ?>
                            <?php $row_query->the_post(); ?>
                            <?php $render_home_card( 'row' ); ?>
                        <?php endwhile; ?>
                    </div>
                </section>
                <?php
                wp_reset_postdata();
            endforeach;
            ?>
        </div>
    </div>
</section>

<section class="section cp-map-feature" id="ban-do-vuon-len">
    <div class="container container--wide cp-map-feature__inner">
        <div class="cp-map-feature__copy">
            <span class="section-label"><?php echo charity_t( 'Đồng Du Ký', 'Dong Du Journeys' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Bản đồ Vươn Lên', 'Rise Up Map' ); ?></h2>
            <p><?php echo charity_t(
                'Bản đồ Việt Nam giúp đánh dấu nơi thành viên và cựu thành viên HBVL đang học tập, làm việc hoặc sinh hoạt. Đây là nền cho các hoạt động ghé thăm, gặp gỡ và kết nối cộng đồng theo vùng.',
                'The Vietnam map shows where members and alumni are studying, working, or active. It supports local visits, meetups, and regional community connection.'
            ); ?></p>
        </div>
        <div class="cp-map-feature__visual">
            <figure class="cp-map-feature__map">
                <img src="<?php echo esc_url( charity_vietnam_map_image_url() ); ?>" alt="<?php echo esc_attr( charity_t( 'Bản đồ Việt Nam gồm Phú Quốc, quần đảo Hoàng Sa và quần đảo Trường Sa', 'Map of Vietnam including Phu Quoc, Hoang Sa and Truong Sa archipelagos' ) ); ?>">
                <span class="cp-map-pin cp-map-pin--north" aria-hidden="true"></span>
                <span class="cp-map-pin cp-map-pin--central" aria-hidden="true"></span>
                <span class="cp-map-pin cp-map-pin--south" aria-hidden="true"></span>
            </figure>
            <ul class="cp-map-feature__legend">
                <li><?php echo charity_t( 'Thành viên đang hoạt động', 'Active members' ); ?></li>
                <li><?php echo charity_t( 'Cựu học bổng', 'Alumni' ); ?></li>
                <li><?php echo charity_t( 'Điểm hẹn chuyến đi', 'Trip meetups' ); ?></li>
            </ul>
        </div>
    </div>
</section>

<?php get_footer(); ?>
