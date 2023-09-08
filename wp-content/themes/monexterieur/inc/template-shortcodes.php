<?php
function monexterieur_menu_contact_nous_dropdown() {

	$dropDown = '';
	
	// $dropDown = do_shortcode( '[wpgmza id="1"]' );

	$dropDown .= do_shortcode( '[contact-form-7 id="58" title="Dropdown Contact Form"]' );

	return $dropDown;
}
add_shortcode('me_contact_nous_dropdown', 'monexterieur_menu_contact_nous_dropdown' );