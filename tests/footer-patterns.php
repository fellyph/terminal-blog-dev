<?php
/**
 * Footer compatibility and content preservation against the real WordPress APIs.
 * Run with: studio wp eval-file /absolute/path/to/tests/footer-patterns.php
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'terminal_blog_dev_footer_navigation' ) ) {
	fwrite( STDERR, "Run this test through studio wp eval-file with Terminal Blog Dev active.\n" );
	exit( 1 );
}

$GLOBALS['terminal_footer_checks'] = 0;
function terminal_footer_check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	++$GLOBALS['terminal_footer_checks'];
}

function terminal_footer_pattern_content( $slug ) {
	ob_start();
	include get_template_directory() . '/patterns/footer-' . $slug . '.php';
	return trim( ob_get_clean() );
}

$registry = WP_Block_Patterns_Registry::get_instance();
$types    = WP_Block_Type_Registry::get_instance();
$missing  = '<!-- wp:group --><div class="wp-block-group"><!-- wp:terminal-footer-test/account /--></div><!-- /wp:group -->';
$bad      = array( 'name' => 'terminal-footer-test/store', 'categories' => array( 'footer' ), 'content' => $missing );
$good     = array( 'name' => 'terminal-footer-test/good', 'categories' => array( 'footer' ), 'content' => '<!-- wp:site-title {"level":0} /-->' );
$banner   = array( 'name' => 'terminal-footer-test/banner', 'categories' => array( 'banner' ), 'content' => $missing );
$fixture  = new WP_Block_Template();
$fixture->source  = 'custom';
$fixture->content = '';
$override = static function ( $template, $id, $type ) use ( $fixture ) {
	return get_stylesheet() . '//footer' === $id && 'wp_template_part' === $type ? $fixture : $template;
};

try {
	$filtered = terminal_blog_dev_compatible_template_part_patterns( array( $bad, $banner, $good ) );
	terminal_footer_check( array( $banner, $good ) === $filtered, 'Only incompatible footer suggestions should disappear, with order preserved.' );
	terminal_footer_check( array( 0, 1 ) === array_keys( $filtered ), 'The filtered collection must stay a JSON array.' );
	foreach ( array( 'blockTypes', 'block_types' ) as $field ) {
		$by_role = array( 'content' => $missing, $field => array( 'core/template-part/footer' ) );
		terminal_footer_check( array() === terminal_blog_dev_compatible_template_part_patterns( array( $by_role ) ), 'Footer roles must be recognized through ' . $field );
	}
	$registry->register( $bad['name'], array_merge( $bad, array( 'title' => 'Missing plugin' ) ) );
	terminal_footer_check( array() === terminal_blog_dev_compatible_template_part_patterns( array( array( 'name' => $bad['name'] ) ) ), 'REST field selection must still check the registered footer.' );
	$nested = array( 'categories' => array( 'footer' ), 'content' => '<!-- wp:pattern {"slug":"terminal-footer-test/store"} /-->' );
	terminal_footer_check( array() === terminal_blog_dev_compatible_template_part_patterns( array( $nested ) ), 'Nested patterns must not conceal missing footer dependencies.' );
	$settings = apply_filters( 'block_editor_settings_all', array( '__experimentalBlockPatterns' => array( $good, $bad ) ), new WP_Block_Editor_Context() );
	terminal_footer_check( array( $good ) === $settings['__experimentalBlockPatterns'], 'The editor must receive compatible footer choices.' );
	$response = apply_filters( 'rest_post_dispatch', new WP_REST_Response( array( $bad, $good ) ), rest_get_server(), new WP_REST_Request( 'GET', '/wp/v2/block-patterns/patterns' ) );
	terminal_footer_check( array( $good ) === $response->get_data(), 'The final REST collection must filter footer dependencies too.' );
	terminal_footer_check( $registry->is_registered( $bad['name'] ), 'Filtering choices must not unregister patterns.' );
	$types->register( 'terminal-footer-test/account' );
	terminal_footer_check( array( $bad ) === terminal_blog_dev_compatible_template_part_patterns( array( $bad ) ), 'A footer must reappear when its optional block is available.' );

	foreach ( array( 'standard', 'compact', 'pixel-art' ) as $variant ) {
		$pattern = $registry->get_registered( 'terminal-blog-dev/footer-' . $variant );
		terminal_footer_check( $pattern && in_array( 'core/template-part/footer', $pattern['blockTypes'], true ) && in_array( 'footer', $pattern['categories'], true ), 'Footer must be discoverable in the template-part chooser: ' . $variant );
		terminal_footer_check( ! terminal_blog_dev_template_part_missing_blocks( parse_blocks( $pattern['content'] ) ), 'Footer must use registered native blocks: ' . $variant );
		terminal_footer_check( ( 'pixel-art' === $variant ) === str_contains( $pattern['content'], 'terminal-footer-art' ), 'Only the pixel-art option should include the illustration.' );
	}
	foreach ( array( 'brand', 'content', 'navigation', 'social-links', 'credits' ) as $slug ) {
		terminal_footer_check( false === $registry->get_registered( 'terminal-blog-dev/footer-' . $slug )['inserter'], 'Footer fragments must remain internal: ' . $slug );
	}
	terminal_footer_check( str_contains( file_get_contents( get_template_directory() . '/parts/footer.html' ), 'terminal-blog-dev/footer-pixel-art' ), 'The uncustomized theme footer must use pixel art.' );
	$pixel_blocks = parse_blocks( terminal_footer_pattern_content( 'pixel-art' ) );
	$art = terminal_blog_dev_find_footer_block( $pixel_blocks, 'core/image' );
	terminal_footer_check( $art && str_contains( $art['attrs']['className'], 'terminal-footer-art--image' ), 'The artwork must use an editable native Image, not an empty Group with a layout picker.' );

	$existing = terminal_blog_dev_current_footer_block( 'core/navigation' );
	$actual = parse_blocks( terminal_footer_pattern_content( 'navigation' ) )[0];
	if ( ! empty( $existing['attrs']['ref'] ) && 'publish' === get_post_status( $existing['attrs']['ref'] ) ) {
		terminal_footer_check( $existing['attrs']['ref'] === $actual['attrs']['ref'], 'The real selected footer menu must survive a layout change.' );
	}
	$socials = terminal_blog_dev_current_footer_block( 'core/social-links' );
	terminal_footer_check( ( $socials ? serialize_block( $socials ) : '' ) === terminal_footer_pattern_content( 'social-links' ), 'The real saved social profiles must be preserved exactly.' );
	foreach ( array( 'standard', 'compact', 'pixel-art' ) as $variant ) {
		$expanded = resolve_pattern_blocks( parse_blocks( terminal_footer_pattern_content( $variant ) ) );
		$expanded_socials = terminal_blog_dev_find_footer_block( $expanded, 'core/social-links' );
		terminal_footer_check( ( $socials['innerBlocks'] ?? array() ) === ( $expanded_socials['innerBlocks'] ?? array() ), 'WordPress pattern resolution must preserve every social profile: ' . $variant );
	}

	add_filter( 'pre_get_block_template', $override, 10, 3 );
	$fixture->content = '<!-- wp:group --><div class="wp-block-group"><!-- wp:navigation --><!-- wp:navigation-link {"label":"A custom destination","url":"/existing-page/","opensInNewTab":true} /--><!-- /wp:navigation --></div><!-- /wp:group -->';
	$inline = parse_blocks( terminal_blog_dev_footer_navigation() )[0];
	$original = terminal_blog_dev_current_footer_block( 'core/navigation' );
	terminal_footer_check( $original['innerBlocks'] === $inline['innerBlocks'], 'Inline links, labels and link targets must survive too.' );
	terminal_footer_check( 'never' === $inline['attrs']['overlayMenu'] && 'Menu do rodapé' === $inline['attrs']['ariaLabel'], 'Footer links must wrap visibly with an accessible landmark name.' );
	$fixture->content = '<!-- wp:social-links --><ul class="wp-block-social-links"><!-- wp:social-link {"url":"https://example.org/profile","service":"wordpress","label":"Custom profile"} /--></ul><!-- /wp:social-links -->';
	terminal_footer_check( $fixture->content === terminal_footer_pattern_content( 'social-links' ), 'Social profile URLs, services and accessible labels must stay intact.' );
	$fixture->content = '';
	$empty = parse_blocks( terminal_blog_dev_footer_navigation() )[0];
	terminal_footer_check( empty( $empty['attrs']['ref'] ) && 'core/page-list' === $empty['innerBlocks'][0]['blockName'], 'A new footer must use native pages, not an arbitrary header menu or invented legal URL.' );
	terminal_footer_check( '' === terminal_footer_pattern_content( 'social-links' ), 'An unconfigured footer must not invent profiles.' );
	$fixture->content = '<!-- wp:navigation {"ref":2147483647} /-->';
	terminal_footer_check( 'core/page-list' === parse_blocks( terminal_blog_dev_footer_navigation() )[0]['innerBlocks'][0]['blockName'], 'An unavailable saved navigation must fall back safely.' );
	$fixture->source = 'theme';
	terminal_footer_check( null === terminal_blog_dev_current_footer_block( 'core/navigation' ), 'Theme defaults must not read themselves recursively as custom content.' );
} finally {
	remove_filter( 'pre_get_block_template', $override, 10 );
	$registry->unregister( $bad['name'] );
	if ( $types->is_registered( 'terminal-footer-test/account' ) ) {
		$types->unregister( 'terminal-footer-test/account' );
	}
}

echo 'Passed ' . $GLOBALS['terminal_footer_checks'] . " footer checks.\n";
