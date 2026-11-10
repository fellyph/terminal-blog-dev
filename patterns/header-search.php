<?php
/**
 * Title: Cabeçalho com busca
 * Slug: terminal-blog-dev/header-search
 * Categories: header, terminal-blog-dev
 * Block Types: core/template-part/header
 * Keywords: cabeçalho, busca, pesquisa, artigos
 * Description: Logo e nome do site com busca visível, menu e seletor de cores. Indicado para um blog com muitos artigos.
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","className":"terminal-site-header terminal-header terminal-header--search","backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull terminal-site-header terminal-header terminal-header--search has-background-background-color has-background">
	<!-- wp:group {"align":"wide","className":"terminal-header__row","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group alignwide terminal-header__row">
		<!-- wp:pattern {"slug":"terminal-blog-dev/header-brand"} /-->
		<!-- wp:search {"label":"Pesquisar no blog","showLabel":false,"placeholder":"Buscar artigos…","buttonText":"Pesquisar","buttonUseIcon":true,"buttonPosition":"button-inside","className":"terminal-header__search","style":{"border":{"radius":"0.25rem","width":"1px"}},"borderColor":"border"} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"align":"wide","className":"terminal-header__row terminal-header__bar","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group alignwide terminal-header__row terminal-header__bar">
		<!-- wp:pattern {"slug":"terminal-blog-dev/header-navigation"} /-->
		<!-- wp:pattern {"slug":"terminal-blog-dev/theme-toggle"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
