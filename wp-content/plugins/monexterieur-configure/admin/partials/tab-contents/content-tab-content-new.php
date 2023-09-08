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

/*
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
*/

$mec_config_products = $object->get_meta( '_mec_config_products' );
?>
<style>
	.mec-config-products {
		--accordionBGColor: #ffffff;
		--borderTop: 1px solid #ccc;
	}

	.mec-config-products .mec-loading {
		opacity: 0.6;
		cursor: progress;
	}

	.mec-config-products .mec-loading button,
	.mec-config-products .mec-loading input,
	.mec-config-products .mec-loading select,
	.mec-config-products .mec-loading select + span.select2 {
		pointer-events: none;
	}

	.mec-config-products .add-product {
		padding: 10px 0;
	}

	.mec-accordions .mec-accordion {
		background: var( --accordionBGColor );
		margin: 0 0 15px 0;
		border-radius: 5px;
		box-shadow: 0 8px 10px rgba(0, 0, 0, .1);
		overflow: hidden;
	}

	.mec-accordions .mec-accordion > :not(.mec-header) {
		display: none;
	}

	.mec-accordions .mec-accordion.mec-open > :not(.mec-header) {
		display: block;
	}

	.mec-accordions .mec-header h2 {
		margin: 0;
		padding: 20px;
		border-left: 5px solid green;
		display: block;
		cursor: pointer;
	}

	.mec-accordions .mec-header h2:hover {
		background: #e7e7e7;
	}

	.mec-accordions .mec-accordion.mec-open > .mec-header > h2 {
		border-left-color: blue;
		background: #e7e7e7;
	}

	.mec-accordions .mec-accordion .mec-body {
		padding: 10px 20px;
		border-bottom: var( --borderTop );
		border-top: var( --borderTop );
	}

	.mec-accordions .mec-accordion .mec-footer {
		padding: 10px 20px;
	}

	.mec-accordions .mec-accordion .mec-body .mec-product {
		padding: 10px 0;
		display: grid;
		gap: 10px;
		vertical-align: middle;
		align-items: center;
		grid: auto-flow / 4fr 120px;
	}

	.mec-accordions .mec-accordion .mec-body .mec-product .mec-select-product {
		display: grid;
		gap: 10px;
		vertical-align: middle;
		align-items: center;
		width: 100%;
		grid: auto-flow / 110px 1fr;
	}

	.mec-accordions .mec-accordion .mec-body .mec-product .mec-select-product select + .select2 {
		min-width: 50% !important;
	}

	.mec-accordions .mec-accordion .mec-body .mec-product .mec-new-instance button {
		width: 100%;
	}

	.mec-accordions .mec-accordion .mec-body .mec-product .mec-select-product span:first-child {
		font-weight: 700;
	}

	.mec-accordions .mec-accordion .mec-body .mec-product .mec-select-product span select {
		display: block;
		width: 100%;
	}

	.mec-accordions .mec-accordion .mec-body .mec-conditions,
	.mec-accordions .mec-accordion .mec-body .mec-quantities {
		margin-bottom: 40px;
	}

	.mec-accordions .mec-accordion .mec-body .mec-condition-header,
	.mec-accordions .mec-accordion .mec-body .mec-quantity-header {
		display: grid;
		grid: auto-flow / 4fr 120px;
		padding: 10px 0;
		border-bottom: var( --borderTop );
		align-items: center;
	}

	.col-full {
		display: grid;
		grid-auto-flow: column;
		grid-template-columns: 40px 1fr 60px 1fr 30px;
		gap: 10px;
		padding: 10px 9px;
		vertical-align: middle;
		align-items: center;
		background: #efefef;
	}

	.mec-quantity-body .col-full {
		grid-template-columns: 220px 1fr 30px;
	}

	.col-full:nth-child(odd) {
		background: #f9f9f9;
	}

	.mec-condition-body .col-full:first-child .mec-condition-info-text > span:not(:first-child),
	.mec-quantity-body .col-full:first-child .mec-condition-info-text > span:not(:first-child) {
		display: none;
	}

	.mec-condition-body .col-full:not(:first-child) .mec-condition-info-text > span:first-child, 
	.mec-quantity-body .col-full:not(:first-child) .mec-condition-info-text > span:first-child {
		display: none;
	}

	.col-full select {
		width: 100%;
	}

	.col-full .product-condition-compare {
		display: grid;
		grid-auto-flow: column;
		gap: 10px;
		align-items: center;
		vertical-align: middle;
	}

	.mec-quantity-multiply {
		margin-top: 15px;
		border-top: var( --borderTop );
	}

	.col-full.mec-quantity-extra {
		grid-template-columns: 220px 1fr 30px 1fr 30px;
	}
