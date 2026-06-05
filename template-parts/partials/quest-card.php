<?php
/**
 * Quest card — a single portfolio CPT item, rendered as a card.
 *
 * @package CactusmanPortfolio
 *
 * @var array $args { Args. }
 *     @type WP_Post $post The portfolio post.
 *     @type int     $num  Index for the "// 01" label.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args['post'] ) ) {
	return;
}

$post_obj = $args['post'];
$num      = isset( $args['num'] ) ? (int) $args['num'] : 1;

$status_slug = cmp_portfolio_status_slug( $post_obj->ID );
$status_term = get_the_terms( $post_obj->ID, 'portfolio_status' );
$status_name = ( $status_term && ! is_wp_error( $status_term ) ) ? $status_term[0]->name : '';
$tech_terms  = get_the_terms( $post_obj->ID, 'tech_tag' );
$external    = get_post_meta( $post_obj->ID, '_cmp_external_url', true );
$icon_svg    = get_post_meta( $post_obj->ID, '_cmp_icon_svg', true );

$permalink = $external ? $external : get_permalink( $post_obj );
$target    = $external ? ' target="_blank" rel="noopener"' : '';
?>
<a class="quest" data-num="// <?php printf( '%02d', $num ); ?>" href="<?php echo esc_url( $permalink ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="quest-header">
		<div class="quest-icon" aria-hidden="true">
			<?php
			if ( $icon_svg ) {
				echo wp_kses(
					$icon_svg,
					array(
						'svg'     => array( 'viewbox' => true, 'xmlns' => true ),
						'rect'    => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'fill' => true, 'opacity' => true ),
						'circle'  => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true ),
						'polygon' => array( 'points' => true, 'fill' => true ),
						'g'       => array( 'fill' => true ),
					)
				);
			} elseif ( has_post_thumbnail( $post_obj ) ) {
				echo get_the_post_thumbnail( $post_obj, 'cmp-quest-card', array( 'loading' => 'lazy' ) );
			} else {
				// Generic placeholder icon.
				echo '<svg viewBox="0 0 16 16"><rect x="2" y="2" width="12" height="12" fill="#4a4477"/></svg>';
			}
			?>
		</div>
		<?php if ( $status_name ) : ?>
			<span class="quest-tag <?php echo esc_attr( $status_slug ); ?>"><?php echo esc_html( $status_name ); ?></span>
		<?php endif; ?>
	</div>

	<div class="quest-date">// <?php echo esc_html( get_the_date( 'M j, Y', $post_obj ) ); ?></div>

	<div class="quest-title"><?php echo esc_html( get_the_title( $post_obj ) ); ?></div>

	<div class="quest-desc">
		<?php
		$excerpt = $post_obj->post_excerpt ? $post_obj->post_excerpt : wp_trim_words( $post_obj->post_content, 28, '…' );
		echo esc_html( $excerpt );
		?>
	</div>

	<?php if ( $tech_terms && ! is_wp_error( $tech_terms ) ) : ?>
		<div class="quest-stack">
			<?php foreach ( $tech_terms as $term ) : ?>
				<span><?php echo esc_html( $term->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</a>
