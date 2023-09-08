<?php

abstract class MonExterieur_Types_Abstract {
	protected $category = array();
	protected $fields = array();
	protected $steps = array();
	protected $quantities = array();

	abstract function get_condition_fields();

	abstract function get_quantity_fields();

	abstract function get_quantity( $_data, $cat_product, $multiply_by );

	abstract function products_to_add( $_data );

	public function __construct( $cat_id ) {
		$this->category = get_term( $cat_id, 'product_cat' );
	}

	/**
	 * @return array|null|WP_Error|WP_Term
	 */
	public function category() {
		return $this->category;
	}

	/**
	 * @return array
	 */
	public function fields() {
		return $this->fields;
	}

	public function get_title( $field ) {
		if ( isset( $this->fields[ $field ] ) ) {
			return $this->fields[ $field ]['title'];
		}

		return '';
	}

	public function get_filed_options_text( $field, $option ) {
		$text = '';
		if ( isset( $this->fields[ $field ] ) && isset( $this->fields[ $field ]['options'] ) ) {
			foreach ( $this->fields[ $field ]['options'] as $field_option ) {
				if ( is_array( $field_option ) ) {
					if ( $option === sanitize_title( $field_option['title'] ) ) {
						$text = $field_option['title'];
						break;
					}
				} else {
					if ( $option === sanitize_title( $field_option ) ) {
						$text = $field_option;
						break;
					}
				}
			}
		}

		return $text;
	}

	/**
	 * @return array
	 */
	public function steps() {
		return $this->steps;
	}

	/**
	 * Get products related to category.
	 *
	 * @return array|stdClass
	 */
	public function get_products() {
		return wc_get_products(
			array(
				'limit'    => - 1,
				'status'   => 'publish',
				'category' => $this->category->slug
			)
		);
	}

