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
                <section class="category-map-demo">
                    <div class="category-map-demo__map" aria-hidden="true">
                        <img src="<?php echo esc_url( charity_vietnam_map_image_url() ); ?>" alt="">
                        <span class="cp-map-pin cp-map-pin--north"></span>
                        <span class="cp-map-pin cp-map-pin--central"></span>
                        <span class="cp-map-pin cp-map-pin--south"></span>
                    </div>
                    <div class="category-map-demo__content">
                        <span class="section-label"><?php echo charity_t( 'Ý tưởng UI', 'UI Concept' ); ?></span>
                        <h2><?php echo charity_t( 'Bản đồ kết nối thành viên', 'Member Connection Map' ); ?></h2>
                        <p><?php echo charity_t(
                            'Giai đoạn tiếp theo có thể thay mockup này bằng bản đồ tương tác, cho phép lọc theo tỉnh, trạng thái thành viên, mục đích liên hệ và gợi ý chuyến đi chung.',
                            'A later phase can replace this mockup with an interactive map supporting province filters, member status, contact purpose, and trip planning.'
                        ); ?></p>
                    </div>
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
