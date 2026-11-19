<?php
/**
 * Carry footer links into the theme's alternative layouts.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find an existing block without choosing content from another template part.
 *
 * @param array  $blocks Parsed blocks.
 * @param string $name   Block type to find.
 * @return array|null First matching block, or null.
 */
function terminal_blog_dev_find_footer_block( $blocks, $name ) {
	foreach ( $blocks as $block ) {
		if ( $name === $block['blockName'] ) {
			return $block;
		}
		$found = terminal_blog_dev_find_footer_block( $block['innerBlocks'], $name );
		if ( $found ) {
			return $found;
		}
	}
	return null;
}

/**
 * Read saved footer content only; never fall back to a header menu or profiles.
 *
 * @param string $name Block type to find.
 * @return array|null Saved block, or null when the footer has no customization.
 */
function terminal_blog_dev_current_footer_block( $name ) {
	$footer = get_block_template( get_stylesheet() . '//footer', 'wp_template_part' );
	return $footer && 'custom' === $footer->source
		? terminal_blog_dev_find_footer_block( parse_blocks( $footer->content ), $name )
		: null;
}

/**
 * Retain a selected menu or inline links; use native pages for a new footer.
 *
 * @return string Editable navigation block markup.
 */
function terminal_blog_dev_footer_navigation() {
	$navigation = terminal_blog_dev_current_footer_block( 'core/navigation' );
	$attributes = array(
		'ariaLabel'   => __( 'Menu do rodapé', 'terminal-blog-dev' ),
		'overlayMenu' => 'never',
		'textColor'   => 'foreground-muted',
		'fontSize'    => 'small',
		'className'   => 'terminal-footer__navigation',
		'layout'      => array( 'type' => 'flex', 'justifyContent' => 'left', 'flexWrap' => 'wrap' ),
	);
	$ref = absint( $navigation['attrs']['ref'] ?? 0 );
	if ( $ref && 'wp_navigation' === get_post_type( $ref ) && 'publish' === get_post_status( $ref ) ) {
		$attributes['ref'] = $ref;
		return '<!-- wp:navigation ' . wp_json_encode( $attributes, JSON_HEX_TAG | JSON_HEX_AMP ) . ' /-->';
	}
	$links = ! $ref && ! empty( $navigation['innerBlocks'] )
		? serialize_blocks( $navigation['innerBlocks'] )
		: '<!-- wp:page-list /-->';
	return '<!-- wp:navigation ' . wp_json_encode( $attributes, JSON_HEX_TAG | JSON_HEX_AMP ) . ' -->' . $links . '<!-- /wp:navigation -->';
}
