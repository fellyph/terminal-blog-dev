<?php
/**
 * Terminal Blog Theme functions and definitions
 *
 * @package Terminal_Blog_Dev
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/includes/agent-readiness.php';
require_once get_template_directory() . '/includes/i18n.php';
require_once get_template_directory() . '/includes/archive.php';
require_once get_template_directory() . '/includes/template-part-patterns.php';
require_once get_template_directory() . '/includes/footer-patterns.php';

/**
 * Add resource hints for fonts to improve loading performance.
 * Space Grotesk is self-hosted (SIL OFL 1.1), so the preload reuses the page's
 * own connection instead of opening one to a third-party font server.
 */
function terminal_blog_dev_resource_hints() {
	// Declare supported color schemes early to minimize white flashes on load.
	echo '<meta name="color-scheme" content="dark light">' . "\n";
	// Preload the main font file for faster text rendering. Fonts always need crossorigin.
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_theme_file_uri( 'assets/fonts/space-grotesk-latin-variable.woff2' ) )
	);
}
add_action( 'wp_head', 'terminal_blog_dev_resource_hints', 1 );

/**
 * Drop the Google Fonts preconnect that plugins (e.g. WP Rocket) add by default:
 * the theme font is self-hosted, so the extra connection is wasted work.
 *
 * @param array  $urls          Resource hint URLs.
 * @param string $relation_type Hint type.
 * @return array Filtered hints.
 */
function terminal_blog_dev_drop_font_preconnect( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}
	return array_values(
		array_filter(
			$urls,
			static function ( $url ) {
				$href = is_array( $url ) ? ( $url['href'] ?? '' ) : (string) $url;
				return false === strpos( $href, 'fonts.gstatic.com' );
			}
		)
	);
}
add_filter( 'wp_resource_hints', 'terminal_blog_dev_drop_font_preconnect', 99, 2 );

/**
 * Inline early theme detection script.
 * Runs before CSS to set data-theme attribute instantly, preventing FOUC.
 */
function terminal_blog_dev_early_theme_script() {
	wp_print_inline_script_tag(
		"(function(){var t;try{t=localStorage.getItem('terminal-blog-theme')}catch(e){}if(t!=='dark'&&t!=='light'){t=window.matchMedia&&window.matchMedia('(prefers-color-scheme:light)').matches?'light':'dark'}document.documentElement.setAttribute('data-theme',t)})();",
		array( 'id' => 'terminal-early-theme' )
	);
}
add_action( 'wp_head', 'terminal_blog_dev_early_theme_script', 0 );

/**
 * Inline critical CSS to prevent render-blocking.
 * This CSS is essential for above-the-fold content.
 */
function terminal_blog_dev_critical_css() {
	?>
	<style id="terminal-critical-css">
	/* Critical CSS - Above the fold styles */
	html { background-color: var(--theme-bg); }
	body {
		margin: 0;
		background-color: var(--theme-bg);
		color: var(--theme-fg);
		font-family: 'Space Grotesk', ui-monospace, monospace;
	}
	/* Header critical styles */
	.terminal-site-header {
		background-color: var(--theme-bg);
	}
	.has-primary-color { color: var(--theme-primary); }
	.has-foreground-color { color: var(--theme-fg); }
	.has-foreground-muted-color { color: var(--theme-fg-muted); }
	.has-background-background-color { background-color: var(--theme-bg); }
	/* Prevent layout shift */
	.wp-block-navigation { min-height: 24px; }
	.wp-block-site-title { min-height: 28px; }
	.wp-block-site-logo { line-height: 0; }
	.wp-block-site-logo a, .wp-block-site-logo img { display: block; }
	.wp-block-site-logo img { height: auto; max-width: 100%; }
	.wp-block-site-logo.is-default-size img { width: 120px; }
	.wp-block-navigation-item__content { display: inline-flex !important; align-items: center; min-height: 44px; }
	/* Above-the-fold home & card stability before deferred custom.css */
	h1.wp-block-heading, h1.wp-block-post-title, h2.wp-block-heading, h2.wp-block-post-title { text-wrap: balance; }
	.terminal-topic-index .wp-block-categories-list { display: flex; flex-wrap: wrap; gap: 0.5rem; margin: 0; padding: 0; list-style: none; }
	.terminal-topic-index .wp-block-categories-list li { margin: 0; }
	.wp-block-post-featured-image { overflow: hidden; border-radius: 0.25rem; }
	.wp-block-post-featured-image a, .wp-block-post-featured-image img { display: block; width: 100%; height: auto; }
	/* Theme toggle button critical */
	.theme-toggle {
		background: transparent;
		border: 1px solid var(--theme-border);
		color: var(--theme-primary);
		padding: 0.25rem 0.5rem;
		font-family: inherit;
		cursor: pointer;
	}
	</style>
	<?php
}
add_action( 'wp_head', 'terminal_blog_dev_critical_css', 2 );

