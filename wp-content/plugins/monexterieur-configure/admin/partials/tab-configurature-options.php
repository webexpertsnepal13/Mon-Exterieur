<?php
$cat_id   = ( $current_cat instanceof WP_Term ) ? $current_cat->term_id : 0;
$cat_slug = ( $current_cat instanceof WP_Term ) ? $current_cat->slug : '';

$object = monexterieur_get_product_cat_class_object( $cat_id );
if ( ! $object ) {
	echo __( 'Product Category Config Class Not Found.', 'monexterieur-configure' );

	return;
}

if ( ! $object->category() instanceof WP_TERM ) {
	echo 'Invalid Product Category';

	return;
}

if ( isset( $_POST['mon_configure_option'] ) && wp_verify_nonce( $_POST['mon_configure_option'], '_mon_configure_option' ) ) {
	$images      = isset( $_POST['configimages'] ) ? array_filter( $_POST['configimages'] ) : array();
	$information = isset( $_POST['information'] ) ? array_filter( $_POST['information'] ) : array();
	$headings    = isset( $_POST['heading'] ) ? array_filter( $_POST['heading'] ) : array();
	$notices     = isset( $_POST['notice'] ) ? array_filter( $_POST['notice'] ) : array();
	// $options['notice'] = isset( $_POST['notice'] ) ? sanitize_textarea_field( $_POST['notice'] ) : '';

	$options['heading']     = array_map( 'sanitize_text_field', $headings );
	$options['notice']      = array_map( 'sanitize_text_field', $notices );
	$options['images']      = array_map( 'esc_url', $images );
	$options['information'] = array_map( 'sanitize_textarea_field', $information );
	$options                = array_filter( $options );

	if ( $options ) {
		update_term_meta( $cat_id, '_config_cat_option_dep', $options );
	} else {
		delete_term_meta( $cat_id, '_config_cat_option_dep' );
	}
}

$_deps = $object->get_meta( '_config_cat_option_dep' );

if ( ! is_array( $_deps ) ) {
	$_deps = array();
}

$steps = $object->steps();

