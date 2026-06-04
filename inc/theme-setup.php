<?php
/**
 * Theme setup — add_theme_support calls, image sizes, nav menus.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core theme support flags.
 */
function cmp_theme_setup() {
	// Site title is rendered manually in header.php.
	add_theme_support( 'title-tag' );

	// Featured images on portfolio items and blog posts.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'cmp-quest-card', 600, 400, true );
	add_image_size( 'cmp-portfolio-hero', 1600, 900, true );

	// Modern HTML5 markup from WordPress core widgets etc.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Pull in <title>, feed links, and core block styles.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	// Editor styles — points the block editor at our compiled CSS.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/build/editor.css' );

	// Custom logo support (used in the menu-bar brand).
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 24,
			'width'       => 24,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Two registered nav menus — but the front page uses its own custom one.
	register_nav_menus(
		array(
			'primary' => __( 'Primary menu (used on blog/portfolio pages)', 'cactusman-portfolio' ),
			'footer'  => __( 'Footer links', 'cactusman-portfolio' ),
		)
	);

	// Make the theme translatable.
	load_theme_textdomain( 'cactusman-portfolio', CMP_THEME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'cmp_theme_setup' );

/**
 * Content width — used by oEmbed and large block alignments.
 */
function cmp_content_width() {
	$GLOBALS['content_width'] = 960;
}
add_action( 'after_setup_theme', 'cmp_content_width', 0 );

/**
 * Body classes — adds helpful classes for the JS shell to key off.
 */
function cmp_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'cmp-world';
	}
	if ( is_singular( 'portfolio_items' ) ) {
		$classes[] = 'cmp-portfolio-single';
	}
	if ( is_singular( 'post' ) || is_home() || is_archive() || is_404() || is_page( 'portfolio' ) ) {
		$classes[] = 'cmp-paper';
	}
	return $classes;
}
add_filter( 'body_class', 'cmp_body_classes' );

/**
 * Fallback favicon links when Site Icon is not configured in Customizer.
 */
function cmp_output_favicon_fallback() {
	if ( has_site_icon() ) {
		return;
	}

	$favicon_uri = get_theme_file_uri( 'favicon.ico' );
	echo '<link rel="icon" href="' . esc_url( $favicon_uri ) . '" sizes="any" />' . "\n";
	echo '<link rel="shortcut icon" href="' . esc_url( $favicon_uri ) . '" />' . "\n";
}
add_action( 'wp_head', 'cmp_output_favicon_fallback', 1 );

/**
 * Serve /favicon.ico from the theme if Site Icon is not configured.
 */
function cmp_serve_favicon_fallback() {
	if ( has_site_icon() ) {
		return;
	}

	$favicon_path = get_theme_file_path( 'favicon.ico' );
	if ( ! file_exists( $favicon_path ) ) {
		return;
	}

	header( 'Content-Type: image/x-icon' );
	header( 'Cache-Control: public, max-age=604800' );
	readfile( $favicon_path );
	exit;
}
add_action( 'do_faviconico', 'cmp_serve_favicon_fallback', 0 );