	/**
	 * Save Backend products for category.
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function save( $data = array() ) {
		if ( wp_verify_nonce( $data["_config_products_{$this->category->term_id}"], "config_products_{$this->category->term_id}" )
		) {
			if ( isset( $data['cat-products'] ) ) {
				$cat_products = $data['cat-products'];
				ksort( $cat_products );
				$temp_cat_products = $cat_products;
				$has_error         = false;
				// fields validation
				foreach ( $cat_products as $key => $cat_product ) {
					// Product must be selected.
					if ( ! isset( $cat_product['product'] ) || ! $cat_product['product'] ) {
						$temp_cat_products[ $key ]['error']   = true;
						$temp_cat_products[ $key ]['section'] = 'Product';
						$has_error                            = true;
						continue;
					}
					// there must be at-least one condition assigned
					if ( ! isset( $cat_product['product-condition']['and'] ) || empty( $cat_product['product-condition']['and'] ) ) {
						$temp_cat_products[ $key ]['error']   = true;
						$temp_cat_products[ $key ]['section'] = 'Product Condition And Empty.';
						$has_error                            = true;
						continue;
					} else {
						// conditions fields must not be empty.
						foreach ( $cat_product['product-condition']['and'] as $condition_key => $condition ) {
							if ( ! isset( $condition['condition'] ) || '' == $condition['condition'] ) {
								$temp_cat_products[ $key ]['error']   = true;
								$temp_cat_products[ $key ]['section'] = "{$condition_key} key condition empty.";
								$has_error                            = true;
								continue;
							}
							// compare value for condition must not be empty.
							if ( ! isset( $condition['compare'] ) || '' == $condition['compare'] ) {
								$temp_cat_products[ $key ]['error']   = true;
								$temp_cat_products[ $key ]['section'] = "{$condition_key} key compare empty.";
								$has_error                            = true;
								continue;
							}

							if ( isset( $condition['compare_as'] ) && '' == $condition['compare_as'] ) {
								$temp_cat_products[ $key ]['error']   = true;
								$temp_cat_products[ $key ]['section'] = "{$condition_key} key compare as empty.";
								$has_error                            = true;
								continue;
							}
						}
					}
					// at-least one quantity calculation criteria must be added.
					if ( ! isset( $cat_product['also-quantity'] ) ) {
						$temp_cat_products[ $key ]['error']   = true;
						$temp_cat_products[ $key ]['section'] = "Also Quantity Empty.";
						$has_error                            = true;
						continue;
					} else {
						// quantity calculation field must not be empty
						foreach ( $cat_product['also-quantity'] as $also_key => $also_quantiy ) {
							if ( '' == $also_quantiy ) {
								$temp_cat_products[ $key ]['error']   = true;
								$temp_cat_products[ $key ]['section'] = "{$also_key } key Quantity Empty.";
								$has_error                            = true;
								continue;
							}
						}
					}
				}
				// if has issue, return data with issue flag.
				if ( true === $has_error ) {
					$temp_cat_products['error'] = true;

					return $temp_cat_products;
				}
				// all good, update term meta.
				update_term_meta( $this->category->term_id, '_config_cat_products', $cat_products );
				// flag as updated.
				$cat_products['updated'] = true;

				// return.
				return $cat_products;
			} else {
				// all sections are removed, so delete term meta.
				delete_term_meta( $this->category->term_id, '_config_cat_products' );
			}

		}

		// return value from term meta.
		return $this->get_meta( '_config_cat_products' );
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get_meta( $key = '' ) {
		return get_term_meta( $this->category->term_id, $key, true );
	}

	/**
	 * TODO: Remove
	 * @return array
	 */
	public function config_products() {
		$cat_products = $this->get_meta( '_config_cat_products' );

		$compare['Poteau']       = filter_input( INPUT_POST, 'poteau' );
		$compare['Panneau']      = filter_input( INPUT_POST, 'panneau' );
		$compare['Soubassement'] = filter_input( INPUT_POST, 'soubassement' );
		$longueur                = filter_input( INPUT_POST, 'longueur' );
		$angle_number            = filter_input( INPUT_POST, 'angle-number' );
		$endpoint_number         = filter_input( INPUT_POST, 'endpoint-number' );

		$product_to_add = array();

		if ( $cat_products ) {
			foreach ( $cat_products as $product ) {
				$result = array();
				if ( isset( $product['product-condition']['and'] ) && is_array( $product['product-condition']['and'] ) ) {
					foreach ( $product['product-condition']['and'] as $key => $condition ) {
						if ( $compare[ $condition['condition'] ] == $condition['compare'] ) {
							$result['and'][ $key ] = 'yes';
						} else {
							$result['and'][ $key ] = 'no';
						}
					} // end loop $product['product-condition']['and']

				} // end check $product['product-condition']['and']

				if ( in_array( 'no', $result['and'] ) ) {
					$final = 'no';
				} else {
					$final = 'yes';
				}

				if ( 'yes' == $final ) {
					$product_to_add[] = $product['product'];
				}
			} // end loop $cat_products
		} // end check $cat_products

		return $product_to_add;
	}

	/**
	 * Check Category Products and add products into cart.
	 *
	 * @param array $_data
	 *
	 * @return bool
	 */
	public function add_to_cart_old( $_data = array() ) {
		// get products to add from Category Class object.
		$product_to_add = $this->products_to_add( $_data );

		if ( empty( $product_to_add ) ) {
			$cat_products = $this->get_meta( '_config_cat_products' );
			if ( is_array( $cat_products ) && ! empty( $cat_products ) ) {
				$counter = 0;
				foreach ( $cat_products as $product ) {
					$result = array();
					if ( isset( $product['product-condition']['and'] ) && is_array( $product['product-condition']['and'] ) ) {
						foreach ( $product['product-condition']['and'] as $key => $condition ) {
							if ( 'any' == $condition['condition'] ) {
								$result['and'][ $key ] = 'yes';
								continue;
							}

							if ( sanitize_title( $_data[ $condition['condition'] ] ) == sanitize_title( $condition['compare'] ) ) {
								$result['and'][ $key ] = 'yes';
							} else {
								$result['and'][ $key ] = 'no';
							}
						} // end loop $product['product-condition']['and']
					} // end check $product['product-condition']['and']

					if ( in_array( 'no', $result['and'] ) ) {
						$final = 'no';
					} else {
						$final = 'yes';
					}

					if ( 'yes' == $final ) {
						$product_to_add[ $counter ]['product_id']  = $product['product'];
						$product_to_add[ $counter ]['product_qty'] = $this->get_quantity( $_data, $product );
						$counter                                   += 1;
					}
				} // end loop $cat_products
			} // end check $cat_products
		}

		if ( ! empty( $product_to_add ) ) {
			foreach ( $product_to_add as $product ) {
				// any error while adding product into cart will be handled by WC as WC Notice.
				try {
					wc()->cart->add_to_cart( $product['product_id'], $product['product_qty'] );
				} catch ( Exception $ex ) {
				}
			}

			return true;
		}

		return false;

	}

