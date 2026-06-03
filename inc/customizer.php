<?php
/**
 * Theme customizer — all the editable text on the front page lives here.
 *
 * Adds a single "Cactusman portfolio" panel containing:
 *  - Hero — top tag, headline (with highlighted span), subtitle
 *  - Status — "currently" line and availability flag
 *  - Contact — email, phone
 *  - Social — GitHub, LinkedIn, Mastodon (etc.)
 *  - Inventory — comma-separated tool list for the inventory grid
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer panel, sections, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function cmp_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'cmp_panel',
		array(
			'title'    => __( 'Cactusman portfolio', 'cactusman-portfolio' ),
			'priority' => 30,
		)
	);

	/* ------------------------------------------------------------------
	 *  Hero
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'cmp_hero',
		array(
			'title' => __( 'Hero / wordmark', 'cactusman-portfolio' ),
			'panel' => 'cmp_panel',
		)
	);

	cmp_add_text_setting( $wp_customize, 'cmp_hero_top_tag', __( 'Top tag', 'cactusman-portfolio' ), 'cmp_hero', '// loading sammuir.co.nz...' );
	cmp_add_text_setting( $wp_customize, 'cmp_hero_headline', __( 'Headline (line 1)', 'cactusman-portfolio' ), 'cmp_hero', 'Sam Muir' );
	cmp_add_text_setting( $wp_customize, 'cmp_hero_headline_hl', __( 'Headline (highlighted line 2)', 'cactusman-portfolio' ), 'cmp_hero', 'creates for the web' );
	cmp_add_textarea_setting( $wp_customize, 'cmp_hero_sub', __( 'Hero subtitle', 'cactusman-portfolio' ), 'cmp_hero', 'Auckland-based designer & developer. Six-plus years of front-end, full-stack, and integration work.' );

	/* ------------------------------------------------------------------
	 *  About
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'cmp_about',
		array(
			'title' => __( 'About panel', 'cactusman-portfolio' ),
			'panel' => 'cmp_panel',
		)
	);
	cmp_add_textarea_setting( $wp_customize, 'cmp_about_body', __( 'About body (HTML allowed)', 'cactusman-portfolio' ), 'cmp_about', '' );
	cmp_add_text_setting( $wp_customize, 'cmp_about_class', __( 'Class label', 'cactusman-portfolio' ), 'cmp_about', 'full-stack generalist · front-end leaning' );
	cmp_add_text_setting( $wp_customize, 'cmp_about_level', __( 'Level number', 'cactusman-portfolio' ), 'cmp_about', '12' );
	cmp_add_textarea_setting( $wp_customize, 'cmp_about_inventory', __( 'Inventory (comma-separated tools)', 'cactusman-portfolio' ), 'cmp_about', 'React, Redux, Next.js, TS, GraphQL, Vite, Docker, .NET / C#, PHP, SQL, Azure (Logic & Function Apps, Azure AD B2C, APIM), WP, Elementor, Woo, Shopify, Stripe, MYOB, G. Maps, Figma, Photoshop, Canva, Git' );

	/* ------------------------------------------------------------------
	 *  Status
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'cmp_status',
		array(
			'title' => __( 'Status', 'cactusman-portfolio' ),
			'panel' => 'cmp_panel',
		)
	);
	cmp_add_text_setting( $wp_customize, 'cmp_status_label', __( 'Menu-bar status text', 'cactusman-portfolio' ), 'cmp_status', 'Available for new work' );
	cmp_add_textarea_setting( $wp_customize, 'cmp_status_body', __( 'About-panel status body', 'cactusman-portfolio' ), 'cmp_status', 'Available for new web design & development work — especially anything with a creative angle.' );

	/* ------------------------------------------------------------------
	 *  Contact
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'cmp_contact',
		array(
			'title' => __( 'Contact', 'cactusman-portfolio' ),
			'panel' => 'cmp_panel',
		)
	);
	cmp_add_text_setting( $wp_customize, 'cmp_contact_email', __( 'Email', 'cactusman-portfolio' ), 'cmp_contact', 'hello@sammuir.co.nz' );
	cmp_add_text_setting( $wp_customize, 'cmp_contact_phone', __( 'Phone', 'cactusman-portfolio' ), 'cmp_contact', '021 175 9457' );
	cmp_add_text_setting( $wp_customize, 'cmp_contact_github', __( 'GitHub handle', 'cactusman07', 'cactusman-portfolio' ), 'cmp_contact', '@cactusman07' );
	cmp_add_text_setting( $wp_customize, 'cmp_contact_linkedin', __( 'LinkedIn URL or handle', 'cactusman-portfolio' ), 'cmp_contact', 'in/sam-muir-a6b54164/' );
	cmp_add_text_setting( $wp_customize, 'cmp_contact_location', __( 'Location', 'cactusman-portfolio' ), 'cmp_contact', 'Auckland, NZ · UTC+13' );
}
add_action( 'customize_register', 'cmp_customize_register' );

/**
 * Helper: add a single-line text setting + control.
 */
function cmp_add_text_setting( $wp_customize, $id, $label, $section, $default = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		$id,
		array(
			'label'   => $label,
			'section' => $section,
			'type'    => 'text',
		)
	);
}

/**
 * Helper: add a multi-line textarea setting + control.
 */
function cmp_add_textarea_setting( $wp_customize, $id, $label, $section, $default = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		$id,
		array(
			'label'   => $label,
			'section' => $section,
			'type'    => 'textarea',
		)
	);
}
