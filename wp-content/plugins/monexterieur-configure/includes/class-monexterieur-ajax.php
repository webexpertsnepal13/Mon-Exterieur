<?php

/**
 * Plugin Ajax Actions
 *
 * Class Monexterieur_Configure_Ajax
 */

class Monexterieur_Configure_Ajax {
	/**
	 * Instance of Class.
	 *
	 * @var null
	 */
	public static $instance = null;

	/**
	 * Static function to initialize/get instance of class.
	 *
	 * @return Monexterieur_Configure_Ajax|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Monexterieur_Configure_Ajax constructor.
	 */
	public function __construct() {
		$this->define_actions();
	}

	/**
	 * Define plugin ajax actions.
	 */
	private function define_actions() {
		add_action( 'wp_ajax_configure_breadcrumb', array( $this, 'configure_breadcrumb' ) );
		add_action( 'wp_ajax_nopriv_configure_breadcrumb', array( $this, 'configure_breadcrumb' ) );

		add_action( 'wp_ajax_configure_next_step', array( $this, 'configure_next_step' ) );
		add_action( 'wp_ajax_nopriv_configure_next_step', array( $this, 'configure_next_step' ) );

		add_action( 'wp_ajax_configure_submit', array( $this, 'configure_submit' ) );
		add_action( 'wp_ajax_nopriv_configure_submit', array( $this, 'configure_submit' ) );
	}

	/**
	 * To get steps breadcrumb of selected Product Cat.
	 */
	public function configure_breadcrumb() {
		// get selected product cat id.
		$product_cat = filter_input( INPUT_POST, 'product_cat' );
		// verify
		if ( ! $product_cat ) {
			wp_send_json_error( __( 'Product category must not be empty.', 'monexterieur-configure' ) );
			exit;
		}
		// get related config class object
		$object = monexterieur_get_product_cat_class_object( $product_cat );
		if ( ! $object ) {
			wp_send_json_error( __( 'Product Category Config Class Not Found.', 'monexterieur-configure' ) );
			exit;
		}
		// steps for breadcrumb
		if ( $steps = $object->steps() ) {
			$dictionary       = array(
				0  => 'Zero',
				1  => 'One',
				2  => 'Two',
				3  => 'Three',
				4  => 'Four',
				5  => 'Five',
				6  => 'Six',
				7  => 'Seven',
				8  => 'Eight',
				9  => 'Nine',
				10 => 'Ten',
			);
			$product_cat_name = $object->category()->name;
			$class            = isset( $dictionary[ count( $steps ) + 1 ] )
				?
				$dictionary[ count( $steps ) + 1 ] . '-options'
				:
				( count( $steps ) + 1 ) . '-options';
			ob_start();
			?>
            <div class="config-breadcrumb <?php echo sanitize_title( $class ); ?>"
                 data-total_steps="<?php echo count( $steps ); ?>">
                <div class="crumb current configure-cat" data-place="0">
                    <p><?php _e( 'Ma Clôture', 'monexterieur-configure' ); ?></p>
                    <span><?php echo $product_cat_name; ?></span>
                    <img src="<?php echo get_template_directory_uri() ?>/images/confirm.svg" alt="" class="tick">
                </div>
				<?php
				foreach ( $steps as $key => $step ) {
					if ( is_array( $step ) ) {
						$title = $step['title'];
					} else {
						$field = $object->get_field( $step, '' );
						$title = isset( $field['title'] ) ? $field['title'] : '';
					}

					$_step = is_array( $step ) ? $step['title'] : $step;
					?>
                    <div class="crumb <?php echo sanitize_title( $_step ); ?>"
                         data-step="<?php echo sanitize_title( $_step ); ?>"
                         data-place="<?php echo( $key + 1 ); ?>">
                        <p><?php echo $title; ?></p>
                        <span></span>
                        <img src="<?php echo get_template_directory_uri() ?>/images/confirm.svg"
                             alt=""
                             class="tick">
                    </div>
					<?php
				} // end loop $steps
				?>
            </div>
			<?php

			$html = ob_get_clean();

			wp_send_json_success( array( 'steps' => $steps, 'breadcrumb' => $html ) );
		}

		exit;

	}

