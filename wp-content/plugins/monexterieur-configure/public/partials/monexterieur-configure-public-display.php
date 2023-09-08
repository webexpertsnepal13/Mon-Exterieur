<section class="section page-container config-wrapper">
    <div class="container">

        <div id="configure-steps-breadcrumb"></div>

		<?php
		if ( $product_cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) ) ) {
			?>
            <div class="config-title clearfix">
                <p><span data-place="0"><?php _e( 'Choisissez votre clôture !', 'monexterieur-configure' ); ?></span></p>
                <div class="right-floating">
                    <div class="flag">
                        <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI; ?>public/images/flag.png"
                             alt="">
                        <strong>Fabriqué<span>En France</span></strong>
                    </div>
                    <div class="rating">
                        <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI; ?>public/images/Image.png"
                             alt="">
                        <span>
                            <strong>4.6/5</strong>
                            <span>Sur Google</span>
                            <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI; ?>public/images/star.svg"
                                 alt="">
                        </span>
                    </div>
                </div>
            </div>
            <div class="config-step-mobile">
                <div class="count">1/6 <span>Ma clôture</span></div>
                <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI . 'public/images/list.svg'; ?>"
                     alt="">
            </div>

            <form name="configurateur" method="post" action="" id="configurateur-form">
                <div id="configure-options">
                    <h2 class="show-mobile"><?php _e( 'Choisissez votre clôture..', 'monexterieur-configure' ); ?></h2>
                    <div class="row cust-row" data-step="select-category">
						<?php
						foreach ( $product_cats as $category ) {
							if ( ! get_term_meta( $category->term_id, '_product_cat_config_class', true ) ) {
								continue;
							}

							$thumbnail    = '';
							$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
							if ( $thumbnail_id ) {
								$thumbnail = wp_get_attachment_image_url( $thumbnail_id, 'product-cat-thumb' );
							}

							$information = $category->description;
							?>
                            <div class="col-md-4 col-lg-5">
                                <div class="config-pro-indv">
                                    <label>
                                        <input type="radio" name="configure-cat"
                                               value="<?php echo $category->term_id; ?>">
                                        <div class="label-inner">
                                            <div class="img-wrap">
                                                <div class="img"
                                                     style="background-image: url('<?php echo $thumbnail; ?>');"></div>
                                            </div>
                                            <p><?php echo $category->name; ?></p>
                                        </div>
                                    </label>
									<?php if ( $information ) { ?>
                                        <a href="javascript:void(0);" class="me_btn custom-modal"
                                           data-target="<?php echo $category->slug; ?>">
											<?php _e( 'Plus d’informations', 'monexterieur-configure' ); ?>
                                        </a>
									<?php } ?>
                                </div>

								<?php if ( $information ) { ?>
                                    <!-- The Modal -->
                                    <div id="<?php echo sanitize_title( $category->slug ); ?>-custom-modal"
                                         class="modal">
                                        <div class="modal-content">
                                            <div class="modal-wrap">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="img-wrap">
                                                            <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI; ?>public/images/Group.svg"
                                                                 alt="">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="content">
															<?php echo apply_filters( 'the_content', $information ); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="close">
                                                    <img src="<?php echo MONEXTERIEUR_CONFIGURE_PLUGIN_URI; ?>public/images/close.svg"
                                                         alt="">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
								<?php } // end check $information ?>
                            </div>
						<?php } // end foreach ?>
                    </div>

                </div>
				<?php wp_nonce_field( 'configure_nonce', '_configure_nonce' ); ?>
            </form>
            <div class="continue">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="notice"><span data-place="0"></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-left" >
                        <a href="javascript:void(0);" class="me_btn disabled" style="border-radius: 5px; display: none;" id="configure-prev-step">
		                    <?php _e( 'Retour', 'monexterieur-configure' ); ?>
                        </a>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="#" class="me_btn disabled" style="border-radius: 5px" id="configure-next-step">
							<?php _e( 'continuer', 'monexterieur-configure' ); ?>
                        </a>
                    </div>
                </div>
            </div>
		<?php } // end get_terms for product_cat ?>

    </div>
</section>