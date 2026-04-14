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
                        'Website ưu tiên 4 nhóm nội dung cốt lõi và luôn sẵn sàng mở rộng thêm chuyên mục, tính năng UI mới trong các giai đoạn tiếp theo.',
                        'The website currently focuses on 4 core content pillars and is ready to expand with additional sections and UI features in future phases.'
                    ); ?>
                </p>
            </div>

            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Liên kết nhanh', 'Quick Links' ); ?></h4>
                <ul class="footer__nav">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a></li>
                    <li><a href="#content-roadmap"><?php echo charity_t( 'Mục nội dung', 'Content Pillars' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php echo charity_t( 'Bài viết', 'Stories' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php echo charity_t( 'Liên hệ', 'Contact' ); ?></a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <span>&copy; <?php echo date( 'Y' ); ?> <?php echo charity_t( 'Học Bổng Vươn Lên', 'Rise Up Scholarship' ); ?>. <?php echo charity_t( 'Tất cả quyền được bảo lưu.', 'All rights reserved.' ); ?></span>
            <span><?php echo charity_t( 'Nền tảng luôn sẵn sàng cập nhật mục mới và tính năng mới.', 'Platform is continuously ready for new sections and features.' ); ?></span>
        </div>
    </div>
</footer>

</div><!-- #page -->

<button class="back-to-top" id="back-to-top" aria-label="<?php echo charity_t( 'Lên đầu trang', 'Back to top' ); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
