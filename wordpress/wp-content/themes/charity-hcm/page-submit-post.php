<?php
/** Template Name: Submit Post */
defined( 'ABSPATH' ) || exit;
if ( ! is_user_logged_in() ) {
    wp_safe_redirect( add_query_arg( 'redirect_to', charity_submit_post_url(), charity_portal_url( 'member' ) ) );
    exit;
}
nocache_headers();
$error = '';
$id = isset( $_GET['article'] ) && is_string( $_GET['article'] ) ? absint( $_GET['article'] ) : 0;
$article = $id ? get_post( $id ) : null;
if ( $id && ! charity_submission_editable( $article ) ) { wp_die( 'Bài này không thuộc quyền sửa của bạn hoặc đã được khóa.', '', [ 'response' => 403 ] ); }
$values = [ 'article_id' => (string) $id, 'article_title' => $article ? $article->post_title : '',
    'article_content' => $article ? $article->post_content : '',
    'article_category' => (string) ( $article ? ( wp_get_post_categories( $id )[0] ?? get_option( 'default_category' ) ) : get_option( 'default_category' ) ) ];
if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
    $input = wp_unslash( $_POST );
    foreach ( $values as $key => $value ) { if ( isset( $input[ $key ] ) && is_string( $input[ $key ] ) ) { $values[ $key ] = $input[ $key ]; } }
    $result = charity_save_submission( $input );
    if ( is_wp_error( $result ) ) { $error = $result->get_error_message(); }
    else { wp_safe_redirect( add_query_arg( 'saved', $result, home_url( '/bai-cua-toi/' ) ), 303 ); exit; }
}
$page = isset( $_GET['list_page'] ) && is_string( $_GET['list_page'] ) ? max( 1, absint( $_GET['list_page'] ) ) : 1;
$mine = new WP_Query( [ 'post_type' => 'post', 'author' => get_current_user_id(), 'post_status' => [ 'draft', 'pending', 'publish', 'future', 'private' ], 'posts_per_page' => 10, 'paged' => $page ] );
get_header();
?>
<main class="feedback-page" id="main-content"><div class="feedback-page__shell">
<section class="feedback-page__intro"><h1>Gửi bài / Bài của tôi</h1><p>Lưu nháp để viết tiếp, hoặc gửi ban biên tập duyệt. Quản trị viên quyết định xuất bản.</p></section>
<section class="feedback-card">
<?php if ( $error ) : ?><p class="auth-message auth-message--error" role="alert"><?php echo esc_html( $error ); ?></p><?php endif; ?>
<?php $saved = isset( $_GET['saved'] ) && is_string( $_GET['saved'] ) ? get_post( absint( $_GET['saved'] ) ) : null;
if ( $saved && 'post' === $saved->post_type && (int) $saved->post_author === get_current_user_id() ) : ?>
<p class="auth-message auth-message--success" role="status">Đã lưu bài #<?php echo (int) $saved->ID; ?> — <?php echo esc_html( charity_submission_label( $saved ) ); ?>.</p>
<?php endif; ?>
<h2><?php echo $id ? 'Sửa bài #' . (int) $id : 'Viết bài mới'; ?></h2>
<form method="post" action="<?php echo esc_url( charity_submit_post_url() ); ?>" class="feedback-form" enctype="multipart/form-data">
<?php wp_nonce_field( 'save_submission', 'submission_nonce' ); ?>
<input type="hidden" name="request_id" value="<?php echo esc_attr( wp_generate_uuid4() ); ?>">
<input type="hidden" name="article_id" value="<?php echo esc_attr( $values['article_id'] ); ?>">
<label for="article-title">Tiêu đề</label><input id="article-title" name="article_title" required maxlength="200" value="<?php echo esc_attr( $values['article_title'] ); ?>">
<label for="article-category">Chuyên mục</label>
<?php wp_dropdown_categories( [ 'name' => 'article_category', 'id' => 'article-category', 'hide_empty' => false, 'hierarchical' => true, 'selected' => absint( $values['article_category'] ) ] ); ?>
<label for="article-content">Nội dung (tối đa 50.000 ký tự)</label><textarea id="article-content" name="article_content" rows="14" maxlength="50000"><?php echo esc_textarea( $values['article_content'] ); ?></textarea>
<label for="article-image">Ảnh đại diện (JPEG, PNG, WebP; tối đa 5 MB)</label><input id="article-image" type="file" name="article_image" accept="image/jpeg,image/png,image/webp">
<p>Không chọn ảnh mới sẽ giữ ảnh đã lưu. Có thể dùng HTML cơ bản để chia đoạn, in đậm và thêm liên kết. Ảnh tải lên có đường dẫn công khai; không tải tài liệu riêng tư.</p>
<button class="btn btn--outline" name="intent" value="draft">Lưu nháp</button>
<button class="btn btn--primary" name="intent" value="submit">Gửi duyệt</button>
</form></section>
<section class="feedback-card" style="margin-top:24px"><h2>Bài của tôi</h2>
<?php if ( ! $mine->posts ) : ?><p>Bạn chưa có bài viết.</p><?php endif; ?>
<?php foreach ( $mine->posts as $item ) : ?>
<article style="padding:16px 0;border-bottom:1px solid #ddd"><h3><?php echo esc_html( $item->post_title ); ?></h3>
<p>#<?php echo (int) $item->ID; ?> · <?php echo esc_html( charity_submission_label( $item ) ); ?></p>
<details><summary>Xem nội dung đã lưu</summary><div style="overflow-wrap:anywhere"><?php echo wp_kses_post( wpautop( $item->post_content ) ); ?></div></details>
<?php $reason = get_post_meta( $item->ID, '_submission_reason', true ); if ( $reason ) : ?><p>Phản hồi gần nhất: <?php echo nl2br( esc_html( $reason ) ); ?></p><?php endif; ?>
<?php if ( charity_submission_editable( $item ) ) : ?><a href="<?php echo esc_url( add_query_arg( 'article', $item->ID, charity_submit_post_url() ) ); ?>">Sửa / viết tiếp</a><?php endif; ?>
<?php if ( 'publish' === $item->post_status ) : ?><a href="<?php echo esc_url( get_permalink( $item ) ); ?>">Xem bài đã đăng</a><?php endif; ?>
</article><?php endforeach; ?>
<?php echo paginate_links( [ 'base' => add_query_arg( 'list_page', '%#%', home_url( '/bai-cua-toi/' ) ), 'current' => $page, 'total' => $mine->max_num_pages ] ); ?>
</section></div></main>
<?php get_footer(); ?>
