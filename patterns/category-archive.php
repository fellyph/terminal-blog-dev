<?php
/**
 * Title: Arquivo de categorias
 * Slug: terminal-blog-dev/category-archive
 * Categories: query
 * Keywords: archive, category, listing, terminal, directory
 * Block Types: core/query
 * Description: Terminal-style file directory listing for category and archive pages.
 * Template Types: archive, category, tag
 * Viewport Width: 1200
 */
?>
<!-- wp:group {"tagName":"section","className":"terminal-directory-listing","layout":{"type":"default"}} -->
<section class="wp-block-group terminal-directory-listing">

	<!-- wp:group {"className":"directory-header","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"},"margin":{"bottom":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}},"layout":{"type":"default"}} -->
	<div class="wp-block-group directory-header" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;margin-bottom:var(--wp--preset--spacing--40);padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="letter-spacing:0.05em;text-transform:uppercase">user@terminal:~$ ls -la /var/archive/</p>
		<!-- /wp:paragraph -->

		<!-- wp:query-title {"type":"archive","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"},"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"textColor":"primary","className":"directory-path"} /-->

		<!-- wp:term-description {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}},"typography":{"fontStyle":"normal","fontWeight":"400"}},"textColor":"foreground-muted","fontSize":"small"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"directory-meta","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group directory-meta" style="margin-bottom:var(--wp--preset--spacing--30)">
		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"x-small"} -->
		<p class="has-foreground-muted-color has-text-color has-x-small-font-size has-monospace-font-family" style="text-transform:uppercase">DATA&nbsp;&nbsp;&nbsp;&nbsp;ARQUIVO</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"x-small"} -->
		<p class="has-foreground-muted-color has-text-color has-x-small-font-size has-monospace-font-family" style="text-transform:uppercase">[ORDEM: MAIS_RECENTES]</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":1,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"file-listing"} -->
	<div class="wp-block-query file-listing">
		<!-- wp:post-template {"layout":{"type":"default"}} -->
			<!-- wp:group {"className":"file-entry","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px","style":"dashed"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
			<div class="wp-block-group file-entry" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-style:dashed;border-bottom-width:1px;padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)">

				<!-- wp:group {"className":"file-meta-left","style":{"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-group file-meta-left">
					<!-- wp:paragraph {"fontFamily":"monospace","textColor":"foreground-muted","fontSize":"small"} -->
					<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family">-rw-r--r--</p>
					<!-- /wp:paragraph -->


					<!-- wp:post-date {"format":"M d H:i","fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} /-->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"file-info","style":{"layout":{"selfStretch":"fill","flexSize":null},"spacing":{"padding":{"left":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group file-info" style="padding-left:var(--wp--preset--spacing--30)">
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:post-title {"level":3,"isLink":true,"fontFamily":"monospace","style":{"typography":{"fontSize":"0.95rem"},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"className":"file-name"} /-->

						<!-- wp:paragraph {"fontFamily":"monospace","textColor":"foreground-muted","fontSize":"small"} -->
						<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family">.md</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->

					<!-- wp:post-excerpt {"excerptLength":20,"fontFamily":"monospace","style":{"spacing":{"margin":{"top":"4px"}}},"textColor":"foreground-muted","fontSize":"x-small"} /-->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"file-meta-right","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
				<div class="wp-block-group file-meta-right">
					<!-- wp:post-terms {"term":"category","fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"x-small","className":"file-tags"} /-->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:group {"className":"directory-footer","style":{"spacing":{"margin":{"top":"var:preset|spacing|40"},"padding":{"top":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
		<div class="wp-block-group directory-footer" style="margin-top:var(--wp--preset--spacing--40);padding-top:var(--wp--preset--spacing--30)">
			<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"x-small"} -->
			<p class="has-primary-color has-text-color has-x-small-font-size has-monospace-font-family" style="text-transform:uppercase">// FIM_DA_LISTAGEM</p>
			<!-- /wp:paragraph -->

			<!-- wp:query-pagination {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small","layout":{"type":"flex","justifyContent":"right"}} -->
				<!-- wp:query-pagination-previous {"label":"[ANTERIORES]"} /-->
				<!-- wp:query-pagination-numbers /-->
				<!-- wp:query-pagination-next {"label":"[PRÓXIMOS]"} /-->
			<!-- /wp:query-pagination -->
		</div>
		<!-- /wp:group -->

		<!-- wp:query-no-results -->
			<!-- wp:group {"className":"empty-directory","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}},"border":{"width":"1px","color":"var:preset|color|warning","style":"dashed"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group empty-directory has-border-color" style="border-color:var(--wp--preset--color--warning);border-style:dashed;border-width:1px;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
				<!-- wp:paragraph {"align":"center","fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"warning"} -->
				<p class="has-text-align-center has-warning-color has-text-color has-monospace-font-family" style="text-transform:uppercase">NENHUM_ARTIGO_ENCONTRADO</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"align":"center","fontFamily":"monospace","textColor":"foreground-muted","fontSize":"small"} -->
				<p class="has-text-align-center has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family"><?php esc_html_e( 'Ainda não há artigos que correspondam a estes critérios.', 'terminal-blog-dev' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"align":"center","fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"x-small"} -->
				<p class="has-text-align-center has-foreground-muted-color has-text-color has-x-small-font-size has-monospace-font-family" style="text-transform:uppercase">Tente pesquisar outro assunto no blog.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->
