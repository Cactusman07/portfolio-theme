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
			<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'back to home page', 'cactusman-portfolio' ); ?></a>
			<h1 class="paper-page__title"><?php the_title(); ?></h1>
		</header>
		<?php if ( is_page( 'portfolio' ) ) : ?>
			<article class="paper-page__content">
				<?php the_content(); ?>
			</article>

			<?php
			$items = cmp_get_portfolio_items( 24 );
			if ( $items ) :
				?>
				<div class="quest-grid">
					<?php
					$num = 1;
					foreach ( $items as $item ) :
						get_template_part( 'template-parts/partials/quest-card', null, array(
							'post' => $item,
							'num'  => $num,
						) );
						$num++;
					endforeach;
					?>
				</div>
			<?php else : ?>
				<p class="cmp-empty"><?php esc_html_e( 'No portfolio items yet.', 'cactusman-portfolio' ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<article class="paper-page__content">
				<?php the_content(); ?>
			</article>
		<?php endif; ?>
	<?php endwhile; ?>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
