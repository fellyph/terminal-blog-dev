<?php
/**
 * Title: Terminal Window
 * Slug: terminal-blog-dev/terminal-window
 * Categories: featured
 * Keywords: terminal, window, code
 * Description: A styled terminal window container.
 * Viewport Width: 800
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|large"}},"backgroundColor":"background-alt","layout":{"type":"default"}} -->
<div class="wp-block-group has-background-alt-background-color has-background has-border-color" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--large);padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
	<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|30","bottom":"var:preset|spacing|20","left":"var:preset|spacing|30"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--30)">
		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.5rem"}},"textColor":"error"} -->
			<p class="has-error-color has-text-color" style="font-size:0.5rem">●</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.5rem"}},"textColor":"warning"} -->
			<p class="has-warning-color has-text-color" style="font-size:0.5rem">●</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.5rem"}},"textColor":"primary"} -->
			<p class="has-primary-color has-text-color" style="font-size:0.5rem">●</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size" style="text-transform:uppercase">TERMINAL_v3.0</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
	<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
		<p class="has-primary-color has-text-color has-small-font-size" style="text-transform:uppercase">C:\USER\BLOG&gt; _</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"textColor":"foreground"} -->
		<p class="has-foreground-color has-text-color"><?php esc_html_e( 'Use esta janela para destacar informações ou trechos de código.', 'terminal-blog-dev' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
