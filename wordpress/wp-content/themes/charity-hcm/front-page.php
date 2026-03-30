<?php get_header(); ?>

<!-- ═══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="hero" id="home">
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <p class="hero__eyebrow">Câu lạc bộ Tình Nguyện</p>
        <h1 class="hero__title"><?php bloginfo( 'name' ); ?></h1>
        <p class="hero__subtitle"><?php bloginfo( 'description' ); ?></p>
        <div class="hero__actions">
            <a href="<?php echo esc_url( get_page_link( get_page_by_path( 'gioi-thieu' ) ) ?: '#about' ); ?>" class="btn btn--primary">
                Về chúng tôi
            </a>
            <a href="<?php echo esc_url( get_page_link( get_page_by_path( 'lien-he' ) ) ?: '#contact' ); ?>" class="btn btn--outline">
                Liên hệ với chúng tôi
            </a>
        </div>
    </div>
    <div class="hero__scroll-hint">
        <span></span>
    </div>
</section>

<!-- ═══ ABOUT ═════════════════════════════════════════════════════════════ -->
<section class="section section--about" id="about">
    <div class="container about__inner">
        <div class="about__text">
            <span class="section-label">Về chúng tôi</span>
            <h2 class="section-title">Chúng tôi là ai?</h2>
            <p>
                Câu lạc bộ tình nguyện của chúng tôi quy tụ những bạn trẻ năng động tại Thành phố Hồ Chí Minh, cùng chung một tấm lòng hướng đến cộng đồng. Từ những buổi gây quỹ nhỏ đến những chuyến công tác thiện nguyện, chúng tôi hành động vì niềm tin rằng mỗi cá nhân đều có thể tạo ra sự thay đổi.
            </p>
            <p>
                Kế thừa tinh thần tương thân tương ái của người Việt Nam, chúng tôi tổ chức các hoạt động hỗ trợ trẻ em có hoàn cảnh khó khăn, người cao tuổi neo đơn, và các cộng đồng bị ảnh hưởng bởi thiên tai tại các tỉnh thành khu vực phía Nam.
            </p>
            <a href="#" class="btn btn--text">
                Xem thêm về chúng tôi
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="about__stats">
            <div class="stat-card">
                <span class="stat-card__number">38+</span>
                <span class="stat-card__label">Trẻ em được hỗ trợ</span>
            </div>
            <div class="stat-card">
                <span class="stat-card__number">15M</span>
                <span class="stat-card__label">VND đã gây quỹ</span>
            </div>
            <div class="stat-card">
                <span class="stat-card__number">22</span>
                <span class="stat-card__label">Thành viên tích cực</span>
            </div>
            <div class="stat-card">
                <span class="stat-card__number">3+</span>
                <span class="stat-card__label">Chiến dịch hoàn thành</span>
            </div>
        </div>
    </div>
</section>

