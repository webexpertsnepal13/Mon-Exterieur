<?php

class MonExterieur_Config_Grille_Rigide extends MonExterieur_Types_Abstract {

	private static $instance = null;

	public static function get_instance( $cat_id ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $cat_id );
		}

		return self::$instance;
	}

	public function __construct( $cat_id ) {
		parent::__construct( $cat_id );

		$this->fields = array(
			'color'              => array(
				'title'   => __( 'Couleur de grille', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array(
					'Gris',
					'Vert'
				)
			),
			'soubassement'       => array(
				'title'   => __( 'Soubassement', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Sans plaque', 'Plaque béton', 'Plaque ardoise' ),
			),
			'type_de_pose'       => array(
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
					)
				)
			),
			'height1' => array(
				'title' => __( 'Hauteur de cloture', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 0.13,
				'value' => true,
				'min' => 0.13,
			),
			'height'             => array(
				'title'      => __( 'Hauteur Cloture', 'monexterieur-configure' ),
				'type'       => 'number_group_option',
				'class'      => '',
				'options'    => array(
					array(
						'condition' => array(
							'soubassement' => 'Sans plaque',
							'type_de_pose' => __( 'A sceller', 'monexterieur-configure' )
						),
						'values'    => array( '1.03', '1.23', '1.53', '1.73' )
					),
					array(
						'condition' => array(
							'soubassement' => 'Plaque béton',
							'type_de_pose' => __( 'A sceller', 'monexterieur-configure' )
						),
						'values'    => array( '1.28', '1.48', '1.78', '1.98' )
					),
					array(
						'condition' => array(
							'soubassement' => 'Plaque ardoise',
							'type_de_pose' => __( 'A sceller', 'monexterieur-configure' )
						),
						'values'    => array( '1.33', '1.53', '1.83', '2.03' )
					),
					array(
						'condition' => array(
							'soubassement' => 'Sans plaque',
							'type_de_pose' => __( 'Sur platine', 'monexterieur-configure' )
						),
						'values'    => array( '1.03', '1.23', '1.53' )
					),
					array(
						'condition' => array(
							'soubassement' => 'Plaque béton',
							'type_de_pose' => __( 'Sur platine', 'monexterieur-configure' )
						),
						'values'    => array( '1.28', '1.48', '1.78', '1.98' )
					),
					array(
						'condition' => array(
							'soubassement' => 'Plaque ardoise',
							'type_de_pose' => __( 'Sur platine', 'monexterieur-configure' )
						),
						'values'    => array( '1.33', '1.53', '1.83', '2.03' )
					)
				),
				'compare_as' => true
			),
			'compare_as'         => array(
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
			'kit_occultation'    => array(
				'title'   => __( 'Kit d\'occultation', 'monextrieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array(
					array(
						'title'      => 'Sans',
						'conditions' => array()
					),
					array(
						'title'      => 'PVC - gris',
						'conditions' => array('!=height' => '1.03','!=#height' => '1.93')
					),
					array(
						'title'      => 'PVC - vert',
						'conditions' => array('!=height' => '1.03','!=#height' => '1.93')
					),
					array(
						'title'      => 'Bois',
						'conditions' => array('!=height' => '1.03','!=#height' => '1.93')
					)
				)
			),
			'longeur'            => array(
				'title' => __( 'Longueur en mètre linéaire', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2.5,
				'min' => 2.5,
				'value' => 2.5,
			),
			'longeur_occulation' => array(
				'title' => __( 'Longueur de l\'occultation', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2,
			),
			'no_of_angle'        => array(
				'title' => __( 'Nombre d\'angle', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 1
			),
			'no_of_endpoint'     => array(
				'title' => __( 'Nombre de poteau extrémité', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2,
				'min' => 2,
				'value' => 2
			),
			'bois'               => array(
				'title' => __( 'Bois', 'monexterieur-configure' ),
			)
		);

		$this->steps = array(
			'color',
			'soubassement',
			'type_de_pose',
			'height',
			'kit_occultation',
			array(
				'title'    => __( 'Dimension', 'monexterieur-configure' ),
				'subgroup' => array( 'longeur', 'longeur_occulation', 'no_of_angle', 'no_of_endpoint' )
			)
		);
	}

	/**
	 * Fields for Conditions
	 *
	 * @return array
	 */
	public function get_condition_fields() {
		return array( 'soubassement', 'type_de_pose', 'height', 'kit_occultation', 'color' );
	}

	/**
	 * Fields to calculate quantity
	 *
	 * @return array
	 */
	public function get_quantity_fields() {
		return array( 'longeur', 'no_of_angle', 'no_of_endpoint', 'height', 'bois' );
	}

	public function get_value( $field, $attrs ) {
		$value = 0.13;
		if ( ! isset( $attrs['soubassement'] ) ) {
			return 0.13;
		}

		if ( sanitize_title( 'Plaque béton' ) === sanitize_title( $attrs['soubassement'] ) || sanitize_title( 'Sans plaque' ) === sanitize_title( $attrs['soubassement'] ) ) {
			$value = 0.25;
		} elseif ( sanitize_title( 'Plaque ardoise' ) === sanitize_title( $attrs['soubassement'] ) ) {
			$value = 0.30;
		}

		if ( isset( $attrs['no_of_decorative'] ) && 0 < intval( $attrs['no_of_decorative'] ) ) {
			$no_of_decorative_lame = intval( $attrs['no_of_decorative'] );
			$total_decorative_lame_height = $no_of_decorative_lame * ( 0.13 * 2 );

			$value = $value + $total_decorative_lame_height;
		}

		return ( $value );
	}

	public function get_quantity_old( $_data, $cat_product ) {
		$quantity_fields = $this->get_quantity_fields();
		$_quantities     = array();
		foreach ( $quantity_fields as $for_quantity ) {
			if ( ! in_array( $for_quantity, $cat_product['also-quantity'] ) ) {
				continue;
			}
			// calculate quantity based on condition
			// formula by client
			if ( ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) || ( 'bois' == $for_quantity ) ) {
				if ( 'longeur' == $for_quantity ) {
					$_quantities['longueur'] = round( $_data[ $for_quantity ] / 2.5 );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$_quantities['no_of_angle'] = round( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					$_quantities['no_of_endpoint'] = round( $_data['no_of_endpoint'] / 2 );
				} else if ( 'height' == $for_quantity ) {
					$panel  = round( $_data['longeur'] / 2.5 );
					$height = floatval( $_data['height'] );
					$soubassement = sanitize_title( $_data['soubassement'] );

					if ( sanitize_title( 'Plaque béton' ) === $soubassement ) {
						$height = $height - 0.25;
					} else if ( sanitize_title( 'Plaque ardoise' ) === $soubassement ) {
						$height = $height - 0.30;
					}

					$_quantities['height'] = $panel;

					if ( in_array( $height, array( '1.03', '1.23', '1.53' ) ) ) {
						$_quantities['height'] = 6 * $panel;
					} else if ( in_array( $height, array( '1.73', '1.93' ) ) ) {
						$_quantities['height'] = 8 * $panel;
					} else {
						$_quantities['height'] = $panel;
					}
					

					/*if ( floatval( $height ) >= 1.03 ) {
						$_quantities['height'] = 6 * $panel;
					}

					if ( floatval( $height ) >= 1.53 ) {
						$_quantities['height'] = 8 * $panel;
					}*/

				} else if ( 'bois' == $for_quantity ) {
					$panel = round( $_data['longeur'] / 2.5 );
					$lame = 1;
					
					/*$height = $height = $_data['height'];
					if( floatval( $height ) >= 1.03 ) {
						$lame = $panel * 2;
					}
					else if( floatval( $height ) >= 1.53 ) {
						$lame = $panel * 3;
					}*/

					$height = floatval( $_data['height'] );
					$soubassement = sanitize_title( $_data['soubassement'] );

					if ( sanitize_title( 'Plaque béton' ) === $soubassement ) {
						$height = $height - 0.25;
					} else if ( sanitize_title( 'Plaque ardoise' ) === $soubassement ) {
						$height = $height - 0.30;
					}

					if( in_array( $height, array( '1.03', '1.23' ) ) ) {
						$lame = $panel * 2;
					} else if( in_array( $height, array( '1.53', '1.73', '1.93' ) ) ) {
						$lame = $panel * 3;
					}

					$_quantities['bois'] = ceil( $lame / 6 );
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
		$_quantities     = array();
		foreach ( $quantity_fields as $for_quantity ) {
			if ( ! in_array( $for_quantity, $cat_product ) ) {
				continue;
			}
			// calculate quantity based on condition
			// formula by client
			if ( ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) || ( 'bois' == $for_quantity ) ) {
				if ( 'longeur' == $for_quantity ) {
					$_quantities['longueur'] = round( $_data[ $for_quantity ] / 2.5 );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$_quantities['no_of_angle'] = round( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					$_quantities['no_of_endpoint'] = round( $_data['no_of_endpoint'] / 2 );
				} else if ( 'height' == $for_quantity ) {
					$panel  = round( $_data['longeur'] / 2.5 );
					$height = floatval( $_data['height'] );
					$soubassement = sanitize_title( $_data['soubassement'] );

					if ( sanitize_title( 'Plaque béton' ) === $soubassement ) {
						$height = $height - 0.25;
					} else if ( sanitize_title( 'Plaque ardoise' ) === $soubassement ) {
						$height = $height - 0.30;
					}

					$_quantities['height'] = $panel;

					if ( in_array( $height, array( '1.03', '1.23', '1.53' ) ) ) {
						$_quantities['height'] = 6 * $panel;
					} else if ( in_array( $height, array( '1.73', '1.93' ) ) ) {
						$_quantities['height'] = 8 * $panel;
					} else {
						$_quantities['height'] = $panel;
					}
				} else if ( 'bois' == $for_quantity ) {
					$panel = round( $_data['longeur'] / 2.5 );
					$lame = 1;

					$height = floatval( $_data['height'] );
					$soubassement = sanitize_title( $_data['soubassement'] );

					if ( sanitize_title( 'Plaque béton' ) === $soubassement ) {
						$height = $height - 0.25;
					} else if ( sanitize_title( 'Plaque ardoise' ) === $soubassement ) {
						$height = $height - 0.30;
					}

					if( in_array( $height, array( '1.03', '1.23' ) ) ) {
						$lame = $panel * 2;
					} else if( in_array( $height, array( '1.53', '1.73', '1.93' ) ) ) {
						$lame = $panel * 3;
					}

					$_quantities['bois'] = ceil( $lame / 6 );
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
	return MonExterieur_Config_Grille_Rigide::get_instance( $cat_id );
}