</style>

<div class="mec-config-products">
	<div class="add-product">
		<button type="button" class="button button-primary"><?php esc_html_e( 'Add Product', 'monexterieur-configure' ); ?></button>
	</div> 

	<div class="mec-cat-products mec-accordions">
		<?php
		if ( is_array( $mec_config_products ) ) {
			$mec_config_products = array_unique( $mec_config_products );
			foreach( $mec_config_products as $product_key => $product_id ) {
				?>
				<div class="mec-cat-product mec-accordion">
					<div class="mec-header">
						<h2 data-default="<?php esc_html_e( 'Add new product', 'monexterieur-configure' ); ?>">
							<?php echo get_the_title( $product_id ); ?>
						</h2>
					</div>

					<div class="mec-body">
						<form action="" method="post">
							<input type="hidden" name="cat-id" value="<?php echo esc_attr( $cat_id ); ?>" />
							<input type="hidden" name="product-key" value="<?php echo esc_attr( $product_key ); ?>" />
							<div class="mec-instances mec-accordions">
								<div class="mec-product">
									<div class="mec-select-product">
										<span><?php esc_html_e( 'Select Product', 'monexterieur-configure' ); ?>:</span>
										<span>
											<select name="mec-product">
												<option value="<?php echo esc_attr( $product_id ); ?>" selected>
													<?php
													$post_status = get_post_status( $product_id );
													echo get_the_title( $product_id );
													if ( 'publish' !== $post_status ) {
														echo " ({$post_status})";
													}
													?>
												</option>
											</select>
										</span>
									</div>

									<div class="mec-new-instance">
										<button style="<?php echo ! $product_id ? "display: none;" : ''; ?>" type="button" class="button button-secondary"><?php esc_html_e( 'Add Instance', 'monexterieur-configure' ); ?></button>
									</div>
								</div>

								<?php $product_config_data = $object->get_meta( "_mec_product_config_{$product_id}" ); ?>
								
								<div class="mec-instance-lists">
									<?php
									if ( isset( $product_config_data['instance'] ) && is_array( $product_config_data['instance'] ) ) {
										foreach ( $product_config_data['instance'] as $instance_key => $instance ) {
											$title = '';
											if ( isset( $instance['conditions'] ) && is_array( $instance['conditions'] ) ) {
												$_title = array();
												foreach ( $instance['conditions'] as $condition_key => $condition ) {
													if ( 'any' === $condition['condition'] ) {
														$_title[] = esc_html__( 'Any Case', 'monexterieur-configure' );
													}
													else if ( isset( $condition['compare_as'] ) ) {
														$_title[] = "{$object->get_title( $condition['condition'] )}: <span style=\"color: blue;\">{$condition['compare_as']}</span> <span style=\"color: green;\">{$condition['compare']}</span>";
													} else {
														if ( 'group' === $object->get_field( $condition['condition'] )['type'] ) {
															$_title[] = "{$object->get_title( $condition['condition'] )}: <span style=\"color: green;\">{$object->get_filed_options_text( $condition['condition'], $condition['compare'] )}</span>";
														} else {
															$_title[] = "{$object->get_title($condition['condition'])}: {$condition['compare']}";
														}
													}
												}

												$title = implode( ' <span style="color: red;">AND</span> ', $_title );
											}

											if ( ! $title ) {
												$title = esc_html__( 'Add product instance', 'monexterieur-configure' );
											}
											?>
											<div class="mec-instance mec-accordion" data-instance_key=<?php echo esc_attr( $instance_key ); ?>>
												<div class="mec-header">
													<h2 data-default="<?php esc_attr_e( 'Add product instance', 'monexterieur-configure' ); ?>">
														<?php echo $title; ?>
													</h2>
												</div>

												<div class="mec-body">
													<div class="mec-conditions">
														<div class="mec-condition-header">
															<strong><?php esc_html_e( 'Conditions', 'monexterieur-configure' ); ?>:</strong>
															<button type="button" class="button button-secondary"><?php esc_html_e( 'Add', 'monexterieur-configure' ); ?></button>
														</div>

														<div class="mec-condition-body">
															<?php 
															if ( isset( $instance['conditions'] ) && is_array( $instance['conditions'] ) ) {
																foreach ( $instance['conditions'] as $condition_key => $condition ) {
																	?>
																	<div class="col-full" data-condition_key="<?php echo esc_attr( $condition_key ); ?>">
																		<div class="mec-condition-info-text">
																			<span><?php _e( 'If', 'monexterieur-configure' ); ?></span>
																			<span><?php _e( 'And if', 'monexterieur-configure' ); ?></span>
																		</div>

																		<div>
																			<?php
																			$dataAttr   = array();
																			$dataAttr[] = 'class="product-condition"';
																			$dataAttr[] = 'name="instance[' . esc_attr( $instance_key ) . '][conditions][' .esc_attr( $condition_key ) . '][condition]"';

																			echo $object->condition_field( $dataAttr, $condition['condition'] );
																			?>
																		</div>

																		<div> <?php _e( 'equals to', 'monexterieur-configure' ); ?> </div>

																		<div class="product-condition-compare">
																			<?php
																			if ( 'any' == $condition['condition'] ) {
																				?>
																				<input type="text" value="any case" readonly name="instance[<?php echo esc_attr( $instance_key ); ?>][conditions][<?php echo esc_attr( $condition_key ); ?>][compare]">
																				<?php
																			} else if ( $field = $object->get_field( strtolower( $condition['condition'] ), '' ) ) {
																				$dataAttr = '';
																				$name     = 'name="instance['. esc_attr( $instance_key ) .'][conditions][' . esc_attr( $condition_key ) . '][compare]"';
																				$field    = $object->get_field( strtolower( $condition['condition'] ), '' );
																				if ( is_array( $field ) ) {
																					$field['chosen'] = $condition['compare'];
																				}

																				if ( 'number_group_option' == $field['type'] ) {
																					echo $object->number_group_option_field( $field, 'admin', $dataAttr, $name );
																				} else {
																					echo $object->group_field( $field, 'admin', $dataAttr, $name );
																				}

																				if ( isset( $field['compare_as'] ) && true === $field['compare_as'] ) {
																					echo ' ' . __( 'must be', 'monexterieur-configure' ) . ' ';
																					$compare_field           = $object->get_field( 'compare_as' );
																					$compare_field['chosen'] = ( isset( $condition['compare_as'] ) ) ? $condition['compare_as'] : '';
																					$compare_name            = 'name="instance[' . esc_attr( $instance_key ) . '][conditions][' . esc_attr( $condition_key ) . '][compare_as]"';
																					echo $object->group_field( $compare_field, 'admin', $dataAttr, $compare_name );
																				}
																			}
																			?>
																		</div>

																		<div class="product-condition-action action-col-remove">
																			<button type="button" class="button button-secondary" title="<?php esc_attr_e( 'Remove condition', 'monexterieur-configure' ); ?>">X</button>
																		</div>
																	</div>
																	<?php
																} // #END foreach $instance['conditions'];
															} // #END if $instance['conditions'];
															?>
														</div>
													</div>

													<div class="mec-quantities">
														<div class="mec-quantity-header">
															<strong><?php esc_html_e( 'Add Quantities from', 'monexterieur-configure' ); ?>:</strong>
															<button type="button" class="button button-secondary"><?php esc_html_e( 'Add', 'monexterieur-configure' ); ?></button>
														</div>

														<div class="mec-quantity-body">
															<div class="mec-quantity-list">
																<?php
																if ( isset( $instance['quantity'] ) && is_array( $instance['quantity'] ) ) {
																	foreach ( $instance['quantity'] as $quantity_key => $also_quantity ) {
																		?>
																		<div class="col-full">
																			<div class="mec-condition-info-text">
																				<span><?php _e( 'Calculate quantity based on', 'monexterieur-configure' ); ?></span>
																				<span><?php _e( 'And also calculate quantity based on', 'monexterieur-configure' ); ?></span>
																			</div>

																			<div>
																				<select class="product-also-quantity" name="instance[<?php echo esc_attr( $instance_key ); ?>][quantity][<?php echo esc_attr( $quantity_key ); ?>]">
																					<option value=""><?php _e( 'Select Option', 'monexterieur-configure' ); ?></option>
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

																			<div class="product-quantity-action action-col-remove">
																				<button type="button" class="button button-secondary" title="Remove quantity">X</button>
																			</div>
																		</div>
																		<?php
																	} #END foreach $condition['quantity'];
																} // #END if $condition['quantity'];
																?>
															</div>

															<div class="mec-quantity-multiply">
																<?php if ( $individual = $object->get_field( 'individual' ) ) { ?>
																	<div class="col-full">
																		<?php
																		$individual['chosen'] = isset( $instance['individual'] ) ? $instance['individual'] : '';
																		echo '<div>' . $individual['title'] . '</div>';
																		echo '<div>' . $object->group_field( $individual, 'admin', '', 'name="instance[' .  esc_attr( $instance_key ) . '][individual]"', array() ) . '</div>';
																		?>
																	</div>
																<?php } ?>

																<?php if ( $extra = $object->get_field( 'extra' ) ) { ?>
																	<div class="col-full mec-quantity-extra">
																		<?php
																		$extra['chosen'] = '';
																		echo '<div>' . $extra['title'] . '</div>';
																		$extra['chosen'] = isset( $instance['extra'] ) ? $instance['extra'] : '';
																		echo $object->group_field( $extra, 'admin', '', 'name="instance[' . esc_attr( $instance_key ) . '][extra]"', array() );

																		if ( isset( $extra['as'] ) && 'number' == $extra['as'] ) {
																			$value = isset( $instance['extra-number'] ) ? $instance['extra-number'] : 0;
																			?>
																			<div>
																				<input type="number" name="instance[<?php echo esc_attr( $instance_key ); ?>][extra-number]" value="<?php echo esc_attr( $instance['extra-number'] ); ?>" min="0" step="1" pattern="number">
																			</div>
																			<?php
																		}
																		?>
																	</div>
																<?php } ?>

																<div class="col-full">
																	<div>
																		<?php _e( 'Multiply total quantity by', 'monexterieur-configure' ); ?>
																	</div>
																	<div>
																		<input type="number" name="instance[<?php echo esc_attr( $instance_key ); ?>][multiply-by]" value="<?php echo esc_attr( $instance['multiply-by'] ); ?>" min="1" step="1" pattern="number">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="mec-footer">
													<button type="button" class="button button-danger">Remove</button>
												</div>
											</div>
											<?php
										} // #END foreach $product_config_data['instance'];
									} // #END if $product_config_data['instance']
									?>
								</div>

							</div>
						</form>
					</div>

					<div class="mec-footer">
						<button type="button" class="button button-danger" data-remove=""><?php esc_html_e( 'Remove', 'monexterieur-configure' ); ?></button>
						<button type="button" class="button button-success" data-save=""><?php esc_html_e( 'Save', 'monexterieur-configure' ); ?></button>
					</div>
				</div>
				<?php
			} // #END foreach $mec_config_products;
			?>
			
			<?php
		} #END if is_array( $mec_config_products );
		?>
	</div>
