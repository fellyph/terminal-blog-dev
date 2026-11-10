<?php
/**
 * Localize terminal labels in theme templates, including older customizations.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portuguese defaults and exact labels shipped by earlier theme versions.
 *
 * @return array<string,string> Plain-text labels, translated with gettext.
 */
function terminal_blog_dev_template_labels() {
	$labels = array(
		'// CARREGANDO_ARQUIVO...' => __( '// CARREGANDO_ARQUIVO...', 'terminal-blog-dev' ),
		'// ARTIGOS_RECENTES' => __( '// ARTIGOS_RECENTES', 'terminal-blog-dev' ),
		'// BUSCANDO_POR_ALGO?' => __( '// BUSCANDO_POR_ALGO?', 'terminal-blog-dev' ),
		'[SOBRE.SH]' => __( '[SOBRE.SH]', 'terminal-blog-dev' ),
		'[EXPLORAR_CONTEÚDO]' => __( '[EXPLORAR_CONTEÚDO]', 'terminal-blog-dev' ),
		'[ABRIR_ARQUIVO]' => __( '[ABRIR_ARQUIVO]', 'terminal-blog-dev' ),
		'[LER_ARTIGO]' => __( '[LER_ARTIGO]', 'terminal-blog-dev' ),
		'[BUSCAR]' => __( '[BUSCAR]', 'terminal-blog-dev' ),
		'Pesquisar novamente' => __( 'Pesquisar novamente', 'terminal-blog-dev' ),
		'Pesquisar no blog' => __( 'Pesquisar no blog', 'terminal-blog-dev' ),
		'buscar_artigos>' => __( 'buscar_artigos>', 'terminal-blog-dev' ),
		'> PRONTO PARA DIGITAR' => __( '> PRONTO PARA DIGITAR', 'terminal-blog-dev' ),
		'> SISTEMA INICIADO' => __( '> SISTEMA INICIADO', 'terminal-blog-dev' ),
		'[ BLOG_SO v2.0.26 // SISTEMA_ATIVO ]' => __( '[ BLOG_SO v2.0.26 // SISTEMA_ATIVO ]', 'terminal-blog-dev' ),
		'C:\\USUARIO\\BLOG> _' => __( 'C:\\USUARIO\\BLOG> _', 'terminal-blog-dev' ),
		'C:\\USUARIO\\SOBRE> cat leia-me.txt' => __( 'C:\\USUARIO\\SOBRE> cat leia-me.txt', 'terminal-blog-dev' ),
		'leitor@terminal:~$ ls -la /var/arquivo/' => __( 'leitor@terminal:~$ ls -la /var/arquivo/', 'terminal-blog-dev' ),
		'> ls -la ~/artigos --limit=6' => __( '> ls -la ~/artigos --limit=6', 'terminal-blog-dev' ),
		'" ./artigos/*' => __( '" ./artigos/*', 'terminal-blog-dev' ),
		'./artigos/' => __( './artigos/', 'terminal-blog-dev' ),
		'-artigo.md' => __( '-artigo.md', 'terminal-blog-dev' ),
		'DATA: 2026-01-15' => __( 'DATA: 2026-01-15', 'terminal-blog-dev' ),
		'CATEGORIA' => __( 'CATEGORIA', 'terminal-blog-dev' ),
		'Título-do-artigo.txt' => __( 'Título-do-artigo.txt', 'terminal-blog-dev' ),
		'~5 MIN DE LEITURA' => __( '~5 MIN DE LEITURA', 'terminal-blog-dev' ),
	);
	$legacy = array(
		'// LOADING_FILE...' => '// CARREGANDO_ARQUIVO...',
		'// POSTS_RECENTES' => '// ARTIGOS_RECENTES',
		'// Posts_RECENTES' => '// ARTIGOS_RECENTES',
		'// RECENT_POSTS' => '// ARTIGOS_RECENTES',
		'// LOOKING_FOR_SOMETHING?' => '// BUSCANDO_POR_ALGO?',
		'[ABOUT_ME.SH]' => '[SOBRE.SH]',
		'[EXPLORE_CONTENT]' => '[EXPLORAR_CONTEÚDO]',
		'[OPEN_FILE]' => '[ABRIR_ARQUIVO]',
		'[READ_MORE]' => '[LER_ARTIGO]',
		'[SEARCH]' => '[BUSCAR]',
		'Search Again' => 'Pesquisar novamente',
		'Search' => 'Pesquisar no blog',
		'search_query>' => 'buscar_artigos>',
		'> READY FOR INPUT' => '> PRONTO PARA DIGITAR',
		'> SYSTEM INITIALIZED' => '> SISTEMA INICIADO',
		'[ BLOG_OS v2.0.26 // SYSTEM_ONLINE ]' => '[ BLOG_SO v2.0.26 // SISTEMA_ATIVO ]',
		'C:\\USER\\BLOG> _' => 'C:\\USUARIO\\BLOG> _',
		'C:\\USER\\ABOUT> cat readme.txt' => 'C:\\USUARIO\\SOBRE> cat leia-me.txt',
		'user@terminal:~$ ls -la /var/archive/' => 'leitor@terminal:~$ ls -la /var/arquivo/',
		'> ls -la ~/posts --limit=6' => '> ls -la ~/artigos --limit=6',
		'" ./posts/*' => '" ./artigos/*',
		'./posts/' => './artigos/',
		'-post.md' => '-artigo.md',
		'DATE: 2026-01-15' => 'DATA: 2026-01-15',
		'CATEGORY' => 'CATEGORIA',
		'Post-Title-Here.txt' => 'Título-do-artigo.txt',
		'~5 MIN READ' => '~5 MIN DE LEITURA',
	);
	foreach ( $legacy as $original => $portuguese ) {
		$labels[ $original ] = $labels[ $portuguese ];
	}
	return $labels;
}

