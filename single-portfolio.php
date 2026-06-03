<?php
/**
 * Single portfolio item — full case-study page.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="cmp-main" class="paper-page single portfolio-single">
	<?php while ( have_posts() ) : the_post();
		$external    = get_post_meta( get_the_ID(), '_cmp_external_url', true );
		$year        = get_post_meta( get_the_ID(), '_cmp_year', true );
		$status_term = get_the_terms( get_the_ID(), 'portfolio_status' );
		$status_name = ( $status_term && ! is_wp_error( $status_term ) ) ? $status_term[0]->name : '';
		$tech_terms  = get_the_terms( get_the_ID(), 'tech_tag' );
	?>
		<header class="paper-page__head">
			<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'back to portfolio', 'cactusman-portfolio' ); ?></a>
			<div class="section-label">// portfolio</div>
			<h1 class="paper-page__title"><?php the_title(); ?></h1>
			<div class="paper-page__meta">
				<?php if ( $status_name ) : ?>
					<span class="quest-tag <?php echo esc_attr( cmp_portfolio_status_slug( get_the_ID() ) ); ?>"><?php echo esc_html( $status_name ); ?></span>
				<?php endif; ?>
				<?php if ( $year ) : ?>
					<span><?php echo esc_html( $year ); ?></span>
				<?php endif; ?>
				<?php if ( $external ) : ?>
					<a class="external" href="<?php echo esc_url( $external ); ?>" target="_blank" rel="noopener">
						<?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', $external ) ); ?> ↗
					</a>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="paper-page__thumb"><?php the_post_thumbnail( 'cmp-portfolio-hero' ); ?></figure>
		<?php endif; ?>

		<article class="paper-page__content">
			<?php the_content(); ?>
		</article>

		<?php if ( $tech_terms && ! is_wp_error( $tech_terms ) ) : ?>
			<footer class="paper-page__foot">
				<div class="section-label">stack</div>
				<div class="quest-stack">
					<?php foreach ( $tech_terms as $t ) : ?>
						<span><?php echo esc_html( $t->name ); ?></span>
					<?php endforeach; ?>
				</div>
			</footer>
		<?php endif; ?>

		<nav class="post-nav">
			<?php
			previous_post_link( '<div class="prev">← %link</div>', '%title', false, '', 'portfolio_status' );
			next_post_link( '<div class="next">%link →</div>', '%title', false, '', 'portfolio_status' );
			?>
		</nav>

	<?php endwhile; ?>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
