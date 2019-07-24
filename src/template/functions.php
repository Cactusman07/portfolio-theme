<?php
/* Add theme support */
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	
/* Register menus */
	function register_theme_menus() {
		register_nav_menus(
			array(
				'main-menu' => __( 'Main Menu' )
			)
		);
	}
	add_action( 'init', 'register_theme_menus' );
	
	/* Add JS */	
	function Portfolio_js() {
		wp_enqueue_script( 'app_js', get_template_directory_uri() . '/js/app.min.js',array('jquery'), '',true);
	}
	add_action( 'wp_enqueue_scripts', 'Portfolio_js');

	/* Add CSS */
	function Portfolio_css() {
		wp_enqueue_style('style_css', get_stylesheet_uri(), false);
	}
	add_action('wp_enqueue_scripts', 'Portfolio_css');
	
	function wpbeginner_remove_version() {
		return '';
	}
	add_filter('the_generator', 'wpbeginner_remove_version');
	
	/* Remove wp version param from any enqueued scripts */
	function vc_remove_wp_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
			$src = remove_query_arg( 'ver', $src );
		return $src;
	}
	add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
	add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
	
	/* Add custom post type 'Portfolio Items' to my Portfolio site */
	function create_postTypePortfolioItems() {
		
		$labels = array(
			'name'					=> _x( 'Portfolio Items', 'Post Type General Name', 'portfolio'),
			'singular name'			=> _x( 'Portfolio Item', 'portfolio'),
			'menu_name'				=> __( 'Portfolio Items', 'portfolio'),
			'all_items'         	=> __( 'All Items', 'portfolio' ),
			'view_item'         	=> __( 'View Item', 'portfolio' ),
			'add_new_item'      	=> __( 'Add New Item', 'portfolio' ),
			'add_new'           	=> __( 'Add Item', 'portfolio' ),
			'edit_item'         	=> __( 'Edit Item', 'portfolio' ),
			'update_item'       	=> __( 'Update Item', 'portfolio' ),
			'search_items'      	=> __( 'Search for Item', 'portfolio' ),
			'not_found'         	=> __( 'Not Found', 'portfolio' ),
			'not_found_in_trash'	=> __( 'Not found in Trash', 'portfolio' ),
		);
		
		$args = array(
			'label'				=> __('portfolio_items', 'portfolio'),
			'description'		=> __('A list of Portfolio Items.', 'portfolio'),
			'labels'			=> $labels,
			'supports'			=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail',	
										  'custom-fields' ),
			'hierarchical' 		=> false,
			'public'			=> true,
			'show_ui'			=> true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => true,
			'menu_position'     => 5,
			'can_export'        => true,
			'has_archive'       => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite' => array(
				'slug' => 'portfolio',
				'with_front' => false,
			),	
		);
		register_post_type('portfolio_items', $args );
	}
	add_action('init', 'create_postTypePortfolioItems', 0 );

	/* Add 2nd thumbnail to portfolio item post types */
	if (class_exists('MultiPostThumbnails')) {
		new MultiPostThumbnails(
			array(
				'label' => __( 'Page header', 'Portfolio'),
				'id' => 'secondary-image',
				'post_type' => 'portfolio_items'
			)
		);
	}
?>