<?php

class MonExterieur_Config_Lame_Commposite extends MonExterieur_Types_Abstract {

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
			'soubassement'     => array(
				'title'   => __( 'Soubassement', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Sans plaque', 'Plaque béton', 'Plaque ardoise' ),
			),
			'type_de_pose'     => array(
				'title'   => __( 'Type de pose', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array(
					array(
						'title'      => __( 'A sceller', 'monexterieur-configure' ),
						'conditions' => array()
					),
					array(
						'title'      => __( 'Sur platine', 'monexterieur-configure' ),
						'conditions' => array()
					),
				)
			),
			'color_lame'         => array(
				'title'   => __( 'Couleurs Lame Composite', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Graphite Black', 'Stone Grey', 'Tropical Brown' )
			),
			'color_decorative'         => array(
				'title'   => __( 'Couleurs Lame Décorative', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Graphite Black', 'Stone Grey', 'Tropical Brown' )
			),
			'couleurs'         => array(
				'title'   => __( 'Couleurs Structure', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Graphite Black', 'Stone Grey', 'Tropical Brown' )
			),
			'longeur'          => array(
				'title' => __( 'Longueur en mètre linéaire', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2,
				'value' => 2,
				'min' => 2,
			),
			'no_of_angle'      => array(
				'title' => __( 'Nombre d\'angle', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 1
			),
			'no_of_endpoint'   => array(
				'title' => __( 'Nombre de poteau extrémité', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2,
				'value' => 2,
				'min' => 2
			),
			'lame_decorative'  => array(
				'title'   => __( 'Lame décorative', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Sans Lame décorative', 'Lame Bulle', 'Lame Toile', 'Lame Palme' ),
				'inside'  => 'no_of_decorative'
			),
			'no_of_decorative' => array(
				'title' => __( 'Nombre de lame décorative', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 1
			),
			'height'           => array(
				'title' => __( 'Hauteur de cloture', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 0.15,
				'value' => true,
			),
			'lame'             => array(
				'title' => __( 'Lame', 'monexterieur-configure' )
			),
			'individual'       => array(
				'title'   => __( 'Sum quantity individually.', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Yes' )
			)
		);

		/*
		$this->steps = array(
			'soubassement',
			'type_de_pose',
			'couleurs',
			array(
				'title'    => __( 'Dimension', 'monexterieur-configure' ),
				'subgroup' => array( 'longeur', 'no_of_angle', 'no_of_endpoint' )
			),
			'color_lame',
			'color_decorative',
			'lame_decorative',
			'height'
		);
		*/
		$this->steps = array(
			'soubassement',
			'type_de_pose',
			'color_lame',
			'couleurs',
			'lame_decorative',
			'color_decorative',
			array(
				'title'    => __( 'Dimension', 'monexterieur-configure' ),
				'subgroup' => array( 'longeur', 'no_of_angle', 'no_of_endpoint' )
			),
			'height'
		);
	}

	/**
	 * Fields for Conditions
	 *
	 * @return array
	 */
	public function get_condition_fields() {
		return array( 'soubassement', 'type_de_pose', 'lame_decorative', 'couleurs', 'color_lame', 'color_decorative' );
	}

	/**
	 * Fields to calculate quantity
	 *
	 * @return array
	 */
	public function get_quantity_fields() {
		return array( 'longeur', 'no_of_angle', 'no_of_endpoint', 'no_of_decorative', 'lame' );
	}

	public function get_value( $field, $attrs ) {
		$value = 0;
		if ( ! isset( $attrs['soubassement'] ) ) {
			return 0;
		}

		if ( sanitize_title( 'Plaque béton' ) === sanitize_title( $attrs['soubassement'] ) ) {
			$value = 0.25;
		} elseif ( sanitize_title( 'Plaque ardoise' ) === sanitize_title( $attrs['soubassement'] ) ) {
			$value = 0.30;
		}

		if ( isset( $attrs['no_of_decorative'] ) && 0 < intval( $attrs['no_of_decorative'] ) ) {
			$no_of_decorative_lame = intval( $attrs['no_of_decorative'] );
			$total_decorative_lame_height = $no_of_decorative_lame * 0.30;

			$value = $value + $total_decorative_lame_height;
		}

		return ( $value );
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
			if ( ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) || ( 'lame' == $for_quantity ) ) {
				if ( 'longeur' == $for_quantity ) {
					$_quantities['longeur'] = intval( $_data[ $for_quantity ] / 2 );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$_quantities['no_of_angle'] = intval( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					if ( isset( $cat_product['individual'] ) && sanitize_title( 'Yes' ) == sanitize_title( $cat_product['individual'] ) ) {
						$_quantities['no_of_endpoint'] = intval( $_data['no_of_endpoint'] );
					} else {
						$_quantities['no_of_endpoint'] = intval( $_data['no_of_endpoint'] / 2 );
					}
				} else if ( 'no_of_decorative' == $for_quantity ) {
					$_quantities['no_of_decorative'] = intval( $_data['no_of_decorative'] );
				} else if ( 'lame' == $for_quantity ) {
					$length = intval( $_data['longeur'] / 2 );
					$height = isset( $_data['height'] ) ? floatval( $_data['height'] ) : 0;

					if ( sanitize_title( 'Plaque béton' ) == sanitize_title( $_data['soubassement'] ) ) {
						$height = $height - 0.25;
					} else if ( sanitize_title( 'Plaque ardoise' ) == sanitize_title( $_data['soubassement'] ) ) {
						$height = $height - 0.3;
					}

					$no_of_decorative = $_data['no_of_decorative'];
					if ( sanitize_title( 'Sans Lame décorative' ) === sanitize_title( $_data['lame_decorative'] ) ) {
						$no_of_decorative = 0;
					}

					$_quantities['lame'] = intval( ( $length * ( $height / 0.15 ) ) - ( intval( $no_of_decorative ) * 2 ) );
				}

				$this->quantities[ $for_quantity ] = $_quantities[ $for_quantity ];
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

	/**
	 * Get quantity for the product.
	 *
	 * @param $_data
	 * @param $cat_product
	 *
	 * @return int
	 */
	public function get_quantity( $_data, $cat_product, $multiply_by ) {
		$quantity_fields = $this->get_quantity_fields();
		$_quantities     = array();
		foreach ( $quantity_fields as $for_quantity ) {
			if ( ! in_array( $for_quantity, $cat_product['quantity'] ) ) {
				continue;
			}

			// calculate quantity based on condition
			// formula by client
			if ( ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) || ( 'lame' == $for_quantity ) ) {
				if ( 'longeur' == $for_quantity ) {
					$_quantities['longeur'] = intval( $_data[ $for_quantity ] / 2 );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$_quantities['no_of_angle'] = intval( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					if ( isset( $cat_product['individual'] ) && sanitize_title( 'Yes' ) == sanitize_title( $cat_product['individual'] ) ) {
						$_quantities['no_of_endpoint'] = intval( $_data['no_of_endpoint'] );
					} else {
						$_quantities['no_of_endpoint'] = intval( $_data['no_of_endpoint'] / 2 );
					}
				} else if ( 'no_of_decorative' == $for_quantity ) {
					$_quantities['no_of_decorative'] = intval( $_data['no_of_decorative'] );
				} else if ( 'lame' == $for_quantity ) {
					$length = intval( $_data['longeur'] / 2 );
					$height = isset( $_data['height'] ) ? floatval( $_data['height'] ) : 0;

					if ( sanitize_title( 'Plaque béton' ) == sanitize_title( $_data['soubassement'] ) ) {
						$height = $height - 0.25;
					} else if ( sanitize_title( 'Plaque ardoise' ) == sanitize_title( $_data['soubassement'] ) ) {
						$height = $height - 0.3;
					}

					$no_of_decorative = $_data['no_of_decorative'];
					if ( sanitize_title( 'Sans Lame décorative' ) === sanitize_title( $_data['lame_decorative'] ) ) {
						$no_of_decorative = 0;
					}

					$_quantities['lame'] = intval( ( $length * ( $height / 0.15 ) ) - ( intval( $no_of_decorative ) * 2 ) );
				}

				$this->quantities[ $for_quantity ] = $_quantities[ $for_quantity ];
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

	public function products_to_add_old( $_data ) {
		return array();
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
						$quantity = array(
							'quantity' => $instance['quantity'],
							'individual' => $instance['individual']
						);
						
						$products_to_add[ $counter ]['product_id']  = $product_config['mec-product'];
						$products_to_add[ $counter ]['product_qty'] = $this->get_quantity( $_data, $quantity, intval( $multiply_by ) );
						$counter                                   += 1;
					}
				}
			}
		}

		return $products_to_add;
	}
}

function monexterieur_product_cat_config_class( $cat_id ) {
	return MonExterieur_Config_Lame_Commposite::get_instance( $cat_id );
}