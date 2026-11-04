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

/**
 * Add resource hints for fonts to improve loading performance.
 * Preconnect establishes early connections, preload fetches the font early.
 */
function terminal_blog_dev_resource_hints() {
	// Preconnect to Google Fonts origin.
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	// Preload the main font file for faster text rendering.
	echo '<link rel="preload" href="https://fonts.gstatic.com/s/spacegrotesk/v16/V8mDoQDjQSkFtoMM3T6r8E7mPbF4Cw.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action( 'wp_head', 'terminal_blog_dev_resource_hints', 1 );

/**
 * Inline early theme detection script.
 * Runs before CSS to set data-theme attribute instantly, preventing FOUC.
 */
function terminal_blog_dev_early_theme_script() {
	?>
	<script id="terminal-early-theme">
	(function(){var t=localStorage.getItem('terminal-blog-theme');if(!t){t=window.matchMedia&&window.matchMedia('(prefers-color-scheme:light)').matches?'light':'dark'}document.documentElement.setAttribute('data-theme',t)})();
	</script>
	<?php
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
	add_editor_style( array( 'assets/css/colors.css', 'assets/css/editor-style.css' ) );

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
 * Enqueue scripts and styles
 */
function terminal_blog_dev_scripts() {
	// Enqueue main stylesheet
	wp_enqueue_style(
		'terminal-blog-dev-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	// Keep shared color tokens render-blocking for the frontend and editor.
	wp_enqueue_style(
		'terminal-blog-dev-colors',
		get_template_directory_uri() . '/assets/css/colors.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);

	// The effect stylesheet depends on the shared palette.
	wp_enqueue_style(
		'terminal-blog-dev-custom',
		get_template_directory_uri() . '/assets/css/custom.css',
		array( 'terminal-blog-dev-colors' ),
		wp_get_theme()->get( 'Version' )
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
		'wp-block-navigation',
	);

	if ( in_array( $handle, $defer_styles, true ) ) {
		// Use print media trick to defer CSS loading
		// The onload switches it back to screen media after load.
		$tag = str_replace(
			"media='all'",
			"media='print' onload=\"this.media='all'\"",
			$tag
		);
		// Add noscript fallback.
		$tag .= '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '"></noscript>' . "\n";
	}

	return $tag;
}
add_filter( 'style_loader_tag', 'terminal_blog_dev_optimize_css_loading', 10, 3 );

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

		/* Custom scrollbar */
		::-webkit-scrollbar {
			width: 8px;
			height: 8px;
		}

		::-webkit-scrollbar-track {
			background: var(--wp--preset--color--background-alt, #0a0f09);
		}

		::-webkit-scrollbar-thumb {
			background: var(--wp--preset--color--secondary, #2e3928);
			border: 1px solid var(--wp--preset--color--primary, #5bec13);
			border-radius: 4px;
		}

		::-webkit-scrollbar-thumb:hover {
			background: var(--wp--preset--color--primary, #5bec13);
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
			'description' => __( 'Terminal-themed patterns for the Terminal Blog Dev theme.', 'terminal-blog-dev' ),
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
