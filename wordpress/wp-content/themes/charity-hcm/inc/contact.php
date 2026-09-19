<?php
/** Private contact inbox; email is a notification, not the only copy. */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
    register_post_type( 'riseup_contact', [
        'labels' => [
            'name' => 'Liên hệ', 'singular_name' => 'Tin nhắn liên hệ',
            'edit_item' => 'Xem / xử lý liên hệ', 'not_found' => 'Chưa có liên hệ.',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-email',
        'supports' => [ 'title', 'editor' ],
        'capabilities' => array_fill_keys( [
            'edit_post', 'read_post', 'delete_post', 'edit_posts', 'edit_others_posts',
            'publish_posts', 'read_private_posts', 'delete_posts', 'delete_private_posts',
            'delete_published_posts', 'delete_others_posts', 'edit_private_posts', 'edit_published_posts',
        ], 'manage_options' ) + [ 'create_posts' => 'do_not_allow' ],
        'map_meta_cap' => false,
    ] );
} );

function charity_receive_contact( array $input ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'login', charity_t( 'Bạn phải đăng nhập để gửi tin nhắn.', 'Please sign in to send a message.' ) );
    }
    foreach ( [ 'contact_nonce', 'contact_name', 'contact_email', 'contact_subject', 'contact_message' ] as $key ) {
        if ( isset( $input[ $key ] ) && ! is_string( $input[ $key ] ) ) {
            return new WP_Error( 'invalid', charity_t( 'Dữ liệu không hợp lệ.', 'Invalid form data.' ) );
        }
    }
    if ( ! wp_verify_nonce( $input['contact_nonce'] ?? '', 'vuonlen_contact_form' ) ) {
        return new WP_Error( 'nonce', charity_t( 'Phiên gửi đã hết hạn. Tải lại trang rồi thử lại.', 'Session expired. Reload the page and try again.' ) );
    }
    $name = sanitize_text_field( $input['contact_name'] ?? '' );
    $email = trim( $input['contact_email'] ?? '' );
    $subject = sanitize_text_field( $input['contact_subject'] ?? '' );
    $message = sanitize_textarea_field( $input['contact_message'] ?? '' );
    if ( '' === $name || '' === $message || ! is_email( $email ) ) {
        return new WP_Error( 'fields', charity_t( 'Vui lòng nhập họ tên, email hợp lệ và nội dung.', 'Please enter your name, a valid email and a message.' ) );
    }
    if ( mb_strlen( $name ) > 120 || strlen( $email ) > 254 || mb_strlen( $subject ) > 200 || mb_strlen( $message ) > 5000 ) {
        return new WP_Error( 'length', charity_t( 'Thông tin quá dài. Nội dung tối đa 5.000 ký tự.', 'Input too long. Message limit: 5,000 characters.' ) );
    }
    $rate_key = 'charity_contact_' . get_current_user_id();
    if ( get_transient( $rate_key ) ) {
        return new WP_Error( 'rate', charity_t( 'Vui lòng đợi một phút trước khi gửi tiếp.', 'Please wait one minute before sending again.' ) );
    }
    $subject = $subject ?: charity_t( 'Liên hệ từ website', 'Contact from website' );
    $body = "Họ tên: {$name}\nEmail: {$email}\n\n{$message}";
    $id = wp_insert_post( wp_slash( [
        'post_type' => 'riseup_contact', 'post_status' => 'private',
        'post_title' => $subject, 'post_content' => $body,
        'post_author' => get_current_user_id(),
        'meta_input' => [ '_contact_email' => $email, '_contact_status' => 'new' ],
    ] ), true );
    if ( is_wp_error( $id ) || ! $id ) {
        return new WP_Error( 'storage', charity_t( 'Chưa lưu được tin nhắn. Vui lòng thử lại.', 'Could not save your message. Please try again.' ) );
    }
    set_transient( $rate_key, $id, MINUTE_IN_SECONDS );
    $sent = wp_mail( 'quykhuyenhocdongdu@gmail.com', '[Liên hệ #' . $id . '] ' . $subject,
        $body, [ 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email ] );
    update_post_meta( $id, '_contact_mail', $sent ? 'accepted' : 'failed' );
    return $id;
}

add_filter( 'manage_riseup_contact_posts_columns', function ( $columns ) {
    $columns['contact_status'] = 'Xử lý / Email thông báo';
    return $columns;
} );
add_action( 'manage_riseup_contact_posts_custom_column', function ( $column, $id ) {
    if ( 'contact_status' === $column ) {
        echo 'done' === get_post_meta( $id, '_contact_status', true ) ? 'Đã xử lý' : 'Mới';
        echo '<br>' . ( 'accepted' === get_post_meta( $id, '_contact_mail', true ) ? 'Đã chuyển cho hệ thống mail' : 'Email chưa gửi được; tin nhắn đã lưu' );
    }
}, 10, 2 );
add_action( 'add_meta_boxes_riseup_contact', function () {
    add_meta_box( 'contact-details', 'Xử lý liên hệ', function ( $post ) {
        wp_nonce_field( 'contact_status', 'contact_status_nonce' );
        $email = get_post_meta( $post->ID, '_contact_email', true );
        echo '<p><a href="' . esc_url( 'mailto:' . $email ) . '">Trả lời qua email: ' . esc_html( $email ) . '</a></p>';
        echo '<label><input type="checkbox" name="contact_done" value="1" ' . checked( get_post_meta( $post->ID, '_contact_status', true ), 'done', false ) . '> Đã xử lý</label>';
        echo '<p>Trả lời bằng ứng dụng email, sau đó đánh dấu và bấm Cập nhật.</p>';
    }, 'riseup_contact', 'side' );
} );
add_action( 'save_post_riseup_contact', function ( $id ) {
    if ( ! current_user_can( 'manage_options' ) || wp_is_post_revision( $id ) || wp_is_post_autosave( $id )
        || ! isset( $_POST['contact_status_nonce'] ) || ! is_string( $_POST['contact_status_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['contact_status_nonce'] ) ), 'contact_status' ) ) {
        return;
    }
    update_post_meta( $id, '_contact_status', isset( $_POST['contact_done'] ) ? 'done' : 'new' );
} );
