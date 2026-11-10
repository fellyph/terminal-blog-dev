<?php
/**
 * Archive integration against the real block renderer and main WordPress query.
 * Run with: studio wp eval-file /absolute/path/to/tests/archive.php
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'terminal_blog_dev_archive_seo_title' ) ) {
	fwrite( STDERR, "Run through studio wp eval-file with Terminal Blog Dev active.\n" );
	exit( 1 );
}

$checks = 0;
$check = static function ( $condition, $message ) use ( &$checks ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	++$checks;
};
$original_query = $GLOBALS['wp_query'];
$original_main = $GLOBALS['wp_the_query'];
$original_post = $GLOBALS['post'] ?? null;
$original_paged = $GLOBALS['paged'] ?? 0;
ob_start();
include get_template_directory() . '/patterns/category-archive.php';
$pattern = ob_get_clean();
$observed = array();
$observer = static function ( $content, $parsed, $instance ) use ( &$observed ) {
	$observed[] = (int) $instance->context['postId'];
	return $content;
};
add_filter( 'render_block_core/post-title', $observer, 10, 3 );

try {
	$categories = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 1 ) );
	$tags = get_terms( array( 'taxonomy' => 'post_tag', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 1 ) );
	$latest = get_posts( array( 'numberposts' => 1 ) );
	$check( ! is_wp_error( $categories ) && $categories && $latest, 'The integration site needs published articles and a category.' );
	$category = $categories[0];
	$cases = array(
		'category' => array( 'category_name' => $category->slug ),
		'date' => array( 'year' => (int) get_the_date( 'Y', $latest[0] ) ),
	);
	if ( ! is_wp_error( $tags ) && $tags ) {
		$cases['tag'] = array( 'tag' => $tags[0]->slug );
	}
	foreach ( $cases as $name => $args ) {
		$query = new WP_Query( array_merge( $args, array( 'posts_per_page' => 2, 'paged' => 1, 'ignore_sticky_posts' => true ) ) );
		$GLOBALS['wp_query'] = $query;
		$GLOBALS['wp_the_query'] = $query;
		$GLOBALS['paged'] = 1;
		$expected = array_map( 'intval', wp_list_pluck( $query->posts, 'ID' ) );
		$observed = array();
		$html = do_blocks( $pattern );
		$check( $expected === $observed, "The $name archive must inherit the real main query and its page size." );
		$check( 1 === substr_count( $html, '<h1 ' ), "The $name archive must have one primary heading." );
		$check( ! str_contains( $html, 'Category:' ) && ! str_contains( $html, 'Categoria:' ) && ! str_contains( $html, 'Tag:' ), 'Archive headings must omit the taxonomy prefix.' );
		$art = new WP_HTML_Tag_Processor( $html );
		$check( $art->next_tag( array( 'tag_name' => 'FIGURE', 'class_name' => 'terminal-archive__art' ) ) && 'true' === $art->get_attribute( 'aria-hidden' ), 'The decorative folder must be hidden from assistive technology.' );
		if ( $query->max_num_pages > 1 ) {
			$check( str_contains( $html, 'wp-block-query-pagination-next' ), 'Archives with more articles must expose native next-page navigation.' );
			$query = new WP_Query( array_merge( $args, array( 'posts_per_page' => 2, 'paged' => 2, 'ignore_sticky_posts' => true ) ) );
			$GLOBALS['wp_query'] = $query;
			$GLOBALS['wp_the_query'] = $query;
			$GLOBALS['paged'] = 2;
			$observed = array();
			$html = do_blocks( $pattern );
			$check( array_map( 'intval', wp_list_pluck( $query->posts, 'ID' ) ) === $observed && ! array_intersect( $expected, $observed ), 'Page two must show different articles from the same filtered main query.' );
			$check( str_contains( $html, 'wp-block-query-pagination-previous' ), 'Page two must expose native previous-page navigation.' );
		}
	}

	$GLOBALS['wp_query'] = new WP_Query( array( 'category_name' => $category->slug, 'posts_per_page' => 2 ) );
	$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];
	$GLOBALS['paged'] = 1;
	$term_name = single_term_title( '', false );
	foreach ( array( 'wpseo_title', 'wpseo_opengraph_title', 'wpseo_twitter_title' ) as $hook ) {
		$check( 'Arquivo de ' . $term_name . ' - Blog' === apply_filters( $hook, $term_name . ' Archives - Blog' ), 'Document and social titles must localize the legacy Archives label.' );
	}
	$check( 'Arquivo de ' . $term_name . ' – Página 2 – Blog' === terminal_blog_dev_archive_seo_title( $term_name . ' Archives – Página 2 – Blog' ), 'SEO pagination, separator and site name must survive localization.' );
	foreach ( array( 'Guia de ' . $term_name, $term_name . ' Archives extras', $term_name . ' - Blog' ) as $custom ) {
		$check( $custom === terminal_blog_dev_archive_seo_title( $custom ), 'Custom SEO titles must remain intact.' );
	}

	$GLOBALS['wp_query'] = new WP_Query( array( 'category_name' => $category->slug, 'post__in' => array( PHP_INT_MAX ) ) );
	$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];
	$observed = array();
	$html = do_blocks( $pattern );
	$check( ! $observed && str_contains( $html, 'Nenhum artigo neste arquivo' ) && str_contains( $html, '[VOLTAR_AO_INÍCIO]' ), 'An empty query must offer a clear message and recovery link.' );
	$check( str_contains( $html, 'name="s"' ) && str_contains( $html, 'Pesquisar no blog' ), 'Empty and populated archives must retain the native blog search.' );

	$ordinary_image = '<figure class="wp-block-image"><img src="/photo.jpg" alt="Uma foto"/></figure>';
	$check( $ordinary_image === terminal_blog_dev_archive_art_accessibility( $ordinary_image, array( 'attrs' => array() ) ), 'Ordinary images and their alternative text must not change.' );
	$GLOBALS['wp_query'] = new WP_Query( array( 'p' => $latest[0]->ID ) );
	$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];
	$check( 'Term Archives - Blog' === terminal_blog_dev_archive_seo_title( 'Term Archives - Blog' ), 'Article SEO titles must not be altered.' );
} finally {
	remove_filter( 'render_block_core/post-title', $observer, 10 );
	$GLOBALS['wp_query'] = $original_query;
	$GLOBALS['wp_the_query'] = $original_main;
	$GLOBALS['post'] = $original_post;
	$GLOBALS['paged'] = $original_paged;
}

echo "Passed $checks archive integration checks.\n";