if ( is_array( $steps ) ) {
	?>
    <style>
        .form-table td {
            vertical-align: top;
        }
    </style>
    <div id="mon-configure-steps">
        <form method="post" action="">
			<?php foreach ( $steps as $step ) { ?>
				<?php if ( ! is_array( $step ) ) { ?>
					<?php $field = $object->get_field( $step ); ?>
                    <h2><?php echo $field['title']; ?></h2>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><?php _e( 'Heading', 'monexterieur-configure' ); ?></th>
                            <td>
								<?php
								$value = isset( $_deps['heading'][ sanitize_title( $step ) ] ) ? $_deps['heading'][ sanitize_title( $step ) ] : '';
								?>
                                <input type="text" class="widefat"
                                       name="heading[<?php echo sanitize_title( $step ); ?>]"
                                       value="<?php echo $value; ?>"><br/>
                                <i><?php _e( 'Breadcrumb heading.', 'monexterieur' ); ?></i>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Notice', 'monexterieur-configure' ); ?></th>
                            <td>
                                <input type="text" class="widefat" name="notice[<?php echo sanitize_title( $step ); ?>]"
                                       value="<?php echo isset( $_deps['notice'][ sanitize_title( $step ) ] ) ? $_deps['notice'][ sanitize_title( $step ) ] : ''; ?>"><br/>
                                <i><?php _e( 'Step notice/conditions (*).', 'monexterieur' ); ?></i>

                            </td>
                        </tr>
                        </tbody>
                    </table>
					<?php if ( 'group' == $field['type'] ) { ?>
                        <table class="form-table">
                            <tbody>
							<?php foreach ( $field['options'] as $key => $options ) { ?>

								<?php if ( ! is_array( $options ) ) { ?>
                                    <tr>
                                        <th><?php echo $options; ?></th>
                                        <td>
                                            <p><strong><?php _e( 'Image', 'monexterieur-configure' ); ?></strong></p>
											<?php
											$name = sanitize_title( $options );
											if ( in_array( $step, array( 'color_lame', 'color_decorative' ) ) ) {
												$name = "{$step}_{$name}";
											}

											$value = isset( $_deps['images'][ $name ] ) ? $_deps['images'][ $name ] : '';
											?>
                                            <input type="text" value="<?php echo $value; ?>" readonly
                                                   name="configimages[<?php echo $name; ?>]">
                                            <button type="button" <?php echo ( $value ) ? 'style="display: none;"' : ''; ?>
                                                    class="mon_cofigure_upload_image_button"><?php _e( 'Update Image', 'monexterier-configure' ); ?></button>
                                            <button type="button" <?php echo ( $value ) ? '' : 'style="display: none;"'; ?>
                                                    class="mon_cofigure_remove_image_button"><?php _e( 'Remove Image', 'monexterier-configure' ); ?></button>

											<?php if ( $value ) { ?>
                                                <img class="true_pre_image" src="<?php echo $value; ?>"
                                                     style="width:300px; height: auto; display:block;"/>
											<?php } ?>
                                        </td>
                                        <td>
                                            <p><strong><?php _e( 'Information', 'monexterieur-configure' ); ?></strong>
                                            </p>
											<?php
											$content = isset( $_deps['information'][ $name ] ) ? $_deps['information'][ $name ] : '';
											//This function adds the WYSIWYG Editor
											wp_editor(
												$content,
												$name,
												array(
													"media_buttons" => false,
													'textarea_name' => 'information[' . $name . ']'
												)
											);
											?>
                                        </td>
                                    </tr>
								<?php } else { ?>
                                    <tr>
                                        <th><?php echo $options['title']; ?></th>
										<?php
										$value = isset( $_deps['images'][ sanitize_title( $options['title'] ) ] ) ? $_deps['images'][ sanitize_title( $options['title'] ) ] : '';
										?>
                                        <td>
                                            <p><strong><?php _e( 'Image', 'monexterieur-configure' ); ?></strong></p>

                                            <input type="text" value="<?php echo $value; ?>" readonly
                                                   name="configimages[<?php echo sanitize_title( $options['title'] ); ?>]">
                                            <button type="button" <?php echo ( $value ) ? 'style="display: none;"' : ''; ?>
                                                    class="mon_cofigure_upload_image_button"><?php _e( 'Update Image', 'monexterier-configure' ); ?></button>
                                            <button type="button" <?php echo ( $value ) ? '' : 'style="display: none;"'; ?>
                                                    class="mon_cofigure_remove_image_button"><?php _e( 'Remove Image', 'monexterier-configure' ); ?></button>

											<?php if ( $value ) { ?>
                                                <img class="true_pre_image" src="<?php echo $value; ?>"
                                                     style="width:300px; height: auto; display:block;"/>
											<?php } ?>
                                        </td>
                                        <td>
                                            <p><strong><?php _e( 'Information', 'monexterieur-configure' ); ?></strong>
                                            </p>
											<?php
											$content = isset( $_deps['information'][ sanitize_title( $options['title'] ) ] ) ? $_deps['information'][ sanitize_title( $options['title'] ) ] : '';
											//This function adds the WYSIWYG Editor
											wp_editor(
												$content,
												sanitize_title( $options['title'] ),
												array(
													"media_buttons" => false,
													'textarea_name' => 'information[' . sanitize_title( $options['title'] ) . ']'
												)
											);
											?>
                                        </td>
                                    </tr>
									<?php
								}
							}
							?>
                            </tbody>
                        </table>
					<?php } ?>
				<?php } else { ?>
                    <h3><?php echo $step['title']; ?></h3>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><?php _e( 'Heading', 'monexterieur-configure' ); ?></th>
                            <td>
								<?php
								$value = isset( $_deps['heading'][ sanitize_title( $step['title'] ) ] ) ? $_deps['heading'][ sanitize_title( $step['title'] ) ] : '';
								?>
                                <input type="text" class="widefat"
                                       name="heading[<?php echo sanitize_title( $step['title'] ); ?>]"
                                       value="<?php echo $value; ?>"><br/>
                                <i><?php _e( 'Breadcrumb heading.', 'monexterieur' ); ?></i>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Notice', 'monexterieur-configure' ); ?></th>
                            <td>
                                <input type="text" class="widefat"
                                       name="notice[<?php echo sanitize_title( $step['title'] ); ?>]"
                                       value="<?php echo isset( $_deps['notice'][ sanitize_title( $step['title'] ) ] ) ? $_deps['notice'][ sanitize_title( $step['title'] ) ] : ''; ?>"><br/>
                                <i><?php _e( 'Step notice/conditions (*).', 'monexterieur' ); ?></i>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="form-table">
                        <tbody>
						<?php
						foreach ( $step['subgroup'] as $subgroup ) {
							$field = $object->get_field( $subgroup );
							?>
                            <tr>
                                <th><?php echo $field['title']; ?></th>
                                <td></td>
                            </tr>
							<?php
						}
						?>
                        </tbody>
                    </table>
				<?php } // end check $step['type'] ?>
			<?php } // end foreach $steps ?>
			<?php wp_nonce_field( '_mon_configure_option', 'mon_configure_option' ); ?>
            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>

    <script type="text/javascript">
        jQuery(function ($) {
            /*
			 * Select/Upload image(s) event
			 */
            $('body').on('click', '.mon_cofigure_upload_image_button', function (e) {
                e.preventDefault();

                var button = $(this),
                    custom_uploader = wp.media({
                        title: 'Insert image',
                        library: {
                            // uncomment the next line if you want to attach image to the current post
                            // uploadedTo : wp.media.view.settings.post.id,
                            type: 'image'
                        },
                        button: {
                            text: 'Use this image' // button label text
                        },
                        multiple: false // for multiple image selection set to true
                    }).on('select', function () { // it also has "open" and "close" events
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $(button).hide().siblings('input').val(attachment.url).siblings('.mon_cofigure_remove_image_button').show().parent().append('<img class="true_pre_image" src="' + attachment.url + '" style="width:300px; height: auto; display:block;" />');
                        /* if you sen multiple to true, here is some code for getting the image IDs
						var attachments = frame.state().get('selection'),
							attachment_ids = new Array(),
							i = 0;
						attachments.each(function(attachment) {
							 attachment_ids[i] = attachment['id'];
							console.log( attachment );
							i++;
						});
						*/
                    })
                        .open();
            });

            /*
             * Remove image event
             */
            $('body').on('click', '.mon_cofigure_remove_image_button', function () {
                $(this).hide().siblings('input').val('').siblings('.mon_cofigure_upload_image_button').show().siblings('img').remove();
                return false;
            });
        });
    </script>
	<?php
}