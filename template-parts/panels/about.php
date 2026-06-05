<?php
/**
 * About panel — bio + character sheet (skills + inventory).
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$body      = cmp_mod( 'cmp_about_body', '<p class="lede">Auckland-based with my wife Emma, our sons Ollie, Lachie & Tommy, and our dog Mac. I have more than a decade of experience in building for the web and automating processes.</p>
<p class="lede">Why Cactusman? Honestly, it came from a love for my favourite game - Final Fantasy - and as with a lot of things in my life, I was once asked to create a \'username\' for something, made up the name on the spot, and it just stuck ever since.</p>' );
$class     = cmp_mod( 'cmp_about_class', 'full-stack generalist · front-end leaning' );
$level     = cmp_mod( 'cmp_about_level', '12' );
$inventory = cmp_split_csv( cmp_mod( 'cmp_about_inventory', '' ) );
$status    = cmp_mod( 'cmp_status_body', 'Available for new web design & development work — especially anything with a creative angle.' );
$skills    = cmp_get_skills( -1 );
?>
<div class="panel-header">
	<div class="title"><span class="pin"></span> <span>~/about</span></div>
	<button class="close" data-close>
		<?php esc_html_e( 'Close', 'cactusman-portfolio' ); ?>
		<kbd>ESC</kbd>
	</button>
</div>
<div class="panel-body">
	<div class="section-label">about &amp; skills</div>
	<h3><?php esc_html_e( "Hi, I'm Sam.", 'cactusman-portfolio' ); ?><br><?php esc_html_e( 'Designer, developer, automation specialist & problem solver.', 'cactusman-portfolio' ); ?></h3>

	<div class="about-grid">
		<div class="about-body"><?php echo wp_kses_post( $body ); ?></div>
		<aside class="status-card">
			<h3>currentStatus</h3>
			<p><?php echo wp_kses_post( $status ); ?></p>
			<div class="closing">}</div>
		</aside>
	</div>

	<div class="sheet">
		<div class="sheet-head">
			<div>
				<div class="sheet-class"><?php echo esc_html( $class ); ?></div>
				<div class="sheet-name">skills / cactusman</div>
			</div>
			<div class="sheet-level">
				<div class="lvl-label">// level</div>
				<div class="lvl-num"><?php echo esc_html( $level ); ?></div>
			</div>
		</div>

		<?php if ( $skills ) : ?>
			<div class="stats-grid">
				<?php foreach ( $skills as $skill ) :
					$raw_pct   = get_post_meta( $skill->ID, '_cmp_skill_percent', true );
					$pct       = ( '' === $raw_pct ) ? 50 : max( 0, min( 100, (int) $raw_pct ) );
					$meta_lbl  = get_post_meta( $skill->ID, '_cmp_skill_meta', true );
					$excerpt   = trim( wp_strip_all_tags( $skill->post_excerpt ) );
					$levelling = (bool) get_post_meta( $skill->ID, '_cmp_skill_levelling', true );
					$fill_cls  = $levelling ? 'stat-bar-fill curiosity' : 'stat-bar-fill';
				?>
					<div class="stat">
						<div class="stat-body">
							<div class="stat-head">
								<div class="stat-name"><?php echo esc_html( $skill->post_title ); ?></div>
								<div class="stat-pct" data-pct="<?php echo (int) $pct; ?>"><?php echo (int) $pct; ?></div>
							</div>
							<div class="stat-bar"><div class="<?php echo esc_attr( $fill_cls ); ?>" data-fill="<?php echo (int) $pct; ?>"></div></div>
							<?php if ( $excerpt || $meta_lbl ) : ?>
								<div class="stat-excerpt">
									<?php
									echo esc_html( $excerpt );
									if ( $excerpt && $meta_lbl ) {
										echo ' · ';
									}
									if ( $meta_lbl ) {
										echo esc_html( $meta_lbl );
									}
									?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="cmp-empty">
				<?php
				printf(
					/* translators: %s: link to skills admin */
					esc_html__( 'No skills published yet. %sAdd some to populate the character sheet.%s', 'cactusman-portfolio' ),
					'<a href="' . esc_url( admin_url( 'edit.php?post_type=skill' ) ) . '">',
					'</a>'
				);
				?>
			</p>
		<?php endif; ?>

		<?php if ( $inventory ) : ?>
			<div class="inventory">
				<div class="inv-label">inventory · tools of the trade</div>
				<div class="inv-grid">
					<?php foreach ( $inventory as $tool ) : ?>
						<div class="inv-slot"><?php echo esc_html( $tool ); ?></div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
