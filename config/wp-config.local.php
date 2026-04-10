<?php
/**
 * WordPress Configuration — Local Development (macOS + Homebrew)
 */

// ** Database settings ** //
define( 'DB_NAME',     'sampleweb_wp' );
define( 'DB_USER',     'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST',     'localhost' );
define( 'DB_CHARSET',  'utf8mb4' );
define( 'DB_COLLATE',  '' );

/**
 * Authentication unique keys and salts.
 * Generate at: https://api.wordpress.org/secret-key/1.1/salt/
 */
define( 'AUTH_KEY',         'xQ7!kR2#mZ8$nT5@pW3^jL9&fH1*cY6(dE4)bA0+gU' );
define( 'SECURE_AUTH_KEY',  'kM3@rW8#tZ2$nH7!pQ5^jL1&fY9*cX6(dB4)eA0+gS' );
define( 'LOGGED_IN_KEY',    'pZ5@wH2#mR8$nT3!kQ7^jL9&fY1*cX6(dB4)eA0+gU' );
define( 'NONCE_KEY',        'tR7@wM2#kZ8$nH3!pQ5^jL1&fY9*cX6(dB4)eA0+gS' );
define( 'AUTH_SALT',        'jZ3@pW8#mR2$nT7!kQ5^tL1&fY9*cX6(dB4)eA0+gH' );
define( 'SECURE_AUTH_SALT', 'nR5@kW2#pZ8$mT3!tQ7^jL1&fY9*cX6(dB4)eA0+gH' );
define( 'LOGGED_IN_SALT',   'mT7@kZ2#pW8$nR3!tQ5^jL1&fY9*cX6(dB4)eA0+gH' );
define( 'NONCE_SALT',       'kZ3@pR2#mW8$nT7!tQ5^jL1&fY9*cX6(dB4)eA0+gH' );

$table_prefix = 'wp_';

define( 'WP_DEBUG',     true );
define( 'WP_DEBUG_LOG', true );

define( 'WPLANG', 'vi' );

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
