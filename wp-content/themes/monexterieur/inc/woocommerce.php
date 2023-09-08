<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Mon_Exterieur
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function monexterieur_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

add_action( 'after_setup_theme', 'monexterieur_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function monexterieur_woocommerce_scripts() {
	// wp_enqueue_style( 'monexterieur-woocommerce-style', get_template_directory_uri() . '/woocommerce.css' );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'monexterieur-woocommerce-style', $inline_font );
}

// add_action( 'wp_enqueue_scripts', 'monexterieur_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 *
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function monexterieur_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}

add_filter( 'body_class', 'monexterieur_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function monexterieur_woocommerce_products_per_page() {
	return 6;
}

add_filter( 'loop_shop_per_page', 'monexterieur_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function monexterieur_woocommerce_thumbnail_columns() {
	return 4;
}

add_filter( 'woocommerce_product_thumbnails_columns', 'monexterieur_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function monexterieur_woocommerce_loop_columns() {
	return 3;
}

add_filter( 'loop_shop_columns', 'monexterieur_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 *
 * @return array $args related products args.
 */
function monexterieur_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 4,
		'columns'        => 4,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'monexterieur_woocommerce_related_products_args' );

if ( ! function_exists( 'monexterieur_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function monexterieur_woocommerce_product_columns_wrapper() {
		$columns = monexterieur_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'monexterieur_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'monexterieur_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function monexterieur_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'monexterieur_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'monexterieur_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function monexterieur_woocommerce_wrapper_before() {
		?>
        <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'monexterieur_woocommerce_wrapper_before' );

if ( ! function_exists( 'monexterieur_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function monexterieur_woocommerce_wrapper_after() {
		?>
        </main><!-- #main -->
        </div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'monexterieur_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'monexterieur_woocommerce_header_cart' ) ) {
 * monexterieur_woocommerce_header_cart();
 * }
 * ?>
 */

if ( ! function_exists( 'monexterieur_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 *
	 * @return array Fragments to refresh via AJAX.
	 */
	function monexterieur_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		monexterieur_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'monexterieur_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'monexterieur_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function monexterieur_woocommerce_cart_link() {
		?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>"
           title="<?php esc_attr_e( 'View your shopping cart', 'monexterieur' ); ?>">
			<?php
			$item_count_text = sprintf(
			/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'monexterieur' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
            <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span
                    class="count"><?php echo esc_html( $item_count_text ); ?></span>
        </a>
		<?php
	}
}

if ( ! function_exists( 'monexterieur_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function monexterieur_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
        <ul id="site-header-cart" class="site-header-cart">
            <li class="<?php echo esc_attr( $class ); ?>">
				<?php monexterieur_woocommerce_cart_link(); ?>
            </li>
            <li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
            </li>
        </ul>
		<?php
	}
}

/**
 * Register side for WooCommerce Archive
 */
if ( ! function_exists( 'monexterieur_wc_sidebar' ) ) {
	function monexterieur_wc_sidebar() {
		register_sidebar(
			array(
				'name'          => __( 'Shop Sidebar', 'textdomain' ),
				'id'            => 'shop-sidebar',
				'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'monexterieur_wc_sidebar' );

/**
 * Alphabetical Order
 */
if ( ! function_exists( 'monexterieur_wc_products_ordering' ) ) {
	function monexterieur_wc_products_ordering( $sort_args ) {
		$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

		if ( 'alphabet' == $orderby_value ) {
			$sort_args['orderby']  = 'title';
			$sort_args['order']    = 'asc';
			$sort_args['meta_key'] = '';
		}

		return $sort_args;
	}
}
// add_filter( 'woocommerce_get_catalog_ordering_args', 'monexterieur_wc_products_ordering' );

/**
 * WC Variable Product with Color Variation option Output.
 */
if ( ! function_exists( 'monexterieur_color_variation_attribute_options' ) ) {
	function monexterieur_color_variation_attribute_options( $args ) {
		$args = wp_parse_args(
			apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ),
			array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'show_option_none' => __( 'Choose an option', 'woocommerce' ),
			)
		);

		// Get selected value.
		if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
			$selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
			$args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
		}

		include get_template_directory() . '/template-parts/content-variation.php';
	}
}


if( ! function_exists( 'monexterieur_woocommerce_product_single_add_to_cart_text' ) ) {
	/**
     * Add to cart French text.
     *
	 * @param $text
	 *
	 * @return string|void
	 */
    function monexterieur_woocommerce_product_single_add_to_cart_text( $text ) {
        $text = __( 'Ajouter au panier', 'monexterieur' );

        return $text;
    }
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'monexterieur_woocommerce_product_single_add_to_cart_text' );
