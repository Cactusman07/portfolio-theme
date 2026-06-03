<?php
/**
 * Page template — generic WP page.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="cmp-main" class="paper-page">
	<?php while ( have_posts() ) : the_post(); ?>
		<header class="paper-page__head">
			<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'back to workshop', 'cactusman-portfolio' ); ?></a>
			<h1 class="paper-page__title"><?php the_title(); ?></h1>
		</header>
		<article class="paper-page__content">
			<?php the_content(); ?>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
