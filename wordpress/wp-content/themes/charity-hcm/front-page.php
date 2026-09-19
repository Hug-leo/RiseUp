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
        <div class="cp-hero__badge-wrap">
            <span class="cp-hero__badge">
                <span class="cp-hero__badge-dot"></span>
                <?php echo charity_t( 'Quỹ Khuyến Học Đông Du • Tiếp Sức Tri Thức', 'Dong Du Study Encouragement Fund • Rise Up' ); ?>
            </span>
        </div>
        <h1 class="cp-hero__title"><?php echo charity_t( 'Học Bổng <span class="cp-hero__title-highlight">Vươn Lên</span>', 'Rise Up <span class="cp-hero__title-highlight">Scholarship</span>' ); ?></h1>
        <p class="cp-hero__desc"><?php echo charity_t( 'Một không gian kết nối câu chuyện học bổng, lan tỏa tinh thần hiếu học và nâng bước ước mơ trên khắp 63 tỉnh thành Việt Nam.', 'A home for scholarship stories, member connection, and the spirit of rising through education across Vietnam.' ); ?></p>
        <div class="cp-hero__actions">
            <a class="btn btn--primary btn--lg" href="#featured-stories"><?php echo charity_t( 'Đọc tin nổi bật', 'Featured Stories' ); ?></a>
            <a class="btn btn--map btn--lg" href="<?php echo esc_url( home_url( '/category/dong-du-ky/ban-do-vuon-len/' ) ); ?>"><?php echo charity_t( 'Bản đồ Vươn Lên', 'Member Map' ); ?></a>
            <a class="btn btn--outline btn--lg" href="<?php echo esc_url( $submit_url ); ?>"><?php echo charity_t( 'Gửi bài viết', 'Submit a Story' ); ?></a>
        </div>
        <div class="cp-hero__stats">
            <div class="hero-stat">
                <span class="hero-stat__num">63 / 34</span>
                <span class="hero-stat__label"><?php echo charity_t( 'Tỉnh/thành kết nối', 'Provinces & Cities' ); ?></span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat__num">5</span>
                <span class="hero-stat__label"><?php echo charity_t( 'Trụ cột nội dung', 'Content Pillars' ); ?></span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat__num">20+</span>
                <span class="hero-stat__label"><?php echo charity_t( 'Năm khuyến học', 'Years of Giving' ); ?></span>
            </div>
        </div>
    </div>
</section>

