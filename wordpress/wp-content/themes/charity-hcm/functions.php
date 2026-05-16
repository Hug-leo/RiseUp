<?php
defined( 'ABSPATH' ) || exit;

define( 'CHARITY_HCM_VERSION', '2.2.0' );
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
        'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&family=Noto+Sans:wght@400;500;700&family=Playfair+Display:wght@700&display=swap',
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
        'ajaxurl'      => admin_url( 'admin-ajax.php' ),
        'nonce'        => wp_create_nonce( 'charity_load_more' ),
        'loadMoreText' => charity_t( 'Xem thêm bài viết', 'Load more stories' ),
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

    // Scholarship Programs
    register_post_type( 'program', [
        'labels'             => [
            'name'          => __( 'Chương trình học bổng', 'charity-hcm' ),
            'singular_name' => __( 'Chương trình', 'charity-hcm' ),
            'add_new_item'  => __( 'Thêm chương trình mới', 'charity-hcm' ),
        ],
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'            => [ 'slug' => 'chuong-trinh' ],
        'show_in_rest'       => true,
    ] );
} );

// ─── AJAX: Load More Posts ────────────────────────────────────────────────────
function charity_content_groups() {
    return [
        [
            'slug'     => 'tin-tuc',
            'title_vi' => 'TIN TỨC',
            'title_en' => 'NEWS',
            'summary_vi' => 'Cập nhật hoạt động, gương mặt tiêu biểu và hành trình tiếp nối của cộng đồng học bổng.',
            'summary_en' => 'Updates, featured members, and alumni giving-back stories from the scholarship community.',
            'items'    => [
                [
                    'slug' => 'chuyen-vuon-len',
                    'vi' => 'Chuyện Vươn Lên',
                    'en' => 'Rise Up Stories',
                    'desc_vi' => 'Cập nhật hoạt động, sự kiện, chương trình và thông báo mới nhất do Quỹ HBVL tổ chức.',
                    'desc_en' => 'Latest activities, events, programs, and announcements organized by the scholarship fund.',
                ],
                [
                    'slug' => 'guong-mat-vuon-len',
                    'vi' => 'Gương mặt Vươn Lên',
                    'en' => 'Rise Up Faces',
                    'desc_vi' => 'Giới thiệu cá nhân đang sinh hoạt tại Quỹ có thành tích nổi bật trong học tập hoặc nghề nghiệp.',
                    'desc_en' => 'Profiles of members with strong academic, career, or community achievements.',
                ],
                [
                    'slug' => 'tiep-noi',
                    'vi' => 'Tiếp nối',
                    'en' => 'Giving Back',
                    'desc_vi' => 'Câu chuyện anh chị từng nhận học bổng đã trưởng thành, quay lại đóng góp, hỗ trợ và chia sẻ kinh nghiệm.',
                    'desc_en' => 'Alumni stories about returning to support, mentor, and share practical experience.',
                ],
            ],
        ],
        [
            'slug'     => 'dong-du-ky',
            'title_vi' => 'ĐÔNG DU KÝ',
            'title_en' => 'DONG DU JOURNEYS',
            'summary_vi' => 'Không gian ghi lại hành trình, bản đồ kết nối và những địa điểm đáng trải nghiệm.',
            'summary_en' => 'A space for journeys, connection maps, and meaningful places to experience.',
            'items'    => [
                [
                    'slug' => 'ban-do-vuon-len',
                    'vi' => 'Bản đồ Vươn Lên',
                    'en' => 'Rise Up Map',
                    'desc_vi' => 'Bản đồ Việt Nam đánh dấu nơi thành viên và cựu thành viên HBVL đang hoạt động để kết nối, gặp gỡ hoặc lên kế hoạch chuyến đi chung.',
                    'desc_en' => 'A Vietnam map showing where members and alumni are active, helping people connect and plan visits or trips.',
                ],
                [
                    'slug' => 'nhat-ky-chuyen-di',
                    'vi' => 'Nhật ký chuyến đi',
                    'en' => 'Travel Journals',
                    'desc_vi' => 'Tổng hợp câu chuyện về những chuyến đi chơi, đi học, đi làm với kỷ niệm, bài học và ý nghĩa muốn chia sẻ.',
                    'desc_en' => 'Stories from trips for study, work, or discovery, with memories, lessons, and reflections.',
                ],
                [
                    'slug' => 'gioi-thieu-dia-diem',
                    'vi' => 'Giới thiệu địa điểm',
                    'en' => 'Place Spotlights',
                    'desc_vi' => 'Gợi ý địa điểm đã được thành viên khám phá, nêu cảnh đẹp, điểm thú vị, giá trị văn hóa và lý do nên trải nghiệm.',
                    'desc_en' => 'Recommended places explored by members, including scenery, cultural value, and reasons to visit.',
                ],
            ],
        ],
        [
            'slug'     => 'so-tay-kien-thuc',
            'title_vi' => 'SỔ TAY KIẾN THỨC',
            'title_en' => 'KNOWLEDGE HANDBOOK',
            'summary_vi' => 'Các mẹo học tập, kỹ năng sống và góc nhìn tích cực từ đời sống quanh ta.',
            'summary_en' => 'Study tips, life skills, and positive observations from everyday life.',
            'items'    => [
                [
                    'slug' => 'bi-kip',
                    'vi' => 'Bí kíp',
                    'en' => 'Tips',
                    'desc_vi' => 'Chia sẻ mẹo nhỏ trong cuộc sống, phương pháp học, cách ghi nhớ nhanh, truyền động lực và rèn luyện tư duy phản biện.',
                    'desc_en' => 'Practical tips for life, learning methods, memory techniques, motivation, and critical thinking.',
                ],
                [
                    'slug' => 'the-gioi-quanh-ta',
                    'vi' => 'Thế giới quanh ta',
                    'en' => 'Around Us',
                    'desc_vi' => 'Bài viết ngắn, câu chuyện đời sống, kiến thức khoa học, lối sống hữu ích và những điều đọng lại trong quan sát hằng ngày.',
                    'desc_en' => 'Short reflections, daily-life stories, science notes, useful lifestyles, and observations that stay with us.',
                ],
            ],
        ],
        [
            'slug'     => 'goc-sach-hay',
            'title_vi' => 'GÓC SÁCH HAY',
            'title_en' => 'BOOK CORNER',
            'summary_vi' => 'Tóm tắt và cảm nhận về những cuốn sách nuôi dưỡng nghị lực, tri thức và lối sống đẹp.',
            'summary_en' => 'Summaries and reflections on books that nurture resilience, knowledge, and meaningful living.',
            'items'    => [
                [
                    'slug' => 'tom-tat-sach',
                    'vi' => 'Tóm tắt sách',
                    'en' => 'Book Summaries',
                    'desc_vi' => 'Tổng hợp sách về nghị lực, vượt khó, vươn lên trong học tập và cuộc sống do thành viên trong Quỹ gợi ý.',
                    'desc_en' => 'Recommended books about resilience, overcoming hardship, and rising through education and life.',
                ],
                [
                    'slug' => 'viet-cam-nhan-sach',
                    'vi' => 'Viết cảm nhận sách',
                    'en' => 'Book Reflections',
                    'desc_vi' => 'Cảm nhận về những cuốn sách có ý nghĩa đặc biệt, kể lại kỷ niệm, động lực và giá trị mà sách mang lại.',
                    'desc_en' => 'Personal reflections on meaningful books, memories, motivation, and values they bring.',
                ],
            ],
        ],
        [
            'slug'     => 'sinh-hoat',
            'title_vi' => 'SINH HOẠT',
            'title_en' => 'COMMUNITY ACTIVITIES',
            'summary_vi' => 'Kho tư liệu phục vụ sinh hoạt tập thể, trò chơi cộng đồng và bài hát truyền thống.',
            'summary_en' => 'Resources for group activities, community games, and traditional songs.',
            'items'    => [
                [
                    'slug' => 'tro-choi-sinh-hoat-tap-the',
                    'vi' => 'Trò chơi/sinh hoạt tập thể',
                    'en' => 'Group Games',
                    'desc_vi' => 'Tổng hợp trò chơi vòng tròn, trò chơi dân gian và kỹ năng tổ chức sinh hoạt cho thành viên hoặc các em nhỏ.',
                    'desc_en' => 'Circle games, folk games, and facilitation skills for members and children activities.',
                ],
                [
                    'slug' => 'tong-hop-bai-hat',
                    'vi' => 'Tổng hợp bài hát',
                    'en' => 'Song Library',
                    'desc_vi' => 'Kho lưu trữ các bài hát truyền thống của Quỹ và bài hát sinh hoạt cộng đồng vui tươi, tích cực.',
                    'desc_en' => 'A library of the fund traditional songs and positive community activity songs.',
                ],
            ],
        ],
    ];
}

