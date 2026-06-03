<?php
/**
 * Script and style enqueue.
 *
 * In development the front-end pulls assets from the Vite dev server on
 * localhost:5173 (HMR enabled). In production it loads compiled files from
 * /assets/build/. Toggle by defining CMP_DEV in wp-config.php:
 *
 *     define( 'CMP_DEV', true );
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether we're in dev mode (Vite dev server).
 */
function cmp_is_dev() {
	return defined( 'CMP_DEV' ) && CMP_DEV;
}

/**
 * Front-end script + style enqueue.
 */
function cmp_enqueue_assets() {
	// Google Fonts — preconnect + the actual stylesheet.
	wp_enqueue_style(
		'cmp-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Press+Start+2P&display=swap',
		array(),
		null
	);

	if ( cmp_is_dev() ) {
		// Vite dev server — inject the HMR client and the entry as a module.
		add_action(
			'wp_head',
			function () {
				echo '<script type="module" src="http://localhost:5173/@vite/client"></script>' . "\n";
				echo '<script type="module" src="http://localhost:5173/src/js/main.js"></script>' . "\n";
			},
			1
		);
		return;
	}

	// Production: load the compiled CSS + JS.
	$build_dir = CMP_THEME_DIR . '/assets/build';
	$build_url = CMP_THEME_URI . '/assets/build';

	$css_file = $build_dir . '/main.css';
	$js_file  = $build_dir . '/main.js';

	if ( file_exists( $css_file ) ) {
		wp_enqueue_style(
			'cmp-main',
			$build_url . '/main.css',
			array( 'cmp-fonts' ),
			filemtime( $css_file )
		);
	}

	if ( file_exists( $js_file ) ) {
		wp_enqueue_script(
			'cmp-main',
			$build_url . '/main.js',
			array(),
			filemtime( $js_file ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);

		// Pass data from PHP to JS (landmark x-coords, REST nonces, etc).
		wp_localize_script(
			'cmp-main',
			'CMP',
			array(
				'restUrl'    => esc_url_raw( rest_url( 'cmp/v1/' ) ),
				'restNonce'  => wp_create_nonce( 'wp_rest' ),
				'homeUrl'    => esc_url( home_url( '/' ) ),
				'themeUrl'   => esc_url( CMP_THEME_URI ),
				'landmarks'  => cmp_get_landmark_positions(),
				'isFront'    => is_front_page(),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'cmp_enqueue_assets' );

/**
 * Preconnect to Google Fonts for slightly faster typography load.
 */
function cmp_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com' );
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	if ( 'dns-prefetch' === $relation ) {
		$hints[] = 'https://official-joke-api.appspot.com';
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'cmp_resource_hints', 10, 2 );
