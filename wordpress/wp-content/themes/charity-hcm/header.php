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
        </div>
    </div>
</div>

<header id="masthead" class="site-header" role="banner">
    <div class="container header__inner">
        <div class="header__brand">
            <?php if ( has_custom_logo() ) : ?>
                <div class="header__logo"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <div class="header__logo-placeholder">
                    <img src="<?php echo esc_url( CHARITY_HCM_URI . '/assets/img/dong-du-logo.jpg' ); ?>" alt="<?php echo esc_attr( charity_t( 'Vươn Lên', 'Rise Up' ) ); ?>">
                </div>
            <?php endif; ?>

            <div class="header__title">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__site-name">
                    <?php echo charity_t( 'Vươn Lên', 'Rise Up' ); ?>
                </a>
                <span class="header__tagline"><?php echo charity_t( 'Quỹ Khuyến Học Đông Du', 'Dong Du Study Encouragement Fund' ); ?></span>
            </div>
        </div>

        <nav id="site-navigation" class="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'charity-hcm' ); ?>">
            <div class="main-nav__mobile-head">
                <span class="main-nav__mobile-label"><?php echo charity_t( 'Ngôn ngữ', 'Language' ); ?></span>
                <div class="lang-switcher lang-switcher--mobile" aria-label="<?php echo esc_attr( charity_t( 'Chọn ngôn ngữ', 'Choose language' ) ); ?>">
                    <a href="<?php echo esc_url( charity_lang_url( 'vi' ) ); ?>" class="lang-switcher__btn <?php echo charity_get_lang() === 'vi' ? 'active' : ''; ?>">VI</a>
                    <a href="<?php echo esc_url( charity_lang_url( 'en' ) ); ?>" class="lang-switcher__btn <?php echo charity_get_lang() === 'en' ? 'active' : ''; ?>">EN</a>
                </div>
            </div>
            <?php charity_render_primary_menu(); ?>
        </nav>

        <button class="nav-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="<?php echo esc_attr( charity_t( 'Mở trình đơn', 'Open menu' ) ); ?>">
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
    echo '<li><a href="#content-roadmap">' . charity_t( 'Chuyên mục', 'Sections' ) . '</a></li>';
    echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . charity_t( 'Bài viết', 'Stories' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/lien-he/' ) ) . '">' . charity_t( 'Liên hệ', 'Contact' ) . '</a></li>';
    echo '</ul>';
}
?>