add_action( 'init', function () {
    foreach ( charity_content_groups() as $group ) {
        $parent = term_exists( $group['title_vi'], 'category' );

        if ( ! $parent ) {
            $parent = wp_insert_term( $group['title_vi'], 'category', [
                'slug'        => $group['slug'],
                'description' => $group['summary_vi'],
            ] );
        }

        if ( is_wp_error( $parent ) ) {
            continue;
        }

        $parent_id = is_array( $parent ) ? (int) $parent['term_id'] : (int) $parent;

        foreach ( $group['items'] as $item ) {
            if ( term_exists( $item['vi'], 'category' ) ) {
                continue;
            }

            wp_insert_term( $item['vi'], 'category', [
                'slug'        => $item['slug'],
                'parent'      => $parent_id,
                'description' => $item['desc_vi'],
            ] );
        }
    }
}, 20 );

function charity_find_content_group_by_slug( $slug ) {
    foreach ( charity_content_groups() as $group ) {
        if ( $group['slug'] === $slug ) {
            return $group;
        }

        foreach ( $group['items'] as $item ) {
            if ( $item['slug'] === $slug ) {
                return [
                    'parent' => $group,
                    'item'   => $item,
                ];
            }
        }
    }

    return null;
}

function charity_category_url_by_slug( $slug ) {
    $term = get_category_by_slug( $slug );
    return $term ? get_category_link( $term ) : home_url( '/category/' . $slug . '/' );
}

