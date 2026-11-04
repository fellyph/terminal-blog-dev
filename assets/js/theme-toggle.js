/**
 * Terminal Blog - Theme Toggle
 *
 * Handles dark/light mode switching with localStorage persistence
 * and prefers-color-scheme support.
 *
 * @since 1.0.0
 */

/* global localStorage, terminalBlogThemeI18n */

( function () {
	'use strict';

	const STORAGE_KEY = 'terminal-blog-theme';
	const DARK_THEME = 'dark';
	const LIGHT_THEME = 'light';
	let sessionTheme = null;
	const STRINGS =
		typeof terminalBlogThemeI18n === 'object'
			? terminalBlogThemeI18n
			: {
					dark: 'Escuro',
					light: 'Claro',
					toggle: 'Alternar tema de cores',
					current: 'Tema atual',
					changed: 'Tema alterado para',
			  };

	function getThemeLabel( theme ) {
		return theme === LIGHT_THEME ? STRINGS.light : STRINGS.dark;
	}

	/**
	 * Read a valid persisted preference when browser storage is available.
	 *
	 * @return {string|null} A saved theme, or null when unavailable or invalid.
	 */
	function getStoredTheme() {
		try {
			const stored = localStorage.getItem( STORAGE_KEY );
			return stored === DARK_THEME || stored === LIGHT_THEME
				? stored
				: null;
		} catch {
			return null;
		}
	}

	/**
	 * Get the user's preferred theme from various sources
	 * Priority: localStorage > system preference > default (dark)
	 */
	function getPreferredTheme() {
		// Keep an explicit choice for this page even when persistence fails.
		const preferred = sessionTheme || getStoredTheme();
		if ( preferred ) {
			return preferred;
		}

		// Check system preference
		if (
			window.matchMedia &&
			window.matchMedia( '(prefers-color-scheme: light)' ).matches
		) {
			return LIGHT_THEME;
		}

		// Default to dark (terminal aesthetic)
		return DARK_THEME;
	}

	/**
	 * Apply theme to the document
	 *
	 * @param {string} theme - The theme to apply ('dark' or 'light')
	 */
	function applyTheme( theme ) {
		document.documentElement.setAttribute( 'data-theme', theme );

		// Update all toggle buttons
		const toggles = document.querySelectorAll( '.theme-toggle' );
		toggles.forEach( function ( toggle ) {
			toggle.setAttribute( 'data-theme', theme );
			toggle.setAttribute(
				'aria-label',
				STRINGS.toggle +
					'. ' +
					STRINGS.current +
					': ' +
					getThemeLabel( theme ) +
					'.'
			);
			const valueEl = toggle.querySelector( '.theme-toggle__value' );
			if ( valueEl ) {
				valueEl.textContent = getThemeLabel( theme ).toUpperCase();
			}
		} );
	}

	/**
	 * Toggle between themes
	 */
	function toggleTheme() {
		const current =
			document.documentElement.getAttribute( 'data-theme' ) || DARK_THEME;
		const next = current === DARK_THEME ? LIGHT_THEME : DARK_THEME;

		// Persistence is optional; it must not block the visible state change.
		sessionTheme = next;
		try {
			localStorage.setItem( STORAGE_KEY, next );
		} catch {
			// The choice remains available for the rest of this page session.
		}

		// Apply with animation
		const toggles = document.querySelectorAll( '.theme-toggle' );
		toggles.forEach( function ( toggle ) {
			toggle.classList.add( 'switching' );
			setTimeout( function () {
				toggle.classList.remove( 'switching' );
			}, 300 );
		} );

		applyTheme( next );

		// Announce change for screen readers
		const announcement = document.createElement( 'div' );
		announcement.setAttribute( 'role', 'status' );
		announcement.setAttribute( 'aria-live', 'polite' );
		announcement.className = 'sr-only';
		announcement.textContent =
			STRINGS.changed + ' ' + getThemeLabel( next ) + '.';
		document.body.appendChild( announcement );
		setTimeout( function () {
			announcement.remove();
		}, 1000 );
	}

	/**
	 * Initialize theme toggle functionality
	 */
	function init() {
		// Apply initial theme immediately (before DOM ready for flash prevention)
		applyTheme( getPreferredTheme() );

		// Set up toggle button handlers when DOM is ready
		if ( document.readyState === 'loading' ) {
			document.addEventListener(
				'DOMContentLoaded',
				setupToggleHandlers
			);
		} else {
			setupToggleHandlers();
		}

		// Listen for system preference changes
		if ( window.matchMedia ) {
			window
				.matchMedia( '(prefers-color-scheme: light)' )
				.addEventListener( 'change', function ( e ) {
					// Only auto-switch if user hasn't manually set a preference
					if ( ! sessionTheme && ! getStoredTheme() ) {
						applyTheme( e.matches ? LIGHT_THEME : DARK_THEME );
					}
				} );
		}
	}

	/**
	 * Set up click handlers for toggle buttons
	 */
	function setupToggleHandlers() {
		const toggles = document.querySelectorAll( '.theme-toggle' );
		toggles.forEach( function ( toggle ) {
			toggle.addEventListener( 'click', toggleTheme );

			// Keyboard support
			toggle.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'Enter' || e.key === ' ' ) {
					e.preventDefault();
					toggleTheme();
				}
			} );
		} );

		// Re-apply theme to update button states
		applyTheme( getPreferredTheme() );
	}

	// Initialize immediately
	init();
} )();
