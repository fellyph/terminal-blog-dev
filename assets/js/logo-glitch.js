/**
 * 3D Glitch effect for the site logo using GSAP.
 *
 * Progressive enhancement: the logo is fully visible by default (CSS).
 * This script adds RGB-split layers and GSAP-driven 3D glitch motion.
 * Respects prefers-reduced-motion: when reduced, it does nothing.
 */
( function () {
	'use strict';

	if ( typeof window.gsap === 'undefined' ) {
		return;
	}

	const reduceMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	);
	if ( reduceMotion.matches ) {
		return;
	}

	function init() {
		const logoLink = document.querySelector(
			'.wp-block-site-logo .custom-logo-link'
		);
		const logoImg = logoLink && logoLink.querySelector( '.custom-logo' );
		if ( ! logoLink || ! logoImg ) {
			return;
		}

		// Build a 3D stage that holds the original image plus two RGB-split clones.
		const stage = document.createElement( 'span' );
		stage.className = 'logo-glitch-stage';

		const base = document.createElement( 'span' );
		base.className = 'logo-glitch-layer logo-glitch-base';

		const red = document.createElement( 'span' );
		red.className = 'logo-glitch-layer logo-glitch-red';

		const cyan = document.createElement( 'span' );
		cyan.className = 'logo-glitch-layer logo-glitch-cyan';

		const src = logoImg.currentSrc || logoImg.src;
		[ base, red, cyan ].forEach( function ( layer ) {
			layer.style.backgroundImage = 'url("' + src + '")';
		} );

		stage.appendChild( red );
		stage.appendChild( cyan );
		stage.appendChild( base );

		// Hide the real image (keep it for a11y/SEO) and mount the stage.
		logoImg.classList.add( 'logo-glitch-source-hidden' );
		logoLink.classList.add( 'logo-glitch-ready' );
		logoLink.appendChild( stage );

		const gsap = window.gsap;

		// Continuous gentle 3D float so the mark feels alive.
		gsap.to( stage, {
			rotationY: 14,
			rotationX: -8,
			duration: 4,
			ease: 'sine.inOut',
			yoyo: true,
			repeat: -1,
			transformPerspective: 600,
			transformOrigin: '50% 50%',
		} );

		// Reset helper for the RGB layers.
		function resetLayers() {
			gsap.set( [ red, cyan ], { x: 0, y: 0, opacity: 0, skewX: 0 } );
			gsap.set( base, { x: 0, y: 0, skewX: 0, rotationZ: 0 } );
		}
		resetLayers();

		// One glitch burst: rapid RGB offset + slice + 3D jitter.
		function glitchBurst( intensity ) {
			const i = intensity || 1;
			const tl = gsap.timeline( {
				defaults: {
					ease: 'rough({ strength: 2, points: 16, taper: none, randomize: true })',
				},
			} );
			const dx = function () {
				return ( Math.random() * 14 - 7 ) * i;
			};
			const dy = function () {
				return ( Math.random() * 8 - 4 ) * i;
			};

			tl.to(
				red,
				{ x: dx, y: dy, opacity: 0.85, skewX: 8 * i, duration: 0.06 },
				0
			)
				.to(
					cyan,
					{
						x: dx,
						y: dy,
						opacity: 0.85,
						skewX: -8 * i,
						duration: 0.06,
					},
					0
				)
				.to(
					base,
					{
						x: dx() * 0.4,
						skewX: 4 * i,
						rotationZ: ( Math.random() * 4 - 2 ) * i,
						duration: 0.06,
					},
					0
				)
				.to( red, { x: dx, y: dy, duration: 0.05 }, 0.06 )
				.to( cyan, { x: dx, y: dy, duration: 0.05 }, 0.06 )
				.to(
					[ red, cyan ],
					{
						x: 0,
						y: 0,
						opacity: 0,
						skewX: 0,
						duration: 0.08,
						ease: 'power2.out',
					},
					0.14
				)
				.to(
					base,
					{
						x: 0,
						skewX: 0,
						rotationZ: 0,
						duration: 0.08,
						ease: 'power2.out',
					},
					0.14
				);
			return tl;
		}

		// Schedule random idle glitches.
		function scheduleIdle() {
			const delay = 2.4 + Math.random() * 4.5;
			gsap.delayedCall( delay, function () {
				glitchBurst( 1 );
				scheduleIdle();
			} );
		}
		scheduleIdle();

		// Intensify on hover/focus.
		let hoverLoop;
		function startHover() {
			stopHover();
			hoverLoop = gsap.timeline( { repeat: -1 } );
			hoverLoop.add( glitchBurst( 1.8 ) ).to( {}, { duration: 0.18 } );
		}
		function stopHover() {
			if ( hoverLoop ) {
				hoverLoop.kill();
				hoverLoop = null;
				resetLayers();
			}
		}
		logoLink.addEventListener( 'mouseenter', startHover );
		logoLink.addEventListener( 'mouseleave', stopHover );
		logoLink.addEventListener( 'focus', startHover );
		logoLink.addEventListener( 'blur', stopHover );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
