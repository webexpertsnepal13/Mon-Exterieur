<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

    <section class="related products" id="shop-product-contain">

		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Nous Vous Conseillons Aussi', 'woocommerce' ) );

		if ( $heading ) :
			?>
                <h2><?php echo esc_html( $heading ); ?></h2>
            <?php endif; ?>
        <div class="related-slider">
            <?php
            woocommerce_product_loop_start();
            foreach ( $related_products as $related_product ) {
                $post_object = get_post( $related_product->get_id() );

                setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                wc_get_template_part( 'content', 'product' );
            }
            woocommerce_product_loop_end();
            wp_reset_postdata();
            ?>
        </div>



    </section>
<?php
endif;
