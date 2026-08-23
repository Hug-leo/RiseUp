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
                        <span class="section-label"><?php echo charity_t( 'Bản đồ tương tác', 'Interactive map' ); ?></span>
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

                    <div class="student-map__toggle" role="group" aria-label="<?php echo esc_attr( charity_t( 'Chọn phân chia tỉnh thành', 'Select province division' ) ); ?>">
                        <button class="map-toggle-btn active" data-map="63" type="button">
                            <span><?php echo esc_html( charity_t( '63 tỉnh thành', '63 provinces' ) ); ?></span>
                        </button>
                        <button class="map-toggle-btn" data-map="34" type="button">
                            <span><?php echo esc_html( charity_t( '34 tỉnh thành', '34 provinces' ) ); ?></span>
                        </button>
                    </div>

                    <div class="student-map__wrap" aria-live="polite">
                        <div class="student-map__svg-container active" id="student-map-63" data-map="63" aria-hidden="false">
                            <?php
                            $svg_63 = CHARITY_HCM_DIR . '/assets/img/vietnam-63-provinces.svg';
                            if ( file_exists( $svg_63 ) ) {
                                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted theme SVG asset.
                                echo file_get_contents( $svg_63 );
                            } else {
                                echo '<p class="student-map__fallback">' . esc_html( charity_t( 'Chưa tìm thấy bản đồ 63 tỉnh thành.', 'The 63-province map asset is missing.' ) ) . '</p>';
                            }
                            ?>
                        </div>

                        <div class="student-map__svg-container" id="student-map-34" data-map="34" aria-hidden="true">
                            <?php
                            $svg_34 = CHARITY_HCM_DIR . '/assets/img/vietnam-34-provinces.svg';
                            if ( file_exists( $svg_34 ) ) {
                                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted theme SVG asset.
                                echo file_get_contents( $svg_34 );
                            } else {
                                echo '<p class="student-map__fallback">' . esc_html( charity_t( 'Chưa tìm thấy bản đồ 34 tỉnh thành.', 'The 34-province map asset is missing.' ) ) . '</p>';
                            }
                            ?>
                        </div>

                        <div class="student-map__tooltip" id="student-map-tooltip" role="tooltip" aria-live="polite"></div>
                    </div>

                    <p class="student-map__note">
                        <?php echo esc_html( charity_t(
                            'Bản đồ thể hiện đất liền, Phú Quốc, các đảo ven bờ và hai quần đảo Hoàng Sa, Trường Sa của Việt Nam. Số lượng được tính từ danh sách thành viên theo từng tỉnh.',
                            'The map shows mainland Vietnam, Phu Quoc, coastal islands, and the Hoang Sa and Truong Sa archipelagos of Vietnam. Counts are derived from the member list for each province.'
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
