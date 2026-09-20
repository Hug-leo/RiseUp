<?php
defined( 'ABSPATH' ) || exit;

// Submitted articles use the native classic editor alongside the review controls.
add_filter( 'use_block_editor_for_post', function ( $use, $post ) {
    return get_post_meta( $post->ID, '_submission', true ) ? false : $use;
}, 10, 2 );

function charity_submission_label( $post ) {
    $review = get_post_meta( $post->ID, '_submission_review', true );
    if ( 'draft' === $post->post_status && in_array( $review, [ 'changes', 'rejected' ], true ) ) {
        return 'changes' === $review ? 'Cần sửa' : 'Từ chối';
    }
    return [ 'draft' => 'Nháp', 'pending' => 'Chờ duyệt', 'publish' => 'Đã đăng', 'future' => 'Đã lên lịch', 'private' => 'Riêng tư' ][ $post->post_status ] ?? $post->post_status;
}

function charity_submission_editable( $post ) {
    return $post && 'post' === $post->post_type && (int) $post->post_author === get_current_user_id()
        && 'draft' === $post->post_status && 'rejected' !== get_post_meta( $post->ID, '_submission_review', true );
}

function charity_save_submission( array $input ) {
    if ( ! is_user_logged_in() || ! current_user_can( 'read' ) ) {
        return new WP_Error( 'permission', 'Vui lòng đăng nhập để gửi bài.' );
    }
    foreach ( [ 'submission_nonce', 'request_id', 'article_id', 'article_title', 'article_content', 'article_category', 'intent' ] as $key ) {
        if ( ! isset( $input[ $key ] ) || ! is_string( $input[ $key ] ) ) {
            return new WP_Error( 'invalid', 'Dữ liệu không hợp lệ.' );
        }
    }
    if ( ! wp_verify_nonce( $input['submission_nonce'], 'save_submission' ) || ! preg_match( '/^[a-f0-9-]{36}$/', $input['request_id'] ) ) {
        return new WP_Error( 'nonce', 'Phiên gửi đã hết hạn. Tải lại trang rồi thử lại.' );
    }
    $key = 'submission_' . get_current_user_id() . '_' . $input['request_id'];
    if ( $saved = get_transient( $key ) ) { return (int) $saved; }
    $id = absint( $input['article_id'] );
    if ( $id && ! charity_submission_editable( get_post( $id ) ) ) {
        return new WP_Error( 'locked', 'Bạn không thể sửa bài này. Bài chờ duyệt hoặc đã đăng được khóa.' );
    }
    $title = sanitize_text_field( $input['article_title'] );
    $content = trim( wp_kses_post( $input['article_content'] ) );
    $cat = absint( $input['article_category'] );
    if ( ! in_array( $input['intent'], [ 'draft', 'submit' ], true ) || '' === $title || mb_strlen( $title ) > 200 || mb_strlen( $content ) > 50000
        || ( 'submit' === $input['intent'] && '' === trim( wp_strip_all_tags( $content ) ) ) || ! term_exists( $cat, 'category' ) ) {
        return new WP_Error( 'fields', 'Nhập tiêu đề (tối đa 200 ký tự), chuyên mục và nội dung (tối đa 50.000 ký tự).' );
    }
    // Reserve the form token atomically so double clicks cannot create two posts.
    $lock = $id ? '_submission_lock_' . $id : '_lock_' . $key;
    if ( ! add_option( $lock, time(), '', false ) ) {
        return new WP_Error( 'busy', 'Yêu cầu đang được xử lý. Tải lại trang để kiểm tra Bài của tôi.' );
    }
    try {
        if ( $saved = get_transient( $key ) ) { return (int) $saved; }
        if ( $id && ! charity_submission_editable( get_post( $id ) ) ) { return new WP_Error( 'locked', 'Bài đã được khóa.' ); }
        $attachment = 0;
        if ( isset( $_FILES['article_image'] ) && UPLOAD_ERR_NO_FILE !== ( $_FILES['article_image']['error'] ?? null ) ) {
            $file = $_FILES['article_image'];
            if ( UPLOAD_ERR_OK !== ( $file['error'] ?? null ) || ! is_string( $file['tmp_name'] ?? null ) || ! is_string( $file['name'] ?? null )
                || ! is_uploaded_file( $file['tmp_name'] ) || filesize( $file['tmp_name'] ) > 5 * MB_IN_BYTES
                || ! in_array( wp_get_image_mime( $file['tmp_name'] ), [ 'image/jpeg', 'image/png', 'image/webp' ], true ) ) {
                return new WP_Error( 'image', 'Ảnh không hợp lệ. Chọn JPEG, PNG hoặc WebP tối đa 5 MB. Nội dung chưa được lưu.' );
            }
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            $attachment = media_handle_upload( 'article_image', 0, [], [ 'test_form' => false, 'mimes' => [ 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp' ] ] );
            if ( is_wp_error( $attachment ) ) { return new WP_Error( 'image', 'Không tải được ảnh. Nội dung chưa được lưu; hãy thử lại.' ); }
        }
        $result = wp_insert_post( wp_slash( [
            'ID' => $id, 'post_type' => 'post', 'post_author' => get_current_user_id(),
            'post_title' => $title, 'post_content' => $content, 'post_category' => [ $cat ],
            'post_status' => 'submit' === $input['intent'] ? 'pending' : 'draft',
        ] ), true );
        if ( is_wp_error( $result ) || ! $result ) {
            if ( $attachment ) { wp_delete_attachment( $attachment, true ); }
            return new WP_Error( 'save', 'Chưa lưu được bài. Vui lòng thử lại.' );
        }
        if ( $attachment ) {
            wp_update_post( [ 'ID' => $attachment, 'post_parent' => $result ] );
            set_post_thumbnail( $result, $attachment );
        }
        update_post_meta( $result, '_submission', 1 );
        if ( 'submit' === $input['intent'] ) { delete_post_meta( $result, '_submission_review' ); }
        set_transient( $key, $result, DAY_IN_SECONDS );
        return $result;
    } finally {
        delete_option( $lock );
    }
}

function charity_review_submission( $id, $decision, $reason ) {
    $post = get_post( $id );
    if ( ! $post || 'post' !== $post->post_type || 'pending' !== $post->post_status || ! current_user_can( 'edit_post', $id ) ) {
        return new WP_Error( 'permission', 'Chỉ xử lý bài đang chờ duyệt và bạn có quyền biên tập.' );
    }
    if ( ! in_array( $decision, [ 'changes', 'rejected' ], true ) || ( 'rejected' === $decision && ! current_user_can( 'manage_options' ) ) ) {
        return new WP_Error( 'permission', 'Bạn không có quyền thực hiện thao tác này.' );
    }
    $reason = sanitize_textarea_field( $reason );
    if ( '' === $reason || mb_strlen( $reason ) > 2000 ) { return new WP_Error( 'reason', 'Nhập lý do từ 1 đến 2.000 ký tự.' ); }
    $result = wp_update_post( [ 'ID' => $id, 'post_status' => 'draft' ], true );
    if ( is_wp_error( $result ) ) { return $result; }
    update_post_meta( $id, '_submission_review', $decision );
    update_post_meta( $id, '_submission_reason', wp_slash( $reason ) );
    $edit_lock = get_post_meta( $id, '_edit_lock', true );
    $lock_parts = explode( ':', $edit_lock );
    if ( isset( $lock_parts[1] ) && (int) $lock_parts[1] === get_current_user_id() ) {
        delete_post_meta( $id, '_edit_lock', $edit_lock );
    }
    return $id;
}

add_action( 'add_meta_boxes_post', function ( $post ) {
    if ( 'pending' !== $post->post_status ) { return; }
    add_meta_box( 'submission-review', 'Phản hồi người gửi', function ( $post ) {
        // Submit a separate form outside the editor to avoid nested forms.
        $url = add_query_arg( '_wpnonce', wp_create_nonce( 'review_article_' . $post->ID ), admin_url( 'admin-post.php?action=charity_review_article&article_id=' . $post->ID ) );
        echo '<p>Lưu nội dung biên tập trước khi gửi phản hồi.</p><textarea id="submission-reason" maxlength="2000" rows="4" style="width:100%" placeholder="Lý do yêu cầu sửa / từ chối"></textarea>';
        echo '<p><button type="button" class="button" data-review="changes">Yêu cầu sửa</button> ';
        if ( current_user_can( 'manage_options' ) ) { echo '<button type="button" class="button" data-review="rejected">Từ chối</button>'; }
        echo '</p><script>document.querySelectorAll("[data-review]").forEach(function(b){b.onclick=function(){var r=document.getElementById("submission-reason").value.trim();if(!r){alert("Vui lòng nhập lý do.");return;}var f=document.createElement("form");f.method="post";f.action=' . wp_json_encode( $url ) . ';Object.entries({decision:b.dataset.review,reason:r}).forEach(function(v){var i=document.createElement("input");i.type="hidden";i.name=v[0];i.value=v[1];f.appendChild(i);});document.body.appendChild(f);f.submit();};});</script>';
    }, 'post', 'side' );
} );
add_action( 'admin_post_charity_review_article', function () {
    $id = isset( $_GET['article_id'] ) && is_string( $_GET['article_id'] ) ? absint( $_GET['article_id'] ) : 0;
    check_admin_referer( 'review_article_' . $id );
    if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! is_string( $_POST['decision'] ?? null ) || ! is_string( $_POST['reason'] ?? null ) ) { wp_die( 'Invalid request', '', [ 'response' => 400 ] ); }
    $result = charity_review_submission( $id, wp_unslash( $_POST['decision'] ), wp_unslash( $_POST['reason'] ) );
    if ( is_wp_error( $result ) ) { wp_die( esc_html( $result->get_error_message() ), '', [ 'response' => 403 ] ); }
    wp_safe_redirect( admin_url( 'edit.php?post_status=draft&post_type=post' ) );
    exit;
} );