<!-- ═══ FEATURED NEWS ══════════════════════════════════════════════════════ -->
<section class="section section--news section--bg-light" id="news">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Cập nhật mới nhất</span>
            <h2 class="section-title">Tin Tức Nổi Bật</h2>
        </div>

        <?php
        $featured_query = new WP_Query( [
            'post_type'      => 'post',
            'posts_per_page' => 4,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );
        ?>

        <?php if ( $featured_query->have_posts() ) : ?>
        <div class="news-grid">

            <?php
            $i = 0;
            while ( $featured_query->have_posts() ) :
                $featured_query->the_post();
                $i++;
            ?>
            <?php if ( $i === 1 ) : // Large featured card ?>
            <article class="news-card news-card--featured">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="news-card__img-wrap">
                    <?php the_post_thumbnail( 'card-wide', [ 'class' => 'news-card__img', 'alt' => get_the_title() ] ); ?>
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
                    <h3 class="news-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="news-card__excerpt"><?php the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btn--text btn--sm">
                        Xem thêm
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>

            <?php else : // Small cards ?>
            <article class="news-card news-card--small">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="news-card__img-wrap">
                    <?php the_post_thumbnail( 'card-thumb', [ 'class' => 'news-card__img', 'alt' => get_the_title() ] ); ?>
                </a>
                <?php endif; ?>
                <div class="news-card__body">
                    <div class="news-card__meta">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="news-card__date">
                            <?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?>
                        </time>
                    </div>
                    <h3 class="news-card__title news-card__title--sm">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <a href="<?php the_permalink(); ?>" class="btn btn--text btn--xs">Xem thêm →</a>
                </div>
            </article>
            <?php endif; ?>

            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <div class="load-more-wrap" id="news-load-more-wrap">
            <button
                class="btn btn--outline-dark load-more-btn"
                id="news-load-more"
                data-page="2"
                data-cat="0"
            >
                Xem thêm tin tức
            </button>
        </div>
        <div id="news-more-posts" class="news-more-grid"></div>

        <?php else : ?>
        <p class="no-content">Chưa có bài viết nào. <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>">Thêm bài viết mới</a>.</p>
        <?php endif; ?>
    </div>
</section>

<!-- ═══ EVENTS ════════════════════════════════════════════════════════════ -->
<section class="section section--events" id="events">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Những điều đang diễn ra</span>
            <h2 class="section-title">Các Sự Kiện Nổi Bật</h2>
        </div>

        <?php
        $events_query = new WP_Query( [
            'post_type'      => 'event',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        ] );
        ?>

        <div class="events-track-wrap">
            <div class="events-track" id="events-track">

            <?php if ( $events_query->have_posts() ) : ?>
                <?php while ( $events_query->have_posts() ) : $events_query->the_post(); ?>
                <article class="event-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="event-card__img-wrap">
                        <?php the_post_thumbnail( 'event-thumb', [ 'class' => 'event-card__img', 'alt' => get_the_title() ] ); ?>
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
                        <a href="<?php the_permalink(); ?>" class="event-card__link">Xem thêm →</a>
                    </div>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>

            <?php else : ?>
                <!-- Placeholder event cards when no events are added yet -->
                <?php
                $placeholders = [
                    [ 'Chiến dịch Xuân Tình Nguyện', 'Hỗ trợ các gia đình khó khăn trước Tết Nguyên Đán.' ],
                    [ 'Mùa Hè Xanh', 'Tình nguyện phục vụ cộng đồng tại các vùng ngoại ô.' ],
                    [ 'Gây quỹ Ánh Sáng', 'Quyên góp học bổng cho trẻ em nghèo hiếu học.' ],
                    [ 'Ngày hội Trao yêu thương', 'Phân phát nhu yếu phẩm tại các mái ấm.' ],
                    [ 'Chạy bộ từ thiện', 'Marathon gây quỹ vì trẻ em có hoàn cảnh đặc biệt.' ],
                    [ 'Trại hè kỹ năng', 'Trang bị kỹ năng sống cho thanh thiếu niên.' ],
                ];
                foreach ( $placeholders as $p ) :
                ?>
                <article class="event-card">
                    <div class="event-card__img-wrap event-card__img-wrap--placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                    <div class="event-card__body">
                        <h4 class="event-card__title"><?php echo esc_html( $p[0] ); ?></h4>
                        <p class="event-card__excerpt"><?php echo esc_html( $p[1] ); ?></p>
                        <a href="#" class="event-card__link">Xem thêm →</a>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>

            </div><!-- .events-track -->

            <button class="events-nav events-nav--prev" id="events-prev" aria-label="Previous">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button class="events-nav events-nav--next" id="events-next" aria-label="Next">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
            </button>
        </div>
    </div>
</section>

<!-- ═══ DONATE CTA ════════════════════════════════════════════════════════ -->
<section class="section section--donate">
    <div class="container donate__inner">
        <div class="donate__text">
            <span class="section-label section-label--light">Hỗ trợ chúng tôi</span>
            <h2 class="donate__title">Tham gia hoạt động tài trợ cho các chương trình</h2>
            <p>Mỗi đóng góp của bạn, dù nhỏ hay lớn, đều trực tiếp mang lại thay đổi tích cực cho những mảnh đời cần được yêu thương.</p>
        </div>
        <div class="donate__actions">
            <a href="#" class="btn btn--gold">Tài trợ ngay</a>
            <a href="#" class="btn btn--outline-white">Hồ sơ tài trợ</a>
        </div>
    </div>
</section>

<!-- ═══ PROGRAMS / CLUBS ═══════════════════════════════════════════════════ -->
<section class="section section--programs" id="programs">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Những gì chúng tôi làm</span>
            <h2 class="section-title">Các Chương Trình &amp; Hoạt Động</h2>
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
            <article class="program-card">
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
            <article class="program-card">
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
    <div class="container history__inner">
        <div class="history__content">
            <span class="section-label section-label--light">Hành trình của chúng tôi</span>
            <h2 class="history__title">Những Câu Chuyện Tình Nguyện</h2>
            <p>Mỗi chuyến đi là một trang ký ức. Mỗi nụ cười là một nguồn động lực. Cùng chúng tôi nhìn lại những hành trình đã qua và hướng tới những điều tốt đẹp hơn phía trước.</p>
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog' ) ); ?>" class="btn btn--gold">
                Xem thêm câu chuyện
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
