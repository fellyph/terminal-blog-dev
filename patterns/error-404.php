<?php
/**
 * Title: Erro 404 — Página não encontrada
 * Slug: terminal-blog-dev/error-404
 * Categories: featured
 * Keywords: 404, error, not found
 * Description: A terminal-style error page with retro CRT aesthetics.
 * Template Types: 404
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"align":"wide","style":{"border":{"width":"1px","color":"var:preset|color|error","radius":"var:custom|border-radius|medium"},"spacing":{"padding":{"top":"var:preset|spacing|60","right":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60"}}},"layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide has-border-color" style="border-color:var(--wp--preset--color--error);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}},"textColor":"error","fontSize":"small"} -->
		<p class="has-error-color has-text-color has-small-font-size" style="letter-spacing:0.1em;text-transform:uppercase">PÁGINA_NÃO_ENCONTRADA</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"clamp(3rem, 8vw, 6rem)","textTransform":"uppercase","letterSpacing":"-0.02em","fontStyle":"normal","fontWeight":"700"}},"textColor":"error"} -->
		<h1 class="wp-block-heading has-error-color has-text-color" style="font-size:clamp(3rem, 8vw, 6rem);font-style:normal;font-weight:700;letter-spacing:-0.02em;text-transform:uppercase">ERRO 404</h1>
		<!-- /wp:heading -->

		<!-- wp:separator {"backgroundColor":"border","className":"is-style-wide"} -->
		<hr class="wp-block-separator has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background is-style-wide"/>
		<!-- /wp:separator -->

		<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40)">
			<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"foreground-muted","fontSize":"small"} -->
			<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="letter-spacing:0.05em;text-transform:uppercase">&gt; VERIFIQUE_O_ENDEREÇO</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"foreground-muted","fontSize":"small"} -->
			<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="letter-spacing:0.05em;text-transform:uppercase">&gt; ERRO: PÁGINA_NÃO_ENCONTRADA</p>
			<!-- /wp:paragraph -->


			<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"foreground-muted","fontSize":"small"} -->
			<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="letter-spacing:0.05em;text-transform:uppercase">&gt; ESCOLHA_UMA_OPÇÃO_ABAIXO</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}},"typography":{"lineHeight":"1.8"}},"textColor":"foreground","fontSize":"medium"} -->
		<p class="has-foreground-color has-text-color has-medium-font-size" style="line-height:1.8;margin-top:var(--wp--preset--spacing--50)"><?php esc_html_e( 'A página solicitada não foi encontrada. Verifique o endereço, volte ao início ou pesquise outro assunto.', 'terminal-blog-dev' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--50)">
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '[VOLTAR_AO_INÍCIO]', 'terminal-blog-dev' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"textColor":"foreground-muted","className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-foreground-muted-color has-text-color wp-element-button" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>"><?php esc_html_e( '[VER_ARTIGOS]', 'terminal-blog-dev' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:search {"label":"Pesquisar no site","showLabel":false,"placeholder":"buscar_artigos>","buttonText":"[BUSCAR]","buttonPosition":"button-inside","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}},"border":{"radius":"var:custom|border-radius|medium","width":"1px"}},"borderColor":"border","fontSize":"small"} /-->
</div>
<!-- /wp:group -->
