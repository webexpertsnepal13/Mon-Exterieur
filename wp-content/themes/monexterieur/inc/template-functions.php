<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Mon_Exterieur
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function monexterieur_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Add a class for shop page.
	if ( is_shop() ) {
		$classes[] = 'monexterieur-shop';
	}

	return $classes;
}

add_filter( 'body_class', 'monexterieur_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function monexterieur_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wp_head', 'monexterieur_pingback_header' );

/**
 * If admin bar is showing, push HTML to avoid overlap by admin bar.
 */
function monexterieur_admin_bar_height_push() {
	if ( is_admin_bar_showing() ) {
		?>
        <style type="text/css" media="print">
            #wpadminbar {
                display: none;
            }
        </style>
        <style type="text/css" media="screen">
            html,
            .site-header {
                margin-top: 32px !important;
            }

            * html body {
                margin-top: 32px !important;
            }

            @media screen and ( max-width: 782px ) {
                html,
                .site-header {
                    margin-top: 46px !important;
                }

                * html body {
                    margin-top: 46px !important;
                }
            }
        </style>
		<?php
	}
}

add_action( 'wp_head', 'monexterieur_admin_bar_height_push' );

/**
 * Add Custom attribute on next post link HTML tag.
 *
 * @param $attr
 *
 * @return string
 */
function monexterieur_next_posts_link_attributes( $attr ) {
	$attr .= ' class="me_btn" style="display: block;"';

	return $attr;
}

add_filter( 'next_posts_link_attributes', 'monexterieur_next_posts_link_attributes' );


/**
 * Search WooCommerce Product only.
 *
 * @param $query
 */
function monexterieur_search_woocommerce_product_only( $query ) {
	if ( ! is_admin() && is_search() && $query->is_main_query() ) {
		$query->set( 'post_type', 'product' );
	}
}

add_action( 'pre_get_posts', 'monexterieur_search_woocommerce_product_only' );