		/**
	 * Check Category Products and add products into cart.
	 *
	 * @param array $_data
	 *
	 * @return bool
	 */
	public function add_to_cart( $_data = array() ) {
		// get products to add from Category Class object.
		$products_to_add = $this->products_to_add( $_data );

		if ( empty( $products_to_add ) ) {
			$products_to_add = array();
			$cat_products = $this->get_meta( '_mec_config_products' );

			if ( is_array( $cat_products ) && ! empty( $cat_products ) ) {
				$counter = 0;
				foreach ( $cat_products as $cat_product ) {
					$product_config = $this->get_meta( "_mec_product_config_{$cat_product}" );

					if ( ! is_array( $product_config ) || ! isset( $product_config['instance'] ) || ! is_array( $product_config['instance'] ) ) {
						continue;
					}

					foreach ( $product_config['instance'] as $instance_key => $instance ) {
						
						$result = array();

						if ( ! isset( $instance['conditions'] ) || ! is_array( $instance['conditions'] ) ) {
							continue;
						}

						foreach ( $instance['conditions'] as $condition_key => $condition ) {
							if ( 'any' == $condition['condition'] ) {
								$result[ $condition_key ] = 'yes';
								continue;
							}

							if ( 'height' == $condition['condition'] ) {
								if ( true === $this->check_compare_as( $_data, $condition ) ) {
									$result[ $condition_key ] = 'yes';
								} else {
									$result[ $condition_key ] = 'no';
								}

								continue;
							}

							if ( sanitize_title( $_data[ $condition['condition'] ] ) == sanitize_title( $condition['compare'] ) ) {
								$result[ $condition_key ] = 'yes';
							} else {
								$result[ $condition_key ] = 'no';
							}
						}

						if ( in_array( 'no', $result ) ) {
							$final = 'no';
						} else {
							$final = 'yes';
						}
		
						if ( 'yes' == $final ) {
							$multiply_by = isset( $instance['multiply-by'] ) ? $instance['multiply-by'] : 1;
							$products_to_add[ $counter ]['product_id']  = $product_config['mec-product'];
							$products_to_add[ $counter ]['product_qty'] = $this->get_quantity( $_data, $instance['quantity'], intval( $multiply_by ) );
							$counter                                   += 1;
						}
					}
				}
			}
		}

		if ( ! empty( $products_to_add ) ) {
			foreach ( $products_to_add as $product ) {
				// any error while adding product into cart will be handled by WC as WC Notice.
				try {
					wc()->cart->add_to_cart( $product['product_id'], $product['product_qty'] );
				} catch ( Exception $ex ) {
				}
			}

			return true;
		}

		return false;

	}

	/**
	 * Get specific field.
	 *
	 * @param string $field
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public function get_field( $field = '', $content = '' ) {
		if ( ! isset( $this->fields[ $field ] ) ) {
			return '';
		}

		return $this->fields[ $field ];
	}

	/**
	 * Condition for product.
	 * Admin side only.
	 *
	 * @param array $dataAttr
	 * @param string $chosen
	 *
	 * @return string
	 */
	public function condition_field( $dataAttr = array(), $chosen = '' ) {
		$return = '';

		$return .= '<select ' . implode( ' ', $dataAttr ) . '>';
		$return .= '<option value="">' . __( 'Select Condition', '' ) . '</option>';
		$return .= '<option value="any" ' . selected( $chosen, 'any', false ) . '>' . __( 'Any Case', '' ) . '</option>';
		foreach ( $this->get_condition_fields() as $condition ) {
			$field = $this->get_field( $condition, '' );
			if ( $field ) {
				$selected = selected( sanitize_title( $chosen ), sanitize_title( $condition ), false );
				$return   .= '<option value="' . sanitize_title( $condition ) . '" ' . $selected . '>' . $field['title'] . '</option>';
			}
		}

		$return .= '</select>';

		return $return;
	}

