<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

<!-- ═══ TOPBAR ═══════════════════════════════════════════════════════════ -->
<div class="topbar">
    <div class="container topbar__inner">
        <span class="topbar__left">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            <?php echo charity_t( 'Thành phố Hồ Chí Minh, Việt Nam', 'Ho Chi Minh City, Vietnam' ); ?>
        </span>
        <div class="topbar__right">
            <div class="lang-switcher">
                <a href="<?php echo esc_url( charity_lang_url( 'vi' ) ); ?>" class="lang-switcher__btn <?php echo charity_get_lang() === 'vi' ? 'active' : ''; ?>">VI</a>
                <span class="lang-switcher__sep">|</span>
                <a href="<?php echo esc_url( charity_lang_url( 'en' ) ); ?>" class="lang-switcher__btn <?php echo charity_get_lang() === 'en' ? 'active' : ''; ?>">EN</a>
            </div>
            <div class="topbar__social">
            <a href="#" aria-label="Facebook" class="topbar__social-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            </a>
            <a href="#" aria-label="YouTube" class="topbar__social-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
            </a>
            <a href="#" aria-label="Instagram" class="topbar__social-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
            </a>
        </div>
    </div>
</div>

<!-- ═══ HEADER ════════════════════════════════════════════════════════════ -->
<header id="masthead" class="site-header" role="banner">
    <div class="container header__inner">

        <div class="header__brand">
            <?php if ( has_custom_logo() ) : ?>
                <div class="header__logo"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <div class="header__logo-placeholder">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="30" cy="30" r="28" fill="#b71c1c" stroke="#fff" stroke-width="2"/>
                        <path d="M30 14l3.09 9.51H43.5l-8.27 6.01 3.16 9.73L30 33.24l-8.39 6.01 3.16-9.73L16.5 23.51H26.91L30 14z" fill="#ffd700"/>
                        <text x="30" y="48" text-anchor="middle" fill="white" font-size="8" font-family="Arial" font-weight="bold">TN</text>
                    </svg>
                </div>
            <?php endif; ?>

            <div class="header__title">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__site-name">
                    <?php bloginfo( 'name' ); ?>
                </a>
                <span class="header__tagline"><?php bloginfo( 'description' ); ?></span>
            </div>
        </div>

        <nav id="site-navigation" class="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'charity-hcm' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => 'charity_fallback_menu',
            ] );
            ?>
        </nav>

        <button class="nav-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="Toggle menu">
            <span class="nav-toggle__bar"></span>
            <span class="nav-toggle__bar"></span>
            <span class="nav-toggle__bar"></span>
        </button>
    </div>
</header>

<?php
function charity_fallback_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . charity_t( 'Trang chủ', 'Home' ) . '</a></li>';
    echo '<li><a href="#">' . charity_t( 'Giới thiệu', 'About' ) . '</a></li>';
    echo '<li><a href="' . esc_url( get_post_type_archive_link( 'event' ) ) . '">' . charity_t( 'Sự kiện', 'Events' ) . '</a></li>';
    echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . charity_t( 'Tin tức', 'News' ) . '</a></li>';
    echo '<li><a href="#">' . charity_t( 'Liên hệ', 'Contact' ) . '</a></li>';
    echo '</ul>';
}
?>
