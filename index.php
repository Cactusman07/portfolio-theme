<?php
/**
 * Index — generic fallback template, used for the blog listing.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="cmp-main" class="paper-page">
	<header class="paper-page__head">
		<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'back to workshop', 'cactusman-portfolio' ); ?></a>
		<h1 class="paper-page__title">
			<?php if ( is_home() ) : ?>
				<?php esc_html_e( 'Field journal', 'cactusman-portfolio' ); ?>
			<?php else : ?>
				<?php the_archive_title(); ?>
			<?php endif; ?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="journal-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/partials/journal-entry', null, array( 'post' => get_post() ) ); ?>
			<?php endwhile; ?>
		</div>

		<nav class="pagination">
			<?php
			the_posts_pagination(
				array(
					'prev_text' => '← ' . __( 'newer', 'cactusman-portfolio' ),
					'next_text' => __( 'older', 'cactusman-portfolio' ) . ' →',
				)
			);
			?>
		</nav>
	<?php else : ?>
		<p class="cmp-empty"><?php esc_html_e( 'Nothing here yet.', 'cactusman-portfolio' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