	/**
	 * Render field.
	 *
	 * @param $field
	 * @param string $dataAttr
	 * @param string $name
	 * @param array $args Submitted Data (frontend)
	 *
	 * @return string
	 */
	public function render_field( $field, $dataAttr = '', $name = '', $args = array() ) {
		$return = '';
		switch ( $field['type'] ) {
			case 'group':
				$return = $this->group_field( $field, 'view', $dataAttr, $name, $args );
				break;

			case 'number':
				$return = $this->number_field( $field, 'view', $dataAttr, $name, $args );
				break;

			case 'number_group':
				$return = $this->number_group_field( $field, 'view', $dataAttr, $name, $args );
				break;

			case 'number_group_step':
				$return = $this->number_group_step_field( $field, 'view', $dataAttr, $name, $args );
				break;

			case 'number_group_option':
				$return = $this->number_group_option_field( $field, 'view', $dataAttr, $name, $args );
				break;

			default:
				break;
		}

		return $return;
	}

	/**
	 * Group field (Radio type for frontend)
	 *
	 * @param $field
	 * @param string $content
	 * @param string $dataAttr
	 * @param string $name
	 * @param array $args
	 *
	 * @return string
	 */
	public function group_field( $field, $content = 'admin', $dataAttr = '', $name = '', $args = array() ) {
		$return = '';
		if ( 'admin' == $content ) {
			$return .= '<select class="' . $field['class'] . '" ' . $name . ' ' . $dataAttr . '>';
			$return .= '<option value="">' . sprintf( __( 'Select %s', '' ), $field['title'] ) . '</option>';
			foreach ( $field['options'] as $option ) {
				$filter_callback = 'sanitize_title';
				if ( isset( $field['filter_callback'] ) && function_exists( $field['filter_callback'] ) ) {
					$filter_callback = $field['filter_callback'];
				}
				$selected = isset( $field['chosen'] ) ? $field['chosen'] : '';
				if ( is_array( $option ) ) {
					$title  = $option['title'];
					$return .= '<option value="' . $filter_callback( $title ) . '" ' . selected( $filter_callback( $selected ), $filter_callback( $title ), false ) . '>' . $title . '</option>';
				} else {
					$return .= '<option value="' . $filter_callback( $option ) . '" ' . selected( $filter_callback( $selected ), $filter_callback( $option ), false ) . '>' . $option . '</option>';
				}
			}
			$return .= '</select>';
		} else {
			$options = array();
			foreach ( $field['options'] as $_options ) {
				if ( isset( $_options['conditions'] ) && 0 < count( $_options['conditions'] ) ) {
					$check = array();
					foreach ( $_options['conditions'] as $key => $condition ) {
						$_key = str_replace( array( '!=', '#' ), array( '', '' ), $key );

						if ( isset( $args[ $_key ] ) && 0 === strpos( $key, '!=' ) ) {
							if ( sanitize_title( $args[ $_key ] ) !== sanitize_title( $condition ) ) {
								$check[] = 'yes';
							} else {
								$check[] = 'no';
							}
						} else {
							if ( isset( $args[ $_key ] ) && ( sanitize_title( $args[ $_key ] ) == sanitize_title( $condition ) ) ) {
								$check[] = 'yes';
							} else {
								$check[] = 'no';
							}
						}
						
					}

					if ( ! in_array( 'no', $check ) ) {
						$options[] = $_options;
					}
				}
				else {
					$options[] = $_options;
				}
			}

			if ( empty( $options ) ) {
				$options = $field['options'];
			}

			foreach ( $options as $option ) {
				$class = '';
				if ( isset( $field['inside'] ) ) {
					if ( $inside_field = $this->get_field( $field['inside'] ) ) {
						if ( 'number' == $inside_field['type'] ) {
							$class = 'with-quantity';
						}
					}
				}

				$_deps = $this->get_meta( '_config_cat_option_dep' );

				$value       = is_array( $option ) ? $option['title'] : $option;
				$field_name = sanitize_title( $value );
				if ( in_array( $name, array( 'color_lame', 'color_decorative' ) ) ) {
					$field_name = "{$name}_{$field_name}";
				}
				
				$thumbnail   = isset( $_deps['images'][ $field_name ] ) ? $_deps['images'][ $field_name ] : '';
				$information = isset( $_deps['information'][ $field_name ] ) ? $_deps['information'][ $field_name ] : '';

				$display_name = $value;
				if ( 'color_lame' !== $name ) {
					switch( $value ) {
						case 'Graphite Black':
							$display_name = 'Noir 9005';
							break;

						case 'Stone Grey':
							$display_name = 'Gris 7016';
							break;

						case 'Tropical Brown':
							$display_name = 'Marron 8017';
							break;

						case 'Pin44':
							$display_name = 'Bois Pin Cl4';
							break;
					}
				}

				$return .= '<div class="col-md-4 col-lg-5">';
				$return .= '<div class="config-pro-indv ' . $class . '">';
				$return .= '<label>';
				$return .= '<input type="radio" name="' . $name . '" value="' . $value . '">';
				$return .= '<div class="label-inner">';
				$return .= '<div class="img-wrap">';
				$return .= '<div class="img" style="background-image: url(' . $thumbnail . ');"></div>';
				$return .= '</div>';
				$return .= '<p>' . $display_name . '</p>';
				$return .= '</div>';
				$return .= '</label>';

				// check if add extra field, ignoe for "Sans" value
				if ( isset( $field['inside'] ) && ( strpos( sanitize_title( $value ), 'sans' ) === false ) ) {
					if ( $inside_field = $this->get_field( $field['inside'] ) ) {
						if ( 'number' == $inside_field['type'] ) {
							$step = isset( $inside_field['step'] ) ? $inside_field['step'] : 1;
							$min  = isset( $inside_field['min'] ) ? $inside_field['min'] : 1;
							$max  = isset( $inside_field['max'] ) ? $inside_field['max'] : '';
							ob_start();
							?>
                            <div class="quantity">
                                <div class="quantity-inc">
                                    <span class="button">-</span>
                                    <input type="number"
                                           class="input-text qty text input inside"
                                           step="<?php echo esc_attr( $step ); ?>"
                                           min="<?php echo esc_attr( $min ); ?>"
                                           max="<?php echo esc_attr( $max ); ?>"
                                           name="<?php echo esc_attr( $field['inside'] ); ?>"
                                           value="1" title="" size="4" placeholder="" inputmode="numeric">
                                    <span class="button">+</span>
                                </div>
                            </div>
							<?php
							$return .= ob_get_clean();
						}
					}
				}

				if ( $information ) {
					$return .= '<a href="javascript:void(0);" class="me_btn custom-modal" data-target="' . sanitize_title( $value ) . '">' . __( 'Plus d’informations', 'monexterieur-configure' ) . '</a>';
				}
				$return .= '</div>';

				if ( $information ) {
					ob_start();
					?>
                    <!-- The Modal -->
                    <div id="<?php echo sanitize_title( $value ); ?>-custom-modal" class="modal">
                        <div class="modal-content">
                            <div class="modal-wrap">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="img-wrap">
                                            <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI; ?>public/images/Group.svg"
                                                 alt="">
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="content">
											<?php echo apply_filters( 'the_content', $information ); ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="close">
                                    <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI; ?>public/images/close.svg"
                                         alt="">
                                </span>
                            </div>
                        </div>

                    </div>
					<?php
					$return .= ob_get_clean();
				}

				$return .= '</div>';
			}
		}

		return $return;
	}

