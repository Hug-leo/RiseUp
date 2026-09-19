<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="footer__top">
        <div class="container footer__grid">

            <div class="footer__col footer__col--brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__brand">
                    <img src="<?php echo esc_url( CHARITY_HCM_URI . '/assets/img/dong-du-logo.jpg' ); ?>" alt="<?php echo esc_attr( charity_t( 'Vươn Lên', 'Rise Up' ) ); ?>">
                    <div>
                        <strong><?php echo charity_t( 'Học Bổng Vươn Lên', 'Rise Up Scholarship' ); ?></strong>
                        <span><?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?></span>
                    </div>
                </a>
                <p class="footer__about">
                    <?php echo charity_t(
                        'Website lưu giữ tin tức, câu chuyện học bổng, hoạt động cộng đồng và bản đồ kết nối của Học Bổng Vươn Lên.',
                        'The website gathers Rise Up news, scholarship stories, community activities, and the member connection map.'
                    ); ?>
                </p>
                <div class="footer__pill-tag">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span><?php echo charity_t( 'Trao cơ hội — Nối tương lai', 'Empowering dreams, bridging futures' ); ?></span>
                </div>

            </div>

            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Chương trình', 'Content Pillars' ); ?></h4>
                <ul class="footer__nav">
                    <?php
                    $footer_groups = charity_content_groups();
                    foreach ( $footer_groups as $fgroup ) :
                        $furl = charity_category_url_by_slug( $fgroup['slug'] );
                    ?>
                        <li>
                            <a href="<?php echo esc_url( $furl ); ?>">
                                <?php echo esc_html( charity_t( $fgroup['title_vi'], $fgroup['title_en'] ) ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Liên kết nhanh', 'Quick Links' ); ?></h4>
                <ul class="footer__nav">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo charity_t( 'Trang chủ', 'Home' ); ?></a></li>
                    <li><a href="<?php echo esc_url( charity_category_url_by_slug( 'ban-do-vuon-len' ) ); ?>"><?php echo charity_t( 'Bản đồ sinh viên', 'Student Map' ); ?></a></li>
                    <li><a href="<?php echo esc_url( charity_portal_url( 'member' ) ); ?>"><?php echo charity_t( 'Cổng thành viên', 'Member Portal' ); ?></a></li>
                    <li><a href="<?php echo esc_url( charity_submit_post_url() ); ?>"><?php echo charity_t( 'Đóng góp bài viết', 'Submit Story' ); ?></a></li>
                    <li><a href="<?php echo esc_url( charity_portal_url( 'feedback' ) ); ?>"><?php echo charity_t( 'Gửi ý kiến phản hồi', 'Send Feedback' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php echo charity_t( 'Liên hệ', 'Contact' ); ?></a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Đồng hành cùng Quỹ', 'Get In Touch' ); ?></h4>
                <p class="footer__contact-lead">
                    <?php echo charity_t(
                        'Mọi đóng góp, câu hỏi và đề xuất hợp tác xin vui lòng liên hệ Ban Điều Hành Quỹ.',
                        'For contributions, inquiries, and partnerships, please contact the Fund Committee.'
                    ); ?>
                </p>
                <div class="footer__contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>contact@hocbongvuonlen.vn</span>
                </div>
                <div class="footer__contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>TP. Hồ Chí Minh, Việt Nam</span>
                </div>
            </div>


        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <span>&copy; <?php echo wp_date( 'Y' ); ?> <?php echo charity_t( 'Học Bổng Vươn Lên', 'Rise Up Scholarship' ); ?> · <?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?>. <?php echo charity_t( 'Tất cả quyền được bảo lưu.', 'All rights reserved.' ); ?></span>
            <span><?php echo charity_t( 'Xây dựng với tâm huyết vì thế hệ sinh viên Việt Nam.', 'Built with passion for Vietnam’s future generations.' ); ?></span>
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
