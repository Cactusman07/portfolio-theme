<?php
/**
 * Menu bar — fixed bottom navigation. Each item walks Cactuar to its landmark
 * x-position and opens the matching panel once he arrives.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cactusImage = get_template_directory_uri() . '/assets/5124543.jpg';

$positions    = cmp_get_landmark_positions();
$status_label = cmp_mod( 'cmp_status_label', __( 'Available for new work', 'cactusman-portfolio' ) );
$compact_menu = is_home() || is_archive() || is_singular( array( 'post', 'portfolio_items' ) ) || is_page( array( 'blog', 'portfolio' ) );
?>
<nav class="menu-bar" aria-label="<?php esc_attr_e( 'Primary', 'cactusman-portfolio' ); ?>">
	<a class="menu-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Sam Muir — home', 'cactusman-portfolio' ); ?>">
		<span class="icon"><img src="<?php echo esc_url( $cactusImage ); ?>" alt="<?php esc_attr_e( 'Cactusman', 'cactusman-portfolio' ); ?>"></span>
		<div>
			<div class="name">Sam Muir</div>
			<div class="sub">is cactusman</div>
		</div>
	</a>

	<div class="menu-items" id="menu-items">
		<?php
		if ( $compact_menu ) :
			?>
			<a class="menu-item" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php get_template_part( 'template-parts/partials/menu-icon', null, array( 'name' => 'home' ) ); ?>
				<span class="label"><?php esc_html_e( 'Home', 'cactusman-portfolio' ); ?></span>
				<span class="key">1</span>
			</a>
			<?php
		else :
			$items = array(
				'home'      => array( __( 'Home', 'cactusman-portfolio' ), 'home' ),
				'about'     => array( __( 'About', 'cactusman-portfolio' ), 'about' ),
				'portfolio' => array( __( 'Portfolio', 'cactusman-portfolio' ), 'portfolio' ),
				'blog'      => array( __( 'Blog', 'cactusman-portfolio' ), 'blog' ),
				'contact'   => array( __( 'Contact', 'cactusman-portfolio' ), 'contact' ),
			);
			$i = 1;
			foreach ( $items as $slug => $info ) :
				list( $label, $icon ) = $info;
				$x = isset( $positions[ $slug ] ) ? (int) $positions[ $slug ] : 0;
				?>
				<button class="menu-item" data-section="<?php echo esc_attr( $slug ); ?>" data-x="<?php echo (int) $x; ?>">
					<?php get_template_part( 'template-parts/partials/menu-icon', null, array( 'name' => $icon ) ); ?>
					<span class="label"><?php echo esc_html( $label ); ?></span>
					<span class="key"><?php echo (int) $i; ?></span>
				</button>
				<?php
				$i++;
			endforeach;
		endif;
		?>
	</div>

	<div class="menu-status">
		<span class="dot" aria-hidden="true"></span>
		<span id="status-text"><?php echo esc_html( $status_label ); ?></span>
		<button class="sound-toggle" id="sound-toggle" aria-label="<?php esc_attr_e( 'Toggle sound', 'cactusman-portfolio' ); ?>">
			<svg class="icon" viewBox="0 0 12 12" aria-hidden="true">
				<rect x="2" y="4" width="2" height="4" fill="currentColor"/>
				<polygon points="4,4 7,2 7,10 4,8" fill="currentColor"/>
				<rect x="8" y="3" width="1" height="6" fill="currentColor"/>
				<rect x="10" y="5" width="1" height="2" fill="currentColor"/>
			</svg>
			<span class="label-on"><?php esc_html_e( 'sound on', 'cactusman-portfolio' ); ?></span>
			<span class="label-off"><?php esc_html_e( 'sound off', 'cactusman-portfolio' ); ?></span>
		</button>
	</div>
</nav>
