<?php
if ( PHP_SAPI !== 'cli' ) { exit; }
require dirname( __DIR__ ) . '/wordpress/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/user.php';
$users = []; $posts = []; $keys = [];
function submission_check( $ok, $label ) {
    if ( ! $ok ) { throw new RuntimeException( $label ); }
    echo "PASS: {$label}\n";
}
try {
    foreach ( [ 'riseup_member', 'riseup_collaborator', 'administrator' ] as $role ) {
        $users[ $role ] = wp_insert_user( [ 'user_login' => 'submission_rules_' . wp_generate_password( 12, false ), 'user_pass' => wp_generate_password( 24 ), 'role' => $role ] );
    }
    wp_set_current_user( 0 );
    submission_check( is_wp_error( charity_save_submission( [] ) ), 'Guest cannot save' );
    wp_set_current_user( $users['riseup_member'] );
    $input = [ 'submission_nonce' => wp_create_nonce( 'save_submission' ), 'request_id' => wp_generate_uuid4(), 'article_id' => '0', 'article_title' => 'Rules test', 'article_content' => '<p>Test</p>', 'article_category' => (string) get_option( 'default_category' ), 'intent' => 'submit' ];
    $keys[] = 'submission_' . get_current_user_id() . '_' . $input['request_id'];
    foreach ( [ [ 'submission_nonce', 'bad' ], [ 'article_title', [] ], [ 'article_category', '99999999' ], [ 'intent', 'publish' ], [ 'article_content', '  ' ] ] as [ $key, $value ] ) {
        $bad = $input; $bad[ $key ] = $value;
        submission_check( is_wp_error( charity_save_submission( $bad ) ), 'Reject invalid ' . $key );
    }
    $id = charity_save_submission( $input );
    submission_check( is_int( $id ), 'Valid submission saved' ); $posts[] = $id;
    wp_set_current_user( $users['riseup_collaborator'] );
    submission_check( ! current_user_can( 'publish_posts' ) && ! current_user_can( 'delete_post', $id ), 'Collaborator cannot publish or delete another author post' );
    submission_check( is_wp_error( charity_review_submission( $id, 'rejected', 'No' ) ), 'Collaborator cannot reject finally' );
    submission_check( is_wp_error( charity_review_submission( $id, 'changes', '' ) ), 'Reason required' );
    wp_set_current_user( $users['administrator'] );
    submission_check( $id === charity_review_submission( $id, 'rejected', 'Không phù hợp' ), 'Admin can reject with reason' );
    wp_set_current_user( $users['riseup_member'] );
    submission_check( ! charity_submission_editable( get_post( $id ) ) && 'Từ chối' === charity_submission_label( get_post( $id ) ), 'Rejected article locked and labelled' );
} finally {
    foreach ( $posts as $id ) { wp_delete_post( $id, true ); }
    foreach ( $keys as $key ) { delete_transient( $key ); }
    foreach ( $users as $id ) { wp_delete_user( $id ); }
    echo "Test records removed.\n";
}
