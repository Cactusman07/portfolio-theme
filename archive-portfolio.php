<?php
/**
 * Portfolio archive — grid view of all portfolio items.
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
		<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'back to home page', 'cactusman-portfolio' ); ?></a>
		<h1 class="paper-page__title"><?php esc_html_e( 'Portfolio archive', 'cactusman-portfolio' ); ?></h1>
		<p><?php esc_html_e( 'Every project, dated and tagged.', 'cactusman-portfolio' ); ?></p>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="quest-grid">
			<?php
			$num = 1;
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/partials/quest-card', null, array(
					'post' => get_post(),
					'num'  => $num,
				) );
				$num++;
			endwhile;
			?>
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
		<p class="cmp-empty"><?php esc_html_e( 'No portfolio items yet.', 'cactusman-portfolio' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
