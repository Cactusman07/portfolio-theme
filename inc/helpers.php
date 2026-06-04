<?php
/**
 * Helper functions used across templates.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Short alias for get_theme_mod with consistent fallback.
 *
 * @param string $key     Customizer setting id.
 * @param string $default Default value if setting is empty.
 * @return string
 */
function cmp_mod( $key, $default = '' ) {
	$value = get_theme_mod( $key, $default );
	return ( '' === $value || null === $value ) ? $default : $value;
}

/**
 * The x-coordinates (in world pixels) of each landmark. Used by both PHP for
 * rendering and JS for proximity / fast-travel.
 *
 * @return array<string,int>
 */
function cmp_get_landmark_positions() {
	return apply_filters(
		'cmp_landmark_positions',
		array(
			'home'      => 280,
			'about'     => 760,
			'portfolio' => 1380,
			'blog'      => 2000,
			'contact'   => 2620,
		)
	);
}

/**
 * Get portfolio items, ordered by featured-order, then date.
 *
 * @param int $limit Number of items.
 * @return WP_Post[]
 */
function cmp_get_portfolio_items( $limit = 6 ) {
	$post_types = array();

	if ( post_type_exists( 'portfolio_items' ) ) {
		$post_types[] = 'portfolio_items';
	}
	if ( post_type_exists( 'portfolio' ) ) {
		$post_types[] = 'portfolio';
	}

	if ( empty( $post_types ) ) {
		return array();
	}

	$query = new WP_Query(
		array(
			'post_type'           => $post_types,
			'posts_per_page'      => -1,
			'post_status'         => 'publish',
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	$posts = $query->posts;

	usort(
		$posts,
		static function ( $a, $b ) {
			$a_order = (int) get_post_meta( $a->ID, '_cmp_featured_order', true );
			$b_order = (int) get_post_meta( $b->ID, '_cmp_featured_order', true );

			if ( $a_order === $b_order ) {
				return strcmp( $b->post_date_gmt, $a->post_date_gmt );
			}

			return $b_order <=> $a_order;
		}
	);

	return array_slice( $posts, 0, max( 1, (int) $limit ) );
}

/**
 * Get skills for the character sheet, ordered by menu_order.
 *
 * @param int $limit Number of skills.
 * @return WP_Post[]
 */
function cmp_get_skills( $limit = 6 ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'skill',
			'posts_per_page' => $limit,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
	return $query->posts;
}

/**
 * Get latest blog posts for the journal panel.
 *
 * @param int $limit Number of posts.
 * @return WP_Post[]
 */
function cmp_get_journal_posts( $limit = 6 ) {
	return get_posts(
		array(
			'post_type'      => 'post',
			'posts_per_page' => $limit,
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		)
	);
}

/**
 * Pretty-print the first tech-tag term for a post, for the small badge.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function cmp_first_tech_tag( $post_id ) {
	$terms = get_the_terms( $post_id, 'tech_tag' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	return esc_html( $terms[0]->name );
}

/**
 * Get the portfolio status term slug for css-tagging purposes.
 *
 * @param int $post_id Post ID.
 * @return string Term slug, e.g. "live", "wip", "ongoing".
 */
function cmp_portfolio_status_slug( $post_id ) {
	$terms = get_the_terms( $post_id, 'portfolio_status' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	return sanitize_html_class( $terms[0]->slug );
}

/**
 * Render a pixel-art SVG icon by name. Looks in /assets/svg/ first.
 *
 * @param string $name SVG basename (without extension).
 * @return string|false SVG markup, or false if not found.
 */
function cmp_inline_svg( $name ) {
	$path = CMP_THEME_DIR . '/assets/svg/' . sanitize_file_name( $name ) . '.svg';
	if ( ! file_exists( $path ) ) {
		return false;
	}
	return file_get_contents( $path );
}

/**
 * Comma-string → array of trimmed strings.
 *
 * @param string $value Raw csv-ish string.
 * @return string[]
 */
function cmp_split_csv( $value ) {
	if ( ! is_string( $value ) || '' === trim( $value ) ) {
		return array();
	}
	$parts = array_map( 'trim', explode( ',', $value ) );
	return array_values( array_filter( $parts ) );
}
