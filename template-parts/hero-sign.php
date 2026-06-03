<?php
/**
 * Hero sign — large wordmark and intro text at the start of the world.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$top_tag      = cmp_mod( 'cmp_hero_top_tag', '// booting ' . get_bloginfo( 'name' ) . '...');
$headline     = cmp_mod( 'cmp_hero_headline', 'Sam Muir' );
$headline_hl  = cmp_mod( 'cmp_hero_headline_hl', 'creates for the web' );
$sub          = cmp_mod( 'cmp_hero_sub', 'I\'m an Auckland-based designer, developer and automation specialist with 12+ years turning complex ideas into high-performing user experiences and efficient automated solutions.' );
?>
<div class="hero-sign">
	<div class="top-tag"><?php echo esc_html( $top_tag ); ?></div>
	<h1>
		<?php echo esc_html( $headline ); ?><br>
		<span class="hl"><?php echo esc_html( $headline_hl ); ?></span>
	</h1>
	<div class="sub"><?php echo wp_kses_post( $sub ); ?></div>
</div>
