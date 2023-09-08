<?php
/**
 * Not a WooCommerce Template.
 */

global $product;
$dimension = wc_format_dimensions( $product->get_dimensions( false ) );
?>
<div class="product-information">
	<?php if ( $dimension && ( 'N/A' != $dimension && 'ND' != $dimension ) ) { ?>
        <div class="row">
            <div class="col-7">
                <div class="bold-title">
					<?php _e( 'Dimensions', 'monexterieur' ); ?>
                </div>
            </div>
            <div class="col-5">
                <div class="detail">
					<?php echo preg_replace( "/\s+/", "", $dimension ); ?>
                </div>
            </div>
        </div>
	<?php } ?>

	<?php if ( $material = $product->get_meta( '_mon_material', true ) ) { ?>
        <div class="row">
            <div class="col-7">
                <div class="bold-title">
					<?php _e( 'Matieré', 'monexterieur' ); ?>
                </div>
            </div>
            <div class="col-5">
                <div class="detail">
					<?php echo $material; ?>
                </div>
            </div>
        </div>
	<?php } ?>

	<?php if ( $condition = $product->get_meta( '_mon_condition', true ) ) { ?>
        <div class="row">
            <div class="col-7">
                <div class="bold-title">
					<?php _e( 'Conditionnement', 'monexterieur' ); ?>
                </div>
            </div>
            <div class="col-5">
                <div class="detail">
					<?php echo $condition; ?>
                </div>
            </div>
        </div>
	<?php } ?>

	<?php if ( $weight = $product->get_weight() ) { ?>
        <div class="row">
            <div class="col-7">
                <div class="bold-title">
					<?php _e( 'Poids', 'monexterieur' ); ?>
                </div>
            </div>
            <div class="col-5">
                <div class="detail">
					<?php echo $weight . get_option( 'woocommerce_weight_unit' ); ?>
                </div>
            </div>
        </div>
	<?php } ?>

	<?php if ( $_mon_fabrication = $product->get_meta( '_mon_fabrication', true ) ) { ?>
        <div class="row">
            <div class="col-7">
                <div class="bold-title">
					<?php _e( 'Fabrication', 'monexterieur' ); ?>
                </div>
            </div>
            <div class="col-5">
                <div class="detail">
					<?php echo $_mon_fabrication; ?>
                </div>
            </div>
        </div>
	<?php } ?>

	<?php if ( $_mon_warranty = $product->get_meta( '_mon_warranty', true ) ) { ?>
        <div class="row">
            <div class="col-7">
                <div class="bold-title">
					<?php _e( 'Garantie', 'monexterieur' ); ?>
                </div>
            </div>
            <div class="col-5">
                <div class="detail">
					<?php echo $_mon_warranty; ?>
                </div>
            </div>
        </div>
	<?php } ?>
</div>