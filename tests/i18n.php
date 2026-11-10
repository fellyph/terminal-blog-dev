<?php
/**
 * Theme-label localization using WordPress parsing, queries and a real MO file.
 * Run with: studio wp eval-file /absolute/path/to/tests/i18n.php
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'terminal_blog_dev_template_labels' ) ) {
	fwrite( STDERR, "Run through studio wp eval-file with Terminal Blog Dev active.\n" );
	exit( 1 );
}

$GLOBALS['terminal_i18n_checks'] = 0;
function terminal_i18n_check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	++$GLOBALS['terminal_i18n_checks'];
}

function terminal_i18n_pattern( $slug ) {
	ob_start();
	include get_template_directory() . '/patterns/' . $slug . '.php';
	return ob_get_clean();
}

$fixture = new WP_Block_Template();
$fixture->theme = get_stylesheet();
$fixture->source = 'custom';
$fixture->content = '<!-- wp:group --><div class="wp-block-group"><!-- wp:paragraph --><p>// LOADING_FILE...</p><!-- /wp:paragraph --><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/about?label=ABOUT_ME.SH" data-label="[ABOUT_ME.SH]">[ABOUT_ME.SH]</a></div><!-- /wp:button --><!-- wp:search {"label":"Search Again","placeholder":"search_query>","buttonText":"[SEARCH]"} /--><!-- wp:navigation {"ref":14093} /--></div><!-- /wp:group -->';
$original = $fixture->content;
$translated = terminal_blog_dev_localize_template( $fixture );
terminal_i18n_check( str_contains( $translated->content, '// CARREGANDO_ARQUIVO...' ), 'A saved template must localize its old decorative labels.' );
terminal_i18n_check( str_contains( $translated->content, '>[SOBRE.SH]</a>' ), 'Button labels must keep the terminal filename treatment in Portuguese.' );
terminal_i18n_check( str_contains( $translated->content, 'href="/about?label=ABOUT_ME.SH" data-label="[ABOUT_ME.SH]"' ), 'Localization must not change URLs or unrelated HTML attributes.' );
terminal_i18n_check( $fixture !== $translated && $original === $fixture->content && 'custom' === $translated->source, 'Template localization must return a copy and preserve metadata.' );
$blocks = parse_blocks( $translated->content )[0]['innerBlocks'];
terminal_i18n_check( 'Pesquisar novamente' === $blocks[2]['attrs']['label'] && 'buscar_artigos>' === $blocks[2]['attrs']['placeholder'] && '[BUSCAR]' === $blocks[2]['attrs']['buttonText'], 'Search attributes and accessible labels must be translated together.' );
terminal_i18n_check( 14093 === $blocks[3]['attrs']['ref'], 'Saved navigation references must survive localization.' );
terminal_i18n_check( $translated->content === terminal_blog_dev_localize_template( $translated )->content, 'Repeated localization must be idempotent.' );
terminal_i18n_check( str_contains( terminal_blog_dev_localize_template_content( '<!-- wp:heading --><h2 class="wp-block-heading">// Posts_RECENTES</h2><!-- /wp:heading -->' ), '// ARTIGOS_RECENTES' ), 'The mixed-case heading in older saved home templates must be recognized.' );
$foreign = clone $fixture;
$foreign->theme = 'another-theme';
terminal_i18n_check( $foreign === terminal_blog_dev_localize_template( $foreign ), 'Other themes must not be affected.' );
terminal_i18n_check( null === terminal_blog_dev_localize_template( null ), 'An unavailable template must remain unavailable.' );
terminal_i18n_check( null === terminal_blog_dev_localize_templates( null ), 'A missing short-circuit collection must not prevent core from querying.' );

$protected = '<!-- wp:code --><pre class="wp-block-code"><code>READY FOR INPUT</code></pre><!-- /wp:code --><!-- wp:html --><p>// LOADING_FILE...</p><!-- /wp:html --><!-- wp:third-party/widget --><section><!-- wp:paragraph --><p>// LOADING_FILE...</p><!-- /wp:paragraph --></section><!-- /wp:third-party/widget -->';
terminal_i18n_check( $protected === terminal_blog_dev_localize_template_content( $protected ), 'Code, custom HTML and unfamiliar plugin blocks must remain intact.' );
$prose = '<!-- wp:paragraph --><p>We discuss READY FOR INPUT and [ABOUT_ME.SH] in this article.</p><!-- /wp:paragraph -->';
terminal_i18n_check( $prose === terminal_blog_dev_localize_template_content( $prose ), 'Only complete known labels may change, never words within prose.' );
$post = new WP_Post( (object) array( 'ID' => 1, 'post_type' => 'post', 'post_content' => $original ) );
$posts = terminal_blog_dev_localize_template_posts( array( $post ) );
terminal_i18n_check( $post === $posts[0] && $original === $posts[0]->post_content, 'Articles must not be rewritten even if they contain an old label.' );
$collection = apply_filters( 'get_block_templates', array( 'saved' => $fixture, 'foreign' => $foreign ) );
terminal_i18n_check( array( 'saved', 'foreign' ) === array_keys( $collection ) && $collection['saved']->content === $translated->content && $collection['foreign'] === $foreign, 'The Site Editor collection must preserve keys and scope.' );

$cursors = terminal_i18n_pattern( 'blinking-cursor' );
terminal_i18n_check( str_contains( $cursors, '&gt; PRONTO PARA DIGITAR' ) && str_contains( $cursors, '&gt; SISTEMA INICIADO' ), 'Decorative cursor patterns must default to Portuguese.' );
$credit = terminal_i18n_pattern( 'footer-credits' );
terminal_i18n_check( str_contains( $credit, 'Desenvolvido com <a' ) && str_contains( $credit, '>WordPress</a>.' ), 'A complete translated sentence must retain its spaces, link and brand name.' );
foreach ( glob( get_template_directory() . '/patterns/*.php' ) as $file ) {
	$content = terminal_i18n_pattern( basename( $file, '.php' ) );
	terminal_i18n_check( ! terminal_blog_dev_template_part_missing_blocks( parse_blocks( $content ) ), 'Translated patterns must still use registered native blocks: ' . basename( $file ) );
}

// Exercise the real saved-template path, whose core getter bypasses its filter.
$saved = get_posts( array( 'post_type' => array( 'wp_template', 'wp_template_part' ), 'posts_per_page' => -1, 'suppress_filters' => true ) );
foreach ( $saved as $saved_post ) {
	if ( ! has_term( array( get_template(), get_stylesheet() ), 'wp_theme', $saved_post ) ) {
		continue;
	}
	$before = hash( 'sha256', $saved_post->post_content );
	$queried = get_posts( array( 'post_type' => $saved_post->post_type, 'p' => $saved_post->ID, 'suppress_filters' => false ) );
	terminal_i18n_check( $queried && terminal_blog_dev_localize_template_content( $saved_post->post_content ) === $queried[0]->post_content, 'Native saved-template queries must receive localized content.' );
	terminal_i18n_check( $before === hash( 'sha256', get_post( $saved_post->ID )->post_content ), 'The raw post and object cache must remain unchanged.' );
	$loaded = get_block_template( get_stylesheet() . '//' . $saved_post->post_name, $saved_post->post_type );
	terminal_i18n_check( $loaded && $loaded->content === terminal_blog_dev_localize_template_content( $loaded->content ), 'The actual core/Gutenberg getter must return localized saved templates.' );
}

// Build a tiny catalog to test real gettext loading, escaping and locale changes.
require_once ABSPATH . WPINC . '/pomo/mo.php';
$catalog = new MO();
$catalog->set_header( 'Content-Type', 'text/plain; charset=UTF-8' );
foreach ( array(
	'> SISTEMA INICIADO' => '> SYSTEM INITIALIZED',
	'Pesquisar novamente' => 'Search "again" & learn -->',
	'Desenvolvido com %s.' => 'Powered by %s.',
	'Artigos deste arquivo' => 'Archive articles',
	'Pesquisar artigos…' => 'Search "articles" -->',
	'[LER_ARTIGO]' => '[READ_ARTICLE]',
) as $source => $translation ) {
	$catalog->add_entry( new Translation_Entry( array( 'singular' => $source, 'translations' => array( $translation ) ) ) );
}
$temporary = tempnam( sys_get_temp_dir(), 'terminal-i18n-' );
$mofile = $temporary . '.mo';
rename( $temporary, $mofile );
$catalog->export_to_file( $mofile );
$switched = switch_to_locale( 'en_US' );
try {
	terminal_i18n_check( load_textdomain( 'terminal-blog-dev', $mofile, 'en_US' ), 'A standard MO translation catalog must load.' );
	terminal_i18n_check( str_contains( terminal_i18n_pattern( 'blinking-cursor' ), '&gt; SYSTEM INITIALIZED' ), 'Patterns must honor a loaded translation catalog.' );
	$search = terminal_blog_dev_find_footer_block( parse_blocks( terminal_i18n_pattern( 'search-results' ) ), 'core/search' );
	terminal_i18n_check( 'Search "again" & learn -->' === $search['attrs']['label'], 'Quotes, ampersands and comment terminators in translations must round-trip through block JSON.' );
	terminal_i18n_check( str_contains( terminal_i18n_pattern( 'footer-credits' ), 'Powered by <a' ), 'The translated sentence must retain its format placeholder and link.' );
	$archive = terminal_i18n_pattern( 'category-archive' );
	terminal_i18n_check( str_contains( $archive, '>Archive articles</p>' ), 'Archive interface copy must honor the active catalog.' );
	$archive_search = terminal_blog_dev_find_footer_block( parse_blocks( $archive ), 'core/search' );
	$archive_link = terminal_blog_dev_find_footer_block( parse_blocks( $archive ), 'core/read-more' );
	terminal_i18n_check( 'Search "articles" -->' === $archive_search['attrs']['placeholder'] && '[READ_ARTICLE]' === $archive_link['attrs']['content'], 'Archive search and read-more translations must survive block JSON serialization.' );
	$translated_fixture = terminal_blog_dev_localize_template( $fixture );
	$search = parse_blocks( $translated_fixture->content )[0]['innerBlocks'][2];
	terminal_i18n_check( 'Search "again" & learn -->' === $search['attrs']['label'], 'Legacy template labels must also honor the active catalog.' );
} finally {
	unload_textdomain( 'terminal-blog-dev', true );
	if ( $switched ) {
		restore_previous_locale();
	}
	unlink( $mofile );
}

echo 'Passed ' . $GLOBALS['terminal_i18n_checks'] . " localization checks.\n";
