<?php
/** Run: C:\xampp\php\php.exe scripts/test_contact.php (local WordPress DB required). */
if ( PHP_SAPI !== 'cli' ) { exit; }
require dirname( __DIR__ ) . '/wordpress/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/user.php';

function contact_check( $condition, $label ) {
    if ( ! $condition ) { throw new RuntimeException( $label ); }
    echo "PASS: {$label}\n";
}

$user_id = wp_insert_user( [ 'user_login' => 'contact_test_' . wp_generate_password( 12, false ),
    'user_pass' => wp_generate_password( 32 ), 'role' => 'subscriber' ] );
if ( is_wp_error( $user_id ) ) { throw new RuntimeException( 'Cannot create test user' ); }
$records = [];
$mail_ok = false;
$mail_calls = 0;
add_filter( 'pre_wp_mail', function ( $return, $attributes ) use ( &$mail_ok, &$mail_calls ) {
    ++$mail_calls;
    contact_check( in_array( 'Reply-To: contact-test@example.invalid', $attributes['headers'], true ), 'Safe Reply-To' );
    return $mail_ok;
}, 10, 2 );
try {
    wp_set_current_user( 0 );
    contact_check( 'login' === charity_receive_contact( [] )->get_error_code(), 'Guest rejected' );
    wp_set_current_user( $user_id );
    $input = [ 'contact_nonce' => wp_create_nonce( 'vuonlen_contact_form' ),
        'contact_name' => 'Kiểm thử', 'contact_email' => 'contact-test@example.invalid',
        'contact_subject' => 'CONTACT SELF TEST', 'contact_message' => "Nội dung thử nghiệm O'Reilly \\ path" ];
    foreach ( [
        [ 'contact_nonce', 'bad', 'nonce' ], [ 'contact_email', 'bad@email', 'fields' ],
        [ 'contact_email', "a@example.invalid\r\nBcc: b@example.invalid", 'fields' ],
        [ 'contact_message', '  ', 'fields' ], [ 'contact_name', [], 'invalid' ],
        [ 'contact_message', str_repeat( 'x', 5001 ), 'length' ],
    ] as [ $key, $value, $code ] ) {
        $bad = $input; $bad[ $key ] = $value;
        $result = charity_receive_contact( $bad );
        contact_check( is_wp_error( $result ) && $code === $result->get_error_code(), 'Reject ' . $key . ': ' . $code );
    }
    contact_check( 0 === $mail_calls, 'Invalid data never sends mail' );
    $id = charity_receive_contact( $input );
    contact_check( is_int( $id ) && $id > 0, 'Mail failure still saves contact' );
    $records[] = $id;
    contact_check( 'private' === get_post_status( $id ) && str_contains( get_post( $id )->post_content, $input['contact_message'] ), 'Private storage preserves message' );
    contact_check( 'failed' === get_post_meta( $id, '_contact_mail', true ), 'Mail failure recorded' );
    contact_check( ! current_user_can( 'edit_post', $id ) && ! current_user_can( 'read_post', $id ), 'Member cannot access inbox' );
    contact_check( 'rate' === charity_receive_contact( $input )->get_error_code(), 'Rapid repeat blocked' );
    delete_transient( 'charity_contact_' . $user_id );
    $mail_ok = true;
    $id = charity_receive_contact( $input );
    contact_check( is_int( $id ), 'Mail accepted contact saved' );
    $records[] = $id;
    contact_check( 'accepted' === get_post_meta( $id, '_contact_mail', true ), 'Mail acceptance recorded' );
    $admin = get_users( [ 'role' => 'administrator', 'number' => 1 ] )[0];
    wp_set_current_user( $admin->ID );
    contact_check( current_user_can( 'edit_post', $id ) && current_user_can( 'read_post', $id ), 'Admin can manage inbox' );
    $_POST = [ 'contact_status_nonce' => wp_create_nonce( 'contact_status' ), 'contact_done' => '1' ];
    wp_update_post( [ 'ID' => $id ] );
    contact_check( 'done' === get_post_meta( $id, '_contact_status', true ), 'Admin marks handled' );
} finally {
    $_POST = [];
    foreach ( $records as $id ) { wp_delete_post( $id, true ); }
    delete_transient( 'charity_contact_' . $user_id );
    wp_delete_user( $user_id );
    echo "Test records and test account removed. No email sent.\n";
}
