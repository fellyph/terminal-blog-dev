<?php
/**
 * Title: Rodapé com pixel art
 * Slug: terminal-blog-dev/footer-pixel-art
 * Categories: footer, terminal-blog-dev
 * Block Types: core/template-part/footer
 * Keywords: rodapé, pixel art, terminal, ilustração
 * Description: Cena de um terminal em pixel art, com versões clara e escura, identidade do site, menu e links sociais existentes.
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","className":"terminal-site-footer terminal-footer terminal-footer--pixel-art","backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull terminal-site-footer terminal-footer terminal-footer--pixel-art has-background-background-color has-background">
	<!-- wp:image {"align":"full","sizeSlug":"full","linkDestination":"none","className":"terminal-footer-art terminal-footer-art--image"} -->
	<figure class="wp-block-image alignfull size-full terminal-footer-art terminal-footer-art--image"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/footer-terminal.svg' ); ?>" alt="" width="768" height="144" loading="lazy" decoding="async"/></figure>
	<!-- /wp:image -->
	<!-- wp:pattern {"slug":"terminal-blog-dev/footer-content"} /-->
</div>
<!-- /wp:group -->