/**
 * Replace complete known text nodes, leaving HTML attributes and URLs untouched.
 *
 * @param string $html   Serialized block HTML.
 * @param array  $labels Translated labels.
 * @return string HTML with safely escaped labels.
 */
function terminal_blog_dev_localize_label_html( $html, $labels ) {
	return preg_replace_callback( '/(?<=>)[^<>]+(?=<)/u', static function ( $match ) use ( $labels ) {
		$key = html_entity_decode( trim( $match[0] ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		if ( ! isset( $labels[ $key ] ) || $labels[ $key ] === $key ) {
			return $match[0];
		}
		return str_replace( trim( $match[0] ), esc_html( $labels[ $key ] ), $match[0] );
	}, $html ) ?? $html;
}

/**
 * Translate known labels in native blocks without touching editorial content.
 *
 * @param array $blocks Parsed template blocks.
 * @param array $labels Translated labels.
 * @return array Localized template blocks.
 */
function terminal_blog_dev_localize_template_blocks( $blocks, $labels ) {
	$attributes = array(
		'core/search' => array( 'label', 'placeholder', 'buttonText' ),
		'core/read-more' => array( 'content' ),
		'core/query-pagination-previous' => array( 'label' ),
		'core/query-pagination-next' => array( 'label' ),
	);
	foreach ( $blocks as &$block ) {
		$name = $block['blockName'] ?? '';
		if ( ! str_starts_with( $name, 'core/' ) || in_array( $name, array( 'core/code', 'core/preformatted', 'core/html', 'core/post-content', 'core/navigation', 'core/block' ), true ) ) {
			continue;
		}
		foreach ( $attributes[ $name ] ?? array() as $attribute ) {
			$value = $block['attrs'][ $attribute ] ?? null;
			if ( is_string( $value ) && isset( $labels[ $value ] ) ) {
				$block['attrs'][ $attribute ] = $labels[ $value ];
			}
		}
		if ( in_array( $name, array( 'core/paragraph', 'core/heading', 'core/button' ), true ) ) {
			$block['innerHTML'] = terminal_blog_dev_localize_label_html( $block['innerHTML'], $labels );
			foreach ( $block['innerContent'] as &$fragment ) {
				if ( is_string( $fragment ) ) {
					$fragment = terminal_blog_dev_localize_label_html( $fragment, $labels );
				}
			}
			unset( $fragment );
		}
		$block['innerBlocks'] = terminal_blog_dev_localize_template_blocks( $block['innerBlocks'], $labels );
	}
	unset( $block );
	return $blocks;
}

/**
 * Preserve the original serialization when no known label needs translation.
 *
 * @param string $content Serialized template blocks.
 * @return string Localized template content.
 */
function terminal_blog_dev_localize_template_content( $content ) {
	$blocks = parse_blocks( $content );
	$localized = terminal_blog_dev_localize_template_blocks( $blocks, terminal_blog_dev_template_labels() );
	return $localized === $blocks ? $content : serialize_blocks( $localized );
}

/**
 * Localize a copy of this theme's template, never the stored customization.
 *
 * @param WP_Block_Template|null $template Theme template or template part.
 * @return WP_Block_Template|null Localized template, or the original value.
 */
function terminal_blog_dev_localize_template( $template ) {
	if ( ! $template instanceof WP_Block_Template || ! in_array( $template->theme, array( get_template(), get_stylesheet() ), true ) ) {
		return $template;
	}
	$content = terminal_blog_dev_localize_template_content( $template->content );
	if ( $content === $template->content ) {
		return $template;
	}
	$copy = clone $template;
	$copy->content = $content;
	return $copy;
}
add_filter( 'get_block_template', 'terminal_blog_dev_localize_template' );
add_filter( 'get_block_file_template', 'terminal_blog_dev_localize_template' );
// Gutenberg can supply the complete template before core reaches its getters.
add_filter( 'pre_get_block_template', 'terminal_blog_dev_localize_template', 100 );
add_filter( 'pre_get_block_file_template', 'terminal_blog_dev_localize_template', 100 );

/**
 * Keep the Site Editor's template collection consistent with individual reads.
 *
 * @param array $templates Theme templates and template parts.
 * @return array Localized templates with the same keys and metadata.
 */
function terminal_blog_dev_localize_templates( $templates ) {
	return is_array( $templates ) ? array_map( 'terminal_blog_dev_localize_template', $templates ) : $templates;
}
add_filter( 'get_block_templates', 'terminal_blog_dev_localize_templates' );
add_filter( 'pre_get_block_templates', 'terminal_blog_dev_localize_templates', 100 );

/**
 * Core reads saved parts directly and returns saved templates before its getter
 * filter. Localize copies of those query results too, without mutating the post
 * cache, database, revisions, or posts and pages containing editorial content.
 *
 * @param WP_Post[] $posts Queried posts.
 * @return WP_Post[] Template copies with known decorative labels localized.
 */
function terminal_blog_dev_localize_template_posts( $posts ) {
	foreach ( $posts as $key => $post ) {
		if ( ! $post instanceof WP_Post || ! in_array( $post->post_type, array( 'wp_template', 'wp_template_part' ), true ) || ! has_term( array( get_template(), get_stylesheet() ), 'wp_theme', $post ) ) {
			continue;
		}
		$content = terminal_blog_dev_localize_template_content( $post->post_content );
		if ( $content !== $post->post_content ) {
			$posts[ $key ] = clone $post;
			$posts[ $key ]->post_content = $content;
		}
	}
	return $posts;
}
add_filter( 'the_posts', 'terminal_blog_dev_localize_template_posts' );
