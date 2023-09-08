<?php

class MonExterieur_Config_Panneau_Bois extends MonExterieur_Types_Abstract {
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
			'poteau'         => array(
				'title'   => __( 'Poteau', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'Pin44', 'GM45 Gris', 'GM45 Noir' )
			),
			'panneau'        => array(
				'title'   => __( 'Panneau', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array( 'ETNA', 'AMG', 'AMG BOMBE', 'TREILLIS' )
			),
			'soubassement'   => array(
				'title'   => __( 'Soubassement', 'monexterieur-configure' ),
				'type'    => 'group',
				'class'   => '',
				'options' => array(
					array(
						'title'      => 'Sans plaque',
						'conditions' => array()
					),
					array(
						'title'      => 'Plaque béton',
						'conditions' => array(
							'poteau' => 'GM45 Gris'
						)
					),
					array(
						'title'      => 'Plaque béton',
						'conditions' => array(
							'poteau' => 'GM45 Noir'
						)
					),
				)
			),
			'longeur'        => array(
				'title' => __( 'Longueur en mètre linéaire', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => array(
					array(
						'value'      => '1.90',
						'conditions' => array(
							'and' => array( 'poteau' => 'Pin44' ),
						)
					),
					array(
						'value'      => '1.82',
						'conditions' => array(
							'and' => array( 'poteau' => 'GM45 Gris' )
						)
					),
					array(
						'value'      => '1.82',
						'conditions' => array(
							'and' => array( 'poteau' => 'GM45 Noir' )
						)
					),
				),
				'value' => true,
			),
			'no_of_angle'    => array(
				'title' => __( 'Nombre d\'angle', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 1,
				'also_affect' => 'longeur',
			),
			'no_of_endpoint' => array(
				'title' => __( 'Nombre de poteau extrémité', 'monexterieur-configure' ),
				'type'  => 'number',
				'class' => '',
				'step'  => 2,
				'value' => 2,
				'min' => 2
			)
		);

		$this->steps = array(
			'poteau',
			'panneau',
			'soubassement',
			array(
				'title'    => __( 'Dimension', 'monexterieur-configure' ),
				'subgroup' => array( 'longeur', 'no_of_angle', 'no_of_endpoint' )
			)
		);
	}

	/**
	 * Fields for Conditions
	 *
	 * @return array
	 */
	public function get_condition_fields() {
		return array( 'poteau', 'panneau', 'soubassement' );
	}

	/**
	 * Fields to calculate quantity
	 *
	 * @return array
	 */
	public function get_quantity_fields() {
		return array( 'longeur', 'no_of_angle', 'no_of_endpoint' );
	}

	public function get_value( $field, $attrs ) {
		$value = 0;
		if ( ! isset( $attrs['poteau'] ) ) {
			return 0;
		}

		$size = 0;

		if ( sanitize_title( 'Pin44' ) === sanitize_title( $attrs['poteau'] ) ) {
			$value = 2;
			$size = 0.10;
		} elseif ( sanitize_title( 'GM45' ) === sanitize_title( $attrs['poteau'] ) ) {
			$value = 1.84;
			$size = 0.02;
		}

		if ( isset( $attrs['no_of_angle'] ) && 0 < intval( $attrs['no_of_angle'] ) ) {
			$no_of_angles = intval( $attrs['no_of_angle'] );
			$no_of_angles_height = $no_of_angles * $size;

			$value = $value + $no_of_angles_height;
		}

		return ( $value );
	}

	/**
	 * Calculate quantity for product.
	 *
	 * @param array $_data Form submitted data.
	 * @param array $cat_product Category Specific Product.
	 *
	 * @return float|int
	 */
	public function get_quantity_old( $_data = array(), $cat_product = array() ) {
		$quantity_fields = $this->get_quantity_fields();
		$quantities      = array();
		foreach ( $quantity_fields as $for_quantity ) {
			if ( ! in_array( $for_quantity, $cat_product['also-quantity'] ) ) {
				continue;
			}

			if ( isset( $this->quantities[ $for_quantity ] ) ) {
				$quantities[ $for_quantity ] = $this->quantities[ $for_quantity ];
				continue;
			}

			if ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) {
				if ( 'longeur' == $for_quantity ) {
					$field  = $this->get_field( $for_quantity, '' );
					$length = $_data[ $for_quantity ];
					$step   = $this->number_field_step( $field, $_data );

					$quantities[ $for_quantity ] = round( $length / $step );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$quantities[ $for_quantity ] = round( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					$quantities[ $for_quantity ] = round( $_data['no_of_endpoint'] / 2 );
				}

				$this->quantities[ $for_quantity ] = $quantities[ $for_quantity ];
			}
		}

		if ( ! empty( $quantities ) ) {
			if ( isset( $cat_product['multiple-by'] ) ) {
				return intval( $cat_product['multiple-by'] ) * array_sum( $quantities );
			}

			return array_sum( $quantities );
		}

		return $cat_product['default-qty'];
	}

	public function get_quantity( $_data, $cat_product, $multiply_by ) {
		$quantity_fields = $this->get_quantity_fields();
		$quantities      = array();
		foreach ( $quantity_fields as $for_quantity ) {
			if ( ! in_array( $for_quantity, $cat_product ) ) {
				continue;
			}

			if ( isset( $this->quantities[ $for_quantity ] ) ) {
				$quantities[ $for_quantity ] = $this->quantities[ $for_quantity ];
				continue;
			}

			if ( isset( $_data[ $for_quantity ] ) && '' !== $_data[ $for_quantity ] ) {
				if ( 'longeur' == $for_quantity ) {
					$field  = $this->get_field( $for_quantity, '' );
					$length = $_data[ $for_quantity ];
					$step   = $this->number_field_step( $field, $_data );

					$quantities[ $for_quantity ] = round( $length / $step );
				} else if ( 'no_of_angle' == $for_quantity ) {
					$quantities[ $for_quantity ] = round( $_data['no_of_angle'] );
				} else if ( 'no_of_endpoint' == $for_quantity ) {
					$quantities[ $for_quantity ] = round( $_data['no_of_endpoint'] / 2 );
				}

				$this->quantities[ $for_quantity ] = $quantities[ $for_quantity ];
			}
		}

		if ( ! empty( $quantities ) ) {
			if ( $multiply_by ) {
				return intval( $multiply_by ) * array_sum( $quantities );
			}

			return array_sum( $quantities );
		}

		return 1;
	}

	public function products_to_add( $_data ) {
		return array();
	}
}

function monexterieur_product_cat_config_class( $cat_id ) {
	return MonExterieur_Config_Panneau_Bois::get_instance( $cat_id );
}