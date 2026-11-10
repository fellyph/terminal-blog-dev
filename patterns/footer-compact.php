<?php
/**
 * Title: Rodapé compacto
 * Slug: terminal-blog-dev/footer-compact
 * Categories: footer, terminal-blog-dev
 * Block Types: core/template-part/footer
 * Keywords: rodapé, compacto, simples, menu
 * Description: Título e menu em uma faixa que se reorganiza no celular, com os links sociais existentes e crédito do WordPress.
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","className":"terminal-site-footer terminal-footer terminal-footer--compact","backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull terminal-site-footer terminal-footer terminal-footer--compact has-background-background-color has-background">
	<!-- wp:group {"align":"wide","className":"terminal-footer__row","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group alignwide terminal-footer__row">
		<!-- wp:site-title {"level":0,"fontSize":"medium","textColor":"primary"} /-->
		<!-- wp:pattern {"slug":"terminal-blog-dev/footer-navigation"} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"align":"wide","className":"terminal-footer__row terminal-footer__bottom","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group alignwide terminal-footer__row terminal-footer__bottom">
		<!-- wp:pattern {"slug":"terminal-blog-dev/footer-credits"} /-->
		<!-- wp:pattern {"slug":"terminal-blog-dev/footer-social-links"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
