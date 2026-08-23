<?php
/**
 * Rise Up account roles, authentication portals and member feedback.
 */

defined( 'ABSPATH' ) || exit;

const CHARITY_ACCESS_VERSION = '1.1.0';

function charity_portal_url( $portal = 'member' ) {
    $paths = [
        'admin'        => '/dang-nhap-quan-tri/',
        'collaborator' => '/dang-nhap-cong-tac-vien/',
        'member'       => '/dang-nhap-thanh-vien/',
        'account'      => '/tai-khoan/',
        'feedback'     => '/gui-y-kien/',
    ];

    return home_url( $paths[ $portal ] ?? $paths['member'] );
}

function charity_user_is_collaborator( $user = null ) {
    $user = $user instanceof WP_User ? $user : wp_get_current_user();
    return $user->exists() && in_array( 'riseup_collaborator', (array) $user->roles, true );
}

function charity_user_is_member( $user = null ) {
    $user = $user instanceof WP_User ? $user : wp_get_current_user();
    return $user->exists() && (bool) array_intersect( [ 'riseup_member', 'subscriber' ], (array) $user->roles );
}

function charity_portal_accepts_user( $portal, WP_User $user ) {
    if ( 'admin' === $portal ) {
        return user_can( $user, 'manage_options' );
    }

    if ( 'collaborator' === $portal ) {
        return charity_user_is_collaborator( $user );
    }

    return charity_user_is_member( $user );
}

function charity_portal_redirect_for_user( WP_User $user ) {
    if ( user_can( $user, 'manage_options' ) ) {
        return admin_url();
    }

    if ( charity_user_is_collaborator( $user ) ) {
        return admin_url( 'edit.php' );
    }

    return charity_portal_url( 'account' );
}

function charity_sync_access_roles() {
    if ( CHARITY_ACCESS_VERSION === get_option( 'charity_access_role_version' ) && get_role( 'riseup_collaborator' ) && get_role( 'riseup_member' ) ) {
        return;
    }

    $collaborator_caps = [
        'read'                          => true,
        'upload_files'                  => true,
        'edit_posts'                    => true,
        'edit_others_posts'             => true,
        'edit_published_posts'          => true,
        'edit_private_posts'            => true,
        'publish_posts'                 => true,
        'read_private_posts'            => true,
        'delete_posts'                  => true,
        'delete_others_posts'           => true,
        'delete_published_posts'        => true,
        'delete_private_posts'          => true,
        'moderate_comments'             => true,
        'edit_riseup_feedbacks'         => true,
        'edit_others_riseup_feedbacks'  => true,
        'edit_private_riseup_feedbacks' => true,
        'read_private_riseup_feedbacks' => true,
        'read_riseup_feedback'          => true,
    ];

    $collaborator = get_role( 'riseup_collaborator' );
    if ( ! $collaborator ) {
        $collaborator = add_role( 'riseup_collaborator', 'Cộng tác viên', $collaborator_caps );
    }
    if ( $collaborator ) {
        foreach ( $collaborator_caps as $capability => $grant ) {
            $collaborator->add_cap( $capability, $grant );
        }
    }

    $member = get_role( 'riseup_member' );
    if ( ! $member ) {
        $member = add_role( 'riseup_member', 'Thành viên', [ 'read' => true ] );
    }
    if ( $member ) {
        $member->add_cap( 'read', true );
    }

    $administrator = get_role( 'administrator' );
    if ( $administrator ) {
        foreach ( [
            'edit_riseup_feedback',
            'read_riseup_feedback',
            'delete_riseup_feedback',
            'edit_riseup_feedbacks',
            'edit_others_riseup_feedbacks',
            'publish_riseup_feedbacks',
            'read_private_riseup_feedbacks',
            'delete_riseup_feedbacks',
            'delete_private_riseup_feedbacks',
            'delete_published_riseup_feedbacks',
            'delete_others_riseup_feedbacks',
            'edit_private_riseup_feedbacks',
            'edit_published_riseup_feedbacks',
        ] as $capability ) {
            $administrator->add_cap( $capability, true );
        }
    }

    update_option( 'charity_access_role_version', CHARITY_ACCESS_VERSION, false );
}
add_action( 'init', 'charity_sync_access_roles', 5 );

