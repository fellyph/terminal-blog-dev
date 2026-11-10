<?php
/**
 * Title: Seletor de cores
 * Slug: terminal-blog-dev/theme-toggle
 * Categories: terminal-blog-dev
 * Keywords: tema, cores, claro, escuro
 * Description: Botão para alternar entre as cores claras e escuras em estilo terminal.
 * Viewport Width: 400
 */
?>
<!-- wp:buttons {"className":"theme-toggle-wrapper","layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons theme-toggle-wrapper">
	<!-- wp:button {"tagName":"button","className":"terminal-theme-toggle"} -->
	<div class="wp-block-button terminal-theme-toggle"><button type="button" class="wp-block-button__link wp-element-button">[<?php esc_html_e( 'TEMA', 'terminal-blog-dev' ); ?>: <?php esc_html_e( 'ESCURO', 'terminal-blog-dev' ); ?>]</button></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
