<?php get_header(); ?>

<?php
$content_groups = function_exists( 'charity_content_groups' ) ? charity_content_groups() : [];
$submit_url = function_exists( 'charity_submit_post_url' ) ? charity_submit_post_url() : home_url( '/gui-bai-viet/' );
?>

<section class="cp-hero" id="home">
    <div class="cp-hero__pattern"></div>
    <div class="container cp-hero__inner">
        <img class="cp-hero__logo" src="<?php echo esc_url( CHARITY_HCM_URI . '/assets/img/dong-du-logo.jpg' ); ?>" alt="Dong Du logo">
        <p class="cp-hero__eyebrow"><?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?></p>
        <h1><?php echo charity_t( 'Học Bổng Vươn Lên', 'Rise Up Scholarship' ); ?></h1>
        <p class="cp-hero__subtitle"><?php echo charity_t( 'Một không gian lưu giữ câu chuyện học bổng, kết nối thành viên và lan tỏa tinh thần vươn lên.', 'A home for scholarship stories, member connection, and the spirit of rising through education.' ); ?></p>
        <div class="cp-hero__actions">
            <a class="btn btn--primary" href="#content-roadmap"><?php echo charity_t( 'Khám phá chuyên mục', 'Explore Sections' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( $submit_url ); ?>"><?php echo charity_t( 'Gửi bài viết', 'Submit a Story' ); ?></a>
        </div>
    </div>
</section>

<section class="section cp-roadmap" id="content-roadmap">
    <div class="container container--wide">
        <div class="section-header cp-section-header">
            <span class="section-label"><?php echo charity_t( 'Định hướng nội dung', 'Content Strategy' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( '5 nhóm nội dung chính của Vươn Lên', '5 Core Rise Up Content Groups' ); ?></h2>
            <p class="cp-section-header__lead"><?php echo charity_t(
                'Các chuyên mục được triển khai theo bản đề xuất mới: tin tức, hành trình Đồng Du, sổ tay kiến thức, góc sách và sinh hoạt cộng đồng.',
                'The site now follows the new content proposal: news, Dong Du journeys, knowledge handbook, book corner, and community activities.'
            ); ?></p>
        </div>

        <div class="cp-category-grid cp-category-grid--expanded">
            <?php foreach ( $content_groups as $group ) : ?>
                <?php $group_term = get_category_by_slug( $group['slug'] ); ?>
                <article class="cp-group-card animate-in" data-group="<?php echo esc_attr( $group['slug'] ); ?>">
                    <div class="cp-group-card__head">
                        <div class="cp-group-card__icon" aria-hidden="true"><?php echo charity_group_icon( $group['slug'] ); ?></div>
                        <h3 class="cp-group-card__title">
                            <?php if ( $group_term ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $group_term ) ); ?>"><?php echo esc_html( charity_t( $group['title_vi'], $group['title_en'] ) ); ?></a>
                            <?php else : ?>
                                <?php echo esc_html( charity_t( $group['title_vi'], $group['title_en'] ) ); ?>
                            <?php endif; ?>
                        </h3>
                        <p><?php echo esc_html( charity_t( $group['summary_vi'], $group['summary_en'] ) ); ?></p>
                    </div>
                    <ul class="cp-group-card__list">
                        <?php foreach ( $group['items'] as $item ) : ?>
                            <?php $item_term = get_category_by_slug( $item['slug'] ); ?>
                            <li class="cp-group-item">
                                <h4>
                                    <?php if ( $item_term ) : ?>
                                        <a href="<?php echo esc_url( get_category_link( $item_term ) ); ?>"><?php echo esc_html( charity_t( $item['vi'], $item['en'] ) ); ?></a>
                                    <?php else : ?>
                                        <?php echo esc_html( charity_t( $item['vi'], $item['en'] ) ); ?>
                                    <?php endif; ?>
                                </h4>
                                <p><?php echo esc_html( charity_t( $item['desc_vi'], $item['desc_en'] ) ); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section cp-map-feature" id="ban-do-vuon-len">
    <div class="container container--wide cp-map-feature__inner">
        <div class="cp-map-feature__copy">
            <span class="section-label"><?php echo charity_t( 'Đồng Du Ký', 'Dong Du Journeys' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Bản đồ Vươn Lên', 'Rise Up Map' ); ?></h2>
            <p><?php echo charity_t(
                'Ý tưởng bản đồ Việt Nam sẽ giúp đánh dấu nơi thành viên và cựu thành viên HBVL đang học tập, làm việc hoặc sinh hoạt. Đây là nền cho các hoạt động rủ rê chuyến đi, ghé thăm khi qua tỉnh và kết nối cộng đồng theo vùng.',
                'The Vietnam map concept will show where members and alumni are studying, working, or active. It can support trip planning, local visits, and regional community connection.'
            ); ?></p>
        </div>
        <div class="cp-map-feature__visual" aria-hidden="true">
            <figure class="cp-map-feature__map">
                <img src="<?php echo esc_url( charity_vietnam_map_image_url() ); ?>" alt="">
                <span class="cp-map-pin cp-map-pin--north"></span>
                <span class="cp-map-pin cp-map-pin--central"></span>
                <span class="cp-map-pin cp-map-pin--south"></span>
            </figure>
            <ul class="cp-map-feature__legend">
                <li><?php echo charity_t( 'Thành viên đang hoạt động', 'Active members' ); ?></li>
                <li><?php echo charity_t( 'Cựu học bổng', 'Alumni' ); ?></li>
                <li><?php echo charity_t( 'Điểm hẹn chuyến đi', 'Trip meetups' ); ?></li>
            </ul>
        </div>
    </div>
</section>

<section class="section cp-future section--bg-light" id="future-ready">
    <div class="container container--wide">
        <div class="section-header cp-section-header">
            <span class="section-label"><?php echo charity_t( 'Sẵn sàng vận hành', 'Ready To Operate' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Từ ý tưởng đến chuyên mục có thể đăng bài', 'From Proposal To Publishable Sections' ); ?></h2>
        </div>
        <div class="cp-future__grid">
            <article class="cp-future-card animate-in">
                <h3><?php echo charity_t( 'Category tự tạo', 'Auto-created Categories' ); ?></h3>
                <p><?php echo charity_t( 'Khi theme chạy, các nhóm lớn và mục nhỏ được tạo trong WordPress Category để bài viết có nơi phân loại rõ ràng.', 'When the theme runs, parent groups and child sections are created as WordPress categories for clear publishing.' ); ?></p>
            </article>
            <article class="cp-future-card animate-in">
                <h3><?php echo charity_t( 'Song ngữ VI/EN', 'VI/EN Ready' ); ?></h3>
                <p><?php echo charity_t( 'Homepage dùng cùng hệ thống chuyển ngôn ngữ hiện có, giữ nội dung tiếng Việt và bản tiếng Anh tương ứng.', 'The homepage uses the existing language switcher with Vietnamese copy and matching English labels.' ); ?></p>
            </article>
            <article class="cp-future-card animate-in">
                <h3><?php echo charity_t( 'Mở rộng theo module', 'Modular Growth' ); ?></h3>
                <p><?php echo charity_t( 'Dữ liệu chuyên mục được gom trong một hàm riêng để dùng lại cho form gửi bài, bộ lọc hoặc trang chuyên mục sau này.', 'Section data is centralized for reuse in submission forms, filters, and future section pages.' ); ?></p>
            </article>
        </div>
    </div>
</section>

<section class="section cp-posts" id="stories">
    <div class="container">
        <div class="section-header cp-section-header">
            <span class="section-label"><?php echo charity_t( 'Cập nhật gần đây', 'Latest Updates' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Bài viết mới', 'Recent Stories' ); ?></h2>
        </div>

        <div class="cp-post-grid">
            <?php
            $latest_query = new WP_Query( [
                'post_type'      => 'post',
                'posts_per_page' => 4,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ] );
            ?>

            <?php if ( $latest_query->have_posts() ) : ?>
                <?php while ( $latest_query->have_posts() ) : $latest_query->the_post(); ?>
                    <article class="cp-post-card animate-in">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="cp-post-card__thumb"><?php the_post_thumbnail( 'card-thumb', [ 'alt' => get_the_title() ] ); ?></a>
                        <?php endif; ?>
                        <div class="cp-post-card__body">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
                            <a class="cp-post-card__link" href="<?php the_permalink(); ?>"><?php echo charity_t( 'Đọc tiếp', 'Read More' ); ?> &rarr;</a>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="no-content"><?php echo charity_t( 'Chưa có bài viết nào. Hãy bắt đầu với các chuyên mục ở trên.', 'No stories yet. Start publishing under the sections above.' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
