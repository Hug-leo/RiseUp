<?php get_header(); ?>

<!-- ═══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="hero" id="home">
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <p class="hero__eyebrow"><?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?></p>
        <h1 class="hero__title"><?php bloginfo( 'name' ); ?></h1>
        <p class="hero__subtitle"><?php bloginfo( 'description' ); ?></p>
        <div class="hero__actions">
            <a href="<?php echo esc_url( get_page_link( get_page_by_path( 'gioi-thieu' ) ) ?: '#about' ); ?>" class="btn btn--primary">
                <?php echo charity_t( 'Về chúng tôi', 'About Us' ); ?>
            </a>
            <a href="<?php echo esc_url( get_page_link( get_page_by_path( 'lien-he' ) ) ?: '#contact' ); ?>" class="btn btn--outline">
                <?php echo charity_t( 'Liên hệ với chúng tôi', 'Contact Us' ); ?>
            </a>
        </div>
    </div>
</section>

<!-- ═══ ABOUT ═════════════════════════════════════════════════════════════ -->
<section class="section section--about" id="about">
    <div class="about__inner">
        <div class="about__text">
            <span class="section-label"><?php echo charity_t( 'Về chúng tôi', 'About Us' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Chúng tôi là ai?', 'Who Are We?' ); ?></h2>
            <p>
                <?php echo charity_t(
                    'Quỹ Khuyến Học Đông Du được sáng lập bởi thầy Nguyễn Đức Hoè — Hiệu trưởng Trường Nhật ngữ Đông Du. Quỹ hoạt động từ năm 2018, mỗi năm trao 30 suất học bổng Vươn Lên cho sinh viên các trường đại học tại TP. Hồ Chí Minh.',
                    'Dong Du Study Encouragement Fund was founded by teacher Nguyen Duc Hoe — Principal of Dong Du Japanese Language School. Since 2018, the fund awards 30 Vuon Len scholarships annually to university students in Ho Chi Minh City.'
                ); ?>
            </p>
            <p>
                <?php echo charity_t(
                    'Học bổng dành cho sinh viên có ước mơ và hoài bão lớn, có kế hoạch rõ ràng để thực hiện ước mơ. Ngoài học bổng Vươn Lên, Quỹ còn có học bổng Lá Xanh và Mai Vàng, đồng hành cùng hàng trăm sinh viên mỗi năm.',
                    'The scholarship is for students with big dreams and clear plans to achieve them. Beyond Vuon Len, the fund also offers La Xanh and Mai Vang scholarships, supporting hundreds of students each year.'
                ); ?>
            </p>
        </div>
        <div class="about__stats">
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="30">30+</span>
                <span class="stat-card__label"><?php echo charity_t( 'Suất học bổng/năm', 'Scholarships per Year' ); ?></span>
            </div>
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="9">9M</span>
                <span class="stat-card__label"><?php echo charity_t( 'VND/suất/năm', 'VND per Scholarship' ); ?></span>
            </div>
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="8">8+</span>
                <span class="stat-card__label"><?php echo charity_t( 'Năm hoạt động', 'Years Active' ); ?></span>
            </div>
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="3">3</span>
                <span class="stat-card__label"><?php echo charity_t( 'Quỹ học bổng', 'Scholarship Funds' ); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ═══ STORIES FEED (Vertical scroll) ════════════════════════════════════ -->
<section class="section section--feed" id="news">
    <div class="container">
        <div class="feed-header">
            <h2><?php echo charity_t( 'Bài Viết Mới Nhất', 'Latest Stories' ); ?></h2>
            <a href="<?php echo esc_url( home_url( '/viet-bai/' ) ); ?>" class="btn btn--primary" style="margin-left:auto;font-size:14px;">
                <?php echo charity_t( '✏️ Viết bài', '✏️ Submit Post' ); ?>
            </a>
            <?php
            $cats = get_categories( [ 'hide_empty' => true, 'number' => 5 ] );
            if ( $cats ) :
            ?>
            <div class="feed-tabs">
                <button class="feed-tabs__btn active" data-cat="0"><?php echo charity_t( 'Tất cả', 'All' ); ?></button>
                <?php foreach ( $cats as $cat ) : ?>
                <button class="feed-tabs__btn" data-cat="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div id="stories-feed">
        <?php
        $feed_query = new WP_Query( [
            'post_type'      => 'post',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );
        ?>

        <?php if ( $feed_query->have_posts() ) : ?>
            <?php while ( $feed_query->have_posts() ) : $feed_query->the_post(); ?>
            <?php
            $post_cats = get_the_category();
            $likes = (int) get_post_meta( get_the_ID(), '_post_likes', true );
            $comment_count = get_comments_number();
            ?>
            <article class="story-card animate-in <?php echo ! has_post_thumbnail() ? 'story-card--no-img' : ''; ?>">
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
                                <?php echo charity_t( 'Đọc tiếp', 'Read story' ); ?>  &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>

        <?php else : ?>
            <p class="no-content"><?php echo charity_t( 'Chưa có bài viết nào.', 'No posts yet.' ); ?> <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php echo charity_t( 'Thêm bài viết mới', 'Add a new post' ); ?></a>.</p>
        <?php endif; ?>
        </div>

        <?php if ( $feed_query->max_num_pages > 1 ) : ?>
        <div class="load-more-wrap">
            <button
                class="load-more-btn"
                id="news-load-more"
                data-page="2"
                data-cat="0"
            >
                <?php echo charity_t( 'Xem thêm bài viết', 'Load more stories' ); ?>
            </button>
        </div>
        <?php endif; ?>
        <div id="news-more-posts" class="news-more-grid"></div>
    </div>
</section>

<!-- ═══ EVENTS (Grid, no carousel) ════════════════════════════════════════ -->
<section class="section section--events" id="events">
    <div class="container container--wide">
        <div class="section-header">
            <span class="section-label"><?php echo charity_t( 'Những điều đang diễn ra', 'What\'s Happening' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Các Sự Kiện', 'Events' ); ?></h2>
        </div>

        <?php
        $events_query = new WP_Query( [
            'post_type'      => 'event',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        ] );
        ?>

        <div class="events-grid">
        <?php if ( $events_query->have_posts() ) : ?>
            <?php while ( $events_query->have_posts() ) : $events_query->the_post(); ?>
            <article class="event-card animate-in">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="event-card__img-wrap">
                    <?php the_post_thumbnail( 'event-thumb', [ 'alt' => get_the_title() ] ); ?>
                </div>
                <?php else : ?>
                <div class="event-card__img-wrap event-card__img-wrap--placeholder">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </div>
                <?php endif; ?>
                <div class="event-card__body">
                    <h4 class="event-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <p class="event-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                    <a href="<?php the_permalink(); ?>" class="event-card__link"><?php echo charity_t( 'Xem thêm', 'Learn more' ); ?> &rarr;</a>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>

        <?php else : ?>
            <?php
            $placeholders = [
                [ 'Lễ trao học bổng Vươn Lên 2026', 'Annual Vuon Len scholarship award ceremony for university students.' ],
                [ 'Sinh hoạt định kỳ sinh viên nhận học bổng', 'Monthly gathering for scholarship recipients with mentorship activities.' ],
                [ 'Ngày hội hướng nghiệp', 'Career orientation day connecting students with industry professionals.' ],
                [ 'Trao học bổng Lá Xanh', 'La Xanh scholarship ceremony for high school students.' ],
                [ 'Gây quỹ học bổng Mai Vàng', 'Mai Vang scholarship fundraising event.' ],
                [ 'Hội thảo ước mơ và hành động', 'Dreams and action workshop for aspiring students.' ],
            ];
            foreach ( $placeholders as $p ) :
            ?>
            <article class="event-card animate-in">
                <div class="event-card__img-wrap event-card__img-wrap--placeholder">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </div>
                <div class="event-card__body">
                    <h4 class="event-card__title"><?php echo esc_html( $p[0] ); ?></h4>
                    <p class="event-card__excerpt"><?php echo esc_html( $p[1] ); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ DONATE CTA ════════════════════════════════════════════════════════ -->
<section class="section section--donate">
    <div class="donate__inner">
        <div class="donate__text">
            <span class="section-label section-label--light"><?php echo charity_t( 'Ủng hộ Quỹ', 'Support Our Fund' ); ?></span>
            <h2 class="donate__title"><?php echo charity_t( 'Chung tay góp sức cho những ước mơ lớn', 'Help fuel big dreams through education' ); ?></h2>
            <p><?php echo charity_t(
                'Mỗi đóng góp của bạn, dù nhỏ hay lớn, đều trực tiếp giúp một sinh viên có thêm cơ hội theo đuổi ước mơ trên giảng đường đại học.',
                'Every contribution, big or small, directly helps a student pursue their university dreams.'
            ); ?></p>
        </div>
        <div class="donate__actions">
            <a href="#" class="btn btn--gold"><?php echo charity_t( 'Tài trợ ngay', 'Donate Now' ); ?></a>
            <a href="#" class="btn btn--outline-white"><?php echo charity_t( 'Hồ sơ tài trợ', 'Sponsorship Info' ); ?></a>
        </div>
    </div>
</section>

<!-- ═══ PROGRAMS ═══════════════════════════════════════════════════════════ -->
<section class="section section--programs section--bg-light" id="programs">
    <div class="container container--wide">
        <div class="section-header">
            <span class="section-label"><?php echo charity_t( 'Các chương trình học bổng', 'Scholarship Programs' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Học Bổng &amp; Hoạt Động', 'Scholarships &amp; Activities' ); ?></h2>
        </div>

        <?php
        $programs_query = new WP_Query( [
            'post_type'      => 'program',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        ] );
        ?>

        <div class="programs-grid">
        <?php if ( $programs_query->have_posts() ) : ?>
            <?php while ( $programs_query->have_posts() ) : $programs_query->the_post(); ?>
            <article class="program-card animate-in">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="program-card__img">
                    <?php the_post_thumbnail( 'event-thumb', [ 'alt' => get_the_title() ] ); ?>
                </div>
                <?php else : ?>
                <div class="program-card__icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </div>
                <?php endif; ?>
                <h4 class="program-card__name">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>

        <?php else : ?>
            <?php
            $default_programs = [
                [ '🌿', 'Học Bổng Lá Xanh' ],
                [ '🌟', 'Học Bổng Mai Vàng' ],
                [ '🚀', 'Học Bổng Vươn Lên' ],
                [ '🎯', 'Hướng Nghiệp & Tư Vấn' ],
                [ '📚', 'Sinh Hoạt Định Kỳ' ],
                [ '🤝', 'Kết Nối Cựu SV' ],
            ];
            foreach ( $default_programs as $prog ) :
            ?>
            <article class="program-card animate-in">
                <div class="program-card__icon">
                    <span style="font-size:2.5rem;line-height:1"><?php echo $prog[0]; ?></span>
                </div>
                <h4 class="program-card__name"><?php echo esc_html( $prog[1] ); ?></h4>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ HISTORY / OUR STORY ════════════════════════════════════════════════ -->
<section class="section section--history">
    <div class="section--history__overlay"></div>
    <div class="history__inner">
        <div class="history__content">
            <span class="section-label section-label--light"><?php echo charity_t( 'Hành trình của chúng tôi', 'Our Journey' ); ?></span>
            <h2 class="history__title"><?php echo charity_t( 'Câu Chuyện Vươn Lên', 'Vuon Len Stories' ); ?></h2>
            <p><?php echo charity_t(
                'Mỗi suất học bổng là một câu chuyện về nghị lực và ước mơ. Cùng chúng tôi lắng nghe những câu chuyện của các bạn sinh viên đã và đang vươn lên từ học bổng Đông Du.',
                'Every scholarship is a story of resilience and dreams. Join us in hearing the stories of students who have risen through the Dong Du scholarship.'
            ); ?></p>
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog' ) ); ?>" class="btn btn--gold">
                <?php echo charity_t( 'Xem thêm câu chuyện', 'More Stories' ); ?>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>