<?php
/**
 * Template: Province Detail Page (Trang tỉnh)
 * URL: /tinh/{province-slug}/
 *
 * Reads province data from data/province-directory.json
 * Renders hero photo + member contact table.
 */

$province_slug = sanitize_title( get_query_var( 'province_slug' ) );

// Load province data from JSON.
$contacts_file = CHARITY_HCM_DIR . '/data/province-directory.json';
$all_provinces = [];
if ( file_exists( $contacts_file ) ) {
    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
    $raw = file_get_contents( $contacts_file );
    if ( $raw ) {
        $decoded = json_decode( $raw, true );
        if ( is_array( $decoded ) ) {
            $all_provinces = $decoded;
        }
    }
}

// Find province by slug.
$province_data = null;
foreach ( $all_provinces as $code => $prov ) {
    if ( isset( $prov['slug'] ) && $prov['slug'] === $province_slug ) {
        $province_data         = $prov;
        $province_data['code'] = $code;
        break;
    }
}

// 404 if province not found.
if ( ! $province_data ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    nocache_headers();
    include get_template_directory() . '/404.php';
    exit;
}

$province_name = esc_html( $province_data['name'] );
$members       = $province_data['members'] ?? [];
$photo_url     = esc_url( CHARITY_HCM_URI . '/assets/img/provinces/' . $province_slug . '.jpg' );
$map_cat       = get_category_by_slug( 'ban-do-vuon-len' );
$back_url      = $map_cat ? esc_url( get_category_link( $map_cat->term_id ) ) : esc_url( home_url( '/' ) );

// Set document title for this virtual page.
add_filter( 'document_title_parts', function ( $title ) use ( $province_name ) {
    $title['title'] = $province_name . ' — ' . charity_t( 'Bản đồ Vươn Lên', 'Rise Up Map' );
    return $title;
} );

get_header();
?>

<main class="province-detail" id="main-content">

    <div class="province-detail__hero">
        <img
            src="<?php echo $photo_url; ?>"
            alt="<?php echo $province_name; ?>"
            class="province-detail__photo"
            width="1200"
            height="800"
            loading="eager"
        >
        <div class="province-detail__title-wrap">
            <h1 class="province-detail__title"><?php echo $province_name; ?></h1>
            <a href="<?php echo $back_url; ?>" class="province-detail__back">
                ← <?php echo charity_t( 'Về bản đồ', 'Back to map' ); ?>
            </a>
        </div>
    </div>

    <div class="province-detail__body container">

        <h2 class="province-detail__section-title">
            <?php echo charity_t( 'Thành viên Đông Du', 'Đông Du Members' ); ?>
        </h2>

        <?php if ( ! empty( $members ) ) : ?>
        <table class="province-detail__table">
            <thead>
                <tr>
                    <th><?php echo charity_t( 'Tên', 'Name' ); ?></th>
                    <th><?php echo charity_t( 'Vai trò', 'Role' ); ?></th>
                    <th><?php echo charity_t( 'Liên hệ', 'Contact' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $members as $member ) : ?>
                <tr>
                    <td><?php echo esc_html( $member['name'] ); ?></td>
                    <td><?php echo esc_html( $member['role'] ); ?></td>
                    <td>
                        <a href="<?php echo esc_url( $member['contact'] ); ?>">
                            <?php echo charity_t( 'Liên hệ', 'Contact' ); ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else : ?>
        <p class="province-detail__empty">
            <?php echo charity_t( 'Chưa có thành viên trong tỉnh này.', 'No members listed for this province yet.' ); ?>
        </p>
        <?php endif; ?>

    </div><!-- .province-detail__body -->

</main><!-- .province-detail -->

<?php get_footer(); ?>
