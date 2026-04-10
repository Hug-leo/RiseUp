<?php get_header(); ?>

<!-- ═══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="hero" id="home">
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <p class="hero__eyebrow"><?php echo charity_t( 'Câu lạc bộ Tình Nguyện', 'Volunteer Club' ); ?></p>
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
                    'Câu lạc bộ tình nguyện của chúng tôi quy tụ những bạn trẻ năng động tại Thành phố Hồ Chí Minh, cùng chung một tấm lòng hướng đến cộng đồng. Từ những buổi gây quỹ nhỏ đến những chuyến công tác thiện nguyện, chúng tôi hành động vì niềm tin rằng mỗi cá nhân đều có thể tạo ra sự thay đổi.',
                    'Our volunteer club brings together dynamic young people in Ho Chi Minh City, united by a shared commitment to the community. From small fundraisers to volunteer missions, we act on the belief that every individual can make a difference.'
                ); ?>
            </p>
            <p>
                <?php echo charity_t(
                    'Kế thừa tinh thần tương thân tương ái của người Việt Nam, chúng tôi tổ chức các hoạt động hỗ trợ trẻ em có hoàn cảnh khó khăn, người cao tuổi neo đơn, và các cộng đồng bị ảnh hưởng bởi thiên tai tại các tỉnh thành khu vực phía Nam.',
                    'Inheriting the Vietnamese spirit of mutual support, we organize activities to help underprivileged children, elderly people living alone, and communities affected by natural disasters across the southern provinces.'
                ); ?>
            </p>
        </div>
        <div class="about__stats">
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="38">38+</span>
                <span class="stat-card__label"><?php echo charity_t( 'Trẻ em được hỗ trợ', 'Children Supported' ); ?></span>
            </div>
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="15">15M</span>
                <span class="stat-card__label"><?php echo charity_t( 'VND đã gây quỹ', 'VND Raised' ); ?></span>
            </div>
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="22">22</span>
                <span class="stat-card__label"><?php echo charity_t( 'Thành viên tích cực', 'Active Members' ); ?></span>
            </div>
            <div class="stat-card animate-in">
                <span class="stat-card__number" data-count="3">3+</span>
                <span class="stat-card__label"><?php echo charity_t( 'Chiến dịch hoàn thành', 'Campaigns Completed' ); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ═══ STORIES FEED (Vertical scroll) ════════════════════════════════════ -->
<section class="section section--feed" id="news">
    <div class="container">
        <div class="feed-header">
            <h2><?php echo charity_t( 'Bài Viết Mới Nhất', 'Latest Stories' ); ?></h2>
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
                [ 'Chiến dịch Xuân Tình Nguyện', 'Spring volunteer campaign supporting families before Tet.' ],
                [ 'Mùa Hè Xanh', 'Green Summer community service in suburban areas.' ],
                [ 'Gây quỹ Ánh Sáng', 'Light Fund raising scholarships for underprivileged students.' ],
                [ 'Ngày hội Trao yêu thương', 'Love Sharing Day distributing essentials to shelters.' ],
                [ 'Chạy bộ từ thiện', 'Charity marathon fundraising for children.' ],
                [ 'Trại hè kỹ năng', 'Life skills summer camp for youth.' ],
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
            <span class="section-label section-label--light"><?php echo charity_t( 'Hỗ trợ chúng tôi', 'Support Us' ); ?></span>
            <h2 class="donate__title"><?php echo charity_t( 'Tham gia hoạt động tài trợ cho các chương trình', 'Help fund our programs and campaigns' ); ?></h2>
            <p><?php echo charity_t(
                'Mỗi đóng góp của bạn, dù nhỏ hay lớn, đều trực tiếp mang lại thay đổi tích cực cho những mảnh đời cần được yêu thương.',
                'Every contribution you make, big or small, directly creates positive change for lives in need of love and care.'
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
            <span class="section-label"><?php echo charity_t( 'Những gì chúng tôi làm', 'What We Do' ); ?></span>
            <h2 class="section-title"><?php echo charity_t( 'Các Chương Trình &amp; Hoạt Động', 'Programs &amp; Activities' ); ?></h2>
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
                [ '❤️', 'CLB Tình Nguyện Đom Đóm' ],
                [ '📚', 'Học Bổng Ánh Sáng' ],
                [ '🏡', 'Mái Ấm Cộng Đồng' ],
                [ '🌿', 'Mùa Hè Xanh' ],
                [ '🎗️', 'Xuân Tình Nguyện' ],
                [ '🤝', 'Kết Nối Yêu Thương' ],
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
            <h2 class="history__title"><?php echo charity_t( 'Những Câu Chuyện Tình Nguyện', 'Volunteer Stories' ); ?></h2>
            <p><?php echo charity_t(
                'Mỗi chuyến đi là một trang ký ức. Mỗi nụ cười là một nguồn động lực. Cùng chúng tôi nhìn lại những hành trình đã qua và hướng tới những điều tốt đẹp hơn phía trước.',
                'Every trip is a page of memories. Every smile is a source of inspiration. Join us as we look back on our journeys and forward to the brighter days ahead.'
            ); ?></p>
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog' ) ); ?>" class="btn btn--gold">
                <?php echo charity_t( 'Xem thêm câu chuyện', 'More Stories' ); ?>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>