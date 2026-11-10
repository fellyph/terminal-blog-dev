const assert = require( 'node:assert/strict' );
const { execFile } = require( 'node:child_process' );
const { promisify } = require( 'node:util' );
const path = require( 'node:path' );
const { test } = require( 'node:test' );
const run = promisify( execFile );

// CGI exercises PHP's real header handling without a listening socket or database.
async function request( route = '/', options = {} ) {
	const { stdout, stderr } = await run( process.env.PHP_CGI || 'php-cgi', [], {
		env: {
			...process.env,
			GATEWAY_INTERFACE: 'CGI/1.1',
			REDIRECT_STATUS: '200',
			SCRIPT_FILENAME: path.join( __dirname, 'agent-readiness-http-router.php' ),
			SCRIPT_NAME: '/agent-readiness-http-router.php',
			REQUEST_URI: route,
			REQUEST_METHOD: options.method || 'GET',
			HTTP_ACCEPT: options.headers?.Accept || '*/*',
			SERVER_PROTOCOL: 'HTTP/1.1',
			SERVER_NAME: 'example.test',
			SERVER_PORT: '80',
			CONTENT_LENGTH: '0',
		},
		maxBuffer: 1024 * 1024,
	} );
	assert.equal( stderr, '', 'PHP must not emit warnings or errors.' );
	const boundary = stdout.indexOf( '\r\n\r\n' );
	assert.notEqual( boundary, -1, 'CGI response must contain HTTP headers.' );
	const headers = new Headers();
	for ( const line of stdout.slice( 0, boundary ).split( '\r\n' ) ) {
		const colon = line.indexOf( ':' );
		headers.append( line.slice( 0, colon ), line.slice( colon + 1 ).trim() );
	}
	const body = stdout.slice( boundary + 4 );
	return { status: Number.parseInt( headers.get( 'status' ) || '200', 10 ), headers, text: async () => body };
}

for ( const [ name, accept, markdown ] of [
	[ 'default HTML', '*/*', false ],
	[ 'explicit Markdown', 'text/markdown', true ],
	[ 'HTML preferred', 'text/html, text/markdown;q=0.5', false ],
	[ 'Markdown preferred', 'text/html;q=0.5, text/markdown', true ],
	[ 'HTML wins a tie', 'text/html, text/markdown', false ],
	[ 'Markdown forbidden', 'text/markdown;q=0, */*', false ],
] ) {
	test( name, async () => {
		const response = await request( '/', { headers: { Accept: accept } } );
		const body = await response.text();
		assert.equal( response.status, 200 );
		assert.match( response.headers.get( 'content-type' ), markdown ? /^text\/markdown/ : /^text\/html/ );
		assert.match( response.headers.get( 'vary' ), /Accept-Encoding/ );
		assert.match( response.headers.get( 'vary' ), /(?:^|,\s*)Accept(?:,|$)/ );
		assert.match( response.headers.get( 'link' ), /rel="preload"/ );
		assert.match( response.headers.get( 'link' ), /rel="service-desc"/ );
		assert.match( response.headers.get( 'link' ), /rel="service-doc"/ );
		if ( markdown ) {
			assert.match( body, /^# Public article\n/ );
			assert.match( body, /\[guide\]\(<https:\/\/example.test\/guide\/>\)/ );
			assert.match( body, /```js\nconst value = 1;\n\n {2}keep_indentation\(\);\n```/ );
			assert.doesNotMatch( body, /<!doctype|Site navigation|Site footer|<form/ );
			assert.match( response.headers.get( 'cache-control' ), /no-store/ );
			assert.equal( response.headers.get( 'x-content-type-options' ), 'nosniff' );
		} else {
			assert.match( body, /<!doctype html>/ );
			assert.match( body, /method="get" action="https:\/\/example.test\/"/ );
			assert.equal( ( body.match( /toolname="search_blog"/g ) || [] ).length, 1 );
			assert.equal( ( body.match( /<form/g ) || [] ).length, 2 );
			assert.equal( response.headers.get( 'cache-control' ), null );
		}
	} );
}

test( 'HEAD returns the negotiated type with no body', async () => {
	for ( const accept of [ 'text/markdown', 'text/html' ] ) {
		const response = await request( '/article/', { method: 'HEAD', headers: { Accept: accept } } );
		assert.ok( response.headers.get( 'content-type' ).startsWith( accept ) );
		assert.equal( await response.text(), '' );
	}
} );

test( 'protected and non-editorial requests retain their original representation', async () => {
	for ( const route of [ 'protected', 'private', 'draft', 'plugin', 'logged-in', 'preview', 'feed', 'rest', 'disabled' ] ) {
		const response = await request( `/${ route }/`, { headers: { Accept: 'text/markdown' } } );
		assert.match( response.headers.get( 'content-type' ), /^text\/html/, route );
		assert.match( await response.text(), /<!doctype html>/, route );
	}
} );

test( 'POST stays HTML even with a Markdown preference', async () => {
	const response = await request( '/', { method: 'POST', headers: { Accept: 'text/markdown' } } );
	assert.match( response.headers.get( 'content-type' ), /^text\/html/ );
} );

test( 'all existing Vary fields are preserved', async () => {
	const response = await request( '/vary-multiple/' );
	const vary = response.headers.get( 'vary' );
	assert.match( vary, /Accept-Encoding/ );
	assert.match( vary, /Cookie/ );
	assert.match( vary, /(?:^|,\s*)Accept(?:,|$)/ );
	const wildcard = await request( '/vary-star/' );
	assert.equal( wildcard.headers.get( 'vary' ), '*' );
} );