function charity_group_icon( $slug ) {
    $icons = [
        'tin-tuc' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>',
        'dong-du-ky' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>',
        'so-tay-kien-thuc' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
        'goc-sach-hay' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>',
        'sinh-hoat' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    ];
    return isset( $icons[ $slug ] ) ? $icons[ $slug ] : '';
}

function charity_vietnam_map_image_url() {
    return get_template_directory_uri() . '/assets/img/vietnam-map.jpg';
}

function charity_submit_post_url() {
    $submit_pages = get_pages( [
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'page-submit-post.php',
        'number'     => 1,
    ] );

    return $submit_pages ? get_permalink( $submit_pages[0] ) : home_url( '/gui-bai-viet/' );
}

function charity_render_primary_menu() {
    echo '<ul id="primary-menu" class="nav-menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( charity_t( 'Trang chủ', 'Home' ) ) . '</a></li>';

    foreach ( charity_content_groups() as $group ) {
        echo '<li class="menu-item-has-children">';
        echo '<a href="' . esc_url( charity_category_url_by_slug( $group['slug'] ) ) . '">' . esc_html( charity_t( $group['title_vi'], $group['title_en'] ) ) . '</a>';
        echo '<ul class="sub-menu">';
        foreach ( $group['items'] as $item ) {
            echo '<li><a href="' . esc_url( charity_category_url_by_slug( $item['slug'] ) ) . '">' . esc_html( charity_t( $item['vi'], $item['en'] ) ) . '</a></li>';
        }
        echo '</ul>';
        echo '</li>';
    }

    echo '<li><a href="' . esc_url( charity_submit_post_url() ) . '">' . esc_html( charity_t( 'Gửi bài', 'Submit' ) ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/lien-he/' ) ) . '">' . esc_html( charity_t( 'Liên hệ', 'Contact' ) ) . '</a></li>';
    echo '</ul>';
}

add_filter( 'wp_nav_menu_objects', function ( $items ) {
    $title_to_slug = [
        'tin tức' => 'tin-tuc',
        'tin tuc' => 'tin-tuc',
        'đồng du ký' => 'dong-du-ky',
        'dong du ky' => 'dong-du-ky',
        'sổ tay kiến thức' => 'so-tay-kien-thuc',
        'so tay kien thuc' => 'so-tay-kien-thuc',
        'góc sách hay' => 'goc-sach-hay',
        'goc sach hay' => 'goc-sach-hay',
        'sinh hoạt' => 'sinh-hoat',
        'sinh hoat' => 'sinh-hoat',
    ];

    foreach ( $items as $item ) {
        $title = strtolower( remove_accents( $item->title ) );
        $raw_title = strtolower( $item->title );
        $slug  = $title_to_slug[ $raw_title ] ?? $title_to_slug[ $title ] ?? null;

        if ( ! $slug && str_contains( $item->url, '/blog/' ) ) {
            $slug = 'tin-tuc';
        }

        if ( $slug ) {
            $term = get_category_by_slug( $slug );
            if ( $term ) {
                $item->url = get_category_link( $term );
            }
        }
    }

    return $items;
} );

add_action( 'template_redirect', function () {
    if ( ! is_404() ) {
        return;
    }

    $path = trim( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
    if ( ! str_ends_with( $path, 'blog' ) ) {
        return;
    }

    $term = get_category_by_slug( 'tin-tuc' );
    if ( $term ) {
        wp_safe_redirect( get_category_link( $term ), 301 );
        exit;
    }
} );

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
// ─── AJAX: Post Reactions (Like) ─────────────────────────────────────────
add_action( 'wp_ajax_toggle_post_like',        'charity_ajax_toggle_like' );
add_action( 'wp_ajax_nopriv_toggle_post_like', 'charity_ajax_toggle_like' );

function charity_ajax_toggle_like() {
    check_ajax_referer( 'charity_load_more', 'nonce' );

    $post_id = absint( $_POST['post_id'] ?? 0 );
    if ( ! $post_id || ! get_post( $post_id ) ) {
        wp_send_json_error( [ 'message' => 'Invalid post' ] );
    }

    $cookie_key = 'vuonlen_liked_' . $post_id;
    $likes      = (int) get_post_meta( $post_id, '_post_likes', true );
    $already    = isset( $_COOKIE[ $cookie_key ] );

    if ( $already ) {
        $likes = max( 0, $likes - 1 );
        update_post_meta( $post_id, '_post_likes', $likes );
        setcookie( $cookie_key, '', time() - 3600, '/' );
        wp_send_json_success( [ 'likes' => $likes, 'liked' => false ] );
    } else {
        $likes++;
        update_post_meta( $post_id, '_post_likes', $likes );
        setcookie( $cookie_key, '1', time() + YEAR_IN_SECONDS, '/' );
        wp_send_json_success( [ 'likes' => $likes, 'liked' => true ] );
    }
}
// ─── Flush rewrite rules on activation ───────────────────────────────────────
add_action( 'after_switch_theme', function () {
    flush_rewrite_rules();
} );

// ─── Bilingual System (VI/EN) ────────────────────────────────────────────────
function charity_get_lang() {
    if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], [ 'vi', 'en' ], true ) ) {
        setcookie( 'charity_lang', $_GET['lang'], time() + YEAR_IN_SECONDS, '/' );
        return $_GET['lang'];
    }
    return $_COOKIE['charity_lang'] ?? 'vi';
}

