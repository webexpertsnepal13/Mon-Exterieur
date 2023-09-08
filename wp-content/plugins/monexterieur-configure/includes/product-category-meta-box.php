<?php

// source: http://wordpress.stackexchange.com/questions/211703/need-a-simple-but-complete-example-of-adding-metabox-to-taxonomy
// code authored by jgraup - http://wordpress.stackexchange.com/users/84219/jgraup


// REGISTER TERM META

add_action( 'init', 'monexterieur_register_product_cat_config_class' );

function monexterieur_register_product_cat_config_class() {

	register_meta( 'term', '_product_cat_config_class', 'monexterieur_sanitize_product_cat_config_class' );
}

// SANITIZE DATA

function monexterieur_sanitize_product_cat_config_class( $value ) {
	return sanitize_text_field( $value );
}

// GETTER (will be sanitized)

function monexterieur_get_product_cat_config_class( $term_id ) {
	$value = get_term_meta( $term_id, '_product_cat_config_class', true );
	$value = monexterieur_sanitize_product_cat_config_class( $value );

	return $value;
}

// ADD FIELD TO CATEGORY TERM PAGE

add_action( 'product_cat_add_form_fields', 'monexterieur_add_form_field_product_cat_config_class' );

function monexterieur_add_form_field_product_cat_config_class() { ?>
	<?php wp_nonce_field( basename( __FILE__ ), 'product_cat_config_class_nonce' ); ?>
    <div class="form-field term-meta-text-wrap">
        <label for="product-cat-config-class-meta"><?php _e( 'Product Category Config Class', 'monexterieur-configure' ); ?></label>
        <select class="term-meta-product-cat-config-class postform" name="product_cat_config_class" id="product-cat-config-class-meta">
            <option value=""><?php echo __( 'Select Product Category Config Class', 'monexterieur-configure' ); ?></option>
            <?php
            if( $config_class_list = monexterieur_get_product_cat_config_class_list() ) {
                foreach( $config_class_list as $path => $list ) {
                    ?>
                    <option value="<?php echo esc_attr( $path ); ?>"><?php echo $list; ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <p class="description"><?php _e( 'Select the Configure Class that will be used in "Configure Settings (Backend)" to add products and also in frontend to identify the products.', 'monexterieur-configure' ); ?></p>
    </div>
<?php }


// ADD FIELD TO CATEGORY EDIT PAGE

add_action( 'product_cat_edit_form_fields', 'monexterieur_edit_form_field_product_cat_config_class' );

function monexterieur_edit_form_field_product_cat_config_class( $term ) {

	$value = monexterieur_get_product_cat_config_class( $term->term_id );

	if ( ! $value ) {
		$value = "";
	} ?>

    <tr class="form-field term-meta-text-wrap">
        <th scope="row"><label for="term-meta-text"><?php _e( 'Product Category Config Class', 'monexterieur-configure' ); ?></label></th>
        <td>
			<?php wp_nonce_field( basename( __FILE__ ), 'product_cat_config_class_nonce' ); ?>
            <select class="term-meta-product-cat-config-class postform" name="product_cat_config_class" id="product-cat-config-class-meta">
                <option value=""><?php echo __( 'Select Product Category Config Class', 'monexterieur-configure' ); ?></option>
		        <?php
		        if( $config_class_list = monexterieur_get_product_cat_config_class_list() ) {
			        foreach( $config_class_list as $path => $list ) {
				        ?>
                        <option value="<?php echo esc_attr( $path ); ?>" <?php selected( esc_attr( $value ), esc_attr( $path ) ); ?>>
                            <?php echo $list; ?>
                        </option>
				        <?php
			        }
		        }
		        ?>
            </select>
            <p class="description"><?php _e( 'Select the Configure Class that will be used in "Configure Settings (Backend)" to add products and also in frontend to identify the products.', 'monexterieur-configure' ); ?></p>
        </td>
    </tr>
<?php }


// SAVE TERM META (on term edit & create)

add_action( 'edit_product_cat', 'monexterieur_save_product_cat_config_class' );
add_action( 'create_product_cat', 'monexterieur_save_product_cat_config_class' );

function monexterieur_save_product_cat_config_class( $term_id ) {

	// verify the nonce --- remove if you don't care
	if ( ! isset( $_POST['product_cat_config_class_nonce'] ) || ! wp_verify_nonce( $_POST['product_cat_config_class_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	$old_value = monexterieur_get_product_cat_config_class( $term_id );
	$new_value = isset( $_POST['product_cat_config_class'] ) ? monexterieur_sanitize_product_cat_config_class( $_POST['product_cat_config_class'] ) : '';


	if ( $old_value && '' === $new_value ) {
		delete_term_meta( $term_id, '_product_cat_config_class' );
	} else if ( $old_value !== $new_value ) {
		update_term_meta( $term_id, '_product_cat_config_class', $new_value );
	}
}

// MODIFY COLUMNS (add our meta to the list)

add_filter( 'manage_edit-product_cat_columns', 'monexterieur_edit_term_columns', 10, 3 );

function monexterieur_edit_term_columns( $columns ) {

	$columns['_product_cat_config_class'] = __( 'Product Category Config Class', 'monexterieur-configure' );

	return $columns;
}

// RENDER COLUMNS (render the meta data on a column)

add_filter( 'manage_product_cat_custom_column', 'monexterieur_manage_term_custom_column', 10, 3 );

function monexterieur_manage_term_custom_column( $out, $column, $term_id ) {

	if ( '_product_cat_config_class' === $column ) {

		$value = monexterieur_get_product_cat_config_class( $term_id );

		if ( ! $value ) {
			$value = '';
		}

		$config_class_list = monexterieur_get_product_cat_config_class_list();
		if( isset( $config_class_list[ $value ] ) ) {
		    $text = $config_class_list[ $value ];
        }
        else {
		    $text = '';
        }

		$out = sprintf( '<span class="term-meta-text-block" style="" >%s</div>', esc_attr( $text ) );
	}

	return $out;
}