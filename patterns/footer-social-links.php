<?php
/**
 * Title: Links sociais do rodapé
 * Slug: terminal-blog-dev/footer-social-links
 * Inserter: false
 * Description: Preserva os perfis configurados no rodapé atual, sem inventar destinos.
 */

$terminal_footer_socials = terminal_blog_dev_current_footer_block( 'core/social-links' );
if ( $terminal_footer_socials ) {
	// Keep the saved profiles, labels, link targets and native block markup intact.
	echo serialize_block( $terminal_footer_socials ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
