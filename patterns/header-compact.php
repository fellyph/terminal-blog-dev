<?php
/**
 * Title: Cabeçalho compacto
 * Slug: terminal-blog-dev/header-compact
 * Categories: header, terminal-blog-dev
 * Block Types: core/template-part/header
 * Keywords: cabeçalho, compacto, menu, simples
 * Description: Nome do site, menu e seletor de cores em uma faixa compacta. Ideal para dar mais espaço aos artigos.
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","className":"terminal-site-header terminal-header terminal-header--compact","backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull terminal-site-header terminal-header terminal-header--compact has-background-background-color has-background">
	<!-- wp:group {"align":"wide","className":"terminal-header__row","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group alignwide terminal-header__row">
		<!-- wp:group {"className":"terminal-header__brand","layout":{"type":"default"}} -->
		<div class="wp-block-group terminal-header__brand">
			<!-- wp:site-title {"level":0,"textColor":"primary","fontSize":"large"} /-->
		</div>
		<!-- /wp:group -->
		<!-- wp:pattern {"slug":"terminal-blog-dev/header-navigation"} /-->
		<!-- wp:pattern {"slug":"terminal-blog-dev/theme-toggle"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