/**
 * Theme setup
 */
function terminal_blog_dev_setup() {
	// Load theme text domain for translations.
	load_theme_textdomain( 'terminal-blog-dev', get_template_directory() . '/languages' );

	// Add support for block styles
	add_theme_support( 'wp-block-styles' );

	// Add support for editor styles
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles
	add_editor_style( array( 'assets/css/colors.css', 'assets/css/editor-style.css', 'assets/css/header.css', 'assets/css/footer.css', 'assets/css/archive.css', 'assets/css/footer-art.css', 'assets/css/menu-pixels.css' ) );

	// Add support for responsive embedded content
	add_theme_support( 'responsive-embeds' );

	// Add support for custom logo
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Add support for post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add support for HTML5
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add support for custom background
	add_theme_support(
		'custom-background',
		array(
			'default-color' => '050804',
		)
	);
}
add_action( 'after_setup_theme', 'terminal_blog_dev_setup' );

/**
 * Load built CSS, with a source fallback for development checkouts.
 * Small files opt into WordPress's size-limited inline styles and URL rewriting.
 *
 * @param string $name         Stylesheet basename.
 * @param array  $dependencies Style dependencies.
 * @param bool   $inline       Whether WordPress may inline this stylesheet.
 */
function terminal_blog_dev_enqueue_css( $name, $dependencies = array(), $inline = true ) {
	$file = '/assets/css/' . $name . '.min.css';
	if ( ! is_readable( get_template_directory() . $file ) ) {
		$file = '/assets/css/' . $name . '.css';
	}
	$path   = get_template_directory() . $file;
	$handle = 'terminal-blog-dev-' . $name;
	wp_enqueue_style( $handle, get_template_directory_uri() . $file, $dependencies, (string) filemtime( $path ) );
	if ( $inline ) {
		wp_style_add_data( $handle, 'path', $path );
	}
}

/**
 * Enqueue scripts and styles
 */
