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
        <?php
        $banner_icon = ( $group && ! empty( $group['slug'] ) ) ? charity_group_icon( $group['slug'] ) : '';
        if ( $banner_icon ) :
        ?>
        <div class="page-banner__icon" aria-hidden="true">
            <?php echo $banner_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted SVG from charity_group_icon() ?>
        </div>
        <?php endif; ?>
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
                    <div class="container">
                        <div class="student-map__header">
                            <h2 class="student-map__title">
                                <?php echo charity_t( 'Bản đồ sinh viên Vươn Lên', 'Rise Up Student Origins' ); ?>
                            </h2>
                            <p class="student-map__desc">
                                <?php echo charity_t(
                                    'Nơi xuất phát của các thành viên và cựu học sinh chương trình Học Bổng Vươn Lên trên khắp Việt Nam.',
                                    'Origins of Rise Up Scholarship members and alumni across Vietnam.'
                                ); ?>
                            </p>
                        </div>

                        <div class="student-map__wrap">
                            <!-- SVG container: 63-province view (default active) -->
                            <div class="student-map__svg-container active" id="student-map-63" aria-hidden="true">
                                <?php
                                $svg_63 = get_template_directory() . '/assets/img/vietnam-63-provinces.svg';
                                if ( file_exists( $svg_63 ) ) {
                                    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
                                    echo file_get_contents( $svg_63 ); // Inline SVG for JS manipulation
                                }
                                ?>
                            </div>

                            <!-- Tooltip (shared across maps) -->
                            <div class="student-map__tooltip" id="student-map-tooltip" role="tooltip" aria-live="polite"></div>
                        </div>

                        <p class="student-map__note">
                            <?php echo charity_t(
                                '* Dữ liệu minh họa — số lượng thực tế sẽ được cập nhật trong các phiên bản tới.',
                                '* Sample data — actual numbers will be updated in future versions.'
                            ); ?>
                        </p>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ( $current_item && $current_item['slug'] === 'tong-hop-bai-hat' ) : ?>
                <section class="category-song">
                    <div class="category-song__header">
                        <span class="section-label"><?php echo charity_t( 'Bài hát', 'Songs' ); ?></span>
                        <h2><?php echo charity_t( 'Kho lưu trữ bài hát', 'Song Library' ); ?></h2>
                    </div>
                    <?php if ( have_posts() ) : ?>
                        <div class="song-grid">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'template-parts/content', 'song' ); ?>
                            <?php endwhile; ?>
                        </div>
                        <?php the_posts_pagination( [
                            'prev_text' => '&larr; ' . charity_t( 'Trước', 'Previous' ),
                            'next_text' => charity_t( 'Tiếp', 'Next' ) . ' &rarr;',
                        ] ); ?>
                    <?php else : ?>
                        <p class="no-content"><?php echo charity_t( 'Chưa có bài hát nào. Hãy là người đầu tiên chia sẻ!', 'No songs yet. Be the first to share!' ); ?></p>
                    <?php endif; ?>
                </section>
            <?php elseif ( $current_item && $current_item['slug'] === 'guong-mat-vuon-len' ) : ?>
                <section class="category-profile">
                    <div class="category-profile__header">
                        <span class="section-label"><?php echo charity_t( 'Gương mặt tiêu biểu', 'Featured Members' ); ?></span>
                        <h2><?php echo charity_t( 'Những người Vươn Lên', 'Rise Up Members' ); ?></h2>
                    </div>
                    <?php if ( have_posts() ) : ?>
                        <div class="profile-grid">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'template-parts/content', 'profile' ); ?>
                            <?php endwhile; ?>
                        </div>
                        <?php the_posts_pagination( [
                            'prev_text' => '&larr; ' . charity_t( 'Trước', 'Previous' ),
                            'next_text' => charity_t( 'Tiếp', 'Next' ) . ' &rarr;',
                        ] ); ?>
                    <?php else : ?>
                        <p class="no-content"><?php echo charity_t( 'Chưa có hồ sơ nào.', 'No profiles yet.' ); ?></p>
                    <?php endif; ?>
                </section>
            <?php elseif ( $current_item && in_array( $current_item['slug'], [ 'bi-kip', 'the-gioi-quanh-ta' ], true ) ) : ?>
                <section class="category-tips">
                    <div class="category-tips__header">
                        <span class="section-label"><?php echo charity_t( 'Bí kíp & Kiến thức', 'Tips & Knowledge' ); ?></span>
                        <h2><?php echo charity_t( 'Khám phá mẹo hay', 'Discover Tips' ); ?></h2>
                    </div>
                    <?php if ( have_posts() ) : ?>
                        <div class="tips-grid">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'template-parts/content', 'tip' ); ?>
                            <?php endwhile; ?>
                        </div>
                        <?php the_posts_pagination( [
                            'prev_text' => '&larr; ' . charity_t( 'Trước', 'Previous' ),
                            'next_text' => charity_t( 'Tiếp', 'Next' ) . ' &rarr;',
                        ] ); ?>
                    <?php else : ?>
                        <p class="no-content"><?php echo charity_t( 'Chưa có bài viết nào.', 'No posts yet.' ); ?></p>
                    <?php endif; ?>
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
