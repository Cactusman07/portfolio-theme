<?php
/**
 * Parallax background layers — stars, far mountains, mid mountains, treeline.
 *
 * Each .layer has a `data-depth` attribute used by world.js to translate it
 * horizontally as the camera moves.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="parallax" aria-hidden="true">

	<div class="layer stars" data-depth="0.05">
		<svg width="100%" height="100%" viewBox="0 0 2000 400" preserveAspectRatio="none">
			<g fill="#fef6e4">
				<rect x="80" y="40" width="3" height="3"/><rect x="180" y="80" width="2" height="2"/>
				<rect x="240" y="30" width="3" height="3"/><rect x="350" y="100" width="2" height="2"/>
				<rect x="420" y="50" width="3" height="3"/><rect x="540" y="20" width="2" height="2"/>
				<rect x="640" y="90" width="3" height="3"/><rect x="760" y="40" width="2" height="2"/>
				<rect x="880" y="70" width="3" height="3"/><rect x="990" y="30" width="2" height="2"/>
				<rect x="1100" y="80" width="3" height="3"/><rect x="1220" y="50" width="2" height="2"/>
				<rect x="1340" y="90" width="3" height="3"/><rect x="1460" y="40" width="2" height="2"/>
				<rect x="1580" y="60" width="3" height="3"/><rect x="1700" y="30" width="2" height="2"/>
				<rect x="1820" y="80" width="3" height="3"/><rect x="1940" y="50" width="2" height="2"/>
				<rect x="140" y="160" width="2" height="2"/><rect x="300" y="180" width="3" height="3"/>
				<rect x="470" y="150" width="2" height="2"/><rect x="700" y="170" width="3" height="3"/>
				<rect x="850" y="140" width="2" height="2"/><rect x="1050" y="180" width="3" height="3"/>
				<rect x="1280" y="160" width="2" height="2"/><rect x="1500" y="180" width="3" height="3"/>
				<rect x="1720" y="140" width="2" height="2"/><rect x="1900" y="170" width="3" height="3"/>
			</g>
		</svg>
	</div>

	<div class="layer mountains-far" data-depth="0.15">
		<svg width="100%" height="100%" viewBox="0 0 2500 220" preserveAspectRatio="none">
			<path d="M0,220 L0,150 L80,120 L140,100 L200,130 L280,90 L340,110 L420,70 L500,100 L580,80 L660,110 L740,90 L820,120 L900,80 L980,100 L1060,110 L1140,90 L1240,100 L1340,80 L1440,110 L1540,90 L1640,120 L1740,100 L1840,90 L1940,110 L2040,80 L2140,100 L2240,110 L2340,90 L2440,110 L2500,100 L2500,220 Z" fill="#3d3a6e"/>
			<g fill="#5a5589" opacity="0.7">
				<polygon points="420,70 410,85 430,85"/>
				<polygon points="580,80 570,95 590,95"/>
				<polygon points="900,80 890,95 910,95"/>
				<polygon points="1340,80 1330,95 1350,95"/>
				<polygon points="1840,90 1830,105 1850,105"/>
			</g>
		</svg>
	</div>

	<div class="layer mountains-mid" data-depth="0.28">
		<svg width="100%" height="100%" viewBox="0 0 2200 280" preserveAspectRatio="none">
			<path d="M0,280 L0,180 L60,150 L140,100 L220,160 L300,80 L380,120 L460,70 L540,110 L620,90 L700,130 L780,100 L860,140 L940,90 L1020,120 L1100,100 L1180,130 L1280,90 L1380,120 L1460,80 L1560,110 L1660,100 L1760,140 L1860,90 L1960,120 L2060,100 L2160,130 L2200,110 L2200,280 Z" fill="#2a2655"/>
			<g fill="#4a4477">
				<polygon points="300,80 285,105 315,105"/>
				<polygon points="460,70 445,95 475,95"/>
				<polygon points="940,90 925,115 955,115"/>
				<polygon points="1280,90 1265,115 1295,115"/>
				<polygon points="1860,90 1845,115 1875,115"/>
			</g>
		</svg>
	</div>

	<div class="layer forest-band" data-depth="0.33">
		<svg width="100%" height="100%" viewBox="0 0 2300 120" preserveAspectRatio="none">
			<rect x="0" y="102" width="2300" height="18" fill="#13122a"/>
			<g fill="#161434" opacity="0.95">
				<?php for ( $i = 0; $i < 210; $i++ ) :
					$x = (int) ( 6 + ( $i * 11 ) );
					$h = (int) ( 18 + ( ( $i * 9 ) % 28 ) );
					$w = (int) ( 8 + ( ( $i * 3 ) % 8 ) );
					$base = 108;
					$left = $x - (int) floor( $w / 2 );
					$right = $x + (int) floor( $w / 2 );
					$top = $base - $h;
				?>
					<polygon points="<?php echo $x; ?>,<?php echo $top; ?> <?php echo $left; ?>,<?php echo $base; ?> <?php echo $right; ?>,<?php echo $base; ?>"/>
				<?php endfor; ?>
			</g>
			<g fill="#0f1026" opacity="0.95">
				<?php for ( $i = 0; $i < 190; $i++ ) :
					$x = (int) ( 10 + ( $i * 12 ) );
					$h = (int) ( 14 + ( ( $i * 5 ) % 22 ) );
					$w = (int) ( 7 + ( ( $i * 2 ) % 6 ) );
					$base = 116;
					$left = $x - (int) floor( $w / 2 );
					$right = $x + (int) floor( $w / 2 );
					$top = $base - $h;
				?>
					<polygon points="<?php echo $x; ?>,<?php echo $top; ?> <?php echo $left; ?>,<?php echo $base; ?> <?php echo $right; ?>,<?php echo $base; ?>"/>
				<?php endfor; ?>
			</g>
		</svg>
	</div>

</div>
