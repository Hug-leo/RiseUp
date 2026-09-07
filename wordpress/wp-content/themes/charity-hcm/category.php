<?php
get_header();

$term = get_queried_object();
$section_data = $term instanceof WP_Term ? charity_find_content_group_by_slug( $term->slug ) : null;
$group = null;
$current_item = null;

if ( isset( $section_data['item'], $section_data['parent'] ) ) {
    $group = $section_data['parent'];
    $current_item = $section_data['item'];
} elseif ( is_array( $section_data ) && isset( $section_data['items'] ) ) {
    $group = $section_data;
}
?>

<div class="page-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a>
            <span>/</span>
            <?php if ( $group && $current_item ) : ?>
                <?php $parent_term = get_category_by_slug( $group['slug'] ); ?>
                <?php if ( $parent_term ) : ?>
                    <a href="<?php echo esc_url( get_category_link( $parent_term ) ); ?>"><?php echo esc_html( charity_t( $group['title_vi'], $group['title_en'] ) ); ?></a>
                    <span>/</span>
                <?php endif; ?>
            <?php endif; ?>
            <span><?php echo esc_html( single_cat_title( '', false ) ); ?></span>
        </div>
        <h1 class="page-banner__title">
            <?php
            if ( $current_item ) {
                echo esc_html( charity_t( $current_item['vi'], $current_item['en'] ) );
            } elseif ( $group ) {
                echo esc_html( charity_t( $group['title_vi'], $group['title_en'] ) );
            } else {
                echo esc_html( single_cat_title( '', false ) );
            }
            ?>
        </h1>
        <?php if ( $current_item || $group ) : ?>
            <p class="page-banner__desc">
                <?php
                echo esc_html( $current_item
                    ? charity_t( $current_item['desc_vi'], $current_item['desc_en'] )
                    : charity_t( $group['summary_vi'], $group['summary_en'] )
                );
                ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="content-wrap no-sidebar">
    <div class="container container--wide">
        <main id="main" class="site-main" role="main">
            <?php if ( $group && ! $current_item ) : ?>
                <section class="category-idea-panel">
                    <div class="category-idea-panel__header">
                        <span class="section-label"><?php echo charity_t( 'Các mục nhỏ', 'Subsections' ); ?></span>
                        <h2><?php echo charity_t( 'Nội dung cần triển khai', 'Planned Content' ); ?></h2>
                    </div>
                    <div class="category-idea-grid">
                        <?php foreach ( $group['items'] as $idea ) : ?>
                            <?php $idea_term = get_category_by_slug( $idea['slug'] ); ?>
                            <article class="category-idea-card">
                                <h3>
                                    <?php if ( $idea_term ) : ?>
                                        <a href="<?php echo esc_url( get_category_link( $idea_term ) ); ?>"><?php echo esc_html( charity_t( $idea['vi'], $idea['en'] ) ); ?></a>
                                    <?php else : ?>
                                        <?php echo esc_html( charity_t( $idea['vi'], $idea['en'] ) ); ?>
                                    <?php endif; ?>
                                </h3>
                                <p><?php echo esc_html( charity_t( $idea['desc_vi'], $idea['desc_en'] ) ); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ( $current_item && $current_item['slug'] === 'ban-do-vuon-len' ) : ?>
                <section class="student-map-section" id="student-map-interactive">
                    <div class="student-map__header">
                        <h2 class="student-map__title">
                            <?php echo esc_html( charity_t( 'Bản đồ sinh viên Vươn Lên', 'Rise Up Student Origins' ) ); ?>
                        </h2>
                        <p class="student-map__desc">
                            <?php echo esc_html( charity_t(
                                'Khám phá quê quán và nơi kết nối của các thành viên Vươn Lên trên khắp Việt Nam.',
                                'Explore the hometowns and connection points of Rise Up members across Vietnam.'
                            ) ); ?>
                        </p>
                    </div>

                    <div class="student-map__toolbar">
                        <div class="student-map__toggle" role="group" aria-label="<?php echo esc_attr( charity_t( 'Chọn hệ thống đơn vị hành chính cấp tỉnh', 'Select provincial-level administrative system' ) ); ?>">
                            <button class="map-toggle-btn active" data-map="34" type="button" aria-pressed="true">
                                <span><?php echo esc_html( charity_t( '34 tỉnh/thành hiện hành', '34 current provinces/cities' ) ); ?></span>
                            </button>
                            <button class="map-toggle-btn" data-map="63" type="button" aria-pressed="false">
                                <span><?php echo esc_html( charity_t( '63 tỉnh/thành trước sắp xếp', '63 before reorganisation' ) ); ?></span>
                            </button>
                        </div>

                        <div class="student-map__search">
                            <label class="screen-reader-text" for="student-map-search"><?php echo esc_html( charity_t( 'Tìm tỉnh, thành hoặc thành viên', 'Search provinces, cities or members' ) ); ?></label>
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><path d="m21 21-4.35-4.35m2.35-5.15a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            <input id="student-map-search" type="search" autocomplete="off" placeholder="<?php echo esc_attr( charity_t( 'Tìm tỉnh/thành hoặc thành viên', 'Search province/city or member' ) ); ?>">
                            <div class="student-map__search-results" id="student-map-search-results" hidden></div>
                        </div>
                    </div>

                    <div class="student-map__atlas">
                        <div class="student-map__wrap" aria-label="<?php echo esc_attr( charity_t( 'Bản đồ hành chính Việt Nam tương tác', 'Interactive administrative map of Vietnam' ) ); ?>">
                            <div class="student-map__canvas" id="student-map-canvas" aria-live="polite">
                                <p class="student-map__loading"><?php echo esc_html( charity_t( 'Đang tải bản đồ…', 'Loading map…' ) ); ?></p>
                            </div>
                            <div class="student-map__legend" aria-label="<?php echo esc_attr( charity_t( 'Chú giải bản đồ', 'Map legend' ) ); ?>">
                                <span><i class="map-swatch map-swatch--empty"></i><?php echo esc_html( charity_t( 'Chưa có thành viên', 'No members' ) ); ?></span>
                                <span><i class="map-swatch map-swatch--members"></i><?php echo esc_html( charity_t( 'Có thành viên', 'Has members' ) ); ?></span>
                                <span><i class="map-swatch map-swatch--selected"></i><?php echo esc_html( charity_t( 'Đang chọn', 'Selected' ) ); ?></span>
                            </div>
                            <p class="student-map__islands" aria-hidden="true">Hoàng Sa · Trường Sa</p>
                        </div>

                        <aside class="student-map__detail" id="student-map-detail" aria-live="polite" aria-labelledby="student-map-detail-title">
                            <p class="student-map__detail-kicker"><?php echo esc_html( charity_t( 'Tỉnh/thành đang chọn', 'Selected province/city' ) ); ?></p>
                            <h3 id="student-map-detail-title"><?php echo esc_html( charity_t( 'Chọn một tỉnh/thành', 'Choose a province/city' ) ); ?></h3>
                            <p class="student-map__detail-count" id="student-map-detail-count">—</p>
                            <div class="student-map__constituents" id="student-map-constituents" hidden>
                                <h4><?php echo esc_html( charity_t( 'Hình thành từ', 'Formed from' ) ); ?></h4>
                                <ul id="student-map-constituent-list"></ul>
                            </div>
                            <div class="student-map__members">
                                <h4><?php echo esc_html( charity_t( 'Thành viên HBVL', 'HBVL members' ) ); ?></h4>
                                <ol id="student-map-member-list">
                                    <li class="student-map__empty"><?php echo esc_html( charity_t( 'Nhấp hoặc dùng bàn phím để chọn một tỉnh/thành trên bản đồ.', 'Click or use the keyboard to choose a province/city on the map.' ) ); ?></li>
                                </ol>
                            </div>
                        </aside>
                        <div class="student-map__tooltip" id="student-map-tooltip" role="tooltip" aria-live="polite"></div>
                    </div>

                    <p class="student-map__note">
                        <?php echo esc_html( charity_t(
                            'Bản đồ hỗ trợ 34 đơn vị hành chính cấp tỉnh hiện hành và 63 đơn vị ngay trước sắp xếp năm 2025. Số lượng được tính trực tiếp từ danh sách thành viên HBVL; Hoàng Sa và Trường Sa được thể hiện dưới dạng nhãn tham chiếu, không phải đơn vị bổ sung.',
                            'The map supports the 34 current provincial-level units and the 63 units immediately before the 2025 reorganisation. Counts are derived directly from the HBVL member list; Hoang Sa and Truong Sa are reference labels, not additional units.'
                        ) ); ?>
                    </p>
                </section>
            <?php endif; ?>

            <section class="category-posts">
                <div class="category-posts__header">
                    <span class="section-label"><?php echo charity_t( 'Bài viết', 'Posts' ); ?></span>
                    <h2><?php echo charity_t( 'Nội dung đã đăng', 'Published Content' ); ?></h2>
                </div>

                <?php if ( have_posts() ) : ?>
                    <div class="archive-grid">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php get_template_part( 'template-parts/content', 'card' ); ?>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    the_posts_pagination( [
                        'prev_text' => '&larr; ' . charity_t( 'Trước', 'Previous' ),
                        'next_text' => charity_t( 'Tiếp', 'Next' ) . ' &rarr;',
                    ] );
                    ?>
                <?php else : ?>
                    <p class="no-content"><?php echo charity_t( 'Chưa có bài viết trong chuyên mục này. Có thể bắt đầu bằng phần mô tả ý tưởng ở trên.', 'No posts in this section yet. Start from the content idea above.' ); ?></p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<?php get_footer(); ?>
