<?php
/**
 * Title: Crédito do rodapé
 * Slug: terminal-blog-dev/footer-credits
 * Inserter: false
 */
?>
<!-- wp:paragraph {"className":"terminal-footer__credits","textColor":"foreground-muted","fontSize":"small"} -->
<p class="terminal-footer__credits has-foreground-muted-color has-text-color has-small-font-size"><?php
/* translators: %s: Link to WordPress. */
echo wp_kses_post( sprintf( __( 'Desenvolvido com %s.', 'terminal-blog-dev' ), '<a href="https://wordpress.org/" rel="nofollow">WordPress.org</a>' ) );
?></p>
<!-- /wp:paragraph -->