<section class="mission-section" id="mission">
    <div class="container container--wide">
        <div class="section-header">
            <span class="section-label"><?php echo esc_html( charity_t( 'Sứ mệnh & Giá trị', 'Mission & Values' ) ); ?></span>
            <h2 class="section-title"><?php echo esc_html( charity_t( 'Nuôi Dưỡng Khát Vọng, Lan Tỏa Tinh Thần Tự Lực', 'Nurturing Ambition, Spreading Resilience' ) ); ?></h2>
            <p class="section-desc"><?php echo esc_html( charity_t( 'Học bổng Vươn Lên không chỉ là sự hỗ trợ tài chính, mà là bệ phóng tinh thần giúp sinh viên vững bước trên con đường lập thân, lập nghiệp.', 'Rise Up is more than financial assistance; it is a community launchpad empowering students to rise.' ) ); ?></p>
        </div>

        <div class="mission-grid">
            <div class="mission-card">
                <div class="mission-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <h3 class="mission-card__title"><?php echo esc_html( charity_t( 'Tiếp Sức Tri Thức', 'Educational Support' ) ); ?></h3>
                <p class="mission-card__desc"><?php echo esc_html( charity_t( 'Đồng hành cùng các bạn sinh viên vượt khó, tạo điều kiện tiếp cận tri thức và phát triển kỹ năng toàn diện.', 'Accompanying underprivileged students with resources, academic tools, and career skills.' ) ); ?></p>
            </div>
            <div class="mission-card">
                <div class="mission-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 class="mission-card__title"><?php echo esc_html( charity_t( 'Mạng Lưới Gắn Kết', 'Connected Community' ) ); ?></h3>
                <p class="mission-card__desc"><?php echo esc_html( charity_t( 'Kết nối các thế hệ thành viên HBVL trên khắp mọi miền tổ quốc thông qua bản đồ số và các hoạt động giao lưu.', 'Connecting HBVL alumni and members across 63 provinces through digital mapping and interactive events.' ) ); ?></p>
            </div>
            <div class="mission-card">
                <div class="mission-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h3 class="mission-card__title"><?php echo esc_html( charity_t( 'Lan Tỏa & Tiếp Nối', 'Pay It Forward' ) ); ?></h3>
                <p class="mission-card__desc"><?php echo esc_html( charity_t( 'Tinh thần Đông Du: người đi trước dìu dắt người đi sau, đóng góp giá trị thiết thực và trách nhiệm cho cộng đồng.', 'Embodying the Dong Du ethos: alumni guiding juniors and paying forward values to wider society.' ) ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="pillars-section" id="pillars">
    <div class="container container--wide">
        <div class="section-header">
            <span class="section-label"><?php echo esc_html( charity_t( '5 Trụ cột nội dung', '5 Content Pillars' ) ); ?></span>
            <h2 class="section-title"><?php echo esc_html( charity_t( 'Không Gian Tri Thức & Trải Nghiệm', 'Knowledge & Experience Spaces' ) ); ?></h2>
            <p class="section-desc"><?php echo esc_html( charity_t( 'Khám phá các chuyên mục được xây dựng dành riêng cho cộng đồng học bổng Vươn Lên.', 'Explore dedicated sections tailored for the Rise Up scholarship community.' ) ); ?></p>
        </div>

        <div class="pillars-grid">
            <?php foreach ( $content_groups as $index => $group ) : 
                $group_slug = $group['slug'] ?? '';
                $group_url  = charity_category_url_by_slug( $group_slug );
                $group_num  = sprintf( '%02d', $index + 1 );
            ?>
                <a class="pillar-card" href="<?php echo esc_url( $group_url ); ?>">
                    <div>
                        <div class="pillar-card__head">
                            <span class="pillar-card__icon">
                                <?php if ( 'tin-tuc' === $group_slug ) : ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <?php elseif ( 'dong-du-ky' === $group_slug ) : ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                                <?php elseif ( 'so-tay-kien-thuc' === $group_slug ) : ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                <?php elseif ( 'goc-sach-hay' === $group_slug ) : ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                <?php else : ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                                <?php endif; ?>
                            </span>
                            <span class="pillar-card__number"><?php echo esc_html( $group_num ); ?></span>
                        </div>
                        <h3 class="pillar-card__title"><?php echo esc_html( charity_t( $group['title_vi'], $group['title_en'] ) ); ?></h3>
                        <p class="pillar-card__desc"><?php echo esc_html( charity_t( $group['summary_vi'] ?? '', $group['summary_en'] ?? '' ) ); ?></p>
                        
                        <?php if ( ! empty( $group['items'] ) ) : ?>
                            <div class="pillar-card__tags">
                                <?php foreach ( array_slice( $group['items'], 0, 3 ) as $sub_item ) : ?>
                                    <span class="pillar-card__tag"><?php echo esc_html( charity_t( $sub_item['vi'], $sub_item['en'] ) ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="pillar-card__footer">
                        <span><?php echo esc_html( charity_t( 'Khám phá', 'Explore' ) ); ?></span>
                        <span aria-hidden="true">→</span>
                    </div>
                </a>
            <?php endforeach; ?>
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
            <h2 class="section-title"><?php echo charity_t( 'Cộng đồng Vươn Lên trên khắp Việt Nam', 'The Rise Up community across Vietnam' ); ?></h2>
            <p><?php echo charity_t(
                'Khám phá quê quán của thành viên HBVL theo 34 tỉnh/thành hiện hành hoặc 63 tỉnh/thành trước sắp xếp năm 2025.',
                'Explore HBVL member hometowns using either the 34 current units or the 63 units before the 2025 reorganisation.'
            ); ?></p>
            <div class="cp-map-feature__modes" aria-label="<?php echo esc_attr( charity_t( 'Hai chế độ bản đồ', 'Two map modes' ) ); ?>">
                <span><?php echo esc_html( charity_t( '34 tỉnh/thành hiện hành', '34 current provinces/cities' ) ); ?></span>
                <span><?php echo esc_html( charity_t( '63 tỉnh/thành trước sắp xếp', '63 before reorganisation' ) ); ?></span>
            </div>
            <a class="btn btn--map" href="<?php echo esc_url( home_url( '/category/dong-du-ky/ban-do-vuon-len/' ) ); ?>">
                <?php echo esc_html( charity_t( 'Khám phá bản đồ', 'Explore the map' ) ); ?>
                <span aria-hidden="true">→</span>
            </a>
        </div>
        <div class="cp-map-feature__visual">
            <a class="cp-map-feature__map-link" href="<?php echo esc_url( home_url( '/category/dong-du-ky/ban-do-vuon-len/' ) ); ?>" aria-label="<?php echo esc_attr( charity_t( 'Mở bản đồ thành viên Vươn Lên', 'Open the Rise Up member map' ) ); ?>">
            <figure class="cp-map-feature__map">
                <img src="<?php echo esc_url( charity_vietnam_map_image_url() ); ?>" alt="<?php echo esc_attr( charity_t( 'Bản đồ Việt Nam gồm Phú Quốc, quần đảo Hoàng Sa và quần đảo Trường Sa', 'Map of Vietnam including Phu Quoc, Hoang Sa and Truong Sa archipelagos' ) ); ?>">
                <figcaption><?php echo esc_html( charity_t( 'Dữ liệu thành viên HBVL theo tỉnh/thành', 'HBVL member data by province/city' ) ); ?></figcaption>
            </figure>
            </a>
            <ul class="cp-map-feature__legend">
                <li class="cp-map-feature__legend-empty"><?php echo esc_html( charity_t( 'Chưa có thành viên', 'No members' ) ); ?></li>
                <li class="cp-map-feature__legend-members"><?php echo esc_html( charity_t( 'Có thành viên HBVL', 'Has HBVL members' ) ); ?></li>
            </ul>
        </div>
    </div>
</section>

<?php get_footer(); ?>
