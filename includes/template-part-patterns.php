<?php
/**
 * Keep header and footer choices usable without optional plugin blocks.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find a saved menu reference without guessing from other menus on the site.
 *
 * @param array $blocks Parsed header blocks.
 * @return int Navigation post ID, or zero when no menu has been selected.
 */
function terminal_blog_dev_header_menu_ref( $blocks ) {
	foreach ( $blocks as $block ) {
		if ( 'core/navigation' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) {
			return absint( $block['attrs']['ref'] );
		}
		$ref = terminal_blog_dev_header_menu_ref( $block['innerBlocks'] );
		if ( $ref ) {
			return $ref;
		}
	}
	return 0;
}

/**
 * Reuse the customized header's menu when choosing another header layout.
 *
 * @return int Valid saved navigation post ID, or zero for a page-list fallback.
 */
function terminal_blog_dev_current_header_menu() {
	$header = get_block_template( get_stylesheet() . '//header', 'wp_template_part' );
	if ( ! $header || 'custom' !== $header->source ) {
		return 0;
	}
	$ref = terminal_blog_dev_header_menu_ref( parse_blocks( $header->content ) );
	return $ref && 'wp_navigation' === get_post_type( $ref ) && 'publish' === get_post_status( $ref ) ? $ref : 0;
}

/**
 * Check nested blocks and referenced patterns against the site's block registry.
 *
 * @param array $blocks Parsed blocks.
 * @param array $seen   Pattern slugs already traversed on this branch.
 * @return bool Whether the content requires an unavailable block or pattern.
 */
function terminal_blog_dev_template_part_missing_blocks( $blocks, $seen = array() ) {
	$types    = WP_Block_Type_Registry::get_instance();
	$patterns = WP_Block_Patterns_Registry::get_instance();
	foreach ( $blocks as $block ) {
		$name = $block['blockName'];
		if ( $name && ! $types->is_registered( $name ) ) {
			return true;
		}
		if ( 'core/pattern' === $name ) {
			$slug = $block['attrs']['slug'] ?? '';
			if ( ! $patterns->is_registered( $slug ) || in_array( $slug, $seen, true ) ) {
				return true;
			}
			$pattern = $patterns->get_registered( $slug );
			if ( terminal_blog_dev_template_part_missing_blocks( parse_blocks( $pattern['content'] ), array_merge( $seen, array( $slug ) ) ) ) {
				return true;
			}
		}
		if ( terminal_blog_dev_template_part_missing_blocks( $block['innerBlocks'], $seen ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Filter suggestions only; existing templates and plugin registrations stay intact.
 *
 * @param array $patterns Pattern definitions from the registry or REST API.
 * @return array Compatible header/footer choices and unchanged other patterns.
 */
function terminal_blog_dev_compatible_template_part_patterns( $patterns ) {
	return array_values( array_filter( $patterns, static function ( $pattern ) {
		if ( ! is_array( $pattern ) ) {
			return true;
		}
		if ( ! isset( $pattern['content'], $pattern['categories'] ) && isset( $pattern['name'] ) ) {
			$registered = WP_Block_Patterns_Registry::get_instance()->get_registered( $pattern['name'] );
			$pattern    = array_merge( $registered ?? array(), $pattern );
		}
		$block_types = array_merge( $pattern['blockTypes'] ?? array(), $pattern['block_types'] ?? array() );
		$is_part     = array_intersect( array( 'header', 'footer' ), $pattern['categories'] ?? array() ) || array_intersect( array( 'core/template-part/header', 'core/template-part/footer' ), $block_types );
		return ! $is_part || ! terminal_blog_dev_template_part_missing_blocks( parse_blocks( $pattern['content'] ?? '' ) );
	} ) );
}

/**
 * Support editors that still receive registered patterns in their settings.
 *
 * @param array $settings Block editor settings.
 * @return array Settings with compatible header and footer suggestions.
 */
function terminal_blog_dev_template_part_editor_settings( $settings ) {
	if ( isset( $settings['__experimentalBlockPatterns'] ) ) {
		$settings['__experimentalBlockPatterns'] = terminal_blog_dev_compatible_template_part_patterns( $settings['__experimentalBlockPatterns'] );
	}
	return $settings;
}
add_filter( 'block_editor_settings_all', 'terminal_blog_dev_template_part_editor_settings', 100 );

/**
 * Filter the final collection so late-loaded hosting patterns are checked too.
 *
 * @param WP_HTTP_Response $response REST response.
 * @param WP_REST_Server   $server   REST server.
 * @param WP_REST_Request  $request  REST request.
 * @return WP_HTTP_Response Unchanged response or compatible pattern collection.
 */
function terminal_blog_dev_template_part_pattern_response( $response, $server, $request ) {
	if ( $response instanceof WP_HTTP_Response &&
		'/wp/v2/block-patterns/patterns' === untrailingslashit( $request->get_route() ) &&
		200 === $response->get_status() && is_array( $response->get_data() ) ) {
		$response->set_data( terminal_blog_dev_compatible_template_part_patterns( $response->get_data() ) );
	}
	return $response;
}
add_filter( 'rest_post_dispatch', 'terminal_blog_dev_template_part_pattern_response', 100, 3 );
