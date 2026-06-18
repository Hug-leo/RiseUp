<?php
get_header();

$search_query = trim( get_search_query( false ) );
$has_query    = $search_query !== '';
global $wp_query;
?>

<div class="page-banner search-banner">
    <div class="container page-banner__inner">
        <div class="page-banner__breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( charity_t( 'Trang chủ', 'Home' ) ); ?></a>
            <span>/</span>
            <span><?php echo esc_html( charity_t( 'Tìm kiếm', 'Search' ) ); ?></span>
        </div>
        <h1 class="page-banner__title">
            <?php echo esc_html( charity_t( 'Kết quả tìm kiếm', 'Search Results' ) ); ?>
        </h1>
        <p class="page-banner__desc">
            <?php if ( $has_query ) : ?>
                <?php
                printf(
                    esc_html( charity_t( 'Từ khóa: “%s”', 'Keyword: “%s”' ) ),
                    esc_html( $search_query )
                );
                ?>
            <?php else : ?>
                <?php echo esc_html( charity_t( 'Nhập từ khóa để tìm bài viết, hoạt động và câu chuyện trong website.', 'Enter a keyword to find stories, activities, and updates across the site.' ) ); ?>
            <?php endif; ?>
        </p>
    </div>
</div>

<div class="content-wrap no-sidebar search-results-page">
    <div class="container container--wide">
        <main id="main" class="site-main" role="main">
            <section class="search-panel">
                <form class="search-page-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label class="screen-reader-text" for="search-page-field"><?php echo esc_html( charity_t( 'Từ khóa tìm kiếm', 'Search keyword' ) ); ?></label>
                    <input
                        id="search-page-field"
                        class="search-page-form__input"
                        type="search"
                        name="s"
                        value="<?php echo esc_attr( $search_query ); ?>"
                        placeholder="<?php echo esc_attr( charity_t( 'Bạn muốn tìm gì?', 'What are you looking for?' ) ); ?>"
                    >
                    <button class="btn btn--red search-page-form__button" type="submit">
                        <?php echo esc_html( charity_t( 'Tìm kiếm', 'Search' ) ); ?>
                    </button>
                </form>
            </section>

            <?php if ( ! $has_query ) : ?>
                <section class="search-empty-state">
                    <span class="section-label"><?php echo esc_html( charity_t( 'Chưa có từ khóa', 'No keyword yet' ) ); ?></span>
                    <h2><?php echo esc_html( charity_t( 'Hãy nhập một từ khóa để bắt đầu.', 'Enter a keyword to start searching.' ) ); ?></h2>
                    <p><?php echo esc_html( charity_t( 'Ô tìm kiếm phía trên sẽ tìm trong các bài viết và trang hiện có.', 'The search box above will search existing posts and pages.' ) ); ?></p>
                </section>
            <?php elseif ( have_posts() ) : ?>
                <section class="search-results-list">
                    <div class="category-posts__header search-results-list__header">
                        <span class="section-label"><?php echo esc_html( charity_t( 'Kết quả', 'Results' ) ); ?></span>
                        <h2>
                            <?php
                            printf(
                                esc_html( charity_t( 'Tìm thấy %s kết quả', 'Found %s results' ) ),
                                esc_html( number_format_i18n( (int) $wp_query->found_posts ) )
                            );
                            ?>
                        </h2>
                    </div>

                    <div class="archive-grid search-results-grid">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            get_template_part( 'template-parts/content', 'card' );
                        endwhile;
                        ?>
                    </div>

                    <?php
                    the_posts_pagination( [
                        'prev_text' => '&larr; ' . charity_t( 'Trước', 'Previous' ),
                        'next_text' => charity_t( 'Tiếp', 'Next' ) . ' &rarr;',
                    ] );
                    ?>
                </section>
            <?php else : ?>
                <section class="search-empty-state">
                    <span class="section-label"><?php echo esc_html( charity_t( 'Không có kết quả', 'No results' ) ); ?></span>
                    <h2><?php echo esc_html( charity_t( 'Chưa tìm thấy nội dung phù hợp.', 'No matching content found.' ) ); ?></h2>
                    <p>
                        <?php
                        printf(
                            esc_html( charity_t( 'Thử tìm với từ khóa ngắn hơn hoặc chủ đề gần nghĩa với “%s”.', 'Try a shorter keyword or a related topic for “%s”.' ) ),
                            esc_html( $search_query )
                        );
                        ?>
                    </p>
                </section>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
