<?php
/**
 * Discovery and alternate representations of the theme's public content.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Negotiate Markdown only when explicitly requested and preferred to HTML.
 * An exact media range overrides a wildcard, including when its quality is zero.
 *
 * @param string $accept Accept request header.
 * @return bool Whether Markdown is preferred.
 */
function terminal_blog_dev_prefers_markdown( $accept ) {
	$matches = array( 'text/html' => array( -1, 0.0 ), 'text/markdown' => array( -1, 0.0 ) );
	foreach ( explode( ',', strtolower( $accept ) ) as $range ) {
		$parts   = array_map( 'trim', explode( ';', $range ) );
		$media   = array_shift( $parts );
		$quality = 1.0;
		foreach ( $parts as $parameter ) {
			if ( preg_match( '/^q\s*=\s*(.*)$/', $parameter, $match ) ) {
				$quality = preg_match( '/^(?:0(?:\.[0-9]{0,3})?|1(?:\.0{0,3})?)$/', $match[1] ) ? (float) $match[1] : 0.0;
				break;
			}
		}
		foreach ( $matches as $type => $best ) {
			$specificity = $media === $type ? 2 : ( 'text/*' === $media ? 1 : ( '*/*' === $media ? 0 : -1 ) );
			if ( $specificity < 0 ) {
				continue;
			}
			if ( $specificity > $best[0] || ( $specificity === $best[0] && $quality > $best[1] ) ) {
				$matches[ $type ] = array( $specificity, $quality );
			}
		}
	}
	$html     = $matches['text/html'];
	$markdown = $matches['text/markdown'];
	return 2 === $markdown[0] && $markdown[1] > 0 &&
		( $markdown[1] > $html[1] || ( $markdown[1] === $html[1] && $markdown[0] > $html[0] ) );
}

/**
 * Limit alternate representations to anonymous, public editorial views.
 *
 * @return bool Whether the current request can have a Markdown representation.
 */
function terminal_blog_dev_is_public_content_request() {
	$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
	if ( ! in_array( $method, array( 'GET', 'HEAD' ), true ) || is_admin() || wp_doing_ajax() ||
		( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_user_logged_in() ||
		is_preview() || is_404() || is_feed() || is_embed() || is_trackback() || is_robots() || is_favicon() ) {
		return false;
	}
	if ( is_singular() ) {
		$post = get_queried_object();
		return $post instanceof WP_Post && in_array( $post->post_type, array( 'post', 'page' ), true ) &&
			is_post_publicly_viewable( $post ) && '' === $post->post_password;
	}
	return is_front_page() || is_home();
}

/**
 * Keep existing Vary fields, including the special wildcard value.
 */
function terminal_blog_dev_vary_accept() {
	$fields = array();
	foreach ( headers_list() as $header ) {
		if ( 0 === stripos( $header, 'Vary:' ) ) {
			$fields = array_merge( $fields, array_map( 'trim', explode( ',', substr( $header, 5 ) ) ) );
		}
	}
	if ( in_array( '*', $fields, true ) || in_array( 'accept', array_map( 'strtolower', $fields ), true ) ) {
		return;
	}
	$fields[] = 'Accept';
	header( 'Vary: ' . implode( ', ', array_filter( array_unique( $fields ) ) ) );
}

/**
 * Advertise existing API resources and set representation headers before HEAD exits.
 */
function terminal_blog_dev_agent_headers() {
	if ( headers_sent() || ! terminal_blog_dev_is_public_content_request() ) {
		return;
	}
	if ( is_front_page() ) {
		$api_url = esc_url_raw( rest_url( '/' ), array( 'http', 'https' ) );
		if ( '' !== $api_url ) {
			header( 'Link: <' . $api_url . '>; rel="service-desc"; type="application/json"', false );
			header( 'Link: <https://developer.wordpress.org/rest-api/reference/>; rel="service-doc"; type="text/html"', false );
		}
	}
	if ( ! class_exists( 'DOMDocument' ) || ! apply_filters( 'terminal_blog_dev_markdown_enabled', true ) ) {
		return;
	}
	terminal_blog_dev_vary_accept();
	// Accept is used only for media negotiation, never echoed into a response.
	$accept = isset( $_SERVER['HTTP_ACCEPT'] ) ? wp_unslash( $_SERVER['HTTP_ACCEPT'] ) : '';
	if ( terminal_blog_dev_prefers_markdown( $accept ) ) {
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
		nocache_headers();
		header( 'Content-Type: text/markdown; charset=UTF-8' );
		header( 'X-Content-Type-Options: nosniff' );
	}
}
add_action( 'template_redirect', 'terminal_blog_dev_agent_headers', 20 );

/**
 * Render the selected HTML template through a Markdown presentation template.
 *
 * @param string $template Selected WordPress template.
 * @return string Template path.
 */
function terminal_blog_dev_markdown_template( $template ) {
	$accept = isset( $_SERVER['HTTP_ACCEPT'] ) ? wp_unslash( $_SERVER['HTTP_ACCEPT'] ) : '';
	if ( class_exists( 'DOMDocument' ) && apply_filters( 'terminal_blog_dev_markdown_enabled', true ) &&
		terminal_blog_dev_is_public_content_request() && terminal_blog_dev_prefers_markdown( $accept ) ) {
		$GLOBALS['terminal_blog_dev_html_template'] = $template;
		return get_template_directory() . '/includes/markdown-template.php';
	}
	return $template;
}
add_filter( 'template_include', 'terminal_blog_dev_markdown_template', 99 );

/**
 * Expose one existing GET search form per page without a second search backend.
 * Native form navigation remains the execution path for both people and agents.
 *
 * @param string $html Search block markup.
 * @return string Search block with declarative WebMCP attributes.
 */
function terminal_blog_dev_search_tool( $html ) {
	static $registered = false;
	if ( $registered || is_admin() ) {
		return $html;
	}
	$form = new WP_HTML_Tag_Processor( $html );
	if ( ! $form->next_tag( 'form' ) || null !== $form->get_attribute( 'toolname' ) ||
		( $form->get_attribute( 'method' ) && 'get' !== strtolower( $form->get_attribute( 'method' ) ) ) ) {
		return $html;
	}
	$input = new WP_HTML_Tag_Processor( $html );
	$found = false;
	while ( $input->next_tag( 'input' ) ) {
		if ( 's' === $input->get_attribute( 'name' ) ) {
			$input->set_attribute( 'toolparamdescription', __( 'Palavras ou assunto dos artigos que deseja encontrar.', 'terminal-blog-dev' ) );
			$found = true;
			break;
		}
	}
	if ( ! $found ) {
		return $html;
	}
	$form = new WP_HTML_Tag_Processor( $input->get_updated_html() );
	$form->next_tag( 'form' );
	$form->set_attribute( 'toolname', 'search_blog' );
	$form->set_attribute( 'tooldescription', __( 'Pesquisa artigos publicados no blog e abre os resultados. Use para encontrar tutoriais ou publicações por assunto.', 'terminal-blog-dev' ) );
	$form->set_attribute( 'toolautosubmit', true );
	$registered = true;
	return $form->get_updated_html();
}
add_filter( 'render_block_core/search', 'terminal_blog_dev_search_tool' );
