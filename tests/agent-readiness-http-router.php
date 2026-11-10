<?php
/** Isolated HTTP fixture; never boots the real site or its database. */
if ( ! in_array( PHP_SAPI, array( 'cli-server', 'cgi-fcgi' ), true ) ) {
	exit( 1 );
}
require __DIR__ . '/agent-readiness-bootstrap.php';

$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$GLOBALS['request_fixture'] = array( 'front' => true );
$post = new WP_Post( (object) array( 'post_type' => 'post', 'post_status' => 'publish', 'post_password' => '' ) );
if ( in_array( $path, array( '/article/', '/protected/', '/private/', '/draft/', '/plugin/' ), true ) ) {
	$GLOBALS['request_fixture'] = array( 'singular' => true, 'post' => $post );
}
if ( '/protected/' === $path ) { $post->post_password = 'secret'; }
if ( '/private/' === $path ) { $post->post_status = 'private'; }
if ( '/draft/' === $path ) { $post->post_status = 'draft'; }
if ( '/plugin/' === $path ) { $post->post_type = 'private_trip'; }
if ( '/logged-in/' === $path ) { $GLOBALS['request_fixture']['logged_in'] = true; }
if ( '/preview/' === $path ) { $GLOBALS['request_fixture']['preview'] = true; }
if ( '/feed/' === $path ) { $GLOBALS['request_fixture']['feed'] = true; }
if ( '/rest/' === $path ) { define( 'REST_REQUEST', true ); }
if ( '/disabled/' === $path ) {
	add_filter( 'terminal_blog_dev_markdown_enabled', static function () { return false; } );
}

header( 'Content-Type: text/html; charset=UTF-8' );
header( 'Vary: ' . ( '/vary-star/' === $path ? '*' : 'Accept-Encoding' ) );
if ( '/vary-multiple/' === $path ) { header( 'Vary: Cookie', false ); }
header( 'Link: <https://example.test/font.woff2>; rel="preload"; as="font"' );
terminal_blog_dev_agent_headers();
if ( 'HEAD' !== $_SERVER['REQUEST_METHOD'] ) {
	include terminal_blog_dev_markdown_template( __DIR__ . '/fixtures/agent-page.php' );
}