</div>

<template id="template-mec-product-accordion">
	<div class="mec-cat-product mec-accordion mec-open">
		<div class="mec-header">
			<h2 data-default="<?php esc_html_e( 'Add new product', 'monexterieur-configure' ); ?>"><?php esc_html_e( 'Add new product', 'monexterieur-configure' ); ?></h2>
		</div>

		<div class="mec-body">
			<form action="" method="post">
				<input type="hidden" name="cat-id" value="<?php echo esc_attr( $cat_id ); ?>" />
				<div class="mec-instances mec-accordions">
					<div class="mec-product">
						<div class="mec-select-product">
							<span><?php esc_html_e( 'Select Product', 'monexterieur-configure' ); ?>:</span>
							<span>
								<select name="mec-product"></select>
							</span>
						</div>

						<div class="mec-new-instance">
							<button style="display: none;" type="button" class="button button-secondary"><?php esc_html_e( 'Add Instance', 'monexterieur-configure' ); ?></button>
						</div>
					</div>

					<div class="mec-instance-lists"></div>
				</div>
			</form>
		</div>

		<div class="mec-footer">
			<button type="button" class="button button-danger" data-remove=""><?php esc_html_e( 'Remove', 'monexterieur-configure' ); ?></button>
			<button type="button" class="button button-success" data-save=""><?php esc_html_e( 'Save', 'monexterieur-configure' ); ?></button>
		</div>
	</div>
