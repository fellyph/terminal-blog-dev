/**
 * Run the decorative SVG only while it is visible and motion is welcome.
 */
/* global terminalFooterMotionI18n */
( function () {
	'use strict';

	if ( ! window.IntersectionObserver || ! window.matchMedia ) {
		return;
	}

	const reducedMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	);
	const labels =
		typeof terminalFooterMotionI18n === 'object'
			? terminalFooterMotionI18n
			: {
					pause: 'Pausar',
					play: 'Animar',
					pauseLabel: 'Pausar animação do rodapé',
					playLabel: 'Reproduzir animação do rodapé',
			  };

	document
		.querySelectorAll( '.terminal-footer-art' )
		.forEach( function ( art ) {
			let visible = false;
			let paused = false;
			const button = document.createElement( 'button' );
			button.type = 'button';
			button.className = 'terminal-footer-art__toggle';
			art.appendChild( button );

			function update() {
				art.classList.toggle(
					'is-animated',
					visible &&
						! document.hidden &&
						! reducedMotion.matches &&
						! paused
				);
				button.hidden = reducedMotion.matches;
				button.textContent = paused ? labels.play : labels.pause;
				button.setAttribute(
					'aria-label',
					paused ? labels.playLabel : labels.pauseLabel
				);
			}

			button.addEventListener( 'click', function () {
				paused = ! paused;
				update();
			} );

			const observer = new window.IntersectionObserver( function (
				entries
			) {
				visible = entries[ 0 ].isIntersecting;
				update();
			} );
			observer.observe( art );
			document.addEventListener( 'visibilitychange', update );
			reducedMotion.addEventListener( 'change', update );
			update();
		} );
} )();
