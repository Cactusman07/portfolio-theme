<?php
/**
 * Custom post types and taxonomies.
 *
 * - `portfolio_items` CPT for portfolio projects.
 * - `skill` CPT for the character-sheet stats.
 * - `tech_tag` taxonomy shared by portfolio, posts and skills.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Portfolio Items CPT.
 */
function cmp_register_portfolio_items_cpt() {
	$labels = array(
		'name'               => __( 'Portfolio Items', 'cactusman-portfolio' ),
		'singular_name'      => __( 'Portfolio Item', 'cactusman-portfolio' ),
		'add_new_item'       => __( 'Add new portfolio item', 'cactusman-portfolio' ),
		'edit_item'          => __( 'Edit portfolio item', 'cactusman-portfolio' ),
		'view_item'          => __( 'View portfolio item', 'cactusman-portfolio' ),
		'menu_name'          => __( 'Portfolio Items', 'cactusman-portfolio' ),
	);

	register_post_type(
		'portfolio_items',
		array(
			'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-portfolio',
			'has_archive'         => 'portfolio',
			'rewrite'             => array(
				'slug'       => 'portfolio',
				'with_front' => false,
			),
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'menu_position'       => 5,
		)
	);
}
add_action( 'init', 'cmp_register_portfolio_items_cpt' );

/**
 * Ensure portfolio item permalinks resolve even when a static /portfolio page exists.
 */
function cmp_register_portfolio_item_rewrite_rule() {
	add_rewrite_rule( '^portfolio/([^/]+)/?$', 'index.php?post_type=portfolio_items&name=$matches[1]', 'top' );
}
add_action( 'init', 'cmp_register_portfolio_item_rewrite_rule', 20 );

/**
 * Flush rewrite rules after theme switch so custom portfolio routes are registered.
 */
function cmp_flush_rewrites_on_theme_switch() {
	cmp_register_portfolio_items_cpt();
	cmp_register_portfolio_item_rewrite_rule();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'cmp_flush_rewrites_on_theme_switch' );

/**
 * Register the Skill CPT — used to power the character sheet.
 */
function cmp_register_skill_cpt() {
	$labels = array(
		'name'          => __( 'Skills', 'cactusman-portfolio' ),
		'singular_name' => __( 'Skill', 'cactusman-portfolio' ),
		'add_new_item'  => __( 'Add new skill', 'cactusman-portfolio' ),
		'edit_item'     => __( 'Edit skill', 'cactusman-portfolio' ),
		'menu_name'     => __( 'Skills', 'cactusman-portfolio' ),
	);

	register_post_type(
		'skill',
		array(
			'labels'        => $labels,
			'public'        => false,
			'show_ui'       => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-chart-bar',
			'supports'      => array( 'title', 'excerpt', 'page-attributes', 'custom-fields' ),
			'menu_position' => 6,
		)
	);
}
add_action( 'init', 'cmp_register_skill_cpt' );

/**
 * Shared taxonomy: technology / stack tags.
 */
function cmp_register_tech_taxonomy() {
	register_taxonomy(
		'tech_tag',
		array( 'portfolio_items', 'post', 'skill' ),
		array(
			'labels'            => array(
				'name'          => __( 'Tech tags', 'cactusman-portfolio' ),
				'singular_name' => __( 'Tech tag', 'cactusman-portfolio' ),
				'menu_name'     => __( 'Tech tags', 'cactusman-portfolio' ),
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'tech' ),
		)
	);
}
add_action( 'init', 'cmp_register_tech_taxonomy' );

/**
 * Status taxonomy for portfolio items: live / ongoing / wip / brand / b2b portal etc.
 */
