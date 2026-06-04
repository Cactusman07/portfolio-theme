<?php
/**
 * Single legacy portfolio item template shim.
 *
 * Routes `portfolio_items` requests through the shared single portfolio template.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require locate_template( 'single-portfolio.php', false, false );
