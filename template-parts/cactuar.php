<?php
/**
 * Cactuar — the player sprite.
 *
 * Uses animated GIF states (idle + walk) and swaps the image source in JS
 * based on movement direction.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$asset_base = trailingslashit( get_template_directory_uri() ) . 'assets/';
?>
<div
	class="cactuar"
	id="cactuar"
	style="left: 280px;"
	data-idle-south="<?php echo esc_url( $asset_base . 'Cactus_man_breathing-idle_south.gif' ); ?>"
	data-idle-east="<?php echo esc_url( $asset_base . 'Cactus_man_breathing-idle_east.gif' ); ?>"
	data-idle-west="<?php echo esc_url( $asset_base . 'Cactus_man_breathing-idle_west.gif' ); ?>"
	data-walk-east="<?php echo esc_url( $asset_base . 'Cactus_man_walk_east.gif' ); ?>"
	data-walk-west="<?php echo esc_url( $asset_base . 'Cactus_man_walk_west.gif' ); ?>"
>
	<div class="cactuar-bubble" id="cactuar-bubble" aria-live="polite"></div>
	<img id="cactuar-sprite" src="<?php echo esc_url( $asset_base . 'Cactus_man_breathing-idle_south.gif' ); ?>" alt="Cactusman" aria-hidden="true" />
</div>
