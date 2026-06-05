<?php
/**
 * Single post — blog article view.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="cmp-main" class="paper-page single">
	<?php while ( have_posts() ) : the_post();
		$posts_page_id = (int) get_option( 'page_for_posts' );
		$blog_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/blog/' );
		$blog_url      = $blog_url ? $blog_url : home_url( '/' );
	?>

		<header class="paper-page__head">
			<a class="back-link" href="<?php echo esc_url( $blog_url ); ?>">← <?php esc_html_e( 'back to blog', 'cactusman-portfolio' ); ?></a>
			<div class="section-label">// <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></div>
			<h1 class="paper-page__title"><?php the_title(); ?></h1>
			<?php
			$categories = get_the_category();
			$mins = max( 1, (int) round( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) );
			?>
			<div class="paper-page__meta">
				<?php if ( $categories ) : ?>
					<span><?php echo esc_html( implode( ', ', wp_list_pluck( $categories, 'name' ) ) ); ?></span>
					<span aria-hidden="true">·</span>
				<?php endif; ?>
				<span><?php echo (int) $mins; ?> <?php esc_html_e( 'min read', 'cactusman-portfolio' ); ?></span>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="paper-page__thumb"><?php the_post_thumbnail( 'cmp-portfolio-hero' ); ?></figure>
		<?php endif; ?>

		<article class="paper-page__content">
			<?php the_content(); ?>
		</article>

		<?php
		$tags = get_the_tags();
		if ( $tags ) : ?>
			<footer class="paper-page__foot">
				<div class="section-label">tagged</div>
				<p class="tags tags--paragraph"><?php echo esc_html( implode( ', ', wp_list_pluck( $tags, 'name' ) ) ); ?></p>
			</footer>
		<?php endif; ?>

		<nav class="post-nav">
			<?php
			previous_post_link( '<div class="prev">← %link</div>' );
			next_post_link( '<div class="next">%link →</div>' );
			?>
		</nav>

	<?php endwhile; ?>
</main>

<?php
get_template_part( 'template-parts/menu-bar' );
get_footer();