function charity_register_feedback_type() {
    register_post_type( 'riseup_feedback', [
        'labels' => [
            'name'          => 'Ý kiến thành viên',
            'singular_name' => 'Ý kiến thành viên',
            'menu_name'     => 'Ý kiến thành viên',
            'edit_item'     => 'Xem ý kiến',
            'search_items'  => 'Tìm ý kiến',
            'not_found'     => 'Chưa có ý kiến nào.',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => false,
        'menu_icon'           => 'dashicons-email-alt',
        'supports'            => [ 'title', 'editor', 'author' ],
        'map_meta_cap'        => true,
        'capability_type'     => [ 'riseup_feedback', 'riseup_feedbacks' ],
        'capabilities'        => [
            'create_posts' => 'do_not_allow',
        ],
    ] );
}
add_action( 'init', 'charity_register_feedback_type', 8 );

function charity_register_account_routes() {
    $routes = [
        'dang-nhap-quan-tri'      => 'admin',
        'dang-nhap-cong-tac-vien' => 'collaborator',
        'dang-nhap-thanh-vien'    => 'member',
        'tai-khoan'               => 'account',
        'gui-y-kien'              => 'feedback',
    ];

    foreach ( $routes as $path => $portal ) {
        add_rewrite_rule( '^' . $path . '/?$', 'index.php?charity_portal=' . $portal, 'top' );
    }
}
add_action( 'init', 'charity_register_account_routes', 9 );

add_filter( 'query_vars', function ( $vars ) {
    $vars[] = 'charity_portal';
    return $vars;
} );

add_action( 'wp_loaded', function () {
    if ( CHARITY_ACCESS_VERSION === get_option( 'charity_access_rewrite_version' ) ) {
        return;
    }

    flush_rewrite_rules( false );
    update_option( 'charity_access_rewrite_version', CHARITY_ACCESS_VERSION, false );
} );

add_filter( 'template_include', function ( $template ) {
    $portal = sanitize_key( get_query_var( 'charity_portal' ) );
    if ( ! $portal ) {
        return $template;
    }

    status_header( 200 );
    nocache_headers();

    if ( 'feedback' === $portal ) {
        return CHARITY_HCM_DIR . '/page-member-feedback.php';
    }

    return CHARITY_HCM_DIR . '/page-auth-portal.php';
}, 99 );

add_filter( 'document_title_parts', function ( $parts ) {
    $portal = sanitize_key( get_query_var( 'charity_portal' ) );
    $titles = [
        'admin'        => 'Đăng nhập quản trị viên',
        'collaborator' => 'Đăng nhập cộng tác viên',
        'member'       => 'Đăng nhập thành viên',
        'account'      => 'Tài khoản',
        'feedback'     => 'Gửi ý kiến',
    ];
    if ( isset( $titles[ $portal ] ) ) {
        $parts['title'] = $titles[ $portal ];
    }
    return $parts;
} );

function charity_handle_feedback_submission() {
    if ( ! is_user_logged_in() || ! current_user_can( 'read' ) ) {
        wp_safe_redirect( charity_portal_url( 'member' ) );
        exit;
    }

    check_admin_referer( 'charity_submit_feedback', 'feedback_nonce' );

    if ( ! empty( $_POST['feedback_website'] ) ) {
        wp_safe_redirect( add_query_arg( 'feedback', 'success', charity_portal_url( 'feedback' ) ) );
        exit;
    }

    $subject = sanitize_text_field( wp_unslash( $_POST['feedback_subject'] ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['feedback_message'] ?? '' ) );

    if ( '' === $subject || '' === $message || mb_strlen( $subject ) > 160 || mb_strlen( $message ) > 5000 ) {
        wp_safe_redirect( add_query_arg( 'feedback', 'invalid', charity_portal_url( 'feedback' ) ) );
        exit;
    }

    $feedback_id = wp_insert_post( [
        'post_type'    => 'riseup_feedback',
        'post_status'  => 'private',
        'post_title'   => $subject,
        'post_content' => $message,
        'post_author'  => get_current_user_id(),
    ], true );

    $result = is_wp_error( $feedback_id ) ? 'error' : 'success';
    wp_safe_redirect( add_query_arg( 'feedback', $result, charity_portal_url( 'feedback' ) ) );
    exit;
}
add_action( 'admin_post_charity_submit_feedback', 'charity_handle_feedback_submission' );

// WordPress must reject anonymous comments even if a request bypasses the theme form.
add_filter( 'pre_option_comment_registration', '__return_true' );
add_filter( 'preprocess_comment', function ( $comment_data ) {
    $comment_type = $comment_data['comment_type'] ?? '';
    if ( ! is_user_logged_in() && in_array( $comment_type, [ '', 'comment' ], true ) ) {
        wp_die(
            esc_html__( 'Bạn phải đăng nhập để bình luận.', 'charity-hcm' ),
            esc_html__( 'Yêu cầu đăng nhập', 'charity-hcm' ),
            [ 'response' => 403 ]
        );
    }
    return $comment_data;
} );

/**
 * Publish standard comments from authenticated RiseUp roles immediately.
 * Explicit spam/trash results from security filters are intentionally preserved.
 */
add_filter( 'pre_comment_approved', function ( $approved, $comment_data ) {
    if ( is_wp_error( $approved ) || in_array( $approved, [ 'spam', 'trash' ], true ) ) {
        return $approved;
    }

    $comment_type = $comment_data['comment_type'] ?? '';
    if ( ! in_array( $comment_type, [ '', 'comment' ], true ) || ! is_user_logged_in() ) {
        return $approved;
    }

    $user = wp_get_current_user();
    if (
        user_can( $user, 'manage_options' )
        || charity_user_is_collaborator( $user )
        || charity_user_is_member( $user )
    ) {
        return 1;
    }

    return $approved;
}, 99, 2 );

/**
 * Collaborators may moderate non-admin comments, but admin comments are protected.
 * WordPress uses the edit_comment meta capability for trash, spam and permanent delete.
 */
add_filter( 'map_meta_cap', function ( $caps, $cap, $user_id, $args ) {
    if ( 'edit_comment' !== $cap || empty( $args[0] ) ) {
        return $caps;
    }

    $acting_user = get_userdata( $user_id );
    if ( ! $acting_user || ! charity_user_is_collaborator( $acting_user ) ) {
        return $caps;
    }

    $comment = get_comment( absint( $args[0] ) );
    if ( ! $comment || empty( $comment->user_id ) ) {
        return $caps;
    }

    $comment_author = get_userdata( (int) $comment->user_id );
    if ( $comment_author && user_can( $comment_author, 'manage_options' ) ) {
        return [ 'do_not_allow' ];
    }

    return $caps;
}, 20, 4 );

// Members use only the public account area; collaborators and admins use wp-admin.
add_action( 'admin_init', function () {
    if ( wp_doing_ajax() || ! is_user_logged_in() ) {
        return;
    }

    if ( charity_user_is_member() ) {
        wp_safe_redirect( charity_portal_url( 'account' ) );
        exit;
    }

    global $pagenow;
    if ( charity_user_is_collaborator() && 'index.php' === $pagenow ) {
        wp_safe_redirect( admin_url( 'edit.php' ) );
        exit;
    }
} );

add_filter( 'show_admin_bar', function ( $show ) {
    return charity_user_is_member() ? false : $show;
} );

add_filter( 'register_url', fn() => charity_portal_url( 'member' ) );

function charity_render_account_nav() {
    if ( ! is_user_logged_in() ) {
        echo '<a class="account-nav__link" href="' . esc_url( charity_portal_url( 'member' ) ) . '">' . esc_html( charity_t( 'Đăng nhập', 'Sign in' ) ) . '</a>';
        return;
    }

    $user = wp_get_current_user();
    $url  = charity_portal_redirect_for_user( $user );
    echo '<a class="account-nav__link" href="' . esc_url( $url ) . '">' . esc_html( $user->display_name ) . '</a>';
    echo '<a class="account-nav__link" href="' . esc_url( charity_portal_url( 'feedback' ) ) . '">' . esc_html( charity_t( 'Gửi ý kiến', 'Feedback' ) ) . '</a>';
    echo '<a class="account-nav__link" href="' . esc_url( wp_logout_url( home_url( '/' ) ) ) . '">' . esc_html( charity_t( 'Đăng xuất', 'Sign out' ) ) . '</a>';
}

add_filter( 'manage_riseup_feedback_posts_columns', function ( $columns ) {
    $columns['author'] = 'Người gửi';
    return $columns;
} );
