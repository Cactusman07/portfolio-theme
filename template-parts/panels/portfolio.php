<?php
/**
 * Portfolio panel — loops the `portfolio` CPT into quest cards.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = cmp_get_portfolio_items( 8 );
?>
<div class="panel-header">
	<div class="title"><span class="pin"></span> <span>~/portfolio</span></div>
	<button class="close" data-close>
		<?php esc_html_e( 'Close', 'cactusman-portfolio' ); ?>
		<kbd>ESC</kbd>
	</button>
</div>
<div class="panel-body">
	<div class="section-label">selected work</div>
	<h2><?php esc_html_e( 'Recent projects', 'cactusman-portfolio' ); ?></h2>
	<p><?php esc_html_e( 'A mix of front-end product work, automation projects, all the way through to WordPress and Shopify sites built for NZ clients.', 'cactusman-portfolio' ); ?></p>

	<?php if ( $items ) : ?>
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

		<p class="archive-link">
			//
			<a href="<?php echo esc_url( get_post_type_archive_link( 'portfolio' ) ); ?>">
				<?php esc_html_e( 'View full portfolio archive →', 'cactusman-portfolio' ); ?>
			</a>
		</p>
	<?php else : ?>
		<p class="cmp-empty">
			<?php
			printf(
				/* translators: %s: link wrapper */
				esc_html__( 'No portfolio items yet. %sAdd some via Portfolio → Add new%s.', 'cactusman-portfolio' ),
				'<a href="' . esc_url( admin_url( 'post-new.php?post_type=portfolio' ) ) . '">',
				'</a>'
			);
			?>
		</p>
	<?php endif; ?>
</div>