	public function configure_next_step() {
		$step = filter_input( INPUT_POST, 'step' );
		$data = filter_input( INPUT_POST, 'data' );

		if ( ( false === $step || null === $step ) || ! $data ) {
			wp_send_json_error( __( 'Invalid Request.', 'monexterieur-configure' ) );
			exit;
		}

		$_data = array();
		parse_str( $data, $_data );

		$product_cat = isset( $_data['configure-cat'] ) ? $_data['configure-cat'] : 0;

		// verify
		if ( ! $product_cat ) {
			wp_send_json_error( __( 'Product category must not be empty.', 'monexterieur-configure' ) );
			exit;
		}
		// get related config class object
		$object = monexterieur_get_product_cat_class_object( $product_cat );
		if ( ! $object ) {
			wp_send_json_error( __( 'Product Category Config Class Not Found.', 'monexterieur-configure' ) );
			exit;
		}

		$steps = $object->steps();

		$step_disabled = false;
		if ( '21' === $product_cat ) { // Check for "Lame Composite"
			if ( isset( $_data['lame_decorative'] ) && sanitize_title( 'Sans Lame décorative' ) === sanitize_title( $_data['lame_decorative'] ) ) {
				$step_disabled = array_search( 'color_decorative', $steps ) + 1;
				if ( $step == array_search( 'color_decorative', $steps ) ) {
					$step = $step_disabled;
				}
			} 
		}

		if ( ! isset( $steps[ $step ] ) ) {
			wp_send_json_error( __( 'Invalid Request.', 'monexterieur-configure' ) );
			exit;
		}

		$html = '';
		if ( is_array( $steps[ $step ] ) ) {
			$html = '<div class="row cust-row" data-step="' . sanitize_title( $steps[ $step ]['title'] ) . '">';
			$html .= '<div class="col-lg-8">';
			foreach ( $steps[ $step ]['subgroup'] as $sub_key => $sub ) {
				$field = $object->get_field( $sub, '' );
				$html  .= $object->render_field( $field, '', $sub, $_data );
			}
			$html .= '</div>';
			$html .= '</div>';
		} else {
			$field = $object->get_field( $steps[ $step ], '' );
			if ( ! is_array( $field ) ) {
				wp_send_json_error( __( 'Invalid Request.', 'monexterieur-configure' ) );
				exit;
			}

			$html = '<div class="row cust-row" data-step="' . $steps[ $step ] . '">';
			if ( 'group' != $field['type'] ) {
				$html .= '<div class="col-lg-8">';
			}
			$html .= $object->render_field( $field, '', $steps[ $step ], $_data );
			if ( 'group' != $field['type'] ) {
				$html .= '</div>';
			}
			$html .= '</div>';

		}

		$response = array( 'html' => $html, 'heading' => '', 'notice' => '', 'disabledStep' => false );

		if ( false !== $step_disabled ) {
			$response['disabledStep'] = $step_disabled;
		}

		if ( $_deps = $object->get_meta( '_config_cat_option_dep' ) ) {
			$key = is_array( $steps[ $step ] ) ? sanitize_title( $steps[ $step ]['title'] ) : sanitize_title( $steps[ $step ] );

			if ( isset( $_deps['heading'][ $key ] ) ) {
				$response['heading'] = $_deps['heading'][ $key ];
			}

			if ( isset( $_deps['notice'][ $key ] ) ) {
				$response['notice'] = $_deps['notice'][ $key ];
			}
		}

		$response['step'] = $step;


		wp_send_json_success( $response );
		exit;
	}

	public function configure_submit() {
		$data  = filter_input( INPUT_POST, 'data' );
		$_data = array();

		parse_str( $data, $_data );

		if ( ! isset( $_data['_configure_nonce'] ) || ! wp_verify_nonce( $_data['_configure_nonce'], 'configure_nonce' ) ) {
			wp_send_json_error( __( 'Invalid Request.', 'monexterieur-configure' ) );
			exit;
		}

		$product_cat = isset( $_data['configure-cat'] ) ? $_data['configure-cat'] : 0;

		// verify
		if ( ! $product_cat ) {
			wp_send_json_error( __( 'Product category must not be empty.', 'monexterieur-configure' ) );
			exit;
		}
		// get related config class object
		$object = monexterieur_get_product_cat_class_object( $product_cat );
		if ( ! $object ) {
			wp_send_json_error( __( 'Product Category Config Class Not Found.', 'monexterieur-configure' ) );
			exit;
		}

		$steps  = $object->steps();
		$errors = array();
		foreach ( $steps as $step ) {
			if ( is_array( $step ) ) {
				foreach ( $step['subgroup'] as $subgroup ) {
					if ( ! isset( $_data[ $subgroup ] ) || '' === $_data[ $subgroup ] ) {
						$field    = $object->get_field( $subgroup, '' );
						$errors[] = $field['title'];
					}
				}
			} else if ( ! isset( $_data[ $step ] ) || '' === $_data[ $step ] ) {
				$field    = $object->get_field( $step, '' );
				$errors[] = $field['title'];
			}
		}

		if ( ! empty( $errors ) ) {
			wp_send_json_error( sprintf( __( 'Some fields are empty (%s). Please try again.' ), implode( ', ', $errors ) ) );
			exit;
		}

		if ( $object->add_to_cart( $_data ) ) {
			wp_send_json_success( array( 'cart' => wc_get_cart_url() ) );
			exit;
		}

		wp_send_json_error( __( 'Category Products not configured yet.', 'monexterieur-configure' ) );
		exit;
	}
}

Monexterieur_Configure_Ajax::get_instance();