	/**
	 * Input type number field step attribute.
	 *
	 * @param $field
	 * @param array $attr
	 *
	 * @return int|string
	 */
	public function number_field_step( $field, $attr = array() ) {
		$step = 1;
		if ( is_array( $field['step'] ) ) {
			$result = array();
			foreach ( $field['step'] as $main_key => $step_conditions ) {
				foreach ( $step_conditions['conditions'] as $step_key => $conditions ) {
					foreach ( $conditions as $key => $condition ) {
						if ( isset( $attr[ $key ] ) && ( sanitize_title( $attr[ $key ] ) == sanitize_title( $condition ) ) ) {
							$result[ $main_key ][ $step_key ][ $key ] = 'yes';
						} else {
							$result[ $main_key ][ $step_key ][ $key ] = 'no';
						}
					}

					if ( ! in_array( 'no', $result[ $main_key ][ $step_key ] ) ) {
						$step = $step_conditions['value'];
					}
				}
			}
		} else if ( isset( $field['step'] ) ) {
			$step = esc_attr( $field['step'] );
		}

		return $step;
	}

	/**
	 * Input type number field.
	 *
	 * @param $field
	 * @param string $content
	 * @param string $dataAttr
	 * @param string $name
	 * @param array $attr
	 *
	 * @return string
	 */
	public function number_field( $field, $content = 'admin', $dataAttr = '', $name = '', $attr = array() ) {
		$return = '';
		if ( 'admin' == $content ) {

		} else {
			$step = $this->number_field_step( $field, $attr );
			$min  = ( isset( $field['min'] ) ) ? $field['min'] : $step;
			$max  = ( isset( $field['max'] ) ) ? $field['max'] : '';
			$value = 0;

			if ( isset( $field['value'] ) ) {
				$value = $field['value'];
			}

			if ( isset( $field['value'] ) && true === $field['value'] ) {
				$value = $this->get_value( $field, $attr );
				if ( $value ) {
					$min = $value;
				}
			}

			
			
			ob_start();
			?>
            <div class="config-quantity">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="label">
							<?php echo $field['title']; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="quantity">
                            <div class="quantity-inc">
                                <span class="button">-</span>
                                <input type="number" id="input_number_<?php echo sanitize_title( $name ); ?>"
                                       class="input-text qty text input" step="<?php echo $step; ?>"
                                       min="<?php echo $min; ?>" max="<?php echo $max; ?>"
                                       name="<?php echo $name; ?>"
									   readonly
                                       value="<?php echo $value; ?>" data-value="<?php echo $value; ?>" title="" size="4" placeholder="" inputmode="numeric">
                                <span class="button">+</span>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				// applied for "Panneau Bois" only.
				$data_by = '';
				if ( isset( $field['also_affect'] ) && 'longeur' === $field['also_affect'] ) {
					if ( isset( $attr['poteau'] ) ) {
						if ( sanitize_title( 'Pin44' ) === sanitize_title( $attr['poteau']  ) ) {
							$data_by = 0.10;
						} else if ( sanitize_title( 'GM45' ) === sanitize_title( $attr['poteau']  ) ) {
							$data_by = 0.02;
						}
						?>
						<script>
							(function($) {
								var updateLongeur = function(e, $input, action) {
									if( 'input_number_no_of_angle' !== $input.attr('id') ) {
										return;
									}

									var input_value = $input.val(),
										$target = $('#input_number_longeur'),
										target_value = $target.val(),
										_target_value = parseFloat(target_value),
										change_by = '<?php echo $data_by; ?>';

									if( '+' === action.toString() ) {
										input_value = parseFloat(input_value) + 1;
										if( 0 < input_value ) {
											var to_change_by = parseFloat(input_value) * parseFloat(change_by);
											_target_value = _target_value + to_change_by;
										}
									} else {
										input_value = parseFloat(input_value);
										if( 0 > input_value ) {
											input_value = 0;
										}

										if( 0 === input_value ) {
											return;
										}

										var to_change_by = parseFloat(input_value) * parseFloat(change_by);
										_target_value = _target_value - to_change_by;
									}

									$target.val(_target_value.toFixed(2)).trigger('change');
										
									console.log(action, input_value, to_change_by, _target_value.toFixed(2));
								}
								// remove old event.
								$(document.body).off('number_value_change', updateLongeur);
								// add new event.
								$(document.body).on('number_value_change', updateLongeur);
							})(jQuery);
						</script>
						<?php
					}
				}
				?>
            </div>
			<?php

			$return .= ob_get_clean();
		}

		return $return;
	}

