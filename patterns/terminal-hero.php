<?php
/**
 * Title: Terminal Hero
 * Slug: terminal-blog-dev/terminal-hero
 * Categories: featured, banner
 * Keywords: hero, banner, terminal
 * Description: A terminal-style hero section with title and call to action.
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-alt-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"clamp(2.5rem, 6vw, 4rem)","textTransform":"uppercase","letterSpacing":"0.02em"}}} -->
		<h1 class="wp-block-heading" style="font-size:clamp(2.5rem, 6vw, 4rem);letter-spacing:0.02em;text-transform:uppercase">TERMINAL_BLOG</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.8"}},"textColor":"foreground","fontSize":"large"} -->
		<p class="has-foreground-color has-text-color has-large-font-size" style="line-height:1.8"><?php esc_html_e( 'Tutoriais práticos sobre WordPress, inteligência artificial, PWAs e JavaScript, escritos por Fellyph Cintra.', 'terminal-blog-dev' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--50)">
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/#artigos-recentes"><?php esc_html_e( '[VER_ARTIGOS]', 'terminal-blog-dev' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"textColor":"foreground-muted","className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-foreground-muted-color has-text-color wp-element-button" href="/about"><?php esc_html_e( '[SOBRE]', 'terminal-blog-dev' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
