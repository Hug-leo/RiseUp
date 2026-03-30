<?php
defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
    echo '<p class="comments-password-required">Bài viết này được bảo vệ bằng mật khẩu. Vui lòng nhập mật khẩu để xem bình luận.</p>';
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>

    <!-- ── Comment list ─────────────────────────────────────────────────── -->
    <h2 class="comments-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        <?php
        $count = get_comments_number();
        printf(
            esc_html( _n( '%s Bình luận', '%s Bình luận', $count, 'charity-hcm' ) ),
            '<span class="comments-count">' . number_format_i18n( $count ) . '</span>'
        );
        ?>
    </h2>

    <ol class="comment-list">
        <?php
        wp_list_comments( [
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 48,
            'callback'    => 'charity_comment',
        ] );
        ?>
    </ol>

    <?php
    the_comments_pagination( [
        'prev_text' => '&larr; Trang trước',
        'next_text' => 'Trang tiếp &rarr;',
    ] );
    ?>

    <?php endif; // have_comments() ?>


    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p class="comments-closed">Bình luận đã bị đóng cho bài viết này.</p>
    <?php endif; ?>


    <?php if ( comments_open() ) : ?>

    <!-- ── Comment form ─────────────────────────────────────────────────── -->
    <?php
    $commenter     = wp_get_current_commenter();
    $user          = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';

    $fields = [
        'author' => sprintf(
            '<div class="comment-form-field">
                <label for="author">%s <span class="required">*</span></label>
                <input id="author" name="author" type="text" value="%s" required placeholder="Họ và tên của bạn" autocomplete="name">
            </div>',
            esc_html__( 'Tên', 'charity-hcm' ),
            esc_attr( $commenter['comment_author'] )
        ),
        'email'  => sprintf(
            '<div class="comment-form-field">
                <label for="email">%s <span class="required">*</span></label>
                <input id="email" name="email" type="email" value="%s" required placeholder="email@example.com" autocomplete="email">
            </div>',
            esc_html__( 'Email', 'charity-hcm' ),
            esc_attr( $commenter['comment_author_email'] )
        ),
    ];

    comment_form( [
        'title_reply'          => '<span>' . __( 'Để lại bình luận', 'charity-hcm' ) . '</span>',
        'title_reply_to'       => __( 'Trả lời %s', 'charity-hcm' ),
        'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h2>',
        'cancel_reply_before'  => ' <small>',
        'cancel_reply_after'   => '</small>',
        'cancel_reply_link'    => __( 'Huỷ trả lời', 'charity-hcm' ),
        'label_submit'         => __( 'Gửi bình luận', 'charity-hcm' ),
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn btn--comment-submit">%4$s →</button>',
        'class_submit'         => 'submit',
        'comment_field'        => sprintf(
            '<div class="comment-form-field comment-form-field--full">
                <label for="comment">%s <span class="required">*</span></label>
                <textarea id="comment" name="comment" rows="6" required placeholder="%s"></textarea>
            </div>',
            esc_html__( 'Bình luận', 'charity-hcm' ),
            esc_attr__( 'Chia sẻ cảm nghĩ của bạn về bài viết này…', 'charity-hcm' )
        ),
        'fields'               => $fields,
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'class_form'           => 'comment-form',
        'logged_in_as'         => $user->exists()
            ? sprintf(
                '<p class="logged-in-as">Đang đăng nhập với tư cách <strong>%s</strong>. <a href="%s">Đăng xuất?</a></p>',
                esc_html( $user_identity ),
                esc_url( wp_logout_url( get_permalink() ) )
            )
            : '',
        'must_log_in'          => sprintf(
            '<p class="must-log-in">Bạn phải <a href="%s">đăng nhập</a> để bình luận.</p>',
            esc_url( wp_login_url( get_permalink() ) )
        ),
    ] );
    ?>

    <?php endif; // comments_open() ?>

</div><!-- #comments -->

<?php
// ── Custom comment callback ────────────────────────────────────────────────
function charity_comment( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item', $comment ); ?>>

        <div class="comment-body">
            <div class="comment-author-avatar">
                <?php echo get_avatar( $comment, 48, '', get_comment_author( $comment ), [ 'class' => 'avatar' ] ); ?>
            </div>

            <div class="comment-content-wrap">
                <div class="comment-header">
                    <strong class="comment-author-name">
                        <?php comment_author_link( $comment ); ?>
                    </strong>
                    <time class="comment-date" datetime="<?php comment_date( 'c', $comment ); ?>">
                        <?php comment_date( 'd/m/Y', $comment ); ?>
                        lúc
                        <?php comment_time( 'H:i', $comment ); ?>
                    </time>
                    <div class="comment-actions">
                        <?php
                        comment_reply_link( array_merge( $args, [
                            'add_below' => 'comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '',
                            'after'     => '',
                            'reply_text'=> 'Trả lời',
                        ] ) );
                        ?>
                        <?php if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) : ?>
                        <a href="<?php echo esc_url( get_edit_comment_link( $comment ) ); ?>" class="comment-edit-link">Sửa</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( '0' === $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation">Bình luận của bạn đang chờ kiểm duyệt.</p>
                <?php endif; ?>

                <div class="comment-text">
                    <?php comment_text( $comment ); ?>
                </div>
            </div>
        </div>

    <?php
    // Note: closing tag is handled by wp_list_comments
}
