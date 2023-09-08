<?php
/**
 * Helper
 */
function monexterieur_configure_get_configurator_page() {
	return get_theme_mod( 'monexterieur_customizer_plugin_configure' );
}

/**
 * Get Product Category reference class.
 *
 * @return mixed
 */
function monexterieur_get_product_cat_config_class_list() {
	$classes = array(
		'includes/types/class-panneau-bois.php'    => __( 'Panneau bois', 'monexterieur-configure' ),
		'includes/types/class-lame-en-bois.php'    => __( 'Lame en bois', 'monexterieur-configure' ),
		'includes/types/class-lame-commposite.php' => __( 'Lame Composite', 'monexterieur-configure' ),
		'includes/types/class-gabion.php'          => __( 'Gabion', 'monexterieur-configure' ),
		'includes/types/class-grille-rigide.php'   => __( 'Grille Rigide', 'monexterieur-configure' ),
	);

	return apply_filters( 'monexterieur_product_cat_config_class_list', $classes );
}

/**
 * Load Product category reference class.
 *
 * @param $term_id
 *
 * @return bool|MonExterieur_Config_Gabion|MonExterieur_Config_Grille_Rigide|MonExterieur_Config_Lame_Bois|MonExterieur_Config_Lame_Commposite|MonExterieur_Config_Panneau_Bois|null
 */
function monexterieur_get_product_cat_class_object( $term_id ) {
	$object = false;
	if ( $config_class_path = get_term_meta( $term_id, '_product_cat_config_class', true ) ) {
		$path = apply_filters( 'monexterieur_product_cat_config_class_path', MONEXTERIEUR_CONFIGURE_PLUGIN_DIR . $config_class_path, $config_class_path );
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}

	if ( function_exists( 'monexterieur_product_cat_config_class' ) ) {
		$object = monexterieur_product_cat_config_class( $term_id );
	}

	return $object;
}
