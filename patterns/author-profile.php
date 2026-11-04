<?php
/**
 * Title: Author Profile - Terminal Style
 * Slug: terminal-blog-dev/author-profile
 * Categories: featured
 * Keywords: author, profile, user, whoami
 * Description: A terminal-style author profile with whoami command aesthetic.
 * Template Types: author
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">

	<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em"}},"textColor":"foreground-muted","fontSize":"small"} -->
		<p class="has-foreground-muted-color has-text-color has-small-font-size" style="letter-spacing:0.1em;text-transform:uppercase">// PERFIL_DO_AUTOR</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|40","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40"},"margin":{"top":"var:preset|spacing|30"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"default"}} -->
		<div class="wp-block-group has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);margin-top:var(--wp--preset--spacing--30);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:paragraph {"fontFamily":"monospace","textColor":"primary","fontSize":"medium"} -->
			<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family">&gt; whoami</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--50)">

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}},"border":{"width":"2px","color":"var:preset|color|primary","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--primary);border-width:2px;border-radius:var(--wp--custom--border-radius--medium);padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
				<!-- wp:avatar {"size":120,"style":{"border":{"radius":"var:custom|border-radius|medium"}}} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:post-author-name {"isLink":false,"style":{"typography":{"fontSize":"clamp(1.5rem, 4vw, 2.5rem)","textTransform":"uppercase","letterSpacing":"0.02em","fontStyle":"normal","fontWeight":"700"}}} /-->


				<!-- wp:post-author-biography {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}},"typography":{"lineHeight":"1.7"}},"textColor":"foreground-muted"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"border"} -->
	<hr class="wp-block-separator alignwide has-text-color has-border-color has-alpha-channel-opacity has-border-background-color has-background" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--60)"/>
	<!-- /wp:separator -->

	<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|40","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40"},"margin":{"bottom":"var:preset|spacing|40"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"default"}} -->
		<div class="wp-block-group has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);margin-bottom:var(--wp--preset--spacing--40);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:paragraph {"fontFamily":"monospace","textColor":"primary","fontSize":"medium"} -->
			<p class="has-primary-color has-text-color has-medium-font-size has-monospace-font-family">&gt; ls -la ~/posts --limit=6</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:query {"queryId":20,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide"} -->
		<div class="wp-block-query alignwide">
			<!-- wp:post-template {"layout":{"type":"default"}} -->
				<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|40","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40"},"margin":{"bottom":"var:preset|spacing|20"}},"border":{"width":"1px","color":"var:preset|color|border","radius":"var:custom|border-radius|medium"}},"backgroundColor":"background-alt","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
				<div class="wp-block-group has-border-color has-background-alt-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--custom--border-radius--medium);margin-bottom:var(--wp--preset--spacing--20);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} -->
						<p class="has-foreground-muted-color has-text-color has-small-font-size has-monospace-font-family" style="text-transform:uppercase">-rw-r--r--</p>
						<!-- /wp:paragraph -->

						<!-- wp:post-date {"format":"Y-m-d","fontFamily":"monospace","style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted","fontSize":"small"} /-->

						<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontSize":"1rem","textTransform":"uppercase"},"layout":{"selfStretch":"fill","flexSize":null}}} /-->
					</div>
					<!-- /wp:group -->

					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:post-terms {"term":"category","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} /-->

						<!-- wp:read-more {"content":"[LER_ARTIGO]","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"fontSize":"small"} /-->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			<!-- /wp:post-template -->

			<!-- wp:query-no-results -->
				<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"foreground-muted"} -->
				<p class="has-foreground-muted-color has-text-color" style="text-transform:uppercase"><?php esc_html_e( 'Este autor ainda não tem artigos publicados.', 'terminal-blog-dev' ); ?></p>
				<!-- /wp:paragraph -->
			<!-- /wp:query-no-results -->

			<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:query-pagination {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"fontSize":"small","layout":{"type":"flex","justifyContent":"center"}} -->
					<!-- wp:query-pagination-previous {"label":"[<< ANTERIORES]"} /-->
					<!-- wp:query-pagination-numbers /-->
					<!-- wp:query-pagination-next {"label":"[PRÓXIMOS >>]"} /-->
				<!-- /wp:query-pagination -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:query -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
