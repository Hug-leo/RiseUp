<?php
defined( 'ABSPATH' ) || exit;

define( 'CHARITY_HCM_VERSION', '1.0.0' );
define( 'CHARITY_HCM_DIR', get_template_directory() );
define( 'CHARITY_HCM_URI', get_template_directory_uri() );

// ─── Theme Setup ──────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'        => 80,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'custom-header', [
        'default-image' => CHARITY_HCM_URI . '/assets/img/hero-bg.jpg',
    ] );

    add_image_size( 'card-thumb',   600, 400, true );
    add_image_size( 'card-wide',    800, 450, true );
    add_image_size( 'event-thumb',  400, 300, true );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'charity-hcm' ),
        'footer'  => __( 'Footer Menu',  'charity-hcm' ),
    ] );
} );

// ─── Enqueue ──────────────────────────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'charity-hcm-main',
        CHARITY_HCM_URI . '/assets/css/main.css',
        [],
        CHARITY_HCM_VERSION
    );
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap',
        [],
        null
    );
    wp_enqueue_script(
        'charity-hcm-main',
        CHARITY_HCM_URI . '/assets/js/main.js',
        [],
        CHARITY_HCM_VERSION,
        true
    );
    wp_localize_script( 'charity-hcm-main', 'charityHCM', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'charity_load_more' ),
    ] );
} );

// ─── Widget Areas ─────────────────────────────────────────────────────────────
add_action( 'widgets_init', function () {
    register_sidebar( [
        'name'          => __( 'Sidebar', 'charity-hcm' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
    register_sidebar( [
        'name'          => __( 'Footer Col 1', 'charity-hcm' ),
        'id'            => 'footer-1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ] );
} );

// ─── Custom Post Types ────────────────────────────────────────────────────────
add_action( 'init', function () {
    // Events
    register_post_type( 'event', [
        'labels'             => [
            'name'          => __( 'Sự kiện', 'charity-hcm' ),
            'singular_name' => __( 'Sự kiện', 'charity-hcm' ),
            'add_new_item'  => __( 'Thêm sự kiện mới', 'charity-hcm' ),
        ],
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'            => [ 'slug' => 'su-kien' ],
        'show_in_rest'       => true,
    ] );

    // Programs / Clubs
    register_post_type( 'program', [
        'labels'             => [
            'name'          => __( 'Chương trình', 'charity-hcm' ),
            'singular_name' => __( 'Chương trình', 'charity-hcm' ),
            'add_new_item'  => __( 'Thêm chương trình mới', 'charity-hcm' ),
        ],
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-heart',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'            => [ 'slug' => 'chuong-trinh' ],
        'show_in_rest'       => true,
    ] );
} );

// ─── AJAX: Load More Posts ────────────────────────────────────────────────────
add_action( 'wp_ajax_load_more_posts',        'charity_ajax_load_more' );
add_action( 'wp_ajax_nopriv_load_more_posts', 'charity_ajax_load_more' );

function charity_ajax_load_more() {
    check_ajax_referer( 'charity_load_more', 'nonce' );

    $paged    = absint( $_POST['page'] ?? 2 );
    $cat_id   = absint( $_POST['cat']  ?? 0 );
    $per_page = 3;

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ];
    if ( $cat_id ) {
        $args['cat'] = $cat_id;
    }

    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) {
        wp_send_json_error( [ 'message' => 'no_more' ] );
    }

    ob_start();
    while ( $query->have_posts() ) {
        $query->the_post();
        get_template_part( 'template-parts/content', 'card' );
    }
    wp_reset_postdata();
    $html = ob_get_clean();

    wp_send_json_success( [
        'html'    => $html,
        'hasMore' => ( $paged < $query->max_num_pages ),
    ] );
}

// ─── Excerpt length ───────────────────────────────────────────────────────────
add_filter( 'excerpt_length', fn() => 25, 999 );
add_filter( 'excerpt_more',   fn() => '…', 999 );

// ─── Flush rewrite rules on activation ───────────────────────────────────────
add_action( 'after_switch_theme', function () {
    flush_rewrite_rules();
} );
