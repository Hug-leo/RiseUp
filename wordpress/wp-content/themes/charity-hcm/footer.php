<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="footer__top">
        <div class="container footer__grid footer__grid--compact">

            <div class="footer__col footer__col--brand">
                <div class="footer__brand">
                    <img src="<?php echo esc_url( CHARITY_HCM_URI . '/assets/img/dong-du-logo.jpg' ); ?>" alt="<?php echo esc_attr( charity_t( 'Vươn Lên', 'Rise Up' ) ); ?>">
                    <div>
                        <strong><?php echo charity_t( 'Vươn Lên', 'Rise Up' ); ?></strong>
                        <span><?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?></span>
                    </div>
                </div>
                <p class="footer__about">
                    <?php echo charity_t(
                        'Website triển khai 5 nhóm nội dung chính của Học Bổng Vươn Lên và sẵn sàng mở rộng thêm trang chuyên đề, bản đồ cộng đồng, bộ lọc và công cụ quản trị nội dung.',
                        'The website now supports 5 core Rise Up content groups and is ready to expand with feature pages, community map, filters, and content operations tools.'
                    ); ?>
                </p>
            </div>

            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Liên kết nhanh', 'Quick Links' ); ?></h4>
                <ul class="footer__nav">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a></li>
                    <li><a href="#content-roadmap"><?php echo charity_t( 'Chuyên mục', 'Sections' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php echo charity_t( 'Bài viết', 'Stories' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php echo charity_t( 'Liên hệ', 'Contact' ); ?></a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <span>&copy; <?php echo wp_date( 'Y' ); ?> <?php echo charity_t( 'Học Bổng Vươn Lên', 'Rise Up Scholarship' ); ?>. <?php echo charity_t( 'Tất cả quyền được bảo lưu.', 'All rights reserved.' ); ?></span>
            <span><?php echo charity_t( 'Nền tảng luôn sẵn sàng cập nhật chuyên mục và tính năng mới.', 'The platform is ready for new sections and features.' ); ?></span>
        </div>
    </div>
</footer>

</div><!-- #page -->

<button class="back-to-top" id="back-to-top" aria-label="<?php echo esc_attr( charity_t( 'Lên đầu trang', 'Back to top' ) ); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
