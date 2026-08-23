<?php
defined( 'ABSPATH' ) || exit;

$portal = sanitize_key( get_query_var( 'charity_portal' ) );
$portal = in_array( $portal, [ 'admin', 'collaborator', 'member', 'account' ], true ) ? $portal : 'member';
$error  = '';
$notice = '';

if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['charity_auth_action'] ) ) {
    $action = sanitize_key( wp_unslash( $_POST['charity_auth_action'] ) );

    if ( ! isset( $_POST['charity_auth_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['charity_auth_nonce'] ) ), 'charity_auth_' . $action ) ) {
        $error = charity_t( 'Phiên làm việc không hợp lệ. Vui lòng thử lại.', 'Your session is invalid. Please try again.' );
    } elseif ( 'login' === $action ) {
        $login_portal = sanitize_key( wp_unslash( $_POST['login_portal'] ?? 'member' ) );
        $username     = sanitize_text_field( wp_unslash( $_POST['log'] ?? '' ) );
        $password     = (string) wp_unslash( $_POST['pwd'] ?? '' );
        $remember     = ! empty( $_POST['rememberme'] );

        $signed_in = wp_signon( [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        ], is_ssl() );

        if ( is_wp_error( $signed_in ) || ! charity_portal_accepts_user( $login_portal, $signed_in ) ) {
            if ( $signed_in instanceof WP_User ) {
                wp_logout();
            }
            $error = charity_t( 'Thông tin đăng nhập hoặc cổng truy cập không đúng.', 'The credentials or access portal are incorrect.' );
        } else {
            $destination = charity_portal_redirect_for_user( $signed_in );
            if ( charity_user_is_member( $signed_in ) && ! empty( $_POST['redirect_to'] ) ) {
                $destination = wp_validate_redirect( wp_unslash( $_POST['redirect_to'] ), $destination );
            }
            wp_safe_redirect( $destination );
            exit;
        }
    } elseif ( 'register' === $action ) {
        $username = sanitize_user( wp_unslash( $_POST['user_login'] ?? '' ), true );
        $name     = sanitize_text_field( wp_unslash( $_POST['display_name'] ?? '' ) );
        $email    = sanitize_email( wp_unslash( $_POST['user_email'] ?? '' ) );
        $password = (string) wp_unslash( $_POST['user_password'] ?? '' );
        $confirm  = (string) wp_unslash( $_POST['user_password_confirm'] ?? '' );

        if ( ! empty( $_POST['user_website'] ) ) {
            $error = charity_t( 'Không thể tạo tài khoản.', 'The account could not be created.' );
        } elseif ( strlen( $username ) < 3 || strlen( $username ) > 60 || '' === $name || ! is_email( $email ) ) {
            $error = charity_t( 'Vui lòng nhập đầy đủ tên, email và tên đăng nhập hợp lệ.', 'Enter a valid name, email and username.' );
        } elseif ( strlen( $password ) < 10 || $password !== $confirm ) {
            $error = charity_t( 'Mật khẩu phải có ít nhất 10 ký tự và hai ô phải trùng nhau.', 'Passwords must match and contain at least 10 characters.' );
        } elseif ( username_exists( $username ) || email_exists( $email ) ) {
            $error = charity_t( 'Tên đăng nhập hoặc email đã được sử dụng.', 'The username or email is already in use.' );
        } else {
            $user_id = wp_insert_user( [
                'user_login'   => $username,
                'user_pass'    => $password,
                'user_email'   => $email,
                'display_name' => $name,
                'role'         => 'riseup_member',
            ] );

            if ( is_wp_error( $user_id ) ) {
                $error = charity_t( 'Không thể tạo tài khoản. Vui lòng thử lại.', 'The account could not be created. Please try again.' );
            } else {
                $user = wp_signon( [ 'user_login' => $username, 'user_password' => $password, 'remember' => true ], is_ssl() );
                if ( $user instanceof WP_User ) {
                    wp_safe_redirect( charity_portal_url( 'account' ) );
                    exit;
                }
                $notice = charity_t( 'Tạo tài khoản thành công. Bạn có thể đăng nhập.', 'Account created. You can now sign in.' );
            }
        }
    }
}

$labels = [
    'admin'        => [ 'Quản trị viên', 'Administrator', 'Toàn quyền quản trị website.', 'Full website administration access.' ],
    'collaborator' => [ 'Cộng tác viên', 'Collaborator', 'Đăng, sửa, xóa bài viết và quản lý bình luận.', 'Create, edit and delete posts and manage comments.' ],
    'member'       => [ 'Thành viên', 'Member', 'Bình luận bài viết và gửi ý kiến cho ban biên tập.', 'Comment on posts and send feedback to the editorial team.' ],
    'account'      => [ 'Tài khoản của bạn', 'Your account', 'Quản lý các thao tác dành cho thành viên.', 'Access the actions available to your account.' ],
];
$label = $labels[ $portal ];

get_header();
?>

<main class="auth-portal" id="main-content">
    <div class="auth-portal__shell">
        <section class="auth-portal__intro">
            <span class="section-label"><?php echo esc_html( charity_t( 'Khu vực bảo mật', 'Secure area' ) ); ?></span>
            <h1><?php echo esc_html( charity_t( $label[0], $label[1] ) ); ?></h1>
            <p><?php echo esc_html( charity_t( $label[2], $label[3] ) ); ?></p>
            <div class="auth-portal__switcher">
                <a href="<?php echo esc_url( charity_portal_url( 'admin' ) ); ?>"><?php echo esc_html( charity_t( 'Quản trị viên', 'Administrator' ) ); ?></a>
                <a href="<?php echo esc_url( charity_portal_url( 'collaborator' ) ); ?>"><?php echo esc_html( charity_t( 'Cộng tác viên', 'Collaborator' ) ); ?></a>
                <a href="<?php echo esc_url( charity_portal_url( 'member' ) ); ?>"><?php echo esc_html( charity_t( 'Thành viên', 'Member' ) ); ?></a>
            </div>
        </section>

        <section class="auth-card">
            <?php if ( $error ) : ?><div class="auth-message auth-message--error" role="alert"><?php echo esc_html( $error ); ?></div><?php endif; ?>
            <?php if ( $notice ) : ?><div class="auth-message auth-message--success"><?php echo esc_html( $notice ); ?></div><?php endif; ?>

            <?php if ( is_user_logged_in() ) : $current_user = wp_get_current_user(); ?>
                <h2><?php echo esc_html( sprintf( charity_t( 'Xin chào, %s', 'Hello, %s' ), $current_user->display_name ) ); ?></h2>
                <p><?php echo esc_html( charity_t( 'Bạn đã đăng nhập thành công.', 'You are signed in.' ) ); ?></p>
                <div class="auth-card__actions">
                    <?php if ( charity_user_is_member( $current_user ) ) : ?>
                        <a class="btn btn--primary" href="<?php echo esc_url( charity_portal_url( 'feedback' ) ); ?>"><?php echo esc_html( charity_t( 'Gửi ý kiến', 'Send feedback' ) ); ?></a>
                    <?php else : ?>
                        <a class="btn btn--primary" href="<?php echo esc_url( charity_portal_redirect_for_user( $current_user ) ); ?>"><?php echo esc_html( charity_t( 'Vào trang quản lý', 'Open dashboard' ) ); ?></a>
                    <?php endif; ?>
                    <a class="btn btn--outline" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php echo esc_html( charity_t( 'Đăng xuất', 'Sign out' ) ); ?></a>
                </div>
            <?php else : ?>
                <h2><?php echo esc_html( charity_t( 'Đăng nhập', 'Sign in' ) ); ?></h2>
                <form method="post" class="auth-form">
                    <?php wp_nonce_field( 'charity_auth_login', 'charity_auth_nonce' ); ?>
                    <input type="hidden" name="charity_auth_action" value="login">
                    <input type="hidden" name="login_portal" value="<?php echo esc_attr( 'account' === $portal ? 'member' : $portal ); ?>">
                    <?php if ( ! empty( $_GET['redirect_to'] ) ) : ?>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( wp_unslash( $_GET['redirect_to'] ) ); ?>">
                    <?php endif; ?>
                    <label for="auth-log"><?php echo esc_html( charity_t( 'Tên đăng nhập hoặc email', 'Username or email' ) ); ?></label>
                    <input id="auth-log" name="log" type="text" autocomplete="username" required maxlength="100">
                    <label for="auth-pwd"><?php echo esc_html( charity_t( 'Mật khẩu', 'Password' ) ); ?></label>
                    <input id="auth-pwd" name="pwd" type="password" autocomplete="current-password" required>
                    <label class="auth-form__check"><input type="checkbox" name="rememberme" value="1"> <?php echo esc_html( charity_t( 'Ghi nhớ đăng nhập', 'Remember me' ) ); ?></label>
                    <button class="btn btn--primary" type="submit"><?php echo esc_html( charity_t( 'Đăng nhập', 'Sign in' ) ); ?></button>
                    <a class="auth-form__forgot" href="<?php echo esc_url( wp_lostpassword_url( charity_portal_url( 'member' ) ) ); ?>"><?php echo esc_html( charity_t( 'Quên mật khẩu?', 'Forgot password?' ) ); ?></a>
                </form>

                <?php if ( in_array( $portal, [ 'member', 'account' ], true ) ) : ?>
                    <div class="auth-card__divider"><span><?php echo esc_html( charity_t( 'Tạo tài khoản thành viên', 'Create member account' ) ); ?></span></div>
                    <form method="post" class="auth-form auth-form--register">
                        <?php wp_nonce_field( 'charity_auth_register', 'charity_auth_nonce' ); ?>
                        <input type="hidden" name="charity_auth_action" value="register">
                        <input class="auth-honeypot" type="text" name="user_website" tabindex="-1" autocomplete="off">
                        <label for="reg-name"><?php echo esc_html( charity_t( 'Họ và tên', 'Full name' ) ); ?></label>
                        <input id="reg-name" name="display_name" type="text" autocomplete="name" required maxlength="100">
                        <label for="reg-user"><?php echo esc_html( charity_t( 'Tên đăng nhập', 'Username' ) ); ?></label>
                        <input id="reg-user" name="user_login" type="text" autocomplete="username" required minlength="3" maxlength="60">
                        <label for="reg-email">Email</label>
                        <input id="reg-email" name="user_email" type="email" autocomplete="email" required maxlength="100">
                        <label for="reg-pass"><?php echo esc_html( charity_t( 'Mật khẩu (ít nhất 10 ký tự)', 'Password (at least 10 characters)' ) ); ?></label>
                        <input id="reg-pass" name="user_password" type="password" autocomplete="new-password" required minlength="10">
                        <label for="reg-pass-confirm"><?php echo esc_html( charity_t( 'Nhập lại mật khẩu', 'Confirm password' ) ); ?></label>
                        <input id="reg-pass-confirm" name="user_password_confirm" type="password" autocomplete="new-password" required minlength="10">
                        <button class="btn btn--outline" type="submit"><?php echo esc_html( charity_t( 'Đăng ký thành viên', 'Register' ) ); ?></button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php get_footer(); ?>
