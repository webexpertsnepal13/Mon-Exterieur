<?php
function monexterieur_product_quantity_in_decimal_extra_field() {
	$args = array(
		'label'       => __( 'Quantity in Decimal?', 'monexterieur' ),
		'id'          => '_mon_quantity_in_decimal',
		'desc_tip'    => false,
		'description' => __( 'Use decimal quantity for this product?', 'monexterieur' ),
	);

	woocommerce_wp_checkbox( $args );
}

add_action( 'woocommerce_product_options_stock_status', 'monexterieur_product_quantity_in_decimal_extra_field', 10 );

/**
 * Save Product extra field.
 */
function monexterieur_product_quantity_in_decimal_extra_field_save( $product_id ) {

	$decimal_quantity = filter_input( INPUT_POST, '_mon_quantity_in_decimal' );

	// grab the product
	$product = wc_get_product( $product_id );

	if ( $decimal_quantity ) {
		$product->update_meta_data( '_mon_quantity_in_decimal', sanitize_text_field( $decimal_quantity ) );
	} else {
		$product->delete_meta_data( '_mon_quantity_in_decimal' );
	}

	$product->save_meta_data();
}

add_action( 'woocommerce_process_product_meta', 'monexterieur_product_quantity_in_decimal_extra_field_save', 25, 1 );

/**
 * Check if product is enabled for Decimal quantity.
 *
 * @param $product
 *
 * @return bool
 */
function monexterieur_is_product_quantity_in_decimal( $product ) {
	$check = false;
	if ( $product instanceof WC_Product ) {
		if ( $product->get_meta( '_mon_quantity_in_decimal', true ) ) {
			$check = true;
		}
	} else if ( is_numeric( $product ) ) {
		if ( get_post_meta( $product, '_mon_quantity_in_decimal', true ) ) {
			$check = true;
		}
	}

	return $check;
}

/**
 * Check for quantity min value.
 *
 * @param $val
 * @param $product
 *
 * @return float
 */
function monexterieur_quantity_min_decimal( $val, $product ) {
	if ( monexterieur_is_product_quantity_in_decimal( $product ) ) {
		return 0.1;
	}

	return $val;
}

add_filter( 'woocommerce_quantity_input_min', 'monexterieur_quantity_min_decimal', 99, 2 );


/**
 * Check for quantity step value.
 *
 * @param $val
 * @param $product
 *
 * @return float
 */
function monexterieur_quantity_step_allow_decimal( $val, $product ) {
	if ( monexterieur_is_product_quantity_in_decimal( $product ) ) {
		return 0.1;
	}

	return $val;
}

add_filter( 'woocommerce_quantity_input_step', 'monexterieur_quantity_step_allow_decimal', 99, 2 );

/**
 * Add support for decimal quantity value.
 */
function monexterieur_stock_float_val() {
	// Removes the WooCommerce filter, that is validating the quantity to be an int
	remove_filter( 'woocommerce_stock_amount', 'intval' );

	// Add a filter, that validates the quantity to be a float
	add_filter( 'woocommerce_stock_amount', 'floatval' );
}

add_action( 'init', 'monexterieur_stock_float_val', 99 );


// Add unit price fix when showing the unit price on processed orders
//add_filter( 'woocommerce_order_amount_item_total', 'unit_price_fix', 10, 5 );
function unit_price_fix( $price, $order, $item, $inc_tax = false, $round = true ) {
	$qty = ( ! empty( $item['qty'] ) && $item['qty'] != 0 ) ? $item['qty'] : 1;
	if ( $inc_tax ) {
		$price = ( $item['line_total'] + $item['line_tax'] ) / $qty;
	} else {
		$price = $item['line_total'] / $qty;
	}
	$price = $round ? round( $price, 2 ) : $price;

	return $price;
}