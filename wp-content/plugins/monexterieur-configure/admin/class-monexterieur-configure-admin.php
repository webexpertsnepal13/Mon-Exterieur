<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Monexterieur_Configure
 * @subpackage Monexterieur_Configure/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Monexterieur_Configure
 * @subpackage Monexterieur_Configure/admin
 */
class Monexterieur_Configure_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'wp_ajax_mec_select2_product', array( $this, 'search_product' ) );
		add_action( 'wp_ajax_mec_config_save', array( $this, 'save_product_config' ) );
		add_action( 'wp_ajax_mec_config_remove', array( $this, 'remove_product_config' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Monexterieur_Configure_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Monexterieur_Configure_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name . '-jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-select2-style', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/monexterieur-configure-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Monexterieur_Configure_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Monexterieur_Configure_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name . '-select2-script', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js', '', $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/monexterieur-configure-admin.js', array(
			'jquery-ui-accordion',
			$this->plugin_name . '-select2-script',
			'jquery'
		), $this->version, false );

	}

	/**
	 * Register Customizer Settings Page.
	 */
	public function register_admin_page() {
		add_menu_page(
			__( 'Configurature Settings', 'monexterieur-configure' ),
			__( 'Configurature Settings', 'monexterieur-configure' ),
			'manage_options',
			'configurature-settings',
			array( $this, 'configurature_settings' ),
			'dashicons-schedule',
			3
		);

		add_submenu_page(
			'configurature-settings',
			__( 'Configurature Settings (New)', 'monexterieur-configure' ),
			__( 'Configurature Settings (New)', 'monexterieur-configure' ),
			'manage_options',
			'configurature-settings-new',
			array( $this, 'configurature_settings_new' )
		);

		add_submenu_page(
			'configurature-settings',
			__( 'Configurature Option', 'monexterieur-configure' ),
			__( 'Configurature Option', 'monexterieur-configure' ),
			'manage_options',
			'configurature-options',
			array( $this, 'configurature_options' )
		);

	}

	/**
	 * Register Customizer Customization.
	 *
	 * @param $wp_customize
	 */
	public function register_customizer( $wp_customize ) {
		$wp_customize->add_section( 'monexterieur_customizer_plugin_section', array(
			'priority'       => 10,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => __( 'Monexterieur Customizer', 'monexterieur-configure' ),
			'description'    => '',
		) );

		$wp_customize->add_setting( 'monexterieur_customizer_plugin_configure', array(
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'monexterieur_customizer_plugin_configure', array(
			'type'        => 'dropdown-pages',
			'priority'    => 10,
			'section'     => 'monexterieur_customizer_plugin_section',
			'label'       => __( 'Configurateur Page', 'monexterieur-configure' ),
			'description' => '',
		) );
	}

	/**
	 * Locate Admin Template Parts.
	 *
	 * @param string $template
	 * @param array $args
	 */
	public function get_template( $template = '', $args = array() ) {
		if ( ! $template ) {
			return;
		}

		if ( ! file_exists( dirname( __FILE__ ) . "/{$template}" ) ) {
			return;
		}

		require dirname( __FILE__ ) . "/{$template}";
	}

	/**
	 * Admin Configure Setting Template Part.
	 */
	public function configurature_settings() {
		// $this->get_template( 'partials/monexterieur-configure-admin-display.php' );
		$this->get_template( 'partials/monexterieur-configure-admin-display.php' );
	}

	public function configurature_settings_new() {
		$this->get_template( 'partials/monexterieur-configure-admin-display.php' );
	}

	/**
	 * Admin Configure Options Template Part.
	 */
	public function configurature_options() {
		$this->get_template( 'partials/monexterieur-configure-admin-options-display.php' );
	}

	/**
	 * Configuration Settings Add New Product Select2 AJAX Request.
	 */
	public function search_product() {
		// $nonce = filter_input( INPUT_GET, '_nonce' );
		// if ( ! $nonce || ! wp_verify_nonce( $nonce, 'mec_select2_search_product' ) ) {
		// 	wp_send_json_error(
		// 		array(
		// 			'results' => array()
		// 		)
		// 	);
		// 	wp_die();
		// }

		$query_param = sanitize_text_field( filter_input( INPUT_GET, 'q' ) );
		if ( ! $query_param ) {
			wp_send_json_error(
				array(
					'results' => array()
				)
			);
			wp_die();
		}

		$cat_id = filter_input( INPUT_GET, 'cat_id' );

		$query_args = array(
			'post_type' => 'product',
			'post_status' => 'any',
			'numberposts ' => -1,
			's' => $query_param
		);

		$cat_config_products = array();
		if ( $cat_id ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'terms' => $cat_id,
			);

			$cat_config_products = get_term_meta( $cat_id, '_mec_config_products', true );
			if ( ! is_array( $cat_config_products ) ) {
				$cat_config_products = array();
			}
		}

		$query_products = get_posts( $query_args );

		$products = array();
		if ( ! empty( $query_products ) ) {
			foreach ( $query_products as $query_product ) {
				$title = $query_product->post_title;
				if ( 'publish' !== $query_product->post_status ) {
					$title .= " ({$query_product->post_status})";
				}

				$products[] =  array(
					'id' => $query_product->ID,
					'text' => $title,
					'disabled' => in_array( $query_product->ID, $cat_config_products )
				);
			}
		}

		wp_send_json_success(
			array(
				'results' => $products
			)
		);
		wp_die();
	}


	/**
	 * Save product config AJAX request.
	 */
	public function save_product_config() {
		$form_data = filter_input( INPUT_POST, 'formData' );
		$parse_data = array();
		wp_parse_str( $form_data, $parse_data );

		$cat_id = isset( $parse_data['cat-id'] ) ? $parse_data['cat-id'] : '';
		$product_id = isset( $parse_data['mec-product'] ) ? $parse_data['mec-product'] : '';
		if ( ! $cat_id || ! $product_id ) {
			wp_send_json_error( esc_html__( 'Invalid request.', 'monexterieur-configure' ) );
		}

		// Get Cat Config related products.
		$config_products = get_term_meta( $cat_id, '_mec_config_products', true );
		if ( ! is_array( $config_products ) ) {
			$config_products = array();
		}

		$product_key = isset( $parse_data['product-key'] ) ? $parse_data['product-key'] : '';
		// Check if the product was updated.
		if ( array_key_exists( $product_key, $config_products ) ) {
			if ( $config_products[ $product_key ] !== $product_id ) {
				$config_products[ $product_key ] = $product_id;
				delete_term_meta( $cat_id, "_mec_product_config_{$config_products[ $product_key ]}" );
			}
		}

		// New Product added.
		if ( ! in_array( $product_id, $config_products ) ) {
			$config_products[] = $product_id;
		}

		update_term_meta( $cat_id, '_mec_config_products', array_values( $config_products ) );
		update_term_meta( $cat_id, "_mec_product_config_{$product_id}", $parse_data );

		if ( ! in_array( $product_id, $config_products ) ) {
			$config_products[] = $product_id;
			update_term_meta( $cat_id, '_mec_config_products', $config_products );
		}

		wp_send_json_success( esc_html__( 'Product config saved.', 'monexterieur-configure' ) );
		wp_die();
	}


	public function remove_product_config() {
		global $mec_product_id;
		$cat_id = filter_input( INPUT_POST, 'cat_id' );
		$mec_product_id = filter_input( INPUT_POST, 'mec_product' );

		if ( ! $cat_id || ! $mec_product_id ) {
			unset( $GLOBALS['mec_product_id'] );
			wp_send_json_error( esc_html__( 'Invalid request.', 'monexterieur-configure' ) );
		}

		$config_products = get_term_meta( $cat_id, '_mec_config_products', true );
		if ( is_array( $config_products ) ) {
			$config_products = array_filter( $config_products, function( $value ) {
				global $mec_product_id;
				return !((string)$value === (string)$mec_product_id);
			} );
		}

		update_term_meta( $cat_id, '_mec_config_products', array_values( $config_products ) );
		delete_term_meta( $cat_id, "_mec_product_config_{$mec_product_id}" );

		unset( $GLOBALS['mec_product_id'] );

		wp_send_json_success( esc_html__( 'Product config removed..', 'monexterieur-configure' ) );
		wp_die();
	}

}
