<?php
/**
 * Journal entry — a single post rendered as an aged-paper journal card.
 *
 * @package CactusmanPortfolio
 *
 * @var array $args { Args. }
 *     @type WP_Post $post The post to render.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args['post'] ) ) {
	return;
}

$post_obj  = $args['post'];
$categories = get_the_category( $post_obj->ID );
$tags       = get_the_tags( $post_obj->ID );
$read_time = max( 1, (int) round( str_word_count( wp_strip_all_tags( $post_obj->post_content ) ) / 200 ) );
?>
<a class="entry" href="<?php echo esc_url( get_permalink( $post_obj ) ); ?>">
	<?php if ( $categories ) : ?>
		<div class="entry-cats">
			<?php echo esc_html( implode( ', ', wp_list_pluck( $categories, 'name' ) ) ); ?>
		</div>
	<?php endif; ?>
	<div class="entry-date">// <?php echo esc_html( get_the_date( 'M j, Y', $post_obj ) ); ?></div>
	<div class="entry-title"><?php echo esc_html( get_the_title( $post_obj ) ); ?></div>
	<div class="entry-excerpt">
		<?php
		$excerpt = $post_obj->post_excerpt ? $post_obj->post_excerpt : wp_trim_words( $post_obj->post_content, 32, '…' );
		echo esc_html( $excerpt );
		?>
	</div>
	<?php if ( $tags ) : ?>
		<p class="entry-tags">
			<?php
			$tag_names = wp_list_pluck( $tags, 'name' );
			echo esc_html( implode( ', ', $tag_names ) );
			?>
		</p>
	<?php endif; ?>
	<div class="entry-foot">
		<span><?php echo esc_html( get_the_date( 'Y', $post_obj ) ); ?></span>
		<span class="read"><?php echo (int) $read_time; ?> min →</span>
	</div>
</a>
