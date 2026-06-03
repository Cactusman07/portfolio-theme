<?php
/**
 * Cactusman Portfolio — theme functions.
 *
 * Loads all theme modules from /inc/. Each module is responsible for a single
 * concern (theme setup, asset enqueue, post types, customizer, etc.) so this
 * file should stay short.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CMP_THEME_VERSION', '2.0.0' );
define( 'CMP_THEME_DIR', get_template_directory() );
define( 'CMP_THEME_URI', get_template_directory_uri() );

require_once CMP_THEME_DIR . '/inc/theme-setup.php';
require_once CMP_THEME_DIR . '/inc/enqueue.php';
require_once CMP_THEME_DIR . '/inc/post-types.php';
require_once CMP_THEME_DIR . '/inc/customizer.php';
require_once CMP_THEME_DIR . '/inc/helpers.php';
