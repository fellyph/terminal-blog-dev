<?php
/**
 * Markdown representation of the same template WordPress selected for HTML.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) || empty( $GLOBALS['terminal_blog_dev_html_template'] ) || ! is_string( $GLOBALS['terminal_blog_dev_html_template'] ) ) {
	exit;
}

$terminal_blog_dev_normalize     = static function ( $path ) {
	return function_exists( 'wp_normalize_path' ) ? wp_normalize_path( $path ) : str_replace( '\\', '/', $path );
};
$terminal_blog_dev_template_path = realpath( $GLOBALS['terminal_blog_dev_html_template'] );
$terminal_blog_dev_abspath       = realpath( ABSPATH );
$terminal_blog_dev_content_dir   = defined( 'WP_CONTENT_DIR' ) ? realpath( WP_CONTENT_DIR ) : false;
$terminal_blog_dev_in_abspath    = $terminal_blog_dev_template_path && $terminal_blog_dev_abspath && str_starts_with( $terminal_blog_dev_normalize( $terminal_blog_dev_template_path ), trailingslashit( $terminal_blog_dev_normalize( $terminal_blog_dev_abspath ) ) );
$terminal_blog_dev_in_content    = $terminal_blog_dev_template_path && $terminal_blog_dev_content_dir && str_starts_with( $terminal_blog_dev_normalize( $terminal_blog_dev_template_path ), trailingslashit( $terminal_blog_dev_normalize( $terminal_blog_dev_content_dir ) ) );

if ( ! $terminal_blog_dev_template_path || ! is_readable( $terminal_blog_dev_template_path ) || ( ! $terminal_blog_dev_in_abspath && ! $terminal_blog_dev_in_content ) ) {
	exit;
}

require_once __DIR__ . '/class-terminal-blog-dev-markdown.php';

ob_start();
// The path comes exclusively from WordPress's template_include filter.
include $terminal_blog_dev_template_path;
$terminal_blog_dev_html = ob_get_clean();

$terminal_blog_dev_url = is_singular() ? get_permalink() : home_url( '/' );
$terminal_blog_dev_markdown = new Terminal_Blog_Dev_Markdown( $terminal_blog_dev_url );
// Output is a text/markdown representation, not an HTML document.
echo $terminal_blog_dev_markdown->convert( $terminal_blog_dev_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
