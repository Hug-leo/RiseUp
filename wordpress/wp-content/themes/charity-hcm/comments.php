<?php
defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
    echo '<p class="comments-password-required">' . charity_t( 'Bài viết này được bảo vệ bằng mật khẩu.', 'This post is password protected. Enter the password to view comments.' ) . '</p>';
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>

    <h2 class="comments-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        <span class="comments-count"><?php echo number_format_i18n( get_comments_number() ); ?></span>
        <?php echo charity_t( 'Bình luận', 'Comments' ); ?>
    </h2>

    <ol class="comment-list">
        <?php
        wp_list_comments( [
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 40,
            'callback'    => 'charity_comment',
        ] );
        ?>
    </ol>

    <?php
    the_comments_pagination( [
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
    ] );
    ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p class="comments-closed"><?php echo charity_t( 'Bình luận đã bị đóng.', 'Comments are closed.' ); ?></p>
    <?php endif; ?>

    <?php if ( comments_open() ) : ?>

    <?php
    $commenter     = wp_get_current_commenter();
    $user          = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';

    $fields = [
        'author' => sprintf(
            '<div class="comment-form-field">
                <label for="author">%s <span class="required">*</span></label>
                <input id="author" name="author" type="text" value="%s" required placeholder="%s" autocomplete="name">
            </div>',
            charity_t( 'Tên', 'Name' ),
            esc_attr( $commenter['comment_author'] ),
            charity_t( 'Họ và tên', 'Your name' )
        ),
        'email'  => sprintf(
            '<div class="comment-form-field">
                <label for="email">%s <span class="required">*</span></label>
                <input id="email" name="email" type="email" value="%s" required placeholder="email@example.com" autocomplete="email">
            </div>',
            charity_t( 'Email', 'Email' ),
            esc_attr( $commenter['comment_author_email'] )
        ),
    ];

    comment_form( [
        'title_reply'          => '<span>' . charity_t( 'Để lại bình luận', 'Leave a Comment' ) . '</span>',
        'title_reply_to'       => charity_t( 'Trả lời %s', 'Reply to %s' ),
        'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h2>',
        'cancel_reply_before'  => ' <small>',
        'cancel_reply_after'   => '</small>',
        'cancel_reply_link'    => charity_t( 'Huỷ', 'Cancel' ),
        'label_submit'         => charity_t( 'Gửi bình luận', 'Post Comment' ),
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn btn--comment-submit">%4$s</button>',
        'class_submit'         => 'submit',
        'comment_field'        => sprintf(
            '<div class="comment-form-field comment-form-field--full">
                <label for="comment">%s <span class="required">*</span></label>
                <textarea id="comment" name="comment" rows="5" required placeholder="%s"></textarea>
            </div>',
            charity_t( 'Bình luận', 'Comment' ),
            charity_t( 'Chia sẻ suy nghĩ của bạn…', 'Share your thoughts…' )
        ),
        'fields'               => $fields,
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'class_form'           => 'comment-form',
        'logged_in_as'         => $user->exists()
            ? sprintf(
                '<p class="logged-in-as">' . charity_t( 'Đang đăng nhập với tư cách', 'Logged in as' ) . ' <strong>%s</strong>. <a href="%s">' . charity_t( 'Đăng xuất?', 'Log out?' ) . '</a></p>',
                esc_html( $user_identity ),
                esc_url( wp_logout_url( get_permalink() ) )
            )
            : '',
        'must_log_in'          => sprintf(
            '<p class="must-log-in">' . charity_t( 'Bạn phải', 'You must' ) . ' <a href="%s">' . charity_t( 'đăng nhập', 'log in' ) . '</a> ' . charity_t( 'để bình luận.', 'to post a comment.' ) . '</p>',
            esc_url( wp_login_url( get_permalink() ) )
        ),
    ] );
    ?>

    <?php endif; ?>

</div><!-- #comments -->

<?php
if ( ! function_exists( 'charity_comment' ) ) :
function charity_comment( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item', $comment ); ?>>
        <div class="comment-body">
            <div class="comment-author-avatar">
                <?php echo get_avatar( $comment, 40, '', get_comment_author( $comment ), [ 'class' => 'avatar' ] ); ?>
            </div>
            <div class="comment-content-wrap">
                <div class="comment-header">
                    <strong class="comment-author-name">
                        <?php comment_author_link( $comment ); ?>
                    </strong>
                    <time class="comment-date" datetime="<?php comment_date( 'c', $comment ); ?>">
                        <?php comment_date( '', $comment ); ?>
                    </time>
                    <div class="comment-actions">
                        <?php
                        comment_reply_link( array_merge( $args, [
                            'add_below' => 'comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'reply_text' => charity_t( 'Trả lời', 'Reply' ),
                        ] ) );
                        ?>
                    </div>
                </div>
                <div class="comment-text">
                    <?php if ( '0' === $comment->comment_approved ) : ?>
                    <p><em><?php echo charity_t( 'Bình luận đang chờ duyệt.', 'Your comment is awaiting moderation.' ); ?></em></p>
                    <?php endif; ?>
                    <?php comment_text(); ?>
                </div>
            </div>
        </div>
    <?php
}
endif;