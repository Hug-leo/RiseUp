<!-- ═══ FOOTER ════════════════════════════════════════════════════════════ -->
<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="footer__top">
        <div class="container footer__grid">

            <!-- Col 1: Brand -->
            <div class="footer__col footer__col--brand">
                <div class="footer__brand">
                    <svg width="56" height="56" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="30" cy="30" r="28" fill="#b71c1c" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                        <path d="M30 14l3.09 9.51H43.5l-8.27 6.01 3.16 9.73L30 33.24l-8.39 6.01 3.16-9.73L16.5 23.51H26.91L30 14z" fill="#ffd700"/>
                    </svg>
                    <div>
                        <strong><?php bloginfo( 'name' ); ?></strong>
                        <span><?php bloginfo( 'description' ); ?></span>
                    </div>
                </div>
                <p class="footer__about">
                    <?php echo charity_t(
                        'Câu lạc bộ hoạt động vì cộng đồng tại Thành phố Hồ Chí Minh. Chúng tôi kết nối những trái tim nhân ái để mang lại nụ cười cho những hoàn cảnh khó khăn.',
                        'A community-driven club in Ho Chi Minh City. We connect compassionate hearts to bring smiles to those in need.'
                    ); ?>
                </p>
            </div>

            <!-- Col 2: Quick links -->
            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Điều hướng', 'Navigation' ); ?></h4>
                <?php
                wp_nav_menu( [
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer__nav',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => function () {
                        echo '<ul class="footer__nav">';
                        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . charity_t( 'Trang chủ', 'Home' ) . '</a></li>';
                        echo '<li><a href="#">' . charity_t( 'Giới thiệu', 'About' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( get_post_type_archive_link( 'event' ) ) . '">' . charity_t( 'Sự kiện', 'Events' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . charity_t( 'Tin tức', 'News' ) . '</a></li>';
                        echo '<li><a href="#">' . charity_t( 'Liên hệ', 'Contact' ) . '</a></li>';
                        echo '</ul>';
                    },
                ] );
                ?>
            </div>

            <!-- Col 3: Contact -->
            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Liên hệ', 'Contact' ); ?></h4>
                <ul class="footer__contact">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        <?php echo charity_t( 'Thành phố Hồ Chí Minh, Việt Nam', 'Ho Chi Minh City, Vietnam' ); ?>
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        contact@charityhcm.org
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        0901 234 567
                    </li>
                </ul>
            </div>

            <!-- Col 4: Social -->
            <div class="footer__col">
                <h4 class="footer__heading"><?php echo charity_t( 'Theo dõi chúng tôi', 'Follow Us' ); ?></h4>
                <div class="footer__social">
                    <a href="#" class="footer__social-btn footer__social-btn--fb" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        Facebook
                    </a>
                    <a href="#" class="footer__social-btn footer__social-btn--yt" aria-label="YouTube">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                        YouTube
                    </a>
                    <a href="#" class="footer__social-btn footer__social-btn--ig" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                        Instagram
                    </a>
                    <a href="#" class="footer__social-btn footer__social-btn--tk" aria-label="TikTok">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.83a8.22 8.22 0 004.8 1.53V6.91a4.85 4.85 0 01-1.03-.22z"/></svg>
                        TikTok
                    </a>
                </div>
            </div>

        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <span>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php echo charity_t( 'Tất cả quyền được bảo lưu.', 'All rights reserved.' ); ?></span>
            <span><?php echo charity_t( 'Được tạo từ', 'Made with' ); ?> <span style="color:#e74c3c">♥</span> <?php echo charity_t( 'tại Thành phố Hồ Chí Minh', 'in Ho Chi Minh City' ); ?></span>
        </div>
    </div>
</footer>

</div><!-- #page -->

<!-- Back to Top Button -->
<button class="back-to-top" id="back-to-top" aria-label="<?php echo charity_t( 'Lên đầu trang', 'Back to top' ); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