</template>

<template id="template-mec-product-instance">
	<div class="mec-instance mec-accordion mec-open" data-instance_key="{{instanceKey}}">
		<div class="mec-header">
			<h2><?php esc_html_e( 'Add product instance', 'monexterieur-configure' ); ?></h2>
		</div>

		<div class="mec-body">
			<div class="mec-conditions">
				<div class="mec-condition-header">
					<strong><?php esc_html_e( 'Conditions', 'monexterieur-configure' ); ?>:</strong>
					<button type="button" class="button button-secondary"><?php esc_html_e( 'Add', 'monexterieur-configure' ); ?></button>
				</div>

				<div class="mec-condition-body">

				</div>
			</div>

			<div class="mec-quantities">
				<div class="mec-quantity-header">
					<strong><?php esc_html_e( 'Add Quantities from', 'monexterieur-configure' ); ?>:</strong>
					<button type="button" class="button button-secondary"><?php esc_html_e( 'Add', 'monexterieur-configure' ); ?></button>
				</div>

				<div class="mec-quantity-body">
					<div class="mec-quantity-list"></div>
					<div class="mec-quantity-multiply">
						<?php if ( $individual = $object->get_field( 'individual' ) ) { ?>
							<div class="col-full">
								<?php
								echo '<div>' . $individual['title'] . '</div>';
								echo '<div>' . $object->group_field( $individual, 'admin', '', 'name="instance[{{instanceKey}}][individual]"', array() ) . '</div>';
								?>
							</div>
						<?php } ?>

						<?php if ( $extra = $object->get_field( 'extra' ) ) { ?>
							<div class="col-full mec-quantity-extra">
								<?php							
								$extra['chosen'] = '';
								echo '<div>' . $extra['title'] . '</div>';
								echo $object->group_field( $extra, 'admin', '', 'name="instance[{{instanceKey}}][extra]"', array() );

								if ( isset( $extra['as'] ) && 'number' == $extra['as'] ) {
									?>
									<div>by</div>
									<div>
										<input type="number" name="instance[{{instanceKey}}][extra-number]" value="0" min="0" step="1" pattern="number">
									</div>
									<?php
								}
								?>
							</div>
						<?php } ?>

						<div class="col-full">
							<div>
								<?php _e( 'Multiply total quantity by', 'monexterieur-configure' ); ?>
							</div>
							<div>
								<input type="number" name="instance[{{instanceKey}}][multiply-by]" value="1" min="1" step="1" pattern="number">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mec-footer">
			<button type="button" class="button button-danger">Remove</button>
		</div>
	</div>
