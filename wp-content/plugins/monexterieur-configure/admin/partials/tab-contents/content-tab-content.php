<?php
$cat_id   = ( $current_cat instanceof WP_Term ) ? $current_cat->term_id : 0;
$cat_slug = ( $current_cat instanceof WP_Term ) ? $current_cat->slug : '';

$object = monexterieur_get_product_cat_class_object( $cat_id );
if ( ! $object ) {
	echo __( 'Product Category Config Class Not Found.', 'monexterieur-configure' );

	return;
}

if ( ! $object->category() instanceof WP_TERM ) {
	echo 'Invalid Product Category';

	return;
}

$cat_products = array();
if ( isset( $_POST["_config_products_{$cat_id}"] ) ) {
	$cat_products = $object->save( $_POST );
} else {
	$cat_products = $object->get_meta( '_config_cat_products' );
}

if ( ! $cat_products ) {
	$cat_products = array();
}

$add_button_count = 0;
if ( $cat_products ) {
	$add_button_count = intval( max( array_keys( $cat_products ) ) ) + 1;
}

$products = $object->get_products();

if ( isset( $cat_products['error'] ) ) {
	?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Monexterieur configurator products update failed. Check the error.', 'monexterieur-configure' ); ?></p>
    </div>
	<?php
	unset( $cat_products['error'] );
}