	/**
	 * Number field with radio input option
	 *
	 * @param $field
	 * @param string $content
	 * @param string $dataAttr
	 * @param string $name
	 * @param array $args
	 *
	 * @return string
	 */
	public function number_group_field( $field, $content = 'admin', $dataAttr = '', $name = '', $args = array() ) {
		if ( 'admin' == $content ) {
			$return = $this->group_field( $field, $content, $dataAttr, $name, $args );
		} else {

			ob_start();
			foreach ( $field['options'] as $option ) {
				if ( is_array( $option ) ) {
					$value = $_value = $option['value'];
				} else {
					$value = $_value = $option;
				}

				if ( function_exists( 'wc_format_localized_price' ) ) {
					$value = wc_format_localized_price( $value );
				}

				?>
                <div class="confiq-num-indv">
                    <label>
                        <input type="radio" name="<?php echo $name; ?>" value="<?php echo $_value; ?>">
                        <span><?php echo $value; ?>m</span>
                    </label>
                </div>
				<?php
			}
			$return = ob_get_clean();
		}

		return $return;
	}

	/**
	 * @param $field
	 * @param $args
	 *
	 * @return mixed
	 */
	public function number_group_start_condition( $field, $args ) {
		foreach ( $field['start'] as $start_key => $start ) {
			$check = array();
			foreach ( $start['conditions'] as $key => $condition ) {
				if ( isset( $args[ $key ] ) ) {
					if ( is_array( $condition ) ) {
						$_condition = array_map( 'sanitize_title', $condition );
						if ( in_array( sanitize_title( $args[ $key ] ), $_condition ) ) {
							$check[ $key ] = 'yes';
						} else {
							$check[ $key ] = 'no';
						}
					} else {
						if ( sanitize_title( $args[ $key ] ) == sanitize_title( $condition ) ) {
							$check[ $key ] = 'yes';
						} else {
							$check[ $key ] = 'no';
						}
					}
				}
			}

			if ( ! in_array( 'no', $check ) ) {
				return $start['value'];
			}
		}

		return $field['step'];
	}

