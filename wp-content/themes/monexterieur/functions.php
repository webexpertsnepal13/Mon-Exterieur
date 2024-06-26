<?php
/**
 * Mon Exterieur functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mon_Exterieur
 */

if ( ! function_exists( 'monexterieur_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function monexterieur_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Mon Exterieur, use a find and replace
		 * to change 'monexterieur' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'monexterieur', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'monexterieur' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			// 'comment-form',
			// 'comment-list',
			// 'gallery',
			// 'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'monexterieur_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		/**
		 * Add custom image size.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_image_size/
		 */
		add_image_size( 'product-cat-thumb', 200, 160, true );
		add_image_size( 'product-cat-banner', 1366, 528, true );
		add_image_size( 'nos-realisations', 908, 560, true );
		add_image_size( 'fp-extra-block', 536, 353, true );
		add_image_size( 'single-product-gallery', 530, 530, true );
		add_image_size( 'notice-installation', 345, 226, true );
	}
endif;
add_action( 'after_setup_theme', 'monexterieur_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function monexterieur_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'monexterieur_content_width', 640 );
}

add_action( 'after_setup_theme', 'monexterieur_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function monexterieur_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'monexterieur' ),
		'id'            => 'footer-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'monexterieur' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s col-sm">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'monexterieur_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
/**
 * Enqueue scripts and styles.
 */
function monexterieur_scripts() {
	$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) ? '' : '.min';
	$ver = $ver = wp_get_theme()->get( 'Version' );

	//Enqueue fonts
	wp_register_style( 'monexterieur-fonts-style', 'https://use.typekit.net/wtm5xrm.css', '', $ver );
	wp_enqueue_style( 'monexterieur-fonts-style' );

	wp_enqueue_style( 'monexterieur-style', get_template_directory_uri() . '/style.css', '', $ver );

	//Enqueue Main Style
	$main_css_file = 'assets/css/main' . $min . '.css';
	if( defined( 'SCRIPT_DEBUG' ) && true == SCRIPT_DEBUG ) {
		$ver = filemtime( get_template_directory() . "/{$main_css_file}" );
	}
	wp_register_style( 'monexterieur-main-style', get_template_directory_uri() . '/' . $main_css_file, '', $ver );
	wp_enqueue_style( 'monexterieur-main-style' );

	//Enqueue vendor script
	$vendor_js_file = 'js/vendor' . $min . '.js';
	if( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) {
		$ver = filemtime( get_template_directory() . "/{$vendor_js_file}" );
	}
	wp_register_script( 'monexterieur-vendor-script', get_template_directory_uri() . '/' . $vendor_js_file, array( 'jquery' ), $ver, 'true' );
	wp_enqueue_script( 'monexterieur-vendor-script' );

	//Enqueue main script
	$main_js_file = 'assets/js/main' . $min . '.js';
	if( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) {
		$ver = filemtime( get_template_directory() . "/{$main_js_file}" );
	}
	wp_register_script( 'monexterieur-main-script', get_template_directory_uri() . '/' . $main_js_file, array(
		'jquery',
		'monexterieur-vendor-script'
	), $ver, true );
	wp_enqueue_script( 'monexterieur-main-script' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

add_action( 'wp_enqueue_scripts', 'monexterieur_scripts' );


// Register Custom Post Type
function monexterieur_cutom_post_types() {

	$labels = array(
		'name'                  => _x( 'Nos Réalisations', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Nos Réalisations', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Nos Réalisations', 'text_domain' ),
		'name_admin_bar'        => __( 'Nos Réalisations', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);

	$args = array(
		'label'               => __( 'Nos Réalisations', 'text_domain' ),
		'description'         => __( 'Nos Réalisations Post Type', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => false,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'realisations', $args );
}

add_action( 'init', 'monexterieur_cutom_post_types' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce-hooks-functions.php';
	require get_template_directory() . '/inc/woocommerce.php';
	require get_template_directory() . '/inc/woocommerce-hooks.php';
}

/**
 * Admin part files.
 */
if( is_admin() ) {
	require get_template_directory() . '/inc/admin/woocommerce.php';
}

/**
 * Custom Menu Walker
 */
if ( ! class_exists( 'ME_Menu_Walker' ) ) {
	require get_template_directory() . '/inc/class-me-menu-walker.php';
}

/**
 * Theme Shortcodes
 */
require get_template_directory() . '/inc/template-shortcodes.php';

/**
 * Widgets
 */
require get_template_directory() . '/inc/widgets/class-me-configure-widget.php';

/**
 * For calculating shipping costs based on individual product weight and shipping zone
 */
// require get_template_directory() . '/inc/class-me-weight-based-shipping-charge.php';


/* SVG support */
function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );


/**
 * Prevent loading inner pages
 * Set all inner pages to 404 status.
 * Make this SEO friendly
 */
function monexterieur_wp_links_redirect_404() {
	/* check for custom page/links to set 404 */
	if( is_singular( 'attachment' ) ) {
		global $wp_query;

		/* WP set page as 404 */
		$wp_query->set_404();

		/* set hader status to 404 */
		status_header(404);

		/* load 404 template part */
		get_template_part( 404 );

		/* stop processing other PHP scripts */
		exit;
	}
}
add_action( 'wp', 'monexterieur_wp_links_redirect_404', 0 );

/**
 * WooCommerce Fragments.
 *
 * @param array $fragments WooCommerce Fragments.
 * @return array
 */
function monexterieur_add_to_cart_fragment( $fragments ) {
	$fragments['.cart-item-count'] = '<span class="cart-item-count">' . WC()->cart->get_cart_contents_count() . '</span>';
	$fragments['.cart-item-count-mobile'] = '<span class="show-mobile cart-item-count-mobile">' . WC()->cart->get_cart_contents_count() . '</span>';
 	return $fragments;

}
add_filter( 'woocommerce_add_to_cart_fragments', 'monexterieur_add_to_cart_fragment' );

/**
 * Clear cart items.
 */
function monexterieur_clear_cart() {
	$clear_cart = filter_input( INPUT_GET, 'empty-cart' );
	if ( ! $clear_cart || 'empty' !== $clear_cart ) {
		return;
	}

	WC()->cart->empty_cart();
}
add_action( 'init', 'monexterieur_clear_cart', 99 );