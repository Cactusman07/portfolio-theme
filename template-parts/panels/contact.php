<?php
/**
 * Contact panel — email, phone, social links from the Customizer.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email    = cmp_mod( 'cmp_contact_email', 'hello@sammuir.co.nz' );
$phone    = cmp_mod( 'cmp_contact_phone', '021 175 9457' );
$github   = cmp_mod( 'cmp_contact_github', '@cactusman07' );
$linkedin = cmp_mod( 'cmp_contact_linkedin', 'in/sam-muir-a6b54164/' );
$location = cmp_mod( 'cmp_contact_location', 'Auckland, NZ · UTC+13' );
?>
<div class="panel-header">
	<div class="title"><span class="pin"></span> <span>~/contact</span></div>
	<button class="close" data-close>
		<?php esc_html_e( 'Close', 'cactusman-portfolio' ); ?>
		<kbd>ESC</kbd>
	</button>
</div>
<div class="panel-body">
	<div class="section-label">get in touch</div>
	<h2>
		<?php esc_html_e( "Let's build something", 'cactusman-portfolio' ); ?>
		<span class="cb-tag">&lt;together /&gt;</span>
	</h2>
	<p><?php esc_html_e( "If you have a project in mind — a site, an app, a creative front-end build, or a tedious process you'd like to automate — I'd love to hear about it. The kettle's always on.", 'cactusman-portfolio' ); ?></p>

	<div class="contact-grid">
		<a class="contact-card" href="mailto:<?php echo esc_attr( $email ); ?>">
			<div class="label">email</div>
			<div class="value"><?php echo esc_html( $email ); ?></div>
		</a>
		<a class="contact-card" href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>">
			<div class="label">phone</div>
			<div class="value"><?php echo esc_html( $phone ); ?></div>
		</a>
		<a class="contact-card" href="https://github.com/<?php echo esc_attr( ltrim( $github, '@' ) ); ?>" target="_blank" rel="noopener">
			<div class="label">github</div>
			<div class="value"><?php echo esc_html( $github ); ?></div>
		</a>
		<a class="contact-card" href="https://linkedin.com/<?php echo esc_attr( $linkedin ); ?>" target="_blank" rel="noopener">
			<div class="label">linkedin</div>
			<div class="value"><?php echo esc_html( $linkedin ); ?></div>
		</a>
	</div>

	<p class="location-note">// <?php echo esc_html( $location ); ?> · <?php esc_html_e( 'open for remote work across NZ & AU', 'cactusman-portfolio' ); ?></p>
</div>