</template>

<template id="template-mec-product-condition">
    <div class="col-full" data-condition_key="{{conditionKey}}">
		<div class="mec-condition-info-text">
			<span><?php _e( 'If', 'monexterieur-configure' ); ?></span>
			<span><?php _e( 'And if', 'monexterieur-configure' ); ?></span>
		</div>

		<div>
			<?php
			$dataAttr   = array();
			$dataAttr[] = 'class="product-condition"';
			$dataAttr[] = 'name="instance[{{key}}][conditions][{{conditionKey}}][condition]"';

			echo $object->condition_field( $dataAttr, '' );
			?>
		</div>

        <div> <?php _e( 'equals to', 'monexterieur-configure' ); ?> </div>

        <div class="product-condition-compare"></div>

		<div class="product-condition-action action-col-remove">
			<button type="button" class="button button-secondary" title="<?php esc_attr_e( 'Remove condition', 'monexterieur-configure' ); ?>">X</button>
		</div>
    </div>
</template>

<template id="template-mec-product-condition-any">
    <input type="text" readonly="" value="any case" name="instance[{{key}}][conditions][{{conditionKey}}][compare]"/>
</template>

<?php
if ( $condition_fields = $object->get_condition_fields() ) {
	foreach ( $condition_fields as $condition ) {
		if ( $field = $object->get_field( $condition ) ) {
			$dataAttr       = '';
			$name           = '';
			$name           = 'name="instance[{{key}}][conditions][{{conditionKey}}][compare]"';
			$field['class'] = 'product-condition-' . sanitize_title( $field['title'] );
			?>
            <template id="template-mec-product-condition-<?php echo sanitize_title( $condition ); ?>">
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
					$compare_name  = 'name="instance[{{key}}][conditions][{{conditionKey}}][compare_as]"';
					echo $object->group_field( $compare_field, 'admin', $dataAttr, $compare_name );
				}
				?>
            </template>
			<?php
		}
	}
}
?>