function cmp_register_status_taxonomy() {
	register_taxonomy(
		'portfolio_status',
		array( 'portfolio_items' ),
		array(
			'labels'            => array(
				'name'          => __( 'Statuses', 'cactusman-portfolio' ),
				'singular_name' => __( 'Status', 'cactusman-portfolio' ),
				'menu_name'     => __( 'Status', 'cactusman-portfolio' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'cmp_register_status_taxonomy' );

/**
 * Register custom meta fields for portfolio items, exposed to REST.
 *
 * Use ACF or Meta Box plugins for nicer editor UI; this gets a baseline working.
 */
function cmp_register_portfolio_meta() {
	$portfolio_post_types = array( 'portfolio_items' );

	foreach ( $portfolio_post_types as $portfolio_post_type ) {
		register_post_meta(
			$portfolio_post_type,
			'_cmp_external_url',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'esc_url_raw',
				'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
			)
		);
		register_post_meta(
			$portfolio_post_type,
			'_cmp_year',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
			)
		);
		register_post_meta(
			$portfolio_post_type,
			'_cmp_icon_svg',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'wp_kses_post',
				'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
			)
		);
		register_post_meta(
			$portfolio_post_type,
			'_cmp_featured_order',
			array(
				'type'              => 'integer',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 0,
				'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
			)
		);
	}

	// Skill meta — percentage 0–100 and a "levelling up" flag for the green stripe.
	register_post_meta(
		'skill',
		'_cmp_skill_percent',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'default'           => 50,
			'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
		)
	);
	register_post_meta(
		'skill',
		'_cmp_skill_meta',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
		)
	);
	register_post_meta(
		'skill',
		'_cmp_skill_levelling',
		array(
			'type'              => 'boolean',
			'single'            => true,
			'show_in_rest'      => true,
			'default'           => false,
			'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
		)
	);
}
add_action( 'init', 'cmp_register_portfolio_meta' );

/**
 * Skill settings metabox (percent/meta/levelling) for easier editing in admin.
 */
function cmp_add_skill_settings_metabox() {
	add_meta_box(
		'cmp-skill-settings',
		__( 'Skill settings', 'cactusman-portfolio' ),
		'cmp_render_skill_settings_metabox',
		'skill',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'cmp_add_skill_settings_metabox' );

/**
 * Render skill settings metabox fields.
 *
 * @param WP_Post $post Current post object.
 */
function cmp_render_skill_settings_metabox( $post ) {
	wp_nonce_field( 'cmp_save_skill_settings', 'cmp_skill_settings_nonce' );

	$raw_percent = get_post_meta( $post->ID, '_cmp_skill_percent', true );
	$percent     = ( '' === $raw_percent ) ? 50 : max( 0, min( 100, (int) $raw_percent ) );
	$meta_lbl    = (string) get_post_meta( $post->ID, '_cmp_skill_meta', true );
	$levelling   = (bool) get_post_meta( $post->ID, '_cmp_skill_levelling', true );
	?>
	<p>
		<label for="cmp-skill-percent"><strong><?php esc_html_e( 'Skill percent (0-100)', 'cactusman-portfolio' ); ?></strong></label><br>
		<input type="number" id="cmp-skill-percent" name="cmp_skill_percent" min="0" max="100" step="1" value="<?php echo esc_attr( $percent ); ?>" style="width:100%;">
	</p>
	<p>
		<label for="cmp-skill-meta"><strong><?php esc_html_e( 'Meta label (optional)', 'cactusman-portfolio' ); ?></strong></label><br>
		<input type="text" id="cmp-skill-meta" name="cmp_skill_meta" value="<?php echo esc_attr( $meta_lbl ); ?>" style="width:100%;" placeholder="v18 · Redux Toolkit">
	</p>
	<p>
		<label>
			<input type="checkbox" name="cmp_skill_levelling" value="1" <?php checked( $levelling ); ?>>
			<?php esc_html_e( 'Show as still learning', 'cactusman-portfolio' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Save skill settings metabox fields.
 *
 * @param int $post_id Post ID.
 */
function cmp_save_skill_settings_metabox( $post_id ) {
	if ( ! isset( $_POST['cmp_skill_settings_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmp_skill_settings_nonce'] ) ), 'cmp_save_skill_settings' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$percent = isset( $_POST['cmp_skill_percent'] ) ? (int) $_POST['cmp_skill_percent'] : 50;
	$percent = max( 0, min( 100, $percent ) );
	update_post_meta( $post_id, '_cmp_skill_percent', $percent );

	$meta_lbl = isset( $_POST['cmp_skill_meta'] ) ? sanitize_text_field( wp_unslash( $_POST['cmp_skill_meta'] ) ) : '';
	update_post_meta( $post_id, '_cmp_skill_meta', $meta_lbl );

	$levelling = isset( $_POST['cmp_skill_levelling'] ) ? 1 : 0;
	update_post_meta( $post_id, '_cmp_skill_levelling', $levelling );
}
add_action( 'save_post_skill', 'cmp_save_skill_settings_metabox' );
