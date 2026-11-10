<?php
/**
 * Markdown representation of the same template WordPress selected for HTML.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) || empty( $GLOBALS['terminal_blog_dev_html_template'] ) ) {
	exit;
}

require_once __DIR__ . '/class-terminal-blog-dev-markdown.php';

ob_start();
// The path comes exclusively from WordPress's template_include filter.
include $GLOBALS['terminal_blog_dev_html_template'];
$terminal_blog_dev_html = ob_get_clean();

$terminal_blog_dev_url = is_singular() ? get_permalink() : home_url( '/' );
$terminal_blog_dev_markdown = new Terminal_Blog_Dev_Markdown( $terminal_blog_dev_url );
// Output is a text/markdown representation, not an HTML document.
echo $terminal_blog_dev_markdown->convert( $terminal_blog_dev_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
