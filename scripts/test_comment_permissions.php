<?php
// Run with local PHP CLI. No notification emails are sent.
if ( PHP_SAPI !== 'cli' ) { exit; }
require dirname( __DIR__ ) . '/wordpress/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/user.php';
add_filter( 'pre_wp_mail', '__return_false' );
$users = [];
$comments = [];
$post = 0;
function site_check( $ok, $label ) {
    if ( ! $ok ) { throw new RuntimeException( $label ); }
    echo "PASS: {$label}\n";
}
try {
    foreach ( [ 'administrator', 'riseup_collaborator', 'riseup_member' ] as $role ) {
        $id = wp_insert_user( [ 'user_login' => 'comment_test_' . wp_generate_password( 12, false ), 'user_pass' => wp_generate_password( 24 ), 'role' => $role ] );
        if ( is_wp_error( $id ) ) { throw new RuntimeException( 'Test user creation failed' ); }
        $users[ $role ] = $id;
    }
    $post = wp_insert_post( [ 'post_title' => 'Comment permissions test', 'post_status' => 'draft', 'post_author' => $users['administrator'] ] );
    foreach ( $users as $role => $id ) {
        wp_set_current_user( $id );
        site_check( 1 === apply_filters( 'pre_comment_approved', 0, [ 'comment_type' => 'comment' ] ), $role . ': comment approval' );
        site_check( 'spam' === apply_filters( 'pre_comment_approved', 'spam', [ 'comment_type' => 'comment' ] ), $role . ': spam decision preserved' );
        $comments[ $role ] = wp_insert_comment( [ 'comment_post_ID' => $post, 'user_id' => $id, 'comment_content' => 'Test only', 'comment_approved' => 1 ] );
    }
    wp_set_current_user( $users['riseup_collaborator'] );
    site_check( ! current_user_can( 'edit_comment', $comments['administrator'] ), 'Collaborator cannot moderate administrator comment' );
    site_check( current_user_can( 'edit_comment', $comments['riseup_member'] ), 'Collaborator can moderate member comment' );
    wp_set_current_user( $users['riseup_member'] );
    site_check( ! current_user_can( 'edit_comment', $comments['riseup_collaborator'] ), 'Member cannot moderate comments' );
    wp_set_current_user( 0 );
    site_check( (bool) get_option( 'comment_registration' ), 'Anonymous comments require login' );
} finally {
    foreach ( $comments as $id ) { wp_delete_comment( $id, true ); }
    if ( $post ) { wp_delete_post( $post, true ); }
    foreach ( $users as $id ) { wp_delete_user( $id ); }
    echo "Test data removed. No email sent.\n";
}
