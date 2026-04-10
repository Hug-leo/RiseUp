<?php
/**
 * URL Fixer — run this if the site loads but CSS/links are broken.
 * It replaces the old site URL with the current one throughout the database.
 * DELETE THIS FILE after running it.
 *
 * URL: http://localhost/sampleweb/wordpress/fix-urls.php
 */

require_once __DIR__ . '/wp-load.php';

if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( home_url( '/fix-urls.php' ) ) );
    exit;
}
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Permission denied.' );
}

$old_url = isset( $_POST['old_url'] ) ? trim( $_POST['old_url'] ) : '';
$new_url = rtrim( home_url(), '/' );
$done    = false;
$counts  = [];

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && $old_url ) {
    $old_url = rtrim( $old_url, '/' );
    global $wpdb;

    // Tables and columns to search-replace
    $targets = [
        [ $wpdb->options,   'option_value',   "option_name NOT IN ('cron','widget_block')" ],
        [ $wpdb->posts,     'post_content',   null ],
        [ $wpdb->posts,     'post_excerpt',   null ],
        [ $wpdb->posts,     'guid',           null ],
        [ $wpdb->postmeta,  'meta_value',     null ],
        [ $wpdb->usermeta,  'meta_value',     null ],
    ];

    foreach ( $targets as [ $table, $col, $where ] ) {
        $sql = "UPDATE `$table` SET `$col` = REPLACE(`$col`, %s, %s)"
             . ( $where ? " WHERE $where" : '' );
        $result = $wpdb->query( $wpdb->prepare( $sql, $old_url, $new_url ) );
        $counts[] = "$table.$col: $result row(s) updated";
    }

    // Handle serialized data in options/postmeta via PHP
    $serialized_tables = [
        [ $wpdb->options,  'option_id',  'option_value' ],
        [ $wpdb->postmeta, 'meta_id',    'meta_value'   ],
    ];

    foreach ( $serialized_tables as [ $table, $id_col, $val_col ] ) {
        $rows = $wpdb->get_results( "SELECT `$id_col`, `$val_col` FROM `$table`" );
        foreach ( $rows as $row ) {
            $val = $row->$val_col;
            if ( strpos( $val, 'a:' ) === 0 || strpos( $val, 'O:' ) === 0 ) {
                $unserialized = @unserialize( $val );
                if ( $unserialized !== false ) {
                    $new_val = str_replace( $old_url, $new_url, serialize( $unserialized ) );
                    if ( $new_val !== $val ) {
                        $wpdb->update( $table, [ $val_col => $new_val ], [ $id_col => $row->$id_col ] );
                    }
                }
            }
        }
    }

    // Flush caches
    wp_cache_flush();
    delete_option( 'rewrite_rules' );

    $done = true;
}

$current_db_url = get_option( 'siteurl' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>URL Fixer — Vuon Len Scholarship</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f4f4f4; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
  .card { background: #fff; border-radius: 14px; box-shadow: 0 4px 28px rgba(0,0,0,.10); padding: 40px 36px; max-width: 580px; width: 100%; }
  h1 { font-size: 1.4rem; font-weight: 800; margin-bottom: 6px; }
  p  { font-size: 14px; color: #555; margin-bottom: 20px; line-height: 1.6; }
  label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #333; }
  input { width: 100%; padding: 11px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace; margin-bottom: 16px; outline: none; transition: .2s; }
  input:focus { border-color: #1565C0; }
  .hint { font-size: 12px; color: #999; margin-top: -12px; margin-bottom: 16px; }
  button { background: #1565C0; color: #fff; padding: 12px 26px; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: .2s; font-family: inherit; }
  button:hover { background: #0D47A1; }
  .result { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px 20px; margin-top: 20px; }
  .result h3 { color: #15803d; font-size: 15px; margin-bottom: 10px; }
  .result ul { list-style: disc; padding-left: 18px; }
  .result li { font-size: 13px; color: #444; margin-bottom: 4px; font-family: monospace; }
  .info-box { background: #f8f8f8; border: 1px solid #e0e0e0; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px; color: #555; }
  .info-box strong { color: #111; }
  .warn { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #92400e; margin-top: 20px; }
  .actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 20px; }
  .btn-link { display: inline-flex; align-items: center; padding: 11px 22px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; background: #efefef; color: #333; transition: .2s; }
  .btn-link:hover { background: #e0e0e0; }
</style>
</head>
<body>
<div class="card">
    <h1>🔧 URL Fixer</h1>
    <p>Use this if the site loads but CSS or links are broken after moving to a new machine. It replaces the old URL with your current URL throughout the database.</p>

    <div class="info-box">
        <strong>Current URL in database:</strong><br>
        <code><?php echo esc_html( $current_db_url ); ?></code><br><br>
        <strong>Your current URL (new value):</strong><br>
        <code><?php echo esc_html( $new_url ); ?></code>
    </div>

    <?php if ( $current_db_url === $new_url ) : ?>
    <div class="result">
        <h3>✅ No fix needed!</h3>
        <p style="color:#15803d;font-size:14px;margin:0">The URL in the database already matches your current URL. Your site should be working correctly.</p>
    </div>
    <?php elseif ( $done ) : ?>
    <div class="result">
        <h3>✅ URLs updated successfully!</h3>
        <p style="color:#555;font-size:13px;margin-bottom:10px;">Replaced <code><?php echo esc_html( $old_url ); ?></code> → <code><?php echo esc_html( $new_url ); ?></code></p>
        <ul>
            <?php foreach ( $counts as $c ) : ?>
            <li><?php echo esc_html( $c ); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="actions">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-link">→ View Website</a>
        <a href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>" class="btn-link">Save Permalinks</a>
    </div>
    <?php else : ?>
    <form method="POST">
        <label for="old_url">Old URL (from previous machine):</label>
        <input type="url" name="old_url" id="old_url"
               value="<?php echo esc_attr( $current_db_url ); ?>"
               placeholder="http://localhost/sampleweb/wordpress" required>
        <p class="hint">This is the URL that was used on the original machine. It's pre-filled from your database.</p>

        <button type="submit">Run URL Fix →</button>
    </form>
    <?php endif; ?>

    <div class="warn">⚠️ <strong>Delete <code>fix-urls.php</code></strong> from your WordPress folder after you're done with it.</div>
</div>
</body>
</html>
