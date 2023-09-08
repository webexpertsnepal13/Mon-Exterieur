<?php


/**
 * Content Product Title Wrapper
 */
if ( ! function_exists( 'monexterieur_woocommerce_shop_loop_item_title' ) ) {
	function monexterieur_woocommerce_shop_loop_item_title() {
		echo '<div class="me-product-detail-container">';
	}
}

/**
 * Content Product Title Wrapper
 */
if ( ! function_exists( 'monexterieur_woocommerce_shop_loop_item_title_close' ) ) {
	function monexterieur_woocommerce_shop_loop_item_title_close() {
		echo '</div><!-- /.me-product-detail-container -->';
		?>
		<div class="more-detail">
			<span class=""><?php _e( 'VOIR DÉTAILS', 'monexterieur' ); ?></span>
			<svg xmlns="http://www.w3.org/2000/svg" id="arrow-right-line" width="31.183" height="31.183"
			     viewBox="0 0 31.183 31.183">
				<path id="Path_19" data-name="Path 19" d="M31.183,0H0V31.183H31.183Z" fill="none"/>
				<path id="Path_20" data-name="Path 20"
				      d="M19.815,13.029H4v2.6H19.815L12.846,22.6l1.837,1.837L24.789,14.328,14.683,4.222,12.846,6.059Z"
				      transform="translate(1.197 1.264)" fill="#1f1f1f"/>
			</svg>
		</div>
		<?php
	}
}

/**
 * WooCommerce Archive Order-by Filter
 */
if ( ! function_exists( 'monexterieur_wc_archive_product_filter' ) ) {
	function monexterieur_wc_archive_product_filter() {
		if ( is_woocommerce() && ! is_archive() ) {
			return;
		}

		if ( is_shop() ) {
			$url = get_permalink( wc_get_page_id( 'shop' ) );
		} else {
			$object = get_queried_object();
			$url    = get_term_link( $object );
		}

		$orderby = 'menu_order';
		if( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], array( 'date', 'menu_order' ) ) ) {
			$orderby = $_GET['orderby'];
		}
		?>
		<div class="row">
			<div class="col-sm-12">
				<ul class="me-wc-catalog-ordering">
					<li>
						<a <?php echo ( 'menu_order' == $orderby ) ? 'class="active"' : ''; ?> href="<?php echo esc_url( $url ); ?>"><?php _e( 'Alphabétique', 'monexterieur' ); ?></a>
					</li>
					<li>
						<a <?php echo ( 'date' == $orderby ) ? 'class="active"' : ''; ?> href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'date' ), $url ) ); ?>"><?php _e( 'Nouveautés', 'monexterieur' ); ?></a>
					</li>
				</ul>
				<?php // woocommerce_catalog_ordering(); ?>
			</div>
		</div>
		<?php
	}
}

/**
 * Product page product information.
 */
if( ! function_exists( 'monexterieur_template_product_information' ) ) {
    function monexterieur_template_product_information() {
        wc_get_template_part( 'single-product/product', 'information' );
    }
}


/**
 * Exclude products on the shop page
 */
function monexterieur_pre_get_posts_query_exclude_posts( $q ) {
    $exclude_posts = get_posts(
		array(
			'post_type' => 'product',
			'fields' => 'ids',
			'meta_key' => '_product_hide_is_shop',
			'meta_value' => '1',
			'posts_per_page' => -1,
		)
	);


	if ( is_array( $exclude_posts ) && 0 < count( $exclude_posts ) ) {
    	$q->set( 'post__not_in', $exclude_posts );
	}
}


if ( ! function_exists( 'monexterieur_extra_charge_for_home_delivery' ) ) {
	function monexterieur_extra_charge_for_home_delivery() {
		$fee_name = __( 'Livraison au cœur du jardin', 'monexterieur' );
		$weight_threshold = 100;
		$fee_amount = 90;
		$total_weight = 0;

		if ( 'enabled' === WC()->session->get( 'extra-shipping', false ) ) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$total_weight += $cart_item['data']->get_weight() * $cart_item['quantity'];
			}

			if ( $total_weight > $weight_threshold ) {
				WC()->cart->add_fee( $fee_name, $fee_amount, false );
			}
		} else {
			foreach ( WC()->cart->get_fees() as $fee ) {
				if ( $fee->name == $fee_name ) {
					WC()->cart->remove_fee( $fee->id );
					break;
				}
			}
		}
	}
}

if ( ! function_exists( 'monexterieur_checkout_extra_charge_for_home_delivery' ) ) {
	function monexterieur_checkout_extra_charge_for_home_delivery( $post_data ) {
		$data = array();
		parse_str( $post_data, $data );
		
		if ( isset( $data['extra-shipping'] ) ) {
			WC()->session->set( 'extra-shipping', 'enabled' );
		} else {
			WC()->session->__unset( 'extra-shipping' );
		}
	};
}