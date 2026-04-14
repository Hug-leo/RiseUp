<?php get_header(); ?>

<?php
$content_groups = [
    [
        'title_vi' => 'VIẾT VÀ ĐI',
        'title_en' => 'WRITE & TRAVEL',
        'items'    => [
            [
                'vi' => 'Giới thiệu địa điểm',
                'en' => 'Location Spotlights',
                'desc_vi' => 'Chia sẻ các địa điểm có cảnh đẹp, câu chuyện văn hóa và trải nghiệm đáng nhớ.',
                'desc_en' => 'Share meaningful destinations with culture and memorable experiences.',
            ],
            [
                'vi' => 'Chuyến đi trong tuần',
                'en' => 'Weekly Journeys',
                'desc_vi' => 'Tổng hợp chuyến đi tiêu biểu trong tuần cùng bài học và cảm hứng.',
                'desc_en' => 'Weekly trip highlights with lessons and inspiration.',
            ],
            [
                'vi' => 'Ảnh chụp thiên nhiên',
                'en' => 'Nature Photography',
                'desc_vi' => 'Bộ ảnh thiên nhiên có chú thích ý nghĩa và góc nhìn nghệ thuật.',
                'desc_en' => 'Nature photo series with meaningful captions and artistic perspective.',
            ],
        ],
    ],
    [
        'title_vi' => 'SÁNG TÁC',
        'title_en' => 'CREATIVE WRITING',
        'items'    => [
            [
                'vi' => 'Thơ',
                'en' => 'Poetry',
                'desc_vi' => 'Đăng tải các bài thơ do thành viên và bạn đọc sáng tác.',
                'desc_en' => 'Poems contributed by members and readers.',
            ],
            [
                'vi' => 'Truyện ngắn',
                'en' => 'Short Stories',
                'desc_vi' => 'Truyện ngắn về đời sống, gia đình, học tập và hành trình trưởng thành.',
                'desc_en' => 'Stories about life, family, study, and personal growth.',
            ],
            [
                'vi' => 'Tản văn',
                'en' => 'Essays',
                'desc_vi' => 'Những trang viết giàu cảm xúc và góc nhìn về cuộc sống thường ngày.',
                'desc_en' => 'Reflective essays capturing everyday life and emotions.',
            ],
        ],
    ],
    [
        'title_vi' => 'LAN TỎA',
        'title_en' => 'SPREADING IMPACT',
        'items'    => [
            [
                'vi' => 'Gương mặt Vươn Lên',
                'en' => 'Rise Up Faces',
                'desc_vi' => 'Chân dung thành viên, cựu học bổng và người đồng hành nổi bật.',
                'desc_en' => 'Profiles of scholarship members and outstanding supporters.',
            ],
            [
                'vi' => 'Góc tri ân',
                'en' => 'Gratitude Corner',
                'desc_vi' => 'Những lời tri ân từ người nhận học bổng và cộng đồng đồng hành.',
                'desc_en' => 'Stories of gratitude from scholarship recipients and supporters.',
            ],
            [
                'vi' => 'Tiếp nối',
                'en' => 'Giving Back',
                'desc_vi' => 'Cựu học bổng quay lại kết nối, chia sẻ và truyền cảm hứng.',
                'desc_en' => 'Alumni return to mentor, connect, and inspire future students.',
            ],
        ],
    ],
    [
        'title_vi' => 'NGHỆ THUẬT',
        'title_en' => 'ART',
        'items'    => [
            [
                'vi' => 'Vẽ',
                'en' => 'Drawing',
                'desc_vi' => 'Trưng bày các tác phẩm hội họa từ học sinh và thành viên.',
                'desc_en' => 'Visual art and drawing works by students and members.',
            ],
            [
                'vi' => 'Sản phẩm thủ công',
                'en' => 'Handmade Crafts',
                'desc_vi' => 'Giới thiệu sản phẩm handmade như thiệp, vòng tay, đồ lưu niệm.',
                'desc_en' => 'Handmade crafts such as cards, bracelets, and keepsakes.',
            ],
            [
                'vi' => 'Âm nhạc',
                'en' => 'Music',
                'desc_vi' => 'Video hát, múa, nhạc cụ từ các hoạt động tại trường và địa phương.',
                'desc_en' => 'Music, singing, and performance videos from community activities.',
            ],
        ],
    ],
];
?>

