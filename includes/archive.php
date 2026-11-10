<?php
/**
 * Accessibility and localization for the archive presentation.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The folder is decorative; keep its sprite frames out of the reading order.
 *
 * @param string $content Rendered image block.
 * @param array  $block   Parsed block attributes.
 * @return string Image markup with the decorative figure hidden to AT.
 */
function terminal_blog_dev_archive_art_accessibility( $content, $block ) {
	$classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '' );
	if ( ! in_array( 'terminal-archive__art', $classes, true ) ) {
		return $content;
	}
	$processor = new WP_HTML_Tag_Processor( $content );
	if ( $processor->next_tag( 'FIGURE' ) ) {
		$processor->set_attribute( 'aria-hidden', 'true' );
	}
	return $processor->get_updated_html();
}
add_filter( 'render_block_core/image', 'terminal_blog_dev_archive_art_accessibility', 10, 2 );

/**
 * Localize Yoast's old default "Term Archives" template, preserving custom SEO
 * titles, term names, pagination, separators and the site's name.
 *
 * @param string $title Generated document or social title.
 * @return string Localized legacy title, or the original custom title.
 */
function terminal_blog_dev_archive_seo_title( $title ) {
	if ( ! is_category() && ! is_tag() && ! is_tax() ) {
		return $title;
	}
	$term = single_term_title( '', false );
	$prefix = $term . ' Archives';
	if ( ! $term || ! str_starts_with( $title, $prefix ) ) {
		return $title;
	}
	$suffix = substr( $title, strlen( $prefix ) );
	if ( '' !== $suffix && ! preg_match( '/^\s+(?:[-–—|·]|(?:Page|Página)\b)/u', $suffix ) ) {
		return $title;
	}
	/* translators: %s: Name of the category, tag, or taxonomy term. */
	return sprintf( __( 'Arquivo de %s', 'terminal-blog-dev' ), $term ) . $suffix;
}
add_filter( 'wpseo_title', 'terminal_blog_dev_archive_seo_title' );
add_filter( 'wpseo_opengraph_title', 'terminal_blog_dev_archive_seo_title' );
add_filter( 'wpseo_twitter_title', 'terminal_blog_dev_archive_seo_title' );
