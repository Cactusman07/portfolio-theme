<?php
/**
 * Archive portfolio items template shim.
 *
 * Routes `portfolio_items` archive requests through the shared archive template.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require locate_template( 'archive-portfolio.php', false, false );
