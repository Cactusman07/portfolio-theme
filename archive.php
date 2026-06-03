<?php
/**
 * Archive — generic archive (categories, tags, dates).
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="cmp-main" class="paper-page archive">
	<header class="paper-page__head">
		<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'back to workshop', 'cactusman-portfolio' ); ?></a>
		<h1 class="paper-page__title"><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<p>', '</p>' ); ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="journal-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/partials/journal-entry', null, array( 'post' => get_post() ) ); ?>
			<?php endwhile; ?>
		</div>

		<nav class="pagination">
			<?php the_posts_pagination(); ?>
		</nav>
	<?php else : ?>
		<p class="cmp-empty"><?php esc_html_e( 'Nothing here yet.', 'cactusman-portfolio' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
