<?php
/**
 * Woocommerce Admin Part
 */

/**
 * Add extra field in Product backend "Shipping" tab.
 */
function monexterieur_shipping_extra_field() {
	$args = array(
		'label'       => __( 'Material', 'monexterieur' ),
		'placeholder' => __( 'Enter Material', 'monexterieur' ),
		'id'          => '_mon_material',
		'desc_tip'    => true,
		'description' => __( 'Product Material.', 'monexterieur' ),
		'type'        => 'text'
	);
	woocommerce_wp_text_input( $args );

	$args = array(
		'label'       => __( 'Condition', 'monexterieur' ),
		'placeholder' => __( 'Enter Condition', 'monexterieur' ),
		'id'          => '_mon_condition',
		'desc_tip'    => true,
		'description' => __( 'Product Condition.', 'monexterieur' ),
		'type'        => 'text'
	);
	woocommerce_wp_text_input( $args );

	$args = array(
		'label'       => __( 'Fabrication', 'monexterieur' ),
		'placeholder' => __( 'Enter Fabrication', 'monexterieur' ),
		'id'          => '_mon_fabrication',
		'desc_tip'    => true,
		'description' => __( 'Product Fabrication.', 'monexterieur' ),
		'type'        => 'text'
	);
	woocommerce_wp_text_input( $args );

	$args = array(
		'label'             => __( 'Garantie', 'monexterieur' ),
		'placeholder'       => __( '0', 'monexterieur' ),
		'id'                => '_mon_warranty',
		'desc_tip'          => true,
		'description'       => __( 'Number of Warranty Year.', 'monexterieur' ),
		'type'              => 'number',
		'custom_attributes' => array(
			'step' => '1',
			'min'  => '0'
		)
	);
	woocommerce_wp_text_input( $args );
}

add_action( 'woocommerce_product_options_dimensions', 'monexterieur_shipping_extra_field', 10 );

function monexterieur_inventory_extra_field() {
	$args = array(
		'label'       => __( 'Délais de commande long?', 'monexterieur' ),
		'id'          => '_mon_sur_commande',
		'desc_tip'    => false,
		'description' => __( 'Délais de commande long?', 'monexterieur' ),
	);

	woocommerce_wp_checkbox( $args );
}

add_action( 'woocommerce_product_options_stock_status', 'monexterieur_inventory_extra_field', 10 );

/**
 * Save Product extra field.
 */
function monexterieur_inventory_extra_field_process_save( $product_id ) {

	$material     = filter_input( INPUT_POST, '_mon_material' );
	$condition    = filter_input( INPUT_POST, '_mon_condition' );
	$warranty     = filter_input( INPUT_POST, '_mon_warranty' );
	$fabrication  = filter_input( INPUT_POST, '_mon_fabrication' );
	$sur_commande = filter_input( INPUT_POST, '_mon_sur_commande' );

	// grab the product
	$product = wc_get_product( $product_id );

	if ( $material ) {
		$product->update_meta_data( '_mon_material', sanitize_text_field( $material ) );
	} else {
		$product->delete_meta_data( '_mon_material' );
	}

	if ( $condition ) {
		$product->update_meta_data( '_mon_condition', sanitize_text_field( $condition ) );
	} else {
		$product->delete_meta_data( '_mon_condition' );
	}

	if ( $warranty ) {
		$product->update_meta_data( '_mon_warranty', sanitize_text_field( $warranty ) );
	} else {
		$product->delete_meta_data( '_mon_warranty' );
	}

	if ( $fabrication ) {
		$product->update_meta_data( '_mon_fabrication', sanitize_text_field( $fabrication ) );
	} else {
		$product->delete_meta_data( '_mon_fabrication' );
	}

	if ( $sur_commande ) {
		$product->update_meta_data( '_mon_sur_commande', sanitize_text_field( $sur_commande ) );
	} else {
		$product->delete_meta_data( '_mon_sur_commande' );
	}

	$product->save_meta_data();
}

add_action( 'woocommerce_process_product_meta', 'monexterieur_inventory_extra_field_process_save', 25, 1 );