<?php

class Monexterieur_Configure_Shortcodes {
	/**
	 * Instance of Class.
	 *
	 * @var null
	 */
	public static $instance = null;

	/**
	 * Static function to initialize/get instance of class.
	 *
	 * @return Monexterieur_Configure_Shortcodes|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Monexterieur_Configure_Shortcodes constructor.
	 */
	public function __construct() {
		$this->define_actions();
	}

	/**
	 * Define Shortcodes
	 */
	private function define_actions() {
		add_shortcode( 'monexterieur_configurator', array( $this, 'monexterieur_configurator' ) );
	}

	public function monexterieur_configurator() {
		require plugin_dir_path( __FILE__ ) . 'partials/monexterieur-configure-public-display.php';
	}
}

Monexterieur_Configure_Shortcodes::get_instance();