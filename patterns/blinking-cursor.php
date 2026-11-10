<?php
/**
 * Title: Cursor piscante
 * Slug: terminal-blog-dev/blinking-cursor
 * Categories: text
 * Keywords: cursor, terminal, animação
 * Description: Cursores decorativos piscantes com a estética de um terminal.
 * Viewport Width: 800
 */
?>
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">

	<!-- wp:heading {"level":3,"style":{"typography":{"textTransform":"uppercase"}},"className":"has-cursor-block"} -->
	<h3 class="wp-block-heading has-cursor-block" style="text-transform:uppercase">TERMINAL_BLOG</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"has-cursor-underscore"} -->
	<p class="has-cursor-underscore"><?php esc_html_e( '> PRONTO PARA DIGITAR', 'terminal-blog-dev' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"className":"has-cursor-line"} -->
	<p class="has-cursor-line"><?php esc_html_e( '> SISTEMA INICIADO', 'terminal-blog-dev' ); ?></p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
