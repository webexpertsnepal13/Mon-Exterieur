<?php

class MonExterieur_Config_Gabion extends MonExterieur_Types_Abstract {

	private static $instacne = null;

	public static function get_instance( $cat_id ) {
		if ( null === self::$instacne ) {
			self::$instacne = new self( $cat_id );
		}

		return self::$instacne;
	}

	public function __construct( $cat_id ) {
		parent::__construct( $cat_id );

		$this->fields = array(
			'height'         => array(
				'title'           => __( 'Hauteur clôture', 'monexterieur-configure' ),
				'type'            => 'number_group',
				'class'           => '',
				'options'         => array( '0.95', '1.25', '1.55', '1.7' ),
				'compare_as'      => true,
				'filter_callback' => 'esc_attr'
			),
			'longueur'       => array(
				'title' => __( 'Longueur de votre cloture gabion', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2,
				'value' => 2,
				'min' => 2
			),
			'no_of_joints'   => array(
				'title' => __( 'Nombre de gabion jointif ', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 1
			),
			'no_of_angle'    => array(
				'title' => __( 'Nombre d\'angle', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 1
			),
			'no_of_endpoint' => array(
				'title' => __( 'Nombre de poteau extrémité', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2,
				'value' => 2,
				'min' => 2,
			),
			'remplissage'    => array(
				'title'   => __( 'Type de remplissage', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array(
					__( 'Sans', 'monexterieur-configure' ),
					__( 'Pierre Tressy 40/100', 'monexterieur-configure' ),
					__( 'Galet de mer', 'monexterieur-configure' ),
					__( 'Rocher gris bleu', 'monexterieur-configure' ),
					__( 'Gris silver', 'monexterieur-configure' ),
					__( 'Comblanchien', 'monexterieur-configure' ),
				)
			),
			'compare_as'     => array(
				'title'           => '',
				'type'            => 'group',
				'class'           => '',
				'options'         => array(
					'>=',
					'>',
					'=',
					'<=',
					'<'
				),
				'filter_callback' => 'esc_attr'
			),
			'gabion_size'    => array(
				'title' => __( 'Gabion Size', 'monexterieur-configure' )
			),
			'handling'       => array(
				'title' => __( 'Handling', 'monexterieur-configure' )
			),
			'bag'            => array(
				'title' => __( 'Bag', 'monexterieur-configure' )
			)
		);

		$this->steps = array(
			'height',
			array(
				'title'    => __( 'Dimension', 'monexterieur-configure' ),
				'subgroup' => array( 'longueur', 'no_of_joints', 'no_of_endpoint', 'no_of_angle' )
			),
			'remplissage'
		);
	}

	/**
	 * Fields for Conditions
	 *
	 * @return array
	 */
	public function get_condition_fields() {
		return array( 'height', 'remplissage' );
	}

	/**
	 * Fields to calculate quantity
	 *
	 * @return array
	 */
	public function get_quantity_fields() {
		return array( 'longueur', 'no_of_angle', 'no_of_endpoint', 'gabion_size', 'handling', 'bag' );
	}

	/**
	 * Get quantity for the product.
	 *
	 * @param $_data
	 * @param $cat_product
	 *
	 * @return int
	 */
	public function get_quantity_old( $_data, $cat_product ) {
		$quantity_fields = $this->get_quantity_fields();
		$_quantities     = array();
		foreach ( $quantity_fields as $for_quantity ) {
			if ( ! in_array( $for_quantity, $cat_product['also-quantity'] ) ) {
				continue;
			}
			// calculate quantity based on condition
			// formula by client
			if ( ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) || ( in_array( $for_quantity, array(
					'gabion_size',
					'handling',
					'bag'
				) ) ) ) {
				if ( 'longueur' == $for_quantity ) {
					$_quantities['longueur'] = intval( $_data[ $for_quantity ] / 2 );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$_quantities['no_of_angle'] = intval( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					$_quantities['no_of_endpoint'] = intval( $_data['no_of_endpoint'] / 2 );
				} else if ( 'gabion_size' == $for_quantity ) {
					$length = 1;
					$volume = 2 * floatval( $_data['height'] ) * 0.13;

					if ( isset( $_quantities['longueur'] ) ) {
						$length = $_quantities['longueur'];
					} elseif ( isset( $_data['longueur'] ) ) {
						$length = intval( $_data['longueur'] / 2 );
					}

					if ( $length < 1 ) {
						$length = 1;
					}

					$_quantities['gabion_size'] = round( $volume * 1.7 * $length, 2 );
				} else if ( 'handling' == $for_quantity ) {
					$length = 1;
					$volume = 2 * floatval( $_data['height'] ) * 0.13;

					if ( isset( $_quantities['longueur'] ) ) {
						$length = $_quantities['longueur'];
					} elseif ( isset( $_data['longueur'] ) ) {
						$length = intval( $_data['longueur'] / 2 );
					}

					if ( $length < 1 ) {
						$length = 1;
					}

					$quantity = round( $volume * 1.7 * $length );

					if ( $quantity < 1 ) {
						$quantity = 1;
					}

					$_quantities['handling'] = $quantity;
				} else if ( 'bag' == $for_quantity ) {
					$length = 1;
					$volume = 2 * floatval( $_data['height'] ) * 0.13;

					if ( isset( $_quantities['longueur'] ) ) {
						$length = $_quantities['longueur'];
					} elseif ( isset( $_data['longueur'] ) ) {
						$length = intval( $_data['longueur'] / 2 );
					}

					if ( $length < 1 ) {
						$length = 1;
					}

					$quantity = round( $volume * 1.7 * $length );

					if ( $quantity < 1 ) {
						$quantity = 1;
					}

					$_quantities['bag'] = $quantity;
				}
			}
		}

		if ( ! empty( $_quantities ) ) {
			$sum = array_sum( $_quantities );
			if ( isset( $cat_product['multiple-by'] ) && $cat_product['multiple-by'] ) {
				return intval( $cat_product['multiple-by'] ) * $sum;
			}

			return $sum;
		}

		return intval( $cat_product['default-qty'] );
	}

	public function products_to_add_old( $_data ) {
		$product_to_add = array();
		$cat_products   = $this->get_meta( '_config_cat_products' );
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

						if ( 'height' == $condition['condition'] ) {
							if ( true === $this->check_compare_as( $_data, $condition ) ) {
								$result['and'][ $key ] = 'yes';
							} else {
								$result['and'][ $key ] = 'no';
							}

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

		return $product_to_add;
	}

	public function get_quantity( $_data, $cat_product, $multiply_by ) {
		$quantity_fields = $this->get_quantity_fields();
		$_quantities = array();

		foreach ( $quantity_fields as $for_quantity ) {
			if ( ! in_array( $for_quantity, $cat_product ) ) {
				continue;
			}
			// calculate quantity based on condition
			// formula by client
			if ( ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) || ( in_array( $for_quantity, array(
					'gabion_size',
					'handling',
					'bag'
				) ) ) ) {
				if ( 'longueur' == $for_quantity ) {
					$_quantities['longueur'] = intval( $_data[ $for_quantity ] / 2 );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$_quantities['no_of_angle'] = intval( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					$_quantities['no_of_endpoint'] = intval( $_data['no_of_endpoint'] / 2 );
				} else if ( 'gabion_size' == $for_quantity ) {
					$length = 1;
					$volume = 2 * floatval( $_data['height'] ) * 0.13;

					if ( isset( $_quantities['longueur'] ) ) {
						$length = $_quantities['longueur'];
					} elseif ( isset( $_data['longueur'] ) ) {
						$length = intval( $_data['longueur'] / 2 );
					}

					if ( $length < 1 ) {
						$length = 1;
					}

					$_quantities['gabion_size'] = round( $volume * 1.7 * $length, 2 );
				} else if ( 'handling' == $for_quantity ) {
					$length = 1;
					$volume = 2 * floatval( $_data['height'] ) * 0.13;

					if ( isset( $_quantities['longueur'] ) ) {
						$length = $_quantities['longueur'];
					} elseif ( isset( $_data['longueur'] ) ) {
						$length = intval( $_data['longueur'] / 2 );
					}

					if ( $length < 1 ) {
						$length = 1;
					}

					$quantity = round( $volume * 1.7 * $length );

					if ( $quantity < 1 ) {
						$quantity = 1;
					}

					$_quantities['handling'] = $quantity;
				} else if ( 'bag' == $for_quantity ) {
					$length = 1;
					$volume = 2 * floatval( $_data['height'] ) * 0.13;

					if ( isset( $_quantities['longueur'] ) ) {
						$length = $_quantities['longueur'];
					} elseif ( isset( $_data['longueur'] ) ) {
						$length = intval( $_data['longueur'] / 2 );
					}

					if ( $length < 1 ) {
						$length = 1;
					}

					$quantity = round( $volume * 1.7 * $length );

					if ( $quantity < 1 ) {
						$quantity = 1;
					}

					$_quantities['bag'] = $quantity;
				}
			}
		}

		if ( ! empty( $_quantities ) ) {
			$sum = array_sum( $_quantities );
			if ( $multiply_by ) {
				return $multiply_by * $sum;
			}

			return $sum;
		}

		return 1;
	}

	public function products_to_add( $_data ) {
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

		return $products_to_add;
	}

	public function check_compare_as( $_data, $condition ) {
		$bool = false;
		if ( isset( $condition['compare_as'] ) && ! empty( $condition['compare_as'] ) ) {
			$compare = $condition['compare'];
			$value   = $_data['height'];
			switch ( $condition['compare_as'] ) {
				case '<=':
					$bool = ( floatval( $value ) <= floatval( $compare ) );
					break;

				case '<':
					$bool = ( floatval( $value ) < floatval( $compare ) );
					break;

				case '=':
					$bool = ( floatval( $value ) == floatval( $compare ) );
					break;

				case '>=':
					$bool = ( floatval( $value ) >= floatval( $compare ) );
					break;

				case '>':
					$bool = ( floatval( $value ) > floatval( $compare ) );
					break;

				default:
					$bool = false;
					break;

			}
		}

		return $bool;
	}
}

function monexterieur_product_cat_config_class( $cat_id ) {
	return MonExterieur_Config_Gabion::get_instance( $cat_id );
}