if ( isset( $cat_products['updated'] ) ) {
	?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Monexterieur configurator products updated.', 'monexterieur-configure' ); ?></p>
    </div>
	<?php
	unset( $cat_products['updated'] );
}
?>
<form action="" method="post">
    <div id="monexterieur-cat-products">
        <button type="button" id="add-button" data-count="<?php echo $add_button_count; ?>">
            Add Product
        </button>
        <div id="added-products">
			<?php
			if ( $cat_products && is_array( $cat_products ) ) {
				foreach ( $cat_products as $key => $cat_product ) {
					$title     = get_the_title( $cat_product['product'] );
					$has_error = isset( $cat_product['error'] ) ? 'has-error' : '';
					?>
                    <h3 data-key="<?php echo $key; ?>"
                        class="<?php echo $has_error; ?>"><?php echo ( $title ) ? $title : __( 'Manage Product', '' ); ?>
                        <span class="remove" style="float: right;" data-key="<?php echo $key; ?>">x</span>
                    </h3>
                    <div data-key="<?php echo $key; ?>" class="<?php echo $has_error; ?>">
                        <div class="row">
							<?php if ( $has_error ) { ?>
                                <p style="color: red; width: 100%;"><?php _e( 'There is something wrong in this section. All fields are required. Please make sure all field are selected and not empty.', 'monexterieur-configure' ); ?></p>
							<?php } ?>

                            <div class="col-two">
                                <label><?php _e( 'Select Product', 'monexterieur-configure' ); ?> </label>
                                <select class="monexterieur-product" name="cat-products[<?php echo $key; ?>][product]">
                                    <option value=""><?php _e( 'Select Product', 'monexterieur-configure' ); ?></option>
									<?php foreach ( $products as $product ) { ?>
                                        <option value="<?php echo $product->get_id(); ?>" <?php selected( $cat_product['product'], $product->get_id() ); ?>>
											<?php echo $product->get_title(); ?>
                                        </option>
									<?php } // end foreach ?>
                                </select>
                            </div>
                            <div class="col-two">
                                <label><?php _e( 'Default Quantity', 'monexterieur-configure' ); ?></label>
                                <input type="number" min="1"
                                       name="cat-products[<?php echo $key; ?>][default-qty]"
                                       value="<?php echo esc_attr( $cat_product['default-qty'] ); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <h4><?php _e( 'Conditions', 'monexterieur-configure' ); ?></h4>
                            <div class="col-one-three">
                                <div class="condition-btn-wrapper">
									<?php
									$condition_count = 0;
									if ( isset( $cat_product['product-condition']['and'] ) ) {
										$condition_count = intval( max( array_keys( $cat_product['product-condition']['and'] ) ) ) + 1;
									}
									?>
                                    <button type="button" class="condition-and add-condition" data-condition="and"
                                            data-count="<?php echo $condition_count; ?>" data-key="<?php echo $key; ?>">
                                        AND
                                    </button>
                                </div>
                            </div>
                            <div class="col-three-one">
                                <div class="condition-and-wrapper">
									<?php
									if ( isset( $cat_product['product-condition']['and'] ) && is_array( $cat_product['product-condition']['and'] ) && ! empty( $cat_product['product-condition']['and'] ) ) {
										$and_condition = array_filter( $cat_product['product-condition']['and'] );
										foreach ( $cat_product['product-condition']['and'] as $and_key => $and ) {
											?>
                                            <div class="col-full">
                                                <span><?php _e( 'If', 'monexterieur-configure' ); ?> </span>

												<?php
												$dataAttr   = array();
												$dataAttr[] = 'class="product-condition"';
												$dataAttr[] = 'data-condition="and"';
												$dataAttr[] = 'data-condition_key="' . esc_attr( $and_key ) . '"';
												$dataAttr[] = 'data-key="' . esc_attr( $key ) . '"';
												$dataAttr[] = 'name="cat-products[' . esc_attr( $key ) . '][product-condition][and][' . esc_attr( $and_key ) . '][condition]"';

												echo $object->condition_field( $dataAttr, $and['condition'] );
												?>

                                                <span> <?php _e( 'equals to', 'monexterieur-configure' ); ?> </span>

                                                <div class="product-condition-compare" style="display: inline-block;">
													<?php
													if ( 'any' == $and['condition'] ) {
														?>
                                                        <input type="text" value="any case" readonly
                                                               name="cat-products[<?php echo esc_attr( $key ); ?>][product-condition][and][<?php echo esc_attr( $and_key ); ?>][compare]">
														<?php
													} else if ( $field = $object->get_field( strtolower( $and['condition'] ), '' ) ) {
														$dataAttr = '';
														$dataAttr = 'data-key="' . esc_attr( $key ) . '"';
														$name     = "name=\"cat-products[{$key}][product-condition][and][{$and_key}][compare]\"";
														$field    = $object->get_field( strtolower( $and['condition'] ), '' );
														if ( is_array( $field ) ) {
															$field['chosen'] = $and['compare'];
														}

														if ( 'number_group_option' == $field['type'] ) {
															echo $object->number_group_option_field( $field, 'admin', $dataAttr, $name );
														} else {
															echo $object->group_field( $field, 'admin', $dataAttr, $name );
														}

														// echo $object->group_field( $field, 'admin', $dataAttr, $name );

														if ( isset( $field['compare_as'] ) && true === $field['compare_as'] ) {
															echo ' ' . __( 'must be', 'monexterieur-configure' ) . ' ';
															$compare_field           = $object->get_field( 'compare_as' );
															$compare_field['chosen'] = ( isset( $and['compare_as'] ) ) ? $and['compare_as'] : '';
															$compare_name            = 'name="cat-products[' . esc_attr( $key ) . '][product-condition][and][' . esc_attr( $and_key ) . '][compare_as]"';
															echo $object->group_field( $compare_field, 'admin', $dataAttr, $compare_name );
														}
													}
													?>
                                                </div>
                                            </div>
											<?php
										}
									}
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h4>Also Calculate Quantity From:</h4>
                            <div class="col-one-three">
                                <div class="quantity-btn-wrapper">
									<?php
									$also_qty_count = 0;
									if ( isset( $cat_product['also-quantity'] ) ) {
										$also_qty_count = intval( max( array_keys( $cat_product['also-quantity'] ) ) ) + 1;
									}
									?>
                                    <button type="button" class="quantity-and add-quantity" data-condition="and"
                                            data-key="<?php echo $key; ?>"
                                            data-count="<?php echo( isset( $cat_product['also-quantity'] ) ? count( $cat_product['also-quantity'] ) : 0 ); ?>">
                                        AND
                                    </button>
                                </div>
                            </div>
                            <div class="col-three-one">
                                <div class="quantity-and-wrapper">
									<?php
									if ( isset( $cat_product['also-quantity'] ) ) {
										foreach ( $cat_product['also-quantity'] as $also_key => $also_quantity ) {
											?>
                                            <div class="col-full">
                                                Also calculate quantity based on
                                                <select class="product-also-quantity"
                                                        name="cat-products[<?php echo $key; ?>][also-quantity][<?php echo $also_key; ?>]">
                                                    <option value="">Select Option</option>
													<?php
													if ( $quantity_fields = $object->get_quantity_fields() ) {
														foreach ( $quantity_fields as $quantity ) {
															$field    = $object->get_field( $quantity, '' );
															$selected = selected( sanitize_title( $also_quantity ), sanitize_title( $quantity ), false );
															?>
                                                            <option value="<?php echo sanitize_title( $quantity ); ?>" <?php echo $selected; ?>>
																<?php echo $field['title']; ?>
                                                            </option>
															<?php
														}
													}
													?>
                                                </select>
                                            </div>
											<?php
										}
									}
									?>
                                </div>
                                <hr/>

								<?php
								if ( $individual = $object->get_field( 'individual' ) ) {
									$name                 = 'name="cat-products[' . esc_attr( $key ) . '][individual]"';
									$individual['chosen'] = isset( $cat_product['individual'] ) ? $cat_product['individual'] : '';
									echo $individual['title'] . ' ';
									echo $object->group_field( $individual, 'admin', '', $name, array() );
									echo '<hr />';
								}
								?>

								<?php
								if ( $extra = $object->get_field( 'extra' ) ) {
									$extra['chosen'] = '';
									echo $extra['title'] . ' ';
									$name            = 'name="cat-products[' . esc_attr( $key ) . '][extra]"';
									$extra['chosen'] = isset( $cat_product['extra'] ) ? $cat_product['extra'] : '';
									echo $object->group_field( $extra, 'admin', '', $name, array() );

									if ( isset( $extra['as'] ) && 'number' == $extra['as'] ) {
										$value = isset( $cat_product['extra-number'] ) ? $cat_product['extra-number'] : 1;
										?>
                                        <input type="number"
                                               name="cat-products[<?php echo esc_attr( $key ); ?>][extra-number]"
                                               value="<?php echo $value; ?>" min="0" step="1"
                                               pattern="number">
										<?php
									}
									echo '<hr />';
								}
								?>

								<?php _e( 'Multiple of', 'monexterieur-configure' ); ?>
                                <input type="number"
                                       name="cat-products[<?php echo esc_attr( $key ); ?>][multiple-by]"
                                       value="<?php echo( isset( $cat_product['multiple-by'] ) ? $cat_product['multiple-by'] : 1 ); ?>"
                                       min="1" step="1" pattern="number">
                            </div>
                        </div>
                    </div>
					<?php
				}
			}
			?>
        </div>
    </div>
    <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>">
	<?php wp_nonce_field( "config_products_{$cat_id}", "_config_products_{$cat_id}" ); ?>

    <input type="submit" value="Submit">
