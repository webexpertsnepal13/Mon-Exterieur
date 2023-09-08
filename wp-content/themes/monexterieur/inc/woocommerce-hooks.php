<?php
/**
 * Define hooks only. Do not add hook callback function here.
 * Use woocommerce-hooks-functions.php to define callback functions.
 */


/**
 * Global Hooks
 */
remove_action( 'woocommerce_before_main_content', 'monexterieur_woocommerce_wrapper_before', 10 ); // remove default woocommerce opening divs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 ); // remove default woocommerce breadcrumb
remove_action( 'woocommerce_after_main_content', 'monexterieur_woocommerce_wrapper_after', 10 ); // remove default woocommerce ending divs
/**
 * Global Hooks End
 */


/**
 * Archive Page
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop', 'monexterieur_woocommerce_product_columns_wrapper', 40 );
remove_action( 'woocommerce_after_shop_loop', 'monexterieur_woocommerce_product_columns_wrapper_close', 40 );

add_action( 'woocommerce_before_main_content', 'monexterieur_wc_archive_product_filter' );
add_action( 'woocommerce_product_query', 'monexterieur_pre_get_posts_query_exclude_posts' );
/**
 * Shop Page End
 */


/**
 * Content Product
 */
add_action( 'woocommerce_shop_loop_item_title', 'monexterieur_woocommerce_shop_loop_item_title', 1 );
add_action( 'woocommerce_after_shop_loop_item', 'monexterieur_woocommerce_shop_loop_item_title_close', 1 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
/**
 * Content Product End
 */

/**
 * Product Single
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'monexterieur_template_product_information', 21 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 21 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );


/**
 * Cart
 */
add_action( 'woocommerce_cart_calculate_fees', 'monexterieur_extra_charge_for_home_delivery', 10 );


/**
 * Checkout
 */
add_action( 'woocommerce_checkout_update_order_review', 'monexterieur_checkout_extra_charge_for_home_delivery' );
