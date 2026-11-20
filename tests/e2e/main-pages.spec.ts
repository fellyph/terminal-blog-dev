import { test, expect, type Page } from '@playwright/test';

function trackRuntimeErrors( page: Page, { allow404 = false } = {} ) {
	const errors: string[] = [];

	page.on( 'pageerror', ( error ) => {
		errors.push( `pageerror: ${ error.message }` );
	} );

	page.on( 'console', ( message ) => {
		if ( message.type() === 'error' ) {
			const text = message.text();
			if ( allow404 && /status of 404 \(Not Found\)/i.test( text ) ) {
				return;
			}
			errors.push( `console.error: ${ text }` );
		}
	} );

	return errors;
}

async function assertCommonChrome( page: Page ) {
	await expect( page.locator( 'header.wp-block-template-part' ).first() ).toBeVisible();
	await expect( page.locator( 'main' ).first() ).toBeVisible();
	await expect( page.locator( 'footer.wp-block-template-part' ).first() ).toBeVisible();
	await expect( page.locator( 'meta[name="color-scheme"]' ) ).toHaveAttribute(
		'content',
		'dark light'
	);
}

test.describe( 'Terminal Blog Dev main pages', () => {
	test( 'home page renders and toggles light/dark theme', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );

		await expect( page.getByRole( 'heading', { level: 1, name: /TERMINAL_BLOG/i } ) ).toBeVisible();
		await expect( page.getByRole( 'link', { name: /Hello Terminal/i } ).first() ).toBeVisible();
		await expect( page.locator( 'form[toolname="search_blog"]' ) ).toHaveCount( 1 );

		// Performance checks: footer SVG has explicit dimensions, archive CSS is skipped, and navigation CSS is inlined.
		const footerArtImg = page.locator( '.terminal-footer-art--image img' );
		await expect( footerArtImg ).toHaveAttribute( 'width', '768' );
		await expect( footerArtImg ).toHaveAttribute( 'height', '144' );
		await expect( footerArtImg ).toHaveAttribute( 'loading', 'lazy' );
		await expect( page.locator( '#terminal-blog-dev-archive-inline-css, #terminal-blog-dev-archive-css' ) ).toHaveCount( 0 );
		await expect( page.locator( 'link#wp-block-navigation-css' ) ).toHaveCount( 0 );
		await expect( page.locator( '.terminal-footer__credits a' ) ).toHaveText( 'WordPress.org' );

		const initialTheme = await page.locator( 'html' ).getAttribute( 'data-theme' );
		expect( initialTheme ).toMatch( /^(dark|light)$/ );

		const toggle = page.locator( '.theme-toggle' ).first();
		await expect( toggle ).toBeVisible();
		await toggle.click();

		const nextTheme = initialTheme === 'dark' ? 'light' : 'dark';
		await expect( page.locator( 'html' ) ).toHaveAttribute( 'data-theme', nextTheme );
		expect( errors ).toEqual( [] );
	} );

	test( 'single post renders content, code accessibility, reading progress, and related posts', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/hello-terminal/' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );

		await expect( page.getByRole( 'heading', { level: 1, name: /Hello Terminal/i } ) ).toBeVisible();
		await expect( page.locator( '.terminal-reading-progress' ) ).toHaveAttribute( 'aria-hidden', 'true' );
		await expect( page.locator( 'pre.wp-block-code' ).first() ).toHaveAttribute( 'tabindex', '0' );
		await expect( page.locator( 'pre.wp-block-code' ).first() ).toHaveAttribute( 'role', 'region' );

		const related = page.locator( '.terminal-related-posts' );
		await expect( related ).toBeVisible();
		await expect( related.getByRole( 'link', { name: /Guia de Performance/i } ) ).toBeVisible();
		await expect( related.getByRole( 'link', { name: /Hello Terminal/i } ) ).toHaveCount( 0 );
		expect( errors ).toEqual( [] );
	} );

	test( 'standard page renders with default page template', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/sample-page/' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );
		await expect( page.getByRole( 'heading', { level: 1 } ) ).toBeVisible();
		expect( errors ).toEqual( [] );
	} );

	test( 'about page renders custom page-about template', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/about/' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );
		await expect( page.getByRole( 'heading', { level: 1, name: /SOBRE\.SH/i } ) ).toBeVisible();
		await expect( page.getByText( 'Conteudo da pagina sobre o projeto Terminal Blog Dev.' ) ).toBeVisible();
		expect( errors ).toEqual( [] );
	} );

	test( 'contact page renders custom page-contact template and empty state', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/contact/' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );
		await expect( page.getByRole( 'heading', { level: 1, name: /CONTATO\.TXT/i } ) ).toBeVisible();
		await expect( page.getByText( 'As informações de contato ainda não foram publicadas.' ) ).toBeVisible();
		expect( errors ).toEqual( [] );
	} );

	test( 'category archive page renders posts and decorative folder art', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/category/tutoriais/' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );
		await expect( page.getByRole( 'heading', { level: 1, name: /Tutoriais/i } ) ).toBeVisible();
		await expect( page.locator( '.terminal-archive__art' ) ).toHaveAttribute( 'aria-hidden', 'true' );
		await expect( page.locator( '.terminal-archive__art img' ) ).toHaveAttribute( 'width', '256' );
		await expect( page.locator( '.terminal-archive__art img' ) ).toHaveAttribute( 'height', '64' );
		await expect( page.locator( '#terminal-blog-dev-archive-inline-css, #terminal-blog-dev-archive-css' ) ).toHaveCount( 1 );
		await expect( page.getByRole( 'link', { name: 'Hello Terminal', exact: true } ) ).toBeVisible();
		expect( errors ).toEqual( [] );
	} );

	test( 'author archive page renders author profile and posts', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/author/admin/' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );
		await expect( page.getByRole( 'link', { name: 'Hello Terminal', exact: true } ) ).toBeVisible();
		expect( errors ).toEqual( [] );
	} );

	test( 'search results page returns matching posts', async ( { page } ) => {
		const errors = trackRuntimeErrors( page );
		const response = await page.goto( '/?s=Terminal' );

		expect( response?.status() ).toBe( 200 );
		await assertCommonChrome( page );
		await expect( page.getByRole( 'link', { name: 'Hello Terminal', exact: true } ) ).toBeVisible();
		expect( errors ).toEqual( [] );
	} );

	test( '404 page renders custom error template with HTTP 404 status', async ( { page } ) => {
		const errors = trackRuntimeErrors( page, { allow404: true } );
		const response = await page.goto( '/pagina-que-nao-existe-e2e/' );

		expect( response?.status() ).toBe( 404 );
		await assertCommonChrome( page );
		await expect( page.getByRole( 'heading', { level: 1, name: /ERRO 404/i } ) ).toBeVisible();
		await expect( page.getByRole( 'link', { name: /\[VOLTAR_AO_INÍCIO\]/i } ) ).toBeVisible();
		expect( errors ).toEqual( [] );
	} );

	test( 'single post serves Markdown when requested via Accept header', async ( { request } ) => {
		const response = await request.get( '/hello-terminal/', {
			headers: { Accept: 'text/markdown' },
		} );

		expect( response.status() ).toBe( 200 );
		expect( response.headers()[ 'content-type' ] ).toMatch( /^text\/markdown/i );
		expect( response.headers()[ 'x-content-type-options' ] ).toBe( 'nosniff' );

		const body = await response.text();
		expect( body ).toContain( '# Hello Terminal' );
		expect( body ).not.toContain( '<!DOCTYPE' );
	} );
} );
