<?php
/**
 * Title: Navegação do cabeçalho
 * Slug: terminal-blog-dev/header-navigation
 * Inserter: false
 * Description: Reutiliza o menu do cabeçalho atual ou oferece uma lista de páginas.
 */

$terminal_header_navigation = array(
	'ariaLabel'              => __( 'Menu principal', 'terminal-blog-dev' ),
	'overlayMenu'            => 'mobile',
	'textColor'              => 'foreground',
	'overlayBackgroundColor' => 'background',
	'overlayTextColor'       => 'foreground',
	'className'              => 'terminal-header__navigation',
	'layout'                 => array( 'type' => 'flex', 'justifyContent' => 'left' ),
);
$terminal_header_menu = terminal_blog_dev_current_header_menu();
if ( $terminal_header_menu ) {
	$terminal_header_navigation['ref'] = $terminal_header_menu;
}
// Attributes come from the active header and the theme, never user-supplied HTML.
echo '<!-- wp:navigation ' . wp_json_encode( $terminal_header_navigation, JSON_HEX_TAG | JSON_HEX_AMP ) . ( $terminal_header_menu ? ' /-->' : ' --><!-- wp:page-list /--><!-- /wp:navigation -->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
