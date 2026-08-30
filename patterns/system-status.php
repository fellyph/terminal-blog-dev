<?php
/**
 * Title: System Status Widget
 * Slug: terminal-blog-dev/system-status
 * Categories: featured, footer
 * Keywords: status, widget, metrics, system
 * Description: A terminal-style system status widget with metrics and activity log.
 * Viewport Width: 620
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"default"},"className":"system-status-widget"} -->
<div class="wp-block-group system-status-widget has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">

	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em","fontStyle":"normal","fontWeight":"700"}},"textColor":"primary","fontSize":"small"} -->
		<p class="has-primary-color has-text-color has-small-font-size" style="font-style:normal;font-weight:700;letter-spacing:0.1em;text-transform:uppercase">SYSTEM_STATUS</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small","className":"status-indicator-online"} -->
		<p class="has-primary-color has-text-color has-small-font-size status-indicator-online" style="text-transform:uppercase">● ONLINE</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"border"} -->
	<hr class="wp-block-separator has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"/>
	<!-- /wp:separator -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
		<p class="has-primary-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">CONNECTION: ESTABLISHED</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">SOCKET: 443/TLS</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">PROTOCOL: HTTPS/2</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"border"} -->
	<hr class="wp-block-separator has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"/>
	<!-- /wp:separator -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
				<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">LATENCY:</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"700"}},"textColor":"primary","fontSize":"medium"} -->
				<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family" style="font-style:normal;font-weight:700;text-transform:uppercase">42ms</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
				<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">UPTIME:</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"700"}},"textColor":"primary","fontSize":"medium"} -->
				<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family" style="font-style:normal;font-weight:700;text-transform:uppercase">99.9%</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"},"margin":{"top":"var:preset|spacing|20"}}}} -->
	<div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--20)">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
				<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">MEMORY:</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"700"}},"textColor":"primary","fontSize":"medium"} -->
				<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family" style="font-style:normal;font-weight:700;text-transform:uppercase">640KB</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
				<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">TEMP:</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"700"}},"textColor":"primary","fontSize":"medium"} -->
				<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family" style="font-style:normal;font-weight:700;text-transform:uppercase">38°C</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"border"} -->
	<hr class="wp-block-separator has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"/>
	<!-- /wp:separator -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">BANDWIDTH: <span class="has-primary-color">████████</span><span class="has-foreground-muted-color">░░</span> 82%</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">CACHE: <span class="has-primary-color">██████████</span> 100%</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"border"} -->
	<hr class="wp-block-separator has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"/>
	<!-- /wp:separator -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size" style="letter-spacing:0.05em;text-transform:uppercase">ACTIVITY_LOG:</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family">[14:32] <span class="has-primary-color">CACHE_HIT</span> ./posts/</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family">[14:31] <span class="has-primary-color">REQUEST</span> GET /api</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family">[14:30] <span class="has-primary-color">RESPONSE</span> 200 OK</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|20"}}},"backgroundColor":"border"} -->
	<hr class="wp-block-separator has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--20)"/>
	<!-- /wp:separator -->

	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">SYNC: AUTO</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">v3.0.6</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
