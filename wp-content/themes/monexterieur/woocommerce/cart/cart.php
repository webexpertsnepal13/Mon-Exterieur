<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="custom-cart-form woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>
    <p>
		<?php _e( 'Mes produits configurés', 'monexterieur' ); ?>
		
		<?php
		$wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
		if ( $wishlist_page_id ) {
			?>
			&nbsp;&nbsp;&nbsp;
			<a href="<?php echo esc_url( get_the_permalink( $wishlist_page_id ) ); ?>">
				<?php echo esc_html__( 'Voir ma wishlist', 'monexterieur' ); ?>
			</a>
			<?php
		}
		?>
	</p>
	<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
		<div class="tbody-custom">

            <div class="tbody-products-list">
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <div class="product-thumbnail">
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                if ( ! $product_permalink ) {
                                    echo $thumbnail; // PHPCS: XSS ok.
                                } else {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                }
                                ?>
                            </div>
                            <div class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                <strong>
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }

                                    do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                    // Meta data.
                                    echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                    // Backorder notification.
                                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                    }
                                    ?>
                                </strong>
                            </div>
                            <div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                <span>Nombre d’unitè</span>
                                <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                            'min_value'    => '0',
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                ?>
                            </div>
                            <div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                <div>
                                    Prix unitaire
                                </div>
                                <strong>
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                    ?>
                                </strong>
                            </div>
                            <div class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                <div>
                                    Prix
                                </div>
                                <strong>
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                    ?>
                                </strong>

                            </div>
							
							<div class="product-weight">
								<div>Poids</div>
								<strong>
									<?php echo ( ( $_product->get_weight() ? $_product->get_weight() : 0 )  * $cart_item['quantity'] ) . ' ' . get_option( 'woocommerce_weight_unit' ); ?>
								</strong>
							</div>

							<div class="product-remove">
                                <?php
                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"></a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_html__( 'Remove this item', 'woocommerce' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() )
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php do_action( 'woocommerce_cart_contents' ); ?>
            </div>

            <div class="brown-cart-box-wrapper clearfix">
                <div class="cart-total">
					<div class="cart-clear-on-line">
						<a class="me_btn with-radius" href="<?php echo esc_url( add_query_arg( array( 'empty-cart' => 'empty' ), wc_get_cart_url() ) ); ?>"><?php esc_html_e( 'Vider le panier', 'monexterieur' ); ?></a>
                        <div class="cart-total-info">
							<strong>TOTAL POIDS</strong> <span><?php echo WC()->cart->cart_contents_weight . ' ' . get_option( 'woocommerce_weight_unit' ); ?></span><br>

                            <strong>TOTAL</strong> <span><?php echo WC()->cart->get_cart_total(); ?></span>
                        </div>
					</div>
                </div>
                <div class="actions brown-cart-box">
                    <?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon">
                            <label for="coupon_code"><?php esc_html_e( 'Code PROMO', 'woocommerce' ); ?></label>
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                                   placeholder=""/>
                            <button type="submit" class="button me_btn" name="apply_coupon"
                                    value="Apply coupon"><?php _e( 'Appliquer', 'monexterieur' ); ?></button>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                    <?php } ?>

                    <button type="submit" class="button me_btn" name="update_cart"
                            value="<?php esc_attr_e( 'mettre à jour le panier', 'woocommerce' ); ?>"><?php esc_html_e( 'mettre à jour le panier', 'woocommerce' ); ?></button>

                    <?php do_action( 'woocommerce_cart_actions' ); ?>

                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>
                <div class="checkout-way">
                    <a style="display: inline-block;" class="me_btn"
                       href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php _e( 'Passer la commande', 'moneterieur' ); ?></a>
                </div>
            </div>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</div>
	</div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>
