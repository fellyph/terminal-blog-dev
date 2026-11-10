<?php
/**
 * Header compatibility regressions using the running site's real block registry.
 * Run with: studio wp eval-file /absolute/path/to/tests/header-patterns.php
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'terminal_blog_dev_compatible_header_patterns' ) ) {
	fwrite( STDERR, "Run this test through studio wp eval-file with Terminal Blog Dev active.\n" );
	exit( 1 );
}

$GLOBALS['terminal_header_checks'] = 0;
function terminal_header_check( $condition, $message ) {
	global $terminal_header_checks;
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	++$terminal_header_checks;
}

$registry = WP_Block_Patterns_Registry::get_instance();
$types    = WP_Block_Type_Registry::get_instance();
$prefix   = 'terminal-header-test/';
$missing  = '<!-- wp:group --><div class="wp-block-group"><!-- wp:terminal-header-test/account /--></div><!-- /wp:group -->';
$good     = array( 'name' => $prefix . 'good', 'categories' => array( 'header' ), 'content' => '<!-- wp:site-title {"level":0} /-->' );
$bad      = array( 'name' => $prefix . 'store', 'categories' => array( 'header' ), 'content' => $missing );
$banner   = array( 'name' => $prefix . 'banner', 'categories' => array( 'banner' ), 'content' => $missing );
$created  = array();

try {
	$filtered = terminal_blog_dev_compatible_header_patterns( array( $bad, $good, $banner ) );
	terminal_header_check( array( $good, $banner ) === $filtered, 'Only the incompatible header should be removed, with ordering preserved.' );
	terminal_header_check( array( 0, 1 ) === array_keys( $filtered ), 'A filtered REST collection must remain a JSON array.' );
	$by_role = array( 'content' => $missing, 'block_types' => array( 'core/template-part/header' ) );
	terminal_header_check( array() === terminal_blog_dev_compatible_header_patterns( array( $by_role ) ), 'Header roles must work with REST field names too.' );

	foreach ( array(
		'store' => $missing,
		'nested' => '<!-- wp:pattern {"slug":"terminal-header-test/store"} /-->',
		'cycle-a' => '<!-- wp:pattern {"slug":"terminal-header-test/cycle-b"} /-->',
		'cycle-b' => '<!-- wp:pattern {"slug":"terminal-header-test/cycle-a"} /-->',
	) as $slug => $content ) {
		$name = $prefix . $slug;
		$registry->register( $name, array( 'title' => $slug, 'categories' => array( 'header' ), 'content' => $content ) );
		$created[] = $name;
	}
	terminal_header_check( terminal_blog_dev_header_missing_blocks( parse_blocks( '<!-- wp:pattern {"slug":"terminal-header-test/nested"} /-->' ) ), 'A nested pattern must not hide a missing plugin dependency.' );
	terminal_header_check( terminal_blog_dev_header_missing_blocks( parse_blocks( '<!-- wp:pattern {"slug":"terminal-header-test/unknown"} /-->' ) ), 'A missing nested pattern must not be offered as a usable header.' );
	terminal_header_check( terminal_blog_dev_header_missing_blocks( parse_blocks( '<!-- wp:pattern {"slug":"terminal-header-test/cycle-a"} /-->' ) ), 'Circular pattern references must terminate safely.' );
	terminal_header_check( array() === terminal_blog_dev_compatible_header_patterns( array( array( 'name' => $prefix . 'store' ) ) ), 'REST field selection must still detect a registered incompatible header.' );
	terminal_header_check( $registry->is_registered( $prefix . 'store' ), 'Filtering suggestions must not unregister patterns or alter saved content.' );

	$settings = terminal_blog_dev_header_editor_settings( array( '__experimentalBlockPatterns' => array( $good, $bad ), 'unrelated' => true ) );
	terminal_header_check( array( $good ) === $settings['__experimentalBlockPatterns'] && true === $settings['unrelated'], 'Legacy editor settings must retain unrelated options.' );
	$server  = rest_get_server();
	$request = new WP_REST_Request( 'GET', '/wp/v2/block-patterns/patterns' );
	$result  = apply_filters( 'rest_post_dispatch', new WP_REST_Response( array( $good, $bad ) ), $server, $request );
	terminal_header_check( array( $good ) === $result->get_data(), 'The editor REST response must hide late-loaded incompatible headers.' );
	$other = new WP_REST_Response( array( $good, $bad ) );
	terminal_blog_dev_header_pattern_response( $other, $server, new WP_REST_Request( 'GET', '/wp/v2/posts' ) );
	terminal_header_check( array( $good, $bad ) === $other->get_data(), 'Other REST collections must remain unchanged.' );
	$error = new WP_REST_Response( array( $bad ), 403 );
	terminal_blog_dev_header_pattern_response( $error, $server, $request );
	terminal_header_check( 403 === $error->get_status() && array( $bad ) === $error->get_data(), 'REST errors and permission results must remain unchanged.' );
	$wp_error = new WP_Error( 'rest_forbidden', 'Forbidden', array( 'status' => 403 ) );
	terminal_header_check( $wp_error === terminal_blog_dev_header_pattern_response( $wp_error, $server, $request ), 'Permission errors must not cause a PHP fatal error.' );

	$types->register( $prefix . 'account' );
	terminal_header_check( array( $bad ) === terminal_blog_dev_compatible_header_patterns( array( $bad ) ), 'An optional header must remain available once its plugin block is registered.' );

	foreach ( array( 'standard', 'compact', 'search' ) as $variant ) {
		$pattern = $registry->get_registered( 'terminal-blog-dev/header-' . $variant );
		terminal_header_check( null !== $pattern && in_array( 'core/template-part/header', $pattern['blockTypes'], true ), 'Header variant must be discoverable in the template-part chooser: ' . $variant );
		terminal_header_check( ! terminal_blog_dev_header_missing_blocks( parse_blocks( $pattern['content'] ) ), 'Theme headers must have no missing block or pattern dependencies: ' . $variant );
	}
	foreach ( array( 'theme-toggle', 'ascii-art-header' ) as $slug ) {
		$pattern = $registry->get_registered( 'terminal-blog-dev/' . $slug );
		terminal_header_check( ! in_array( 'header', $pattern['categories'], true ), 'A standalone control or decoration must not be offered as a complete header.' );
	}
	terminal_header_check( false === $registry->get_registered( 'terminal-blog-dev/header-brand' )['inserter'], 'The shared brand fragment must remain internal.' );
	$navigation = parse_blocks( $registry->get_registered( 'terminal-blog-dev/header-navigation' )['content'] )[0];
	$menu = terminal_blog_dev_current_header_menu();
	terminal_header_check( $menu === ( $navigation['attrs']['ref'] ?? 0 ), 'Header alternatives must retain the selected header menu.' );
	terminal_header_check( $menu || str_contains( serialize_blocks( $navigation['innerBlocks'] ), 'wp:page-list' ), 'Without a header menu, use pages rather than an arbitrary footer menu.' );
} finally {
	foreach ( $created as $name ) {
		$registry->unregister( $name );
	}
	if ( $types->is_registered( $prefix . 'account' ) ) {
		$types->unregister( $prefix . 'account' );
	}
}

echo 'Passed ' . $GLOBALS['terminal_header_checks'] . " header checks.\n";
