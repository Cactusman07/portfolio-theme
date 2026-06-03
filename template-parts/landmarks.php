<?php
/**
 * Landmarks — the interactable objects placed along the world.
 *
 * Positions come from cmp_get_landmark_positions() and are passed in via
 * get_template_part args (also available globally via the CMP.landmarks JS
 * object, so PHP and JS agree on placement).
 *
 * @package CactusmanPortfolio
 *
 * @var array $args { Landmark args. }
 *     @type array $positions Map of section slug => world x-coord.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$positions = isset( $args['positions'] ) ? $args['positions'] : cmp_get_landmark_positions();
?>

<!-- ABOUT — workbench -->
<div class="landmark" data-section="about" style="left: <?php echo (int) $positions['about']; ?>px; width: 100px; height: 84px;">
	<div class="lbl">// <?php esc_html_e( 'About & skills', 'cactusman-portfolio' ); ?></div>
	<svg viewBox="0 0 100 84">
		<rect x="10" y="56" width="80" height="6" fill="#3a2818"/>
		<rect x="10" y="56" width="80" height="2" fill="#5a3a25"/>
		<rect x="14" y="62" width="6" height="22" fill="#3a2818"/>
		<rect x="80" y="62" width="6" height="22" fill="#3a2818"/>
		<rect x="32" y="28" width="36" height="26" fill="#1a1830"/>
		<rect x="34" y="30" width="32" height="22" fill="#7ab571"/>
		<rect x="36" y="32" width="6" height="2" fill="#1a1830"/>
		<rect x="36" y="36" width="14" height="2" fill="#1a1830"/>
		<rect x="36" y="40" width="10" height="2" fill="#1a1830"/>
		<rect x="36" y="44" width="18" height="2" fill="#1a1830"/>
		<rect x="36" y="48" width="8" height="2" fill="#1a1830"/>
		<rect x="46" y="54" width="8" height="3" fill="#4a3f2b"/>
		<rect x="42" y="56" width="16" height="2" fill="#4a3f2b"/>
		<rect x="22" y="59" width="32" height="3" fill="#2a2418"/>
		<rect x="64" y="50" width="8" height="8" fill="#c45f3a"/>
		<rect x="64" y="48" width="8" height="2" fill="#a8a8a8"/>
		<rect x="72" y="52" width="2" height="4" fill="#c45f3a"/>
		<rect x="66" y="44" width="2" height="2" fill="#fef6e4" opacity="0.6"/>
		<rect x="68" y="42" width="2" height="2" fill="#fef6e4" opacity="0.5"/>
		<rect x="66" y="40" width="2" height="2" fill="#fef6e4" opacity="0.4"/>
		<rect x="14" y="48" width="6" height="8" fill="#c45f3a"/>
		<rect x="16" y="46" width="2" height="2" fill="#c8932f"/>
		<rect x="14" y="56" width="6" height="2" fill="#3a2818"/>
		<rect x="12" y="58" width="10" height="2" fill="#3a2818"/>
	</svg>
</div>

<!-- PORTFOLIO — signpost -->
<div class="landmark" data-section="portfolio" style="left: <?php echo (int) $positions['portfolio']; ?>px; width: 80px; height: 100px;">
	<div class="lbl">// <?php esc_html_e( 'Portfolio & work', 'cactusman-portfolio' ); ?></div>
	<svg viewBox="0 0 80 100">
		<rect x="36" y="10" width="8" height="78" fill="#5a3a25"/>
		<rect x="36" y="10" width="2" height="78" fill="#3a2818"/>
		<rect x="42" y="10" width="2" height="78" fill="#7a5a3d"/>
		<rect x="14" y="14" width="52" height="20" fill="#c45f3a"/>
		<rect x="14" y="14" width="52" height="2" fill="#e67a4f"/>
		<rect x="14" y="32" width="52" height="2" fill="#8b3f25"/>
		<rect x="14" y="14" width="2" height="20" fill="#8b3f25"/>
		<rect x="64" y="14" width="2" height="20" fill="#8b3f25"/>
		<polygon points="66,16 76,24 66,32" fill="#c45f3a"/>
		<polygon points="68,18 76,24 68,30" fill="#e67a4f" opacity="0.4"/>
		<rect x="20" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="24" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="22" y="26" width="2" height="2" fill="#f3ead4"/>
		<rect x="28" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="32" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="30" y="20" width="4" height="2" fill="#f3ead4"/>
		<rect x="36" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="38" y="20" width="2" height="2" fill="#f3ead4"/>
		<rect x="40" y="22" width="2" height="2" fill="#f3ead4"/>
		<rect x="42" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="46" y="20" width="6" height="2" fill="#f3ead4"/>
		<rect x="46" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="50" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="54" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="58" y="20" width="2" height="8" fill="#f3ead4"/>
		<rect x="56" y="20" width="6" height="2" fill="#f3ead4"/>
		<rect x="56" y="24" width="6" height="2" fill="#f3ead4"/>
		<rect x="20" y="44" width="40" height="14" fill="#5a3a25"/>
		<rect x="20" y="44" width="40" height="2" fill="#7a5a3d"/>
		<rect x="22" y="48" width="36" height="2" fill="#f3ead4" opacity="0.7"/>
		<rect x="22" y="52" width="28" height="2" fill="#f3ead4" opacity="0.5"/>
	</svg>
</div>

<!-- BLOG — open book on lectern -->
<div class="landmark" data-section="blog" style="left: <?php echo (int) $positions['blog']; ?>px; width: 110px; height: 110px;">
	<div class="lbl">// <?php esc_html_e( 'Field journal · blog', 'cactusman-portfolio' ); ?></div>
	<svg viewBox="0 0 110 110">
		<rect x="20" y="80" width="70" height="6" fill="#3a2818"/>
		<rect x="20" y="80" width="70" height="2" fill="#5a3a25"/>
		<rect x="30" y="86" width="50" height="20" fill="#3a2818"/>
		<rect x="30" y="86" width="50" height="2" fill="#5a3a25"/>
		<rect x="22" y="52" width="66" height="30" fill="#5a3a25"/>
		<rect x="22" y="52" width="66" height="2" fill="#7a5a3d"/>
		<rect x="22" y="80" width="66" height="2" fill="#2a1810"/>
		<rect x="24" y="50" width="62" height="32" fill="#fef6e4"/>
		<rect x="24" y="50" width="62" height="2" fill="#f4d97e"/>
		<rect x="54" y="50" width="2" height="32" fill="#d4c4a0"/>
		<g fill="#80715a">
			<rect x="28" y="56" width="20" height="1"/><rect x="28" y="60" width="22" height="1"/>
			<rect x="28" y="64" width="18" height="1"/><rect x="28" y="68" width="20" height="1"/>
			<rect x="28" y="72" width="16" height="1"/><rect x="28" y="76" width="20" height="1"/>
			<rect x="58" y="56" width="22" height="1"/><rect x="58" y="60" width="18" height="1"/>
			<rect x="58" y="64" width="20" height="1"/><rect x="58" y="68" width="16" height="1"/>
			<rect x="58" y="72" width="20" height="1"/><rect x="58" y="76" width="18" height="1"/>
		</g>
		<rect x="20" y="42" width="2" height="2" fill="#f4b870" opacity="0.6"/>
		<rect x="88" y="42" width="2" height="2" fill="#f4b870" opacity="0.6"/>
		<rect x="50" y="38" width="2" height="2" fill="#f4b870" opacity="0.5"/>
		<rect x="38" y="40" width="2" height="2" fill="#f4b870" opacity="0.4"/>
		<rect x="70" y="40" width="2" height="2" fill="#f4b870" opacity="0.4"/>
	</svg>
</div>

<!-- CONTACT — mailbox -->
<div class="landmark" data-section="contact" style="left: <?php echo (int) $positions['contact']; ?>px; width: 70px; height: 90px;">
	<div class="lbl">// <?php esc_html_e( 'Get in touch', 'cactusman-portfolio' ); ?></div>
	<svg viewBox="0 0 70 90">
		<rect x="32" y="40" width="6" height="42" fill="#3a2818"/>
		<rect x="32" y="40" width="2" height="42" fill="#2a1810"/>
		<rect x="14" y="14" width="42" height="32" fill="#c45f3a"/>
		<rect x="14" y="14" width="42" height="3" fill="#e67a4f"/>
		<rect x="14" y="43" width="42" height="3" fill="#8b3f25"/>
		<rect x="14" y="14" width="3" height="32" fill="#8b3f25"/>
		<rect x="53" y="14" width="3" height="32" fill="#e67a4f"/>
		<rect x="20" y="20" width="30" height="22" fill="#8b3f25"/>
		<rect x="20" y="20" width="30" height="2" fill="#a04428"/>
		<rect x="38" y="30" width="4" height="4" fill="#c8932f"/>
		<rect x="56" y="18" width="2" height="14" fill="#3a2818"/>
		<rect x="58" y="20" width="8" height="8" fill="#c8932f"/>
		<rect x="58" y="20" width="8" height="2" fill="#e6b85a"/>
		<rect x="22" y="38" width="10" height="6" fill="#f3ead4"/>
		<rect x="22" y="38" width="10" height="1" fill="#d4c4a0"/>
		<rect x="24" y="40" width="6" height="1" fill="#80715a"/>
	</svg>
</div>
