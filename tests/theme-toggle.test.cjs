const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const test = require( 'node:test' );
const vm = require( 'node:vm' );

const themeRoot = path.resolve( __dirname, '..' );
const script = fs.readFileSync( path.join( themeRoot, 'assets/js/theme-toggle.js' ), 'utf8' );
const earlyScript = fs.readFileSync( path.join( themeRoot, 'functions.php' ), 'utf8' )
	.match( /<script id="terminal-early-theme">([\s\S]*?)<\/script>/ )[ 1 ];

function browser( { stored = null, light = false, blockReads = false, blockWrites = false } = {} ) {
	const attributes = {};
	const listeners = {};
	const buttonAttributes = {};
	const value = { textContent: '' };
	const button = {
		setAttribute( key, val ) { buttonAttributes[ key ] = val; },
		querySelector() { return value; },
		addEventListener( name, callback ) { listeners[ name ] = callback; },
		classList: { add() {}, remove() {} },
	};
	let preferenceListener;
	const media = {
		matches: light,
		addEventListener( name, callback ) { preferenceListener = callback; },
	};
	const storage = {
		getItem() {
			if ( blockReads ) { throw new Error( 'SecurityError' ); }
			return stored;
		},
		setItem( key, val ) {
			if ( blockWrites ) { throw new Error( 'QuotaExceededError' ); }
			stored = val;
		},
	};
	const context = {
		localStorage: storage,
		window: { matchMedia() { return media; } },
		document: {
			readyState: 'complete',
			documentElement: {
				setAttribute( key, val ) { attributes[ key ] = val; },
				getAttribute( key ) { return attributes[ key ]; },
			},
			querySelectorAll() { return [ button ]; },
			createElement() { return { setAttribute() {}, remove() {} }; },
			body: { appendChild() {} },
		},
		setTimeout( callback ) { callback(); },
	};
	return {
		run( source = script ) { vm.runInNewContext( source, context ); },
		theme() { return attributes[ 'data-theme' ]; },
		label() { return value.textContent; },
		accessibleLabel() { return buttonAttributes[ 'aria-label' ]; },
		saved() { return stored; },
		click() { listeners.click(); },
		key( key ) { listeners.keydown( { key, preventDefault() {} } ); },
		system( next ) { media.matches = next; preferenceListener( { matches: next } ); },
	};
}

test( 'valid saved preference takes precedence over the system', () => {
	const page = browser( { stored: 'light' } );
	page.run();
	assert.equal( page.theme(), 'light' );
	assert.equal( page.label(), 'CLARO' );
	page.system( false );
	assert.equal( page.theme(), 'light' );
} );

test( 'blocked reads still apply the system preference and install handlers', () => {
	const page = browser( { blockReads: true, blockWrites: true, light: true } );
	page.run();
	assert.equal( page.theme(), 'light' );
	page.click();
	assert.equal( page.theme(), 'dark' );
	assert.equal( page.label(), 'ESCURO' );
	assert.match( page.accessibleLabel(), /Escuro/ );
	page.system( true );
	assert.equal( page.theme(), 'dark' );
} );

test( 'blocked writes do not prevent clicking or keyboard switching', () => {
	const page = browser( { blockWrites: true } );
	page.run();
	page.click();
	assert.equal( page.theme(), 'light' );
	assert.equal( page.label(), 'CLARO' );
	page.key( 'Enter' );
	assert.equal( page.theme(), 'dark' );
	page.key( ' ' );
	assert.equal( page.theme(), 'light' );
	assert.equal( page.saved(), null );
} );

test( 'working storage persists the selected theme', () => {
	const page = browser();
	page.run();
	page.click();
	assert.equal( page.saved(), 'light' );
	const reloaded = browser( { stored: page.saved() } );
	reloaded.run();
	assert.equal( reloaded.theme(), 'light' );
} );

test( 'system changes apply until an explicit theme is selected', () => {
	const page = browser( { blockReads: true } );
	page.run();
	page.system( true );
	assert.equal( page.theme(), 'light' );
	page.click();
	page.system( true );
	assert.equal( page.theme(), 'dark' );
} );

test( 'an invalid saved value does not disable system preference updates', () => {
	const page = browser( { stored: 'invalid' } );
	page.run();
	assert.equal( page.theme(), 'dark' );
	page.system( true );
	assert.equal( page.theme(), 'light' );
} );

for ( const options of [
	{ blockReads: true, light: true, expected: 'light' },
	{ blockReads: true, light: false, expected: 'dark' },
	{ stored: 'invalid', light: true, expected: 'light' },
	{ stored: 'dark', light: true, expected: 'dark' },
] ) {
	test( 'early detection: ' + JSON.stringify( options ), () => {
		const page = browser( options );
		page.run( earlyScript );
		assert.equal( page.theme(), options.expected );
	} );
}
