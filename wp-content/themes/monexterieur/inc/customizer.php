<?php
/**
 * Mon Exterieur Theme Customizer
 *
 * @package Mon_Exterieur
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function monexterieur_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'monexterieur_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'monexterieur_customize_partial_blogdescription',
		) );
	}

	/**
	 * Theme Related
	 */
	/*$wp_customize->add_panel( 'monexterieur_customizer_panel', array(
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __( 'Mon Exterieur', 'monexterieur' ),
		'description'    => __( 'Description of what this panel does.', 'monexterieur' ),
	) );

	$wp_customize->add_section( 'monexterieur_customizer_section', array(
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',monexterieur_configure
		'title'          => __( 'Theme Pages', 'monexterieur' ),
		'description'    => '',
		'panel'          => 'monexterieur_customizer_panel',
	) );

	// Field 1
	$wp_customize->add_setting( 'monexterieur_customizer_notre_magasin', array(
		'default'           => '',
		'type'              => 'theme_mod',
		'sanitize_callback' => 'absint',
		'capability'        => 'edit_theme_options',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'monexterieur_customizer_notre_magasin', array(
		'type'        => 'dropdown-pages',
		'priority'    => 10,
		'section'     => 'monexterieur_customizer_section',
		'label'       => __( 'Notre magasin page', 'monexterieur' ),
		'description' => '',
	) );*/
}

add_action( 'customize_register', 'monexterieur_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function monexterieur_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function monexterieur_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function monexterieur_customize_preview_js() {
	wp_enqueue_script( 'monexterieur-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}

add_action( 'customize_preview_init', 'monexterieur_customize_preview_js' );
