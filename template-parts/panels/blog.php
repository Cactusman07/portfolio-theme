<?php
/**
 * Blog panel — recent posts rendered as journal entries.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$posts_list = cmp_get_journal_posts( 6 );
?>
<div class="panel-header">
	<div class="title"><span class="pin"></span> <span>~/journal</span></div>
	<button class="close" data-close>
		<?php esc_html_e( 'Close', 'cactusman-portfolio' ); ?>
		<kbd>ESC</kbd>
	</button>
</div>
<div class="panel-body">
	<div class="section-label">field journal</div>
	<h2><?php esc_html_e( 'From the blog', 'cactusman-portfolio' ); ?></h2>
	<p><?php esc_html_e( 'Notes from the workshop — debugging stories, build logs, and occasional design rambles.', 'cactusman-portfolio' ); ?></p>

	<?php if ( $posts_list ) : ?>
		<div class="journal-grid">
			<?php foreach ( $posts_list as $post_obj ) : ?>
				<?php get_template_part( 'template-parts/partials/journal-entry', null, array( 'post' => $post_obj ) ); ?>
			<?php endforeach; ?>
		</div>

		<p class="archive-link">
			//
			<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>">
				<?php esc_html_e( 'View all posts →', 'cactusman-portfolio' ); ?>
			</a>
		</p>
	<?php else : ?>
		<p class="cmp-empty"><?php esc_html_e( 'No blog posts yet.', 'cactusman-portfolio' ); ?></p>
	<?php endif; ?>
</div>
