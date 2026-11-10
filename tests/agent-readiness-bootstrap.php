<?php
/**
 * Lightweight request fixtures using the installed WordPress HTML and URL APIs.
 * No database, WordPress configuration, or plugins are loaded.
 */

define( 'ABSPATH', rtrim( getenv( 'WP_TEST_CORE_DIR' ) ?: dirname( __DIR__, 4 ), '/' ) . '/' );
define( 'WPINC', 'wp-includes' );

if ( ! is_file( ABSPATH . WPINC . '/formatting.php' ) ) {
	fwrite( STDERR, "Set WP_TEST_CORE_DIR to a WordPress installation to run these tests.\n" );
	exit( 1 );
}

spl_autoload_register( static function ( $class ) {
	$file = 'class-' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
	foreach ( array( ABSPATH . WPINC . '/', ABSPATH . WPINC . '/html-api/' ) as $directory ) {
		if ( is_file( $directory . $file ) ) {
			require_once $directory . $file;
			return;
		}
	}
} );

require_once ABSPATH . WPINC . '/compat.php';
require_once ABSPATH . WPINC . '/plugin.php';
require_once ABSPATH . WPINC . '/formatting.php';
if ( is_file( ABSPATH . WPINC . '/utf8.php' ) ) {
	require_once ABSPATH . WPINC . '/utf8.php';
}
require_once ABSPATH . WPINC . '/kses.php';
require_once ABSPATH . WPINC . '/http.php';

function __( $text, $domain = '' ) { return $text; }
function is_admin() { return ! empty( $GLOBALS['request_fixture']['admin'] ); }
function wp_doing_ajax() { return ! empty( $GLOBALS['request_fixture']['ajax'] ); }
function is_user_logged_in() { return ! empty( $GLOBALS['request_fixture']['logged_in'] ); }
function is_preview() { return ! empty( $GLOBALS['request_fixture']['preview'] ); }
function is_404() { return ! empty( $GLOBALS['request_fixture']['404'] ); }
function is_feed() { return ! empty( $GLOBALS['request_fixture']['feed'] ); }
function is_embed() { return ! empty( $GLOBALS['request_fixture']['embed'] ); }
function is_trackback() { return ! empty( $GLOBALS['request_fixture']['trackback'] ); }
function is_robots() { return ! empty( $GLOBALS['request_fixture']['robots'] ); }
function is_favicon() { return ! empty( $GLOBALS['request_fixture']['favicon'] ); }
function is_singular() { return ! empty( $GLOBALS['request_fixture']['singular'] ); }
function is_front_page() { return ! empty( $GLOBALS['request_fixture']['front'] ); }
function is_home() { return ! empty( $GLOBALS['request_fixture']['home'] ); }
function get_queried_object() { return $GLOBALS['request_fixture']['post'] ?? null; }
function is_post_publicly_viewable( $post ) { return 'publish' === $post->post_status; }
function get_template_directory() { return dirname( __DIR__ ); }
function get_permalink() { return 'https://example.test/article/'; }
function home_url( $path = '' ) { return 'https://example.test' . $path; }
function rest_url( $path = '' ) { return 'https://example.test/wp-json' . $path; }
function nocache_headers() { header( 'Cache-Control: no-store, no-cache, must-revalidate, private' ); header_remove( 'Last-Modified' ); }

require_once dirname( __DIR__ ) . '/includes/agent-readiness.php';
require_once dirname( __DIR__ ) . '/includes/class-terminal-blog-dev-markdown.php';
