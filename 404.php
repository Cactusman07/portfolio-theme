<?php
/**
 * 404 — content not found.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="cmp-main" class="paper-page error-404">
	<header class="paper-page__head">
		<div class="section-label">// error 404</div>
		<h1 class="paper-page__title"><?php esc_html_e( "Cactuar wandered off the map.", 'cactusman-portfolio' ); ?></h1>
		<p><?php esc_html_e( "The page you were looking for isn't here — try heading back to the workshop, or use the search.", 'cactusman-portfolio' ); ?></p>
	</header>

	<div class="error-404__actions">
		<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'back to workshop', 'cactusman-portfolio' ); ?></a>
	</div>

	<div class="error-404__search">
		<?php get_search_form(); ?>
	</div>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