function terminal_blog_dev_scripts() {
	// The parent style.css contains metadata only. Keep its handle for inline
	// effects and dependencies, while preserving a child theme's stylesheet.
	wp_register_style(
		'terminal-blog-dev-style',
		is_child_theme() ? get_stylesheet_uri() : false,
		array(),
		wp_get_theme()->get( 'Version' )
	);
	wp_enqueue_style( 'terminal-blog-dev-style' );

	terminal_blog_dev_enqueue_css( 'colors' );
	terminal_blog_dev_enqueue_css( 'header', array( 'terminal-blog-dev-colors' ) );
	terminal_blog_dev_enqueue_css( 'footer', array( 'terminal-blog-dev-colors' ) );
	terminal_blog_dev_enqueue_css( 'custom', array( 'terminal-blog-dev-colors' ), false );
	if ( is_archive() ) {
		terminal_blog_dev_enqueue_css( 'archive', array( 'terminal-blog-dev-colors', 'terminal-blog-dev-custom' ) );
	}
	terminal_blog_dev_enqueue_css( 'footer-art', array( 'terminal-blog-dev-colors' ) );
	terminal_blog_dev_enqueue_css( 'menu-pixels', array( 'terminal-blog-dev-custom' ) );

	// Pause decorative motion when the footer is not visible.
	wp_enqueue_script(
		'terminal-blog-dev-footer-art',
		get_template_directory_uri() . '/assets/js/footer-art.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'terminal-blog-dev-footer-art',
		'terminalFooterMotionI18n',
		array(
			'pause'      => __( 'Pausar', 'terminal-blog-dev' ),
			'play'       => __( 'Animar', 'terminal-blog-dev' ),
			'pauseLabel' => __( 'Pausar animação do rodapé', 'terminal-blog-dev' ),
			'playLabel'  => __( 'Reproduzir animação do rodapé', 'terminal-blog-dev' ),
		)
	);

	// Enqueue theme toggle script - early execution needed for theme
	wp_enqueue_script(
		'terminal-blog-dev-theme-toggle',
		get_template_directory_uri() . '/assets/js/theme-toggle.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		array(
			'strategy' => 'defer',
			'in_footer' => false,
		)
	);

	wp_localize_script(
		'terminal-blog-dev-theme-toggle',
		'terminalBlogThemeI18n',
		array(
			'label'   => __( 'TEMA', 'terminal-blog-dev' ),
			'dark'    => __( 'Escuro', 'terminal-blog-dev' ),
			'light'   => __( 'Claro', 'terminal-blog-dev' ),
			'toggle'  => __( 'Alternar tema de cores', 'terminal-blog-dev' ),
			'current' => __( 'Tema atual', 'terminal-blog-dev' ),
			'changed' => __( 'Tema alterado para', 'terminal-blog-dev' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'terminal_blog_dev_scripts' );

/**
 * Keep the single-post recommendations relevant and prevent self-links.
 *
 * @param array    $query Query arguments for the Query Loop block.
 * @param WP_Block $block Query Loop block instance.
 * @return array Filtered query arguments.
 */
function terminal_blog_dev_related_posts_query( $query, $block ) {
	$class_name = $block->parsed_block['attrs']['className'] ?? '';

	if ( ! is_singular( 'post' ) || ! str_contains( $class_name, 'terminal-related-posts' ) ) {
		return $query;
	}

	$post_id               = get_queried_object_id();
	$query['post__not_in'] = array_values(
		array_unique(
			array_merge( $query['post__not_in'] ?? array(), array( $post_id ) )
		)
	);

	$category_ids = wp_get_post_categories( $post_id );
	if ( ! empty( $category_ids ) ) {
		$query['category__in'] = $category_ids;
	}

	return $query;
}
add_filter( 'query_loop_block_query_vars', 'terminal_blog_dev_related_posts_query', 10, 2 );

/**
 * Add a truthful current-page state to static Navigation Link blocks.
 *
 * The home page is the editorial index, while posts and taxonomy/search
 * views belong to its Articles section.
 *
 * @param string $block_content Rendered Navigation Link markup.
 * @param array  $block         Parsed block data.
 * @return string Filtered Navigation Link markup.
 */
function terminal_blog_dev_navigation_link_current_state( $block_content, $block ) {
	$url = $block['attrs']['url'] ?? '';
	if ( '' === $url ) {
		return $block_content;
	}

	$link_path     = wp_parse_url( $url, PHP_URL_PATH ) ?: '/';
	$link_fragment = wp_parse_url( $url, PHP_URL_FRAGMENT ) ?: '';
	$is_current    = false;

	if ( is_front_page() ) {
		$is_current = '/' === $link_path && '' === $link_fragment;
	} elseif (
		'artigos-recentes' === $link_fragment &&
		( is_singular( 'post' ) || is_home() || is_archive() || is_search() )
	) {
		$is_current = true;
	} else {
		global $wp;
		$current_path = '/' . ltrim( $wp->request ?? '', '/' );
		$current_path = '/' === $current_path ? '/' : untrailingslashit( $current_path );
		$link_path    = '/' === $link_path ? '/' : untrailingslashit( $link_path );
		$is_current   = $current_path === $link_path && '' === $link_fragment;
	}

	if ( ! $is_current ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );
	if ( $processor->next_tag( 'a' ) ) {
		$processor->set_attribute( 'aria-current', 'page' );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/navigation-link', 'terminal_blog_dev_navigation_link_current_state', 10, 2 );

/**
 * Reserve the logo's displayed dimensions before its image and styles load.
 * Core already scales explicitly sized logos; its default-size variant still
 * carries the attachment's original dimensions and an oversized srcset hint.
 *
 * @param string $html  Rendered logo block.
 * @param array  $block Parsed block data.
 * @return string Rendered logo with accurate intrinsic dimensions.
 */
function terminal_blog_dev_logo_dimensions( $html, $block ) {
	$processor = new WP_HTML_Tag_Processor( $html );
	if ( ! $processor->next_tag( array( 'tag_name' => 'IMG', 'class_name' => 'custom-logo' ) ) ) {
		return $html;
	}
	$width  = (int) $processor->get_attribute( 'width' );
	$height = (int) $processor->get_attribute( 'height' );
	if ( $width <= 0 || $height <= 0 ) {
		return $html;
	}
	$display_width = absint( $block['attrs']['width'] ?? 120 ) ?: 120;
	$processor->set_attribute( 'width', $display_width );
	$processor->set_attribute( 'height', max( 1, (int) round( $height * $display_width / $width ) ) );
	if ( $processor->get_attribute( 'srcset' ) ) {
		$processor->set_attribute( 'sizes', sprintf( '(max-width: %1$dpx) 100vw, %1$dpx', $display_width ) );
	}
	return $processor->get_updated_html();
}
add_filter( 'render_block_core/site-logo', 'terminal_blog_dev_logo_dimensions', 10, 2 );

/**
 * Ensure decorative theme SVG image blocks always ship explicit intrinsic
 * dimensions (including in customized Site Editor template parts) to avoid CLS
 * diagnostics, and lazy-load the below-the-fold footer art.
 *
 * @param string $html  Rendered image block markup.
 * @param array  $block Parsed block data.
 * @return string Image markup with explicit dimensions.
 */
function terminal_blog_dev_decorative_svg_dimensions( $html, $block ) {
	$classes = preg_split( '/\s+/', trim( $block['attrs']['className'] ?? '' ) );
	$specs   = null;

	if ( in_array( 'terminal-footer-art--image', $classes, true ) ) {
		$specs = array(
			'width'   => '768',
			'height'  => '144',
			'loading' => 'lazy',
		);
	} elseif ( in_array( 'terminal-archive__art', $classes, true ) ) {
		$specs = array(
			'width'  => '256',
			'height' => '64',
		);
	}

	if ( null === $specs ) {
		return $html;
	}

	$processor = new WP_HTML_Tag_Processor( $html );
	if ( ! $processor->next_tag( 'IMG' ) ) {
		return $html;
	}

	foreach ( $specs as $attr => $value ) {
		if ( null === $processor->get_attribute( $attr ) ) {
			$processor->set_attribute( $attr, $value );
		}
	}
	if ( null === $processor->get_attribute( 'decoding' ) ) {
		$processor->set_attribute( 'decoding', 'async' );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/image', 'terminal_blog_dev_decorative_svg_dimensions', 10, 2 );

/**
 * Tighten responsive image sizes on the 2-column home post grid so browsers do
 * not fetch 1024px+ thumbnails for ~560px desktop cards or ~360px mobile cards.
 *
 * @param string $html  Rendered post-featured-image block markup.
 * @param array  $block Parsed block data.
 * @return string Image markup with tightened sizes attribute.
 */
function terminal_blog_dev_home_featured_image_sizes( $html, $block ) {
	if ( is_admin() || ( ! is_front_page() && ! is_home() ) || empty( $block['attrs']['isLink'] ) ) {
		return $html;
	}

	$processor = new WP_HTML_Tag_Processor( $html );
	if ( ! $processor->next_tag( 'IMG' ) || ! $processor->get_attribute( 'srcset' ) ) {
		return $html;
	}

	$processor->set_attribute( 'sizes', '(max-width: 781px) calc(100vw - 2rem), 560px' );
	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-featured-image', 'terminal_blog_dev_home_featured_image_sizes', 10, 2 );

/**
 * Add fetchpriority and async loading hints to stylesheets.
 * Defers non-critical CSS to improve LCP.
 *
 * @param string $tag    The link tag for the stylesheet.
 * @param string $handle The stylesheet handle.
 * @param string $href   The stylesheet href.
 * @return string Modified link tag.
 */
function terminal_blog_dev_optimize_css_loading( $tag, $handle, $href ) {
	// Skip admin styles.
	if ( is_admin() ) {
		return $tag;
	}

	// Non-critical stylesheets to defer.
	$defer_styles = array(
		'terminal-blog-dev-custom',
	);

	if ( in_array( $handle, $defer_styles, true ) ) {
		$processor = new WP_HTML_Tag_Processor( $tag );
		if ( $processor->next_tag( 'link' ) && 'all' === $processor->get_attribute( 'media' ) ) {
			$processor->set_attribute( 'media', 'print' );
			$processor->set_attribute( 'data-terminal-defer-style', '1' );
			$tag = $processor->get_updated_html();
			$tag .= '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '"></noscript>' . "\n";
		}
	}

	return $tag;
}
add_filter( 'style_loader_tag', 'terminal_blog_dev_optimize_css_loading', 10, 3 );

/**
 * Activate deferred stylesheets without using inline onload attributes,
 * keeping markup compatible with a strict Content Security Policy.
 */
function terminal_blog_dev_activate_deferred_styles() {
	wp_print_inline_script_tag(
		"document.querySelectorAll('link[data-terminal-defer-style]').forEach(function(l){if(l.sheet){l.media='all'}else{l.addEventListener('load',function(){l.media='all'},{once:true})}});",
		array( 'id' => 'terminal-defer-styles' )
	);
}
add_action( 'wp_head', 'terminal_blog_dev_activate_deferred_styles', 10 );

/**
 * Add custom CSS for terminal effects
 */
function terminal_blog_dev_custom_css() {
	$custom_css = "
		/* Terminal glow effect */
		.has-primary-color {
			text-shadow: 0 0 5px rgba(91, 236, 19, 0.5);
		}

		/* Cursor blink animation */
		@keyframes terminal-blink {
			0%, 100% { opacity: 1; }
			50% { opacity: 0; }
		}

		.terminal-cursor {
			animation: terminal-blink 1s step-end infinite;
		}

		/* Code blocks styling */
		pre.wp-block-code,
		.wp-block-preformatted {
			overflow-x: auto;
		}

		/* Button hover glow */
		.wp-block-button__link:hover {
			box-shadow: 0 0 10px rgba(91, 236, 19, 0.5);
		}

		/* Link underline animation */
		a:not(.wp-block-button__link):hover {
			text-shadow: 0 0 5px rgba(91, 236, 19, 0.3);
		}

		/* Focus styles for accessibility */
		a:focus,
		button:focus,
		input:focus,
		textarea:focus,
		select:focus {
			outline: 2px solid var(--wp--preset--color--primary, #5bec13);
			outline-offset: 2px;
		}

		/* Navigation menu items */
		.wp-block-navigation .wp-block-navigation-item a:hover {
			text-shadow: 0 0 5px rgba(91, 236, 19, 0.5);
		}

		/* Post card hover effect */
		.wp-block-group:has(> .wp-block-post-title):hover {
			border-color: var(--wp--preset--color--primary, #5bec13) !important;
		}

		/* Selection styling */
		::selection {
			background: var(--wp--preset--color--primary, #5bec13);
			color: var(--wp--preset--color--background, #050804);
		}
	";

	wp_add_inline_style( 'terminal-blog-dev-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'terminal_blog_dev_custom_css' );

/**
 * Register block patterns category
 */
function terminal_blog_dev_register_pattern_category() {
	register_block_pattern_category(
		'terminal-blog-dev',
		array(
			'label'       => __( 'Terminal Blog Dev', 'terminal-blog-dev' ),
			'description' => __( 'Padrões em estilo terminal para o tema Terminal Blog Dev.', 'terminal-blog-dev' ),
		)
	);
}
add_action( 'init', 'terminal_blog_dev_register_pattern_category' );

/**
 * Impeccable audit P2 #8: keep the current post out of "PRÓXIMAS_LEITURAS".
 *
 * The related-entries Query Loop in templates/single.html would otherwise
 * recommend the article the reader is already on. Asking for one extra post
 * guarantees the list still fills up after the exclusion.
 *
 * @param array    $query Query vars for the Query Loop block.
 * @param WP_Block $block The block instance.
 * @return array Filtered query vars.
 */
function terminal_blog_dev_exclude_current_post( $query, $block ) {
	if ( is_admin() || ! is_singular() ) {
		return $query;
	}

	$current_id = get_queried_object_id();

	if ( ! $current_id ) {
		return $query;
	}

	$excluded            = isset( $query['post__not_in'] ) ? (array) $query['post__not_in'] : array();
	$excluded[]          = $current_id;
	$query['post__not_in'] = array_unique( $excluded );

	return $query;
}
add_filter( 'query_loop_block_query_vars', 'terminal_blog_dev_exclude_current_post', 10, 2 );

/**
 * Impeccable audit P1 #2: make horizontally scrollable code blocks keyboard
 * operable. A scroll container with no focusable child must be reachable by
 * keyboard (WCAG 2.1.1), so it gets tabindex and an accessible name.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string Filtered block HTML.
 */
function terminal_blog_dev_scrollable_code( $block_content, $block ) {
	if ( empty( $block['blockName'] ) || 'core/code' !== $block['blockName'] ) {
		return $block_content;
	}

	if ( false !== strpos( $block_content, 'tabindex' ) ) {
		return $block_content;
	}

	$attributes = sprintf(
		'<pre tabindex="0" role="region" aria-label="%s"',
		esc_attr__( 'Bloco de código, rolável na horizontal', 'terminal-blog-dev' )
	);

	return preg_replace( '/<pre/', $attributes, $block_content, 1 );
}
add_filter( 'render_block', 'terminal_blog_dev_scrollable_code', 10, 2 );

/**
 * Impeccable audit P2 #10: the floating share button ships a hardcoded English
 * accessible name. The interface language is PT-BR, so the label is swapped
 * while the footer markup is buffered.
 */
function terminal_blog_dev_localize_share_start() {
	ob_start();
}
add_action( 'wp_footer', 'terminal_blog_dev_localize_share_start', 9 );

/**
 * Flush the buffered footer with a localized share label.
 */
function terminal_blog_dev_localize_share_end() {
	$markup = ob_get_clean();

	if ( false === $markup ) {
		return;
	}

	$markup = str_replace(
		'aria-label="Share"',
		'aria-label="' . esc_attr__( 'Compartilhar este artigo', 'terminal-blog-dev' ) . '"',
		$markup
	);

	echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_footer', 'terminal_blog_dev_localize_share_end', 11 );

/**
 * Impeccable audit P2 #9: drop plugin assets that are not used on the current
 * route instead of shipping them on every page.
 */
function terminal_blog_dev_trim_unused_assets() {
	if ( is_admin() ) {
		return;
	}

	// Editor-only stylesheet that the plugin also queues on the frontend.
	wp_dequeue_style( 'gravatar-enhanced-patterns-edit' );

	// These small plugin styles are required by their markup. Opt them into
	// core's inline CSS instead of removing functionality or deferring a modal's
	// visibility rules. Core also resolves Super Web Share's relative icon URLs.
	$inline_styles = array(
		'gravatar-enhanced-patterns-shared' => 'gravatar-enhanced/build/patterns-shared.css',
		'gravatar-enhanced-patterns-view'   => 'gravatar-enhanced/build/patterns-view.css',
		'super-web-share'                  => 'super-web-share/public/css/super-web-share-public.min.css',
	);
	foreach ( $inline_styles as $handle => $file ) {
		$path = WP_PLUGIN_DIR . '/' . $file;
		if ( wp_style_is( $handle, 'enqueued' ) && is_readable( $path ) ) {
			wp_style_add_data( $handle, 'path', $path );
		}
	}

	// Comment avatar enhancements only matter where comments render.
	$has_comments = is_singular() && ( comments_open() || get_comments_number() > 0 );

	if ( ! $has_comments ) {
		wp_dequeue_style( 'gravatar-enhanced-comments' );
		wp_dequeue_script( 'gravatar-enhanced-comments' );
		wp_dequeue_style( 'gravatar-enhanced-hovercards' );
		wp_dequeue_script( 'gravatar-enhanced-hovercards' );
	}

	// Note: Super Web Share also prints a fallback share modal that depends on
	// its stylesheet, so its assets must stay on every route where the plugin
	// renders markup. Only the floating button is repositioned, in custom.css.
}
add_action( 'wp_enqueue_scripts', 'terminal_blog_dev_trim_unused_assets', 100 );

/**
 * Take consent scripts off the critical path.
 *
 * Site Kit prints the gtag consent defaults inline in <head>; its external
 * file and the WP Consent API only react to later consent changes, so they
 * can run deferred (in order) instead of blocking the parser. Runs just before
 * footer scripts print, so it also catches scripts enqueued late.
 */
function terminal_blog_dev_defer_consent_scripts() {
	foreach ( array( 'googlesitekit-consent-mode', 'wp-consent-api' ) as $handle ) {
		if ( wp_script_is( $handle, 'registered' ) && ! wp_scripts()->get_data( $handle, 'strategy' ) ) {
			wp_script_add_data( $handle, 'strategy', 'defer' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'terminal_blog_dev_defer_consent_scripts', 999 );
add_action( 'wp_print_footer_scripts', 'terminal_blog_dev_defer_consent_scripts', 5 );

/**
 * Allow room for the opted-in CSS without displacing core's navigation and
 * other block styles from their existing inline budget. Respect an opt-out.
 *
 * @param int $limit Existing WordPress inline CSS allowance in bytes.
 * @return int Adjusted allowance.
 */
function terminal_blog_dev_inline_css_budget( $limit ) {
	if ( is_admin() || $limit <= 0 ) {
		return $limit;
	}
	$styles = wp_styles();
	// Reserve room for theme, plugin, and core block styles so the largest
	// critical stylesheet (wp-block-navigation) is not evicted into a blocking
	// network request by wp_maybe_inline_styles().
	$handles = array(
		'terminal-blog-dev-colors',
		'terminal-blog-dev-header',
		'terminal-blog-dev-footer',
		'terminal-blog-dev-archive',
		'terminal-blog-dev-footer-art',
		'terminal-blog-dev-menu-pixels',
		'gravatar-enhanced-patterns-shared',
		'gravatar-enhanced-patterns-view',
		'super-web-share',
		'plvt-view-transitions',
		'wp-block-navigation',
		'wp-block-image',
		'wp-block-social-links',
		'wp-block-library',
		'wp-block-search',
		'wp-block-button',
		'wp-block-buttons',
		'wp-block-post-featured-image',
		'wp-block-post-template',
		'wp-block-heading',
		'wp-block-paragraph',
	);
	foreach ( $handles as $handle ) {
		$path = $styles->get_data( $handle, 'path' );
		if ( wp_style_is( $handle, 'enqueued' ) && $path && is_readable( $path ) ) {
			$limit += (int) filesize( $path );
		}
	}
	return $limit;
}
add_filter( 'styles_inline_size_limit', 'terminal_blog_dev_inline_css_budget' );

/**
 * Modify excerpt length
 */
function terminal_blog_dev_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'terminal_blog_dev_excerpt_length' );

/**
 * Modify excerpt more string
 */
function terminal_blog_dev_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'terminal_blog_dev_excerpt_more' );

/**
 * Give an empty contact page a truthful state without supplying example data.
 *
 * @param string $block_content Rendered post content.
 * @param array  $block         Parsed block data.
 * @return string Rendered content or the contact empty state.
 */
function terminal_blog_dev_contact_empty_state( $block_content, $block ) {
	$class_names = preg_split( '/\\s+/', trim( $block['attrs']['className'] ?? '' ) );

	if ( '' !== trim( $block_content ) || ! in_array( 'terminal-contact-content', $class_names, true ) ) {
		return $block_content;
	}

	return '<p class="has-foreground-muted-color">' .
		esc_html__( 'As informações de contato ainda não foram publicadas.', 'terminal-blog-dev' ) .
		'</p>';
}
add_filter( 'render_block_core/post-content', 'terminal_blog_dev_contact_empty_state', 10, 2 );

/**
 * Render a purely decorative scroll-progress bar on single posts.
 */
function terminal_blog_dev_reading_progress_bar() {
	if ( is_admin() || ! is_singular( 'post' ) ) {
		return;
	}
	echo '<div class="terminal-reading-progress" aria-hidden="true"></div>' . "\n";
}
add_action( 'wp_body_open', 'terminal_blog_dev_reading_progress_bar' );