<template id="template-mec-product-quantity">
	<div class="col-full">
		<div class="mec-condition-info-text">
			<span><?php _e( 'Calculate quantity based on', 'monexterieur-configure' ); ?></span>
			<span><?php _e( 'And also calculate quantity based on', 'monexterieur-configure' ); ?></span>
		</div>

		<div>
			<select class="product-also-quantity" name="instance[{{key}}][quantity][{{quantityKey}}]">
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

		<div class="product-quantity-action action-col-remove">
			<button type="button" class="button button-secondary" title="Remove quantity">X</button>
		</div>
	</div>
</template>

<script>
	var MEC_ACCORDION = (function($) {
		var cat_id = <?php echo $cat_id; ?>;

		var select2Options = {
			ajax: {
				url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				dataType: 'json',
				delay: 300,
				data: function( params ) {
					return {
						q: params.term,
						cat_id: cat_id,
						action: 'mec_select2_product'
					}
				},
				processResults: function( data ) {
					return {
						results: data.data.results
					}
				},
				cache: true,
			},
			placeholder: 'Type 3 words to search product',
			minimumInputLength: 3,
			allowClear: true
		};

		var productChanged = function productChanged(event) {
			var $this = $(event.currentTarget),
				text = $this.find('option:selected').text(),
				$form = $this.closest('.mec-accordion')
				$h2 = $form.find('> .mec-header > h2');

			if ( text ) {
				$h2.text(text);
				$form.find('.mec-new-instance button').show();
			} else {
				$h2.text($h2.data('default'));
				$form.find('.mec-new-instance button').hide();
			}
		};

		var addNewProduct = function addNewProduct(event) {
			event.preventDefault();

			var newProductTpl = $('#template-mec-product-accordion').clone().html();

			$('.mec-cat-products').append(newProductTpl);

			$('.mec-cat-products .mec-cat-product').last().find('select[name="mec-product"]').select2(select2Options).on('change', productChanged);

			$('html, body').animate({
				scrollTop: $('.mec-cat-products .mec-cat-product').last().offset().top - 300
			});
		};

		var toggleAccordion = function toggleAccordion(event) {
			event.preventDefault();
			$(event.currentTarget).closest('.mec-accordion').toggleClass('mec-open');
		};

		var addNewInstance = function addNewInstance(event) {
			event.preventDefault();

			var $this = $(event.currentTarget),
				instanceKey = Math.random().toString(36).slice(2),
				newInstanceTpl = $('#template-mec-product-instance').clone().html();

			while ( $('[data-instance_key="' + instanceKey  + '"]').length ) {
				instanceKey = Math.random().toString(36).slice(2);
			}

			newInstanceTpl = newInstanceTpl.replace(/{{instanceKey}}/g, instanceKey);

			$this.closest('.mec-accordions').find('.mec-instance-lists').append(newInstanceTpl);
		};

		var removeProduct = function removeProduct(event) {
			event.preventDefault();

			var $this = $(event.currentTarget),
				$wrapper = $this.closest('.mec-cat-product');

			if ( confirm( 'Removing Product. Are you sure?') ) {
				var mec_product = $wrapper.find('form select[name="mec-product"]').val();
				if ( mec_product ) {
					$wrapper.addClass('mec-loading');
					$.ajax({
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						dataType: 'json',
						type: 'post',
						cache: false,
						data: {
							action: 'mec_config_remove',
							cat_id: cat_id,
							mec_product: mec_product,
						},
						success: function(response) {
							alert(response.data);
							$this.closest('.mec-accordion').remove();
							$wrapper.removeClass('mec-loading');
							window.location.href = window.location.href;
						},
						error: function(err) {
							alert('Something went wrong. Please try again.');
							$wrapper.removeClass('mec-loading');
						}
					});
				} else {
					$this.closest('.mec-accordion').remove();
				}
			}
		};

		var addNewCondition = function addNewCondition(event) {
			event.preventDefault();

			var $this = $(event.currentTarget),
				$wrapper = $this.closest('.mec-conditions'),
				instanceKey = $this.closest('[data-instance_key]').data('instance_key'),
				new_key = Math.random().toString(36).slice(2),
				newConditionTpl = $('#template-mec-product-condition').clone().html();

			while ( $('[data-condition_key="' + new_key  + '"]').length ) {
				new_key = Math.random().toString(36).slice(2);
			}

			newConditionTpl = newConditionTpl.replace(/{{key}}/g, instanceKey).replace(/{{conditionKey}}/g, new_key);

			$wrapper.find('.mec-condition-body').append(newConditionTpl);
		};

		var addNewQuantity = function addNewQuantity(event) {
			event.preventDefault();

			var $this = $(event.currentTarget),
				instanceKey = $this.closest('[data-instance_key]').data('instance_key'),
				new_key = Math.random().toString(36).slice(2),
				$wrapper = $this.closest('.mec-quantities'),
				newQuantityTpl = $('#template-mec-product-quantity').clone().html();

			newQuantityTpl = newQuantityTpl.replace(/{{key}}/g, instanceKey).replace(/{{quantityKey}}/g, new_key);

			$wrapper.find('.mec-quantity-list').append(newQuantityTpl);
		};

		var init = function init() {
			if ( ! $('.mec-accordions').length ) {
				return;
			}

			$('.mec-config-products').on('click', '.add-product button', addNewProduct);
			$('.mec-config-products').on('click', '.mec-accordions .mec-accordion .mec-header h2', toggleAccordion);
			$('.mec-config-products select[name="mec-product"]').select2(select2Options).on('change', productChanged);
			$('.mec-config-products').on('click', '[data-remove]', removeProduct);

			$('.mec-config-products').on('click', '.action-col-remove > button', function(event) {
				event.preventDefault();

				$(event.currentTarget).parent().parent().remove();
			});

			// Instance
			$('.mec-config-products').on('click', '.mec-new-instance button', addNewInstance);
			$('.mec-config-products').on('click', '.mec-instance .mec-footer  button', function(event) {
				event.preventDefault();

				if ( confirm( 'Removing Instance. Are you sure?') ) {
					$(event.currentTarget).closest('.mec-instance').remove();
				}
			});

			// Conditions
			$('.mec-config-products').on('click', '.mec-condition-header button', addNewCondition);
			$('.mec-config-products').on('change', '.product-condition', function (event) {
				event.preventDefault();
				var $this = $(event.currentTarget),
					value = $this.val(),
					instanceKey = $this.closest('[data-instance_key]').data('instance_key'),
					new_key = $this.closest('[data-condition_key]').data('condition_key'),
					template = $('#template-mec-product-condition-' + value.toLowerCase()).clone().html();

				template = template.replace(/{{key}}/g, instanceKey).replace(/{{conditionKey}}/g, new_key);
				
				$this.parent().siblings('.product-condition-compare').html(template);
			});

			// Quantity
			$('.mec-config-products').on('click', '.mec-quantity-header button', addNewQuantity);

			// Form
			$('.mec-config-products').on('click', '[data-save]', function(event) {
				event.preventDefault();
				$(event.currentTarget).parent().parent().find('.mec-body form').trigger('submit');
			});
			$('.mec-config-products').on('submit', 'form', function(event) {
				event.preventDefault();
				var $wrapper = $(event.currentTarget).closest('.mec-cat-product'),
					formData = {},
					serialized = $(event.currentTarget).serialize();

				try {
					formData = JSON.parse('{"' + decodeURI(serialized.replace(/&/g, "\",\"").replace(/=/g, "\":\"")) + '"}');
				} catch(error) {
					alert('Invalid data');
					return false;
				}

				try {
					if ( 'undefined' === typeof formData['mec-product'] || ! formData['mec-product'] ) {
						throw 'Product not selected.';
					}

					if ( 'undefined' === typeof formData['cat-id'] || ! formData['cat-id'] ) {
						throw 'Invalid category.';
					}
				} catch(error) {
					alert(error);
					return false;
				}

				$wrapper.addClass('mec-loading');
				$.ajax({
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					dataType: 'json',
					type: 'post',
					cache: false,
					data: {
						action: 'mec_config_save',
						formData: serialized
					},
					success: function(response) {
						alert(response.data);
						$wrapper.removeClass('mec-loading');
						window.location.href = window.location.href;
					},
					error: function(err) {
						alert('Something went wrong. Please try again.');
						$wrapper.removeClass('mec-loading');
					}
				});

				return false;
			});
		};

		return {
			init: init
		};
	})(jQuery);

	jQuery(document).on('ready', MEC_ACCORDION.init);
</script>
<?php
