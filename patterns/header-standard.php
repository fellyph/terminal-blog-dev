<?php
/**
 * Title: Cabeçalho padrão
 * Slug: terminal-blog-dev/header-standard
 * Categories: header, terminal-blog-dev
 * Block Types: core/template-part/header
 * Keywords: cabeçalho, blog, logo, menu
 * Description: Logo e nome do site com menu e seletor de cores. Uma opção para blogs e páginas de conteúdo.
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","className":"terminal-site-header terminal-header terminal-header--standard","backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull terminal-site-header terminal-header terminal-header--standard has-background-background-color has-background">
	<!-- wp:group {"align":"wide","className":"terminal-header__row","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group alignwide terminal-header__row">
		<!-- wp:pattern {"slug":"terminal-blog-dev/header-brand"} /-->
		<!-- wp:pattern {"slug":"terminal-blog-dev/header-navigation"} /-->
		<!-- wp:pattern {"slug":"terminal-blog-dev/theme-toggle"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
