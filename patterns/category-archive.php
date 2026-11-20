<?php
/**
 * Title: Arquivo de artigos
 * Slug: terminal-blog-dev/category-archive
 * Categories: query, terminal-blog-dev
 * Keywords: arquivo, categoria, artigos, terminal, diretório
 * Description: Arquivo amplo com títulos legíveis, datas, resumos, pesquisa e uma pasta animada em pixel art.
 * Template Types: archive, category, tag
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","className":"terminal-directory-listing terminal-archive","layout":{"type":"default"}} -->
<section class="wp-block-group alignwide terminal-directory-listing terminal-archive">
	<!-- wp:group {"className":"terminal-archive__intro","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group terminal-archive__intro">
		<!-- wp:group {"className":"terminal-archive__heading","layout":{"type":"default"}} -->
		<div class="wp-block-group terminal-archive__heading">
			<!-- wp:query-title {"type":"archive","showPrefix":false,"level":1,"className":"directory-path","textColor":"primary"} /-->
			<!-- wp:term-description {"className":"terminal-archive__description","textColor":"foreground-muted","fontSize":"medium"} /-->
		</div>
		<!-- /wp:group -->
		<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"terminal-archive__art"} -->
		<figure class="wp-block-image size-full terminal-archive__art"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/archive-folder.svg#frame-1' ); ?>" alt="" width="256" height="64" decoding="async"/></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"terminal-archive__toolbar","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group terminal-archive__toolbar">
		<!-- wp:group {"className":"terminal-archive__summary","layout":{"type":"default"}} -->
		<div class="wp-block-group terminal-archive__summary">
			<!-- wp:paragraph {"fontSize":"medium"} -->
			<p class="has-medium-font-size"><?php esc_html_e( 'Artigos deste arquivo', 'terminal-blog-dev' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"textColor":"foreground-muted","fontSize":"small"} -->
			<p class="has-foreground-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'Mais recentes primeiro', 'terminal-blog-dev' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:search {"label":<?php echo wp_json_encode( __( 'Pesquisar no blog', 'terminal-blog-dev' ), JSON_HEX_TAG | JSON_HEX_AMP ); ?>,"showLabel":false,"placeholder":<?php echo wp_json_encode( __( 'Pesquisar artigos…', 'terminal-blog-dev' ), JSON_HEX_TAG | JSON_HEX_AMP ); ?>,"buttonText":<?php echo wp_json_encode( __( 'Pesquisar', 'terminal-blog-dev' ), JSON_HEX_TAG | JSON_HEX_AMP ); ?>,"buttonUseIcon":true,"buttonPosition":"button-inside","className":"terminal-archive__search","style":{"border":{"radius":"0.25rem","width":"1px"}},"borderColor":"border"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":1,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"file-listing"} -->
	<div class="wp-block-query file-listing">
		<!-- wp:post-template {"layout":{"type":"default"}} -->
			<!-- wp:group {"className":"file-entry","layout":{"type":"default"}} -->
			<div class="wp-block-group file-entry">
				<!-- wp:post-date {"format":"d/m/Y","className":"file-date","fontFamily":"monospace","textColor":"foreground-muted","fontSize":"small"} /-->
				<!-- wp:group {"className":"file-info","layout":{"type":"default"}} -->
				<div class="wp-block-group file-info">
					<!-- wp:post-title {"level":2,"isLink":true,"className":"file-name","fontSize":"large"} /-->
					<!-- wp:post-excerpt {"excerptLength":24,"className":"file-excerpt","textColor":"foreground-muted","fontSize":"small"} /-->
					<!-- wp:post-terms {"term":"category","separator":" · ","className":"file-tags","fontSize":"small"} /-->
				</div>
				<!-- /wp:group -->
				<!-- wp:read-more {"content":<?php echo wp_json_encode( __( '[LER_ARTIGO]', 'terminal-blog-dev' ), JSON_HEX_TAG | JSON_HEX_AMP ); ?>,"className":"file-open","fontFamily":"monospace","fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-pagination {"className":"terminal-archive__pagination","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"},"fontSize":"small"} -->
			<!-- wp:query-pagination-previous {"label":<?php echo wp_json_encode( __( '[ANTERIOR]', 'terminal-blog-dev' ), JSON_HEX_TAG | JSON_HEX_AMP ); ?>} /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next {"label":<?php echo wp_json_encode( __( '[PRÓXIMA]', 'terminal-blog-dev' ), JSON_HEX_TAG | JSON_HEX_AMP ); ?>} /-->
		<!-- /wp:query-pagination -->

		<!-- wp:query-no-results -->
			<!-- wp:group {"className":"empty-directory","layout":{"type":"default"}} -->
			<div class="wp-block-group empty-directory">
				<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
				<h2 class="wp-block-heading has-x-large-font-size"><?php esc_html_e( 'Nenhum artigo neste arquivo', 'terminal-blog-dev' ); ?></h2>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"foreground-muted"} -->
				<p class="has-foreground-muted-color has-text-color"><?php esc_html_e( 'Use a pesquisa para encontrar outro assunto ou volte à página inicial.', 'terminal-blog-dev' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"is-style-outline"} -->
					<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '[VOLTAR_AO_INÍCIO]', 'terminal-blog-dev' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</section>
<!-- /wp:group -->
