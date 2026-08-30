<?php
/**
 * Title: Theme Toggle
 * Slug: terminal-blog-dev/theme-toggle
 * Categories: header
 * Keywords: theme, toggle, dark, light, mode, switch
 * Description: Dark/light mode toggle button with terminal aesthetic.
 * Viewport Width: 400
 */
?>
<!-- wp:group {"className":"theme-toggle-wrapper","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group theme-toggle-wrapper">
	<!-- wp:html -->
	<button
		class="theme-toggle"
		type="button"
		aria-label="<?php esc_attr_e( 'Alternar tema de cores. Tema atual: escuro.', 'terminal-blog-dev' ); ?>"
		data-theme="dark"
	>
		<span class="theme-toggle__label">[TEMA:</span>
		<span class="theme-toggle__value">ESCURO</span>
		<span class="theme-toggle__label">]</span>
	</button>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