</form>
<script type="text/template" id="config-product-template">
    <h3 data-key="{{key}}">Manage Product <span class="remove" style="float: right;" data-key="{{key}}">x</span></h3>
    <div data-key="{{key}}">
        <div class="row">
            <div class="col-two">
                <label><?php _e( 'Select Product', 'monexterieur-configure' ); ?> </label>
                <select class="monexterieur-product" name="cat-products[{{key}}][product]">
                    <option value=""><?php _e( 'Select Product', 'monexterieur-configure' ); ?></option>
					<?php foreach ( $products as $product ) { ?>
                        <option value="<?php echo $product->get_id(); ?>">
							<?php echo $product->get_title(); ?>
                        </option>
					<?php } // end foreach ?>
                </select>
            </div>
            <div class="col-two">
                <label><?php _e( 'Default Quantity', 'monexterieur-configure' ); ?></label>
                <input type="number" value="1" min="1" name="cat-products[{{key}}][default-qty]">
            </div>
        </div>
        <div class="row">
            <h4><?php _e( 'Conditions', 'monexterieur-configure' ); ?></h4>
            <div class="col-one-three">
                <div class="condition-btn-wrapper">
                    <button type="button" class="condition-and add-condition" data-condition="and" data-count="0"
                            data-key="{{key}}">AND
                    </button>
                </div>
            </div>

            <div class="col-three-one">
                <div class="condition-and-wrapper"></div>
            </div>
        </div>
        <div class="row">
            <h4><?php _e( 'Also Calculate Quantity From:', 'monexterieur-configure' ); ?></h4>
            <div class="col-one-three">
                <div class="quantity-btn-wrapper">
                    <button type="button" class="quantity-and add-quantity" data-condition="and" data-count="0"
                            data-key="{{key}}">AND
                    </button>
                </div>
            </div>
            <div class="col-three-one">
                <div class="quantity-and-wrapper"></div>
                <hr/>
				<?php
				if ( $individual = $object->get_field( 'individual' ) ) {
					echo $individual['title'] . ' ';
					echo $object->group_field( $individual, 'admin', '', 'cat-products[{{key}}][individual]', array() );
					echo '<hr />';
				}
				?>

				<?php
				if ( $extra = $object->get_field( 'extra' ) ) {
					$extra['chosen'] = '';
					echo $extra['title'] . ' ';
					echo $object->group_field( $extra, 'admin', '', 'cat-products[{{key}}][extra]', array() );

					if ( isset( $extra['as'] ) && 'number' == $extra['as'] ) {
						?>
                        <input type="number" name="cat-products[{{key}}][extra-number]" value="0" min="0" step="1"
                               pattern="number">
						<?php
					}
					echo '<hr />';
				}
				?>
				<?php _e( 'Multiple of', 'monexterieur-configure' ); ?>
                <input type="number" name="cat-products[{{key}}][multiple-by]" value="1" min="1" step="1"
                       pattern="number">
            </div>
        </div>
    </div>
