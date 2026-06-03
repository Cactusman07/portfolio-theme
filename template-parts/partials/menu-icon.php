<?php
/**
 * Menu icon — pixel-art SVG for each bottom-menu item.
 *
 * @package CactusmanPortfolio
 *
 * @var array $args { Args. }
 *     @type string $name One of: home, about, portfolio, blog, contact.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$name = isset( $args['name'] ) ? $args['name'] : 'home';
?>
<?php switch ( $name ) :
	case 'home': ?>
		<svg class="icon" viewBox="0 0 12 12" aria-hidden="true">
			<rect x="5" y="2" width="2" height="2" fill="#2a2418"/>
			<rect x="3" y="4" width="6" height="6" fill="#2a2418"/>
			<rect x="5" y="6" width="2" height="4" fill="#f3ead4"/>
		</svg>
		<?php break;
	case 'about': ?>
		<svg class="icon" viewBox="0 0 12 12" aria-hidden="true">
			<rect x="4" y="2" width="4" height="3" fill="#2a2418"/>
			<rect x="3" y="5" width="6" height="6" fill="#2a2418"/>
			<rect x="5" y="6" width="2" height="2" fill="#f3ead4"/>
		</svg>
		<?php break;
	case 'portfolio': ?>
		<svg class="icon" viewBox="0 0 12 12" aria-hidden="true">
			<rect x="2" y="3" width="8" height="7" fill="#c45f3a"/>
			<rect x="4" y="1" width="4" height="2" fill="#c45f3a"/>
			<rect x="3" y="5" width="6" height="1" fill="#f3ead4"/>
		</svg>
		<?php break;
	case 'blog': ?>
		<svg class="icon" viewBox="0 0 12 12" aria-hidden="true">
			<rect x="2" y="2" width="8" height="8" fill="#fef6e4"/>
			<rect x="2" y="2" width="8" height="1" fill="#f4d97e"/>
			<rect x="3" y="4" width="3" height="1" fill="#80715a"/>
			<rect x="3" y="6" width="4" height="1" fill="#80715a"/>
			<rect x="3" y="8" width="3" height="1" fill="#80715a"/>
			<rect x="7" y="4" width="3" height="1" fill="#80715a"/>
			<rect x="7" y="6" width="2" height="1" fill="#80715a"/>
			<rect x="7" y="8" width="3" height="1" fill="#80715a"/>
		</svg>
		<?php break;
	case 'contact': ?>
		<svg class="icon" viewBox="0 0 12 12" aria-hidden="true">
			<rect x="2" y="4" width="8" height="6" fill="#c45f3a"/>
			<rect x="2" y="4" width="8" height="1" fill="#e67a4f"/>
			<rect x="4" y="6" width="4" height="3" fill="#8b3f25"/>
			<rect x="10" y="3" width="1" height="3" fill="#3a2818"/>
			<rect x="11" y="3" width="1" height="2" fill="#c8932f"/>
		</svg>
		<?php break;
endswitch;
