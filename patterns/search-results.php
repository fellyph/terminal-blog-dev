<?php
/**
 * Title: Search Results - Grep Style
 * Slug: terminal-blog-dev/search-results
 * Categories: featured
 * Keywords: search, results, grep
 * Description: A terminal-style search results display with grep aesthetic.
 * Template Types: search
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size" style="letter-spacing:0.1em;text-transform:uppercase">// PESQUISA_NO_BLOG</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":1,"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.02em"}}} -->
		<h1 class="wp-block-heading" style="letter-spacing:0.02em;text-transform:uppercase">RESULTADOS_DA_BUSCA</h1>
		<!-- /wp:heading -->

		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|40","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40"},"margin":{"top":"var:preset|spacing|40"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"default"}} -->
		<div class="wp-block-group has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);margin-top:var(--wp--preset--spacing--40);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontFamily":"monospace","textColor":"primary","fontSize":"medium"} -->
				<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family">&gt; grep -ri "</p>
				<!-- /wp:paragraph -->

				<!-- wp:query-title {"type":"search","fontFamily":"monospace","textColor":"primary","fontSize":"medium"} /-->

				<!-- wp:paragraph {"fontFamily":"monospace","textColor":"primary","fontSize":"medium"} -->
				<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family">" ./posts/*</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
		<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--30)">
			<!-- wp:query-title {"type":"search","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"foreground-muted","fontSize":"small"} /-->

			<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"primary","fontSize":"small"} -->
			<p class="has-primary-color has-text-color has-small-font-size" style="letter-spacing:0.05em;text-transform:uppercase">BUSCA_CONCLUÍDA</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"border"} -->
	<hr class="wp-block-separator alignwide has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"/>
	<!-- /wp:separator -->

	<!-- wp:query {"queryId":10,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"relevance","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide"} -->
	<div class="wp-block-query alignwide">
		<!-- wp:post-template {"layout":{"type":"default"}} -->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"},"margin":{"bottom":"var:preset|spacing|30"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);margin-bottom:var(--wp--preset--spacing--30);padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"primary","fontSize":"small"} -->
					<p class="has-primary-color has-text-color has-small-font-size has-monospace-font-family" style="letter-spacing:0.05em;text-transform:uppercase">./posts/</p>
					<!-- /wp:paragraph -->

					<!-- wp:post-date {"format":"Y-m-d","fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"primary","fontSize":"small"} /-->

					<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"primary","fontSize":"small"} -->
					<p class="has-primary-color has-text-color has-small-font-size has-monospace-font-family" style="letter-spacing:0.05em;text-transform:uppercase">-post.md</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:post-title {"level":3,"isLink":true,"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}},"typography":{"fontSize":"1.25rem","textTransform":"uppercase"}}} /-->

				<!-- wp:post-excerpt {"excerptLength":35,"fontFamily":"monospace","style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"textColor":"foreground-muted","fontSize":"small"} /-->

				<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
				<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--30)">
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:post-date {"format":"Y-m-d H:i","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} /-->

						<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
						<p class="has-foreground-muted-color has-text-color has-small-font-size" style="text-transform:uppercase">|</p>
						<!-- /wp:paragraph -->

						<!-- wp:post-terms {"term":"category","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} /-->
					</div>
					<!-- /wp:group -->

					<!-- wp:read-more {"content":"[ABRIR_ARQUIVO]","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"fontSize":"small"} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","right":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)">
				<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"warning","fontSize":"small"} -->
				<p class="has-warning-color has-text-color has-small-font-size" style="letter-spacing:0.05em;text-transform:uppercase">NENHUM_RESULTADO_ENCONTRADO</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":2,"style":{"typography":{"textTransform":"uppercase"}}} -->
				<h2 class="wp-block-heading" style="text-transform:uppercase">BUSCA_SEM_RESULTADOS</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"textColor":"foreground-muted"} -->
				<p class="has-foreground-muted-color has-text-color"><?php esc_html_e( 'Nenhum artigo corresponde à sua busca. Tente outras palavras ou explore os artigos do blog.', 'terminal-blog-dev' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
					<!-- wp:button {"className":"is-style-outline"} -->
					<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>"><?php esc_html_e( '[VER_ARTIGOS]', 'terminal-blog-dev' ); ?></a></div>
					<!-- /wp:button -->

					<!-- wp:button {"textColor":"foreground-muted","className":"is-style-outline"} -->
					<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-foreground-muted-color has-text-color wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '[VOLTAR_AO_INÍCIO]', 'terminal-blog-dev' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:query-no-results -->

		<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--50)">
			<!-- wp:query-pagination {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"fontSize":"small","layout":{"type":"flex","justifyContent":"center"}} -->
				<!-- wp:query-pagination-previous {"label":"[<< ANTERIORES]"} /-->
				<!-- wp:query-pagination-numbers /-->
				<!-- wp:query-pagination-next {"label":"[PRÓXIMOS >>]"} /-->
			<!-- /wp:query-pagination -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:query -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--60)">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size" style="letter-spacing:0.1em;text-transform:uppercase">// NOVA_BUSCA</p>
		<!-- /wp:paragraph -->

		<!-- wp:search {"label":"Pesquisar novamente","showLabel":false,"placeholder":"buscar_artigos>","buttonText":"[BUSCAR]","buttonPosition":"button-inside","style":{"border":{"radius":"var:custom|border-radius|medium","width":"1px"}},"borderColor":"border","fontSize":"small"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