</script>


<script type="text/template" id="config-product-condition-template">
    <div class="col-full">
        <span><?php _e( 'If', 'monexterieur-configure' ); ?> </span>

		<?php
		$dataAttr   = array();
		$dataAttr[] = 'class="product-condition"';
		$dataAttr[] = 'data-condition="{{condition}}"';
		$dataAttr[] = 'data-condition_key="{{condition-key}}"';
		$dataAttr[] = 'data-key="{{key}}"';
		$dataAttr[] = 'name="cat-products[{{key}}][product-condition][{{condition}}][{{condition-key}}][condition]"';

		echo $object->condition_field( $dataAttr, '' );
		?>

        <span> <?php _e( 'equals to', 'monexterieur-configure' ); ?> </span>

        <div class="product-condition-compare" style="display: inline-block;">

        </div>
    </div>
</script>

<?php
if ( $condition_fields = $object->get_condition_fields() ) {
	foreach ( $condition_fields as $condition ) {
		if ( $field = $object->get_field( $condition ) ) {
			$dataAttr       = '';
			$dataAttr       = 'data-key="{{key}}"';
			$name           = '';
			$name           = 'name="cat-products[{{key}}][product-condition][{{condition}}][{{condition-key}}][compare]"';
			$field['class'] = 'product-condition-' . sanitize_title( $field['title'] );
			?>
            <script type="text/template" id="product-condition-<?php echo sanitize_title( $condition ); ?>">
				<?php
				if ( 'number_group_option' == $field['type'] ) {
					echo $object->number_group_option_field( $field, 'admin', $dataAttr, $name );
				} else {
					echo $object->group_field( $field, 'admin', $dataAttr, $name );
				}
				?>

				<?php
				if ( isset( $field['compare_as'] ) && true === $field['compare_as'] ) {
					echo ' ' . __( 'must be', 'monexterieur-configure' ) . ' ';
					$compare_field = $object->get_field( 'compare_as' );
					$compare_name  = 'name="cat-products[{{key}}][product-condition][{{condition}}][{{condition-key}}][compare_as]"';
					echo $object->group_field( $compare_field, 'admin', $dataAttr, $compare_name );
				}
				?>
            </script>
			<?php
		}
	}
}
?>