function charity_t( $vi, $en ) {
    return charity_get_lang() === 'en' ? $en : $vi;
}

function charity_lang_url( $lang ) {
    $url = remove_query_arg( 'lang' );
    return add_query_arg( 'lang', $lang, $url );
}

// ─── AJAX: Frontend Post Submission ──────────────────────────────────────────
add_action( 'wp_ajax_vuonlen_submit_post',        'vuonlen_handle_submit_post' );
add_action( 'wp_ajax_nopriv_vuonlen_submit_post', 'vuonlen_handle_submit_post' );

function vuonlen_handle_submit_post() {
    check_ajax_referer( 'vuonlen_submit_post', 'nonce' );

    $title   = sanitize_text_field( wp_unslash( $_POST['post_title'] ?? '' ) );
    $content = wp_kses_post( wp_unslash( $_POST['post_content'] ?? '' ) );
    $cat_id  = absint( $_POST['post_category'] ?? 0 );
    $author  = sanitize_text_field( wp_unslash( $_POST['author_name'] ?? '' ) );
    $email   = sanitize_email( wp_unslash( $_POST['author_email'] ?? '' ) );

    if ( empty( $title ) || empty( $content ) ) {
        wp_send_json_error( [ 'message' => charity_t(
            'Vui lòng nhập tiêu đề và nội dung.',
            'Please enter a title and content.'
        ) ] );
    }

    if ( mb_strlen( $title ) > 200 ) {
        wp_send_json_error( [ 'message' => charity_t(
            'Tiêu đề quá dài (tối đa 200 ký tự).',
            'Title is too long (max 200 characters).'
        ) ] );
    }

    $post_data = [
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'pending',
        'post_type'    => 'post',
    ];

    if ( $cat_id && term_exists( $cat_id, 'category' ) ) {
        $post_data['post_category'] = [ $cat_id ];
    }

    $post_id = wp_insert_post( $post_data, true );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( [ 'message' => charity_t(
            'Đã xảy ra lỗi. Vui lòng thử lại.',
            'An error occurred. Please try again.'
        ) ] );
    }

    // Store guest author info as post meta
    if ( $author ) {
        update_post_meta( $post_id, '_guest_author_name', $author );
    }
    if ( $email ) {
        update_post_meta( $post_id, '_guest_author_email', $email );
    }

    // Handle featured image upload
    if ( ! empty( $_FILES['post_image'] ) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attach_id = media_handle_upload( 'post_image', $post_id );
        if ( ! is_wp_error( $attach_id ) ) {
            set_post_thumbnail( $post_id, $attach_id );
        }
    }

    wp_send_json_success( [ 'message' => charity_t(
        'Bài viết đã được gửi thành công! Quản trị viên sẽ duyệt bài của bạn sớm.',
        'Your post has been submitted successfully! An admin will review it shortly.'
    ) ] );
}