	/**
	 * @param $field
	 * @param string $content
	 * @param string $dataAttr
	 * @param string $name
	 * @param array $args
	 *
	 * @return string
	 */
	public function number_group_step_field( $field, $content = 'admin', $dataAttr = '', $name = '', $args = array() ) {
		$return = '';
		if ( 'admin' == $content ) {

		} else {
			$limit = 9;
			$step  = $field['step'];
			$start = $this->number_group_start_condition( $field, $args );
			ob_start();
			for ( $i = 0; $i < $limit; $i ++ ) {
				$value = $_value = round( floatval( $start ) + ( $i * floatval( $step ) ), 2 );
				if ( function_exists( 'wc_format_localized_price' ) ) {
					$_value = wc_format_localized_price( $_value );
				}
				?>
                <div class="confiq-num-indv">
                    <label>
                        <input type="radio" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
                        <span><?php echo $_value; ?>m</span>
                    </label>
                </div>
				<?php
			}

			$return = ob_get_clean();
		}

		return $return;
	}

	/**
	 * @param $field
	 * @param $content
	 * @param $dataAttr
	 * @param $name
	 * @param $args
	 *
	 * @return string
	 */
	public function number_group_option_field( $field, $content = 'view', $dataAttr = '', $name = '', $args = array() ) {
		$return = '';
		if ( 'admin' == $content ) {
			$values = array();
			foreach ( $field['options'] as $options ) {
				foreach ( $options['values'] as $value ) {
					if ( ! in_array( $value, $values ) ) {
						$values[] = $value;
					}
				}
			}

			if ( $values ) {
				$values[] = '1.88';
				$selected = isset( $field['chosen'] ) ? $field['chosen'] : '';
				ob_start();
				?>
                <select class="<?php echo $field['class']; ?>" <?php echo $name; ?> <?php echo $dataAttr; ?>>;
                    <option value=""><?php echo sprintf( __( 'Select %s', '' ), $field['title'] ); ?></option>
					<?php foreach ( $values as $value ) { ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( esc_attr( $selected ), esc_attr( $value ) ); ?>><?php echo $value; ?></option>
					<?php } ?>
                </select>
				<?php
			}

			$return = ob_get_clean();
		} else {
			$values = array();
			foreach ( $field['options'] as $options ) {
				$check = array();
				foreach ( $options['condition'] as $key => $condition ) {
					if ( isset( $args[ $key ] ) && sanitize_title( $condition ) == sanitize_title( $args[ $key ] ) ) {
						$check[ $key ] = 'yes';
					} else {
						$check[ $key ] = 'no';
					}
				}

				if ( ! in_array( 'no', $check ) ) {
					$values = $options['values'];
					break;
				}
			}

			if ( $values ) {
				ob_start();
				foreach ( $values as $value ) {
					$_value = $value;
					if ( function_exists( 'wc_format_localized_price' ) ) {
						$value = wc_format_localized_price( $value );
					}
					?>
                    <div class="confiq-num-indv">
                        <label>
                            <input type="radio" name="<?php echo $name; ?>" value="<?php echo $_value; ?>">
                            <span><?php echo $value; ?>m</span>
                        </label>
                    </div>
					<?php
				}

				$return = ob_get_clean();
			}
		}

		return $return;
	}
}