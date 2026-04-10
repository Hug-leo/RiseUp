<?php
/**
 * WordPress Configuration — Production (Hosting)
 *
 * INSTRUCTIONS:
 * 1. Copy this file to: wordpress/wp-config.php
 * 2. Replace all placeholder values (YOUR_*) with credentials from your hosting provider
 * 3. Generate new security keys at: https://api.wordpress.org/secret-key/1.1/salt/
 * 4. Upload the wordpress/ folder to your hosting via FTP/cPanel File Manager
 */

// ** Database settings — get these from your hosting control panel ** //
define( 'DB_NAME',     'YOUR_DB_NAME' );       // e.g. 'username_wp'
define( 'DB_USER',     'YOUR_DB_USER' );       // e.g. 'username_dbuser'
define( 'DB_PASSWORD', 'YOUR_DB_PASSWORD' );   // the password you set in cPanel/Plesk
define( 'DB_HOST',     'localhost' );           // usually 'localhost' — check with your host
define( 'DB_CHARSET',  'utf8mb4' );
define( 'DB_COLLATE',  '' );

/**
 * Authentication unique keys and salts.
 * IMPORTANT: Generate NEW keys for production at https://api.wordpress.org/secret-key/1.1/salt/
 * Copy-paste the output below, replacing all 8 defines.
 */
define( 'AUTH_KEY',         'GENERATE_NEW_KEY' );
define( 'SECURE_AUTH_KEY',  'GENERATE_NEW_KEY' );
define( 'LOGGED_IN_KEY',    'GENERATE_NEW_KEY' );
define( 'NONCE_KEY',        'GENERATE_NEW_KEY' );
define( 'AUTH_SALT',        'GENERATE_NEW_KEY' );
define( 'SECURE_AUTH_SALT', 'GENERATE_NEW_KEY' );
define( 'LOGGED_IN_SALT',   'GENERATE_NEW_KEY' );
define( 'NONCE_SALT',       'GENERATE_NEW_KEY' );

$table_prefix = 'wp_';

/** Production: debug OFF */
define( 'WP_DEBUG',     false );
define( 'WP_DEBUG_LOG', false );

/** Force HTTPS (enable after SSL is configured) */
// define( 'FORCE_SSL_ADMIN', true );

define( 'WPLANG', 'vi' );

/** Disable file editing from wp-admin for security */
define( 'DISALLOW_FILE_EDIT', true );

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
