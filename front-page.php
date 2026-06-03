<?php
/**
 * Front page — the walking pixel world.
 *
 * Renders the game window (sky, mesh canvas, parallax, world, cactuar) and the
 * bottom menu. All panel content is rendered server-side into hidden templates
 * which the JS swaps into the .panel-overlay when activated.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$landmarks = cmp_get_landmark_positions();
?>

<main id="cmp-main" class="game-window" data-cmp-world>

	<div class="sky" aria-hidden="true"></div>
	<div class="night-overlay" id="night-overlay" aria-hidden="true"></div>
	<div class="stars-night" id="stars-night" aria-hidden="true">
		<?php get_template_part( 'template-parts/partials/stars-night' ); ?>
	</div>
	<div class="sky-fx" id="sky-fx" aria-hidden="true"></div>

	<canvas id="mesh-canvas" aria-hidden="true"></canvas>

	<?php get_template_part( 'template-parts/parallax' ); ?>

	<div class="ground" aria-hidden="true"></div>

	<?php get_template_part( 'template-parts/hero-sign' ); ?>

	<div class="world" id="world">

		<?php get_template_part( 'template-parts/landmarks', null, array( 'positions' => $landmarks ) ); ?>

		<?php get_template_part( 'template-parts/cactuar' ); ?>

	</div>

	<aside class="controls-hint" id="controls-hint" aria-label="<?php esc_attr_e( 'Keyboard controls', 'cactusman-portfolio' ); ?>">
		<button class="close" id="hint-close" aria-label="<?php esc_attr_e( 'Close', 'cactusman-portfolio' ); ?>">✕</button>
		<h5>// <?php esc_html_e( 'controls', 'cactusman-portfolio' ); ?></h5>
		<p><kbd>A</kbd> <kbd>D</kbd> <?php esc_html_e( 'or', 'cactusman-portfolio' ); ?> <kbd>←</kbd> <kbd>→</kbd> <?php esc_html_e( 'to walk', 'cactusman-portfolio' ); ?></p>
		<p><kbd>E</kbd> <?php esc_html_e( 'or', 'cactusman-portfolio' ); ?> <kbd>SPACE</kbd> <?php esc_html_e( 'to interact', 'cactusman-portfolio' ); ?></p>
		<p><?php esc_html_e( 'Click ground to walk there', 'cactusman-portfolio' ); ?></p>
	</aside>

	<div class="touch-controls" id="touch-controls" role="group" aria-label="<?php esc_attr_e( 'Touch movement controls', 'cactusman-portfolio' ); ?>">
		<div class="touch-group">
			<button class="touch-btn" id="btn-left" aria-label="<?php esc_attr_e( 'Walk left', 'cactusman-portfolio' ); ?>">◀</button>
			<button class="touch-btn" id="btn-right" aria-label="<?php esc_attr_e( 'Walk right', 'cactusman-portfolio' ); ?>">▶</button>
		</div>
		<div class="touch-group">
			<button class="touch-btn action" id="btn-action" aria-label="<?php esc_attr_e( 'Interact', 'cactusman-portfolio' ); ?>">E</button>
		</div>
	</div>

</main>

<?php get_template_part( 'template-parts/menu-bar' ); ?>

<div class="panel-overlay" id="panel-overlay" role="dialog" aria-modal="true" aria-hidden="true">
	<div class="panel" id="panel-content" tabindex="-1"></div>
</div>

<?php
// All panels are rendered into <template> tags here. JS clones the matching
// template's content into #panel-content on activation.
?>

<template id="tpl-about"><?php get_template_part( 'template-parts/panels/about' ); ?></template>
<template id="tpl-portfolio"><?php get_template_part( 'template-parts/panels/portfolio' ); ?></template>
<template id="tpl-blog"><?php get_template_part( 'template-parts/panels/blog' ); ?></template>
<template id="tpl-contact"><?php get_template_part( 'template-parts/panels/contact' ); ?></template>

<?php get_footer(); ?>
