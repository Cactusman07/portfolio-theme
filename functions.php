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
		wp_enqueue_script( 'particlesMin_js', get_template_directory_uri() . '/js/particles.min.js',array('jquery'), '',true);
		wp_enqueue_script( 'particleConfig_js', get_template_directory_uri() . '/js/particle-config.js',array('jquery'), '',true);
		wp_enqueue_script( 'connectingDot_js', get_template_directory_uri() . '/js/connecting-dot-particles.js',array('jquery'), '',true);
		wp_enqueue_script( 'in-view_js', get_template_directory_uri() . '/js/in-view.min.js',array('jquery'), '',true);
		wp_enqueue_script( 'app_js', get_template_directory_uri() . '/js/app.js',array('jquery'), '',true);
	}
	add_action( 'wp_enqueue_scripts', 'Portfolio_js');

	/* Add CSS */
	function Portfolio_css() {
		wp_enqueue_style('normalize_css', get_template_directory_uri() . '/css/normalize.css', false);
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
	
?>