<script type="text/template" id="product-condition-any">
    <input type="text" readonly="" value="any case"
           name="cat-products[{{key}}][product-condition][{{condition}}][{{condition-key}}][compare]"/>
</script>

<script type="text/template" id="product-also-quantity-template">
    <div class="col-full">
		<?php _e( 'Also calculate quantity based on', 'monexterieur-configure' ); ?>
        <select class="product-also-quantity" name="cat-products[{{key}}][also-quantity][{{qtykey}}]">
            <option value=""><?php _e( 'Select Option', 'monexterieur-configure' ); ?></option>
			<?php
			if ( $quantity_fields = $object->get_quantity_fields() ) {
				foreach ( $quantity_fields as $quantity ) {
					$field = $object->get_field( $quantity, '' );
					?>
                    <option value="<?php echo sanitize_title( $quantity ); ?>"><?php echo $field['title']; ?></option>
					<?php
				}
			}
			?>
        </select>
    </div>
</script>

<script type="text/javascript">
    (function ($) {
        $('#added-products').accordion({
            heightStyle: "content"
        });

        $('#monexterieur-cat-products').on('click', '#add-button', function (e) {
            e.preventDefault();
            var $this = $(this),
                count = $this.attr('data-count'),
                $container = $this.next(),
                $template = $('#config-product-template');

            $container.append($template.html().replace(/{{key}}/g, count));
            $this.attr('data-count', parseInt(count) + 1);
            $('#added-products').accordion('refresh');
            $('.monexterieur-product').select2();
        });

        $('#monexterieur-cat-products').on('change', '.monexterieur-product', function (e) {
            e.preventDefault();
            var $this = $(this),
                val = $this.find('option:selected').text();

            $this.parents('.ui-accordion-content').prev().html('<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>' + val);
        });

        $('#monexterieur-cat-products').on('click', '.add-condition', function (e) {
            e.preventDefault();
            var $this = $(this),
                count = $this.attr('data-key'),
                condition = $this.data('condition'),
                child_count = $this.attr('data-count'),
                template = $('#config-product-condition-template').html();

            template = template.replace(/{{key}}/g, count);
            template = template.replace(/{{condition-key}}/g, child_count.toString());
            template = template.replace(/{{condition}}/g, condition);

            $this.parent().parent().next().find('.condition-' + condition + '-wrapper').append(template);
            $this.attr('data-count', parseInt(child_count) + 1);

        });

        $('#monexterieur-cat-products').on('change', '.product-condition', function (e) {
            e.preventDefault();
            var $this = $(this),
                count = $this.attr('data-key'),
                value = $this.val(),
                condition = $this.attr('data-condition'),
                child_count = $this.attr('data-condition_key'),
                template = $('#product-condition-' + value.toLowerCase()).html();

            template = template.replace(/{{key}}/g, count);
            template = template.replace(/{{condition-key}}/g, child_count.toString());
            template = template.replace(/{{condition}}/g, condition);

            $this.siblings('.product-condition-compare').html(template);
        });

        $('#monexterieur-cat-products').on('click', '.add-quantity', function (e) {
            e.preventDefault();
            var $this = $(this),
                count = $this.attr('data-key'),
                child_count = $this.attr('data-count'),
                condition = $this.data('condition'),
                template = $('#product-also-quantity-template').html();

            template = template.replace(/{{key}}/g, count);
            template = template.replace(/{{qtykey}}/g, child_count);

            $this.parent().parent().next().find('.quantity-' + condition + '-wrapper').append(template);
            $this.attr('data-count', parseInt(child_count) + 1);
        });

        $('#monexterieur-cat-products').on('click', '.remove', function (e) {
            e.preventDefault();
            var key = $(this).attr('data-key');
            $('[data-key="' + key + '"]').remove();

            $('#added-products').accordion('refresh');
        });

        $('.monexterieur-product').select2();
    })(jQuery);
</script>