<section class="cp-hero" id="home">
    <div class="cp-hero__pattern"></div>
    <div class="container cp-hero__inner">
        <img class="cp-hero__logo" src="<?php echo esc_url( CHARITY_HCM_URI . '/assets/img/dong-du-logo.jpg' ); ?>" alt="Dong Du logo">
        <p class="cp-hero__eyebrow"><?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?></p>
        <h1><?php echo charity_t( 'Học Bổng Vươn Lên', 'Rise Up Scholarship' ); ?></h1>
        <p class="cp-hero__subtitle"><?php echo charity_t( 'Hỗ trợ học sinh vượt khó, vươn lên trong học tập và cuộc sống', 'Supporting students to overcome challenges and rise up in education and life' ); ?></p>
        <div class="cp-hero__actions">
            <a class="btn btn--primary" href="#content-roadmap"><?php echo charity_t( 'Khám phá mục nội dung', 'Explore Content Pillars' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php echo charity_t( 'Liên hệ cộng tác', 'Contact Us' ); ?></a>
        </div>
    </div>
</section>

<section class="section cp-roadmap" id="content-roadmap">
    <div class="container container--wide">
        <div class="section-header cp-section-header">
            <span class="section-label"><?php echo charity_t( 'Định hướng nội dung', 'Content Strategy' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Các mục triển khai giai đoạn đầu', 'Initial Content Buckets' ); ?></h2>
            <p class="cp-section-header__lead"><?php echo charity_t(
                'Tập trung triển khai 4 nhóm nội dung theo định hướng ảnh tham chiếu. Các mục khác sẽ bổ sung theo từng đợt phát triển tiếp theo.',
                'We are launching with 4 core content groups from the reference. Additional sections will be introduced in future iterations.'
            ); ?></p>
        </div>

        <div class="cp-category-grid">
            <?php foreach ( $content_groups as $group ) : ?>
                <article class="cp-group-card animate-in">
                    <h3 class="cp-group-card__title"><?php echo esc_html( charity_t( $group['title_vi'], $group['title_en'] ) ); ?></h3>
                    <ul class="cp-group-card__list">
                        <?php foreach ( $group['items'] as $item ) : ?>
                            <li class="cp-group-item">
                                <h4><?php echo esc_html( charity_t( $item['vi'], $item['en'] ) ); ?></h4>
                                <p><?php echo esc_html( charity_t( $item['desc_vi'], $item['desc_en'] ) ); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section cp-future section--bg-light" id="future-ready">
    <div class="container container--wide">
        <div class="section-header cp-section-header">
            <span class="section-label"><?php echo charity_t( 'Sẵn sàng mở rộng', 'Built To Grow' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Không dừng ở phiên bản hiện tại', 'Ready For New Features' ); ?></h2>
        </div>
        <div class="cp-future__grid">
            <article class="cp-future-card animate-in">
                <h3><?php echo charity_t( 'Mở rộng danh mục nhanh', 'Fast Category Expansion' ); ?></h3>
                <p><?php echo charity_t( 'Có thể thêm mục mới ngay trong WordPress Admin mà không phá layout hiện tại.', 'New categories can be added from WordPress Admin without breaking current layout.' ); ?></p>
            </article>
            <article class="cp-future-card animate-in">
                <h3><?php echo charity_t( 'Mở rộng UI theo module', 'Modular UI Growth' ); ?></h3>
                <p><?php echo charity_t( 'Các khối giao diện được tách rõ để thêm section, trang chuyên đề, hoặc landing page mới.', 'UI blocks are modular, ready for additional sections, feature pages, and campaign landing pages.' ); ?></p>
            </article>
            <article class="cp-future-card animate-in">
                <h3><?php echo charity_t( 'Chuẩn bị cho tính năng mới', 'Prepared For New Features' ); ?></h3>
                <p><?php echo charity_t( 'Sẵn sàng tích hợp lọc nâng cao, media gallery, phản hồi cộng đồng, và công cụ quản trị nội dung AI hỗ trợ.', 'Architecture is prepared for advanced filters, media gallery, community feedback, and AI-assisted content operations.' ); ?></p>
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
                <p class="no-content"><?php echo charity_t( 'Chưa có bài viết nào. Hãy bắt đầu với các mục ở trên.', 'No stories yet. Start publishing under the sections above.' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
