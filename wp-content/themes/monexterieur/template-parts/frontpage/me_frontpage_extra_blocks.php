<?php if ( have_rows( 'section_extra_blocks' ) ) { ?>
<section class="section page-container project-block-section">
      <div class="container">
            <div class="row">
                  <?php
				while ( have_rows( 'section_extra_blocks' ) ) {
					the_row();
					$headline         = get_sub_field( 'section_extra_block_headline' );
					$detail           = get_sub_field( 'section_extra_block_detail' );
					$background_image = get_sub_field( 'section_extra_block_background_image' );
					$link_to          = get_sub_field( 'section_extra_block_link_to' );
					$new_tab          = ( isset( $link_to['target'] ) && '_blank' == $link_to['target'] ) ? 'target="_blank"' : '';
					?>
                  <div class="col-md-6">
                        <a href="<?php echo( isset( $link_to['url'] ) ? $link_to['url'] : 'javascript:void(0);' ); ?>"
                              class="project-block-indv" <?php echo $new_tab; ?>>
                              <?php if ( $detail ) { ?>
                              <div class="color-bg">
                                    <div class="inner">
                                          <?php if ( $headline ) { ?>
                                          <h3><?php echo $headline; ?></h3>
                                          <?php } ?>

                                          <?php if ( $detail ) { ?>
                                          <p><?php echo wp_trim_words( $detail , 30, '...' ); ?></p>
                                          <?php } ?>

                                          <div class="link">
                                                <p><?php _e( 'En savoir plus', 'monexterier' ); ?>
                                                      <img src="<?php echo get_template_directory_uri(); ?>/images/arrow-right-line.svg"
                                                            alt="">
                                                </p>
                                          </div>
                                    </div>
                              </div>
                              <?php
							} else {
								?>
                              <div class="img-wrap">
                                    <div class="center-ele">
                                          <img class="proj-logo"
                                                src="<?php echo get_template_directory_uri(); ?>/images/E-white.svg"
                                                alt="">
                                          <?php if ( $headline ) { ?>
                                          <h3><?php echo $headline; ?></h3>
                                          <?php } ?>
                                          <img class="arrow"
                                                src="<?php echo get_template_directory_uri(); ?>/images/arrow-right-line.svg"
                                                alt="">
                                    </div>
                                    <?php if ( $background_image ) { ?>
                                    <div class="img"
                                          style="background-image: url('<?php echo isset( $background_image['sizes']['fp-extra-block'] ) ? $background_image['sizes']['fp-extra-block'] : $background_image['url']; ?>');">
                                    </div>
                                    <?php } ?>
                              </div>
                              <?php
							}
							?>
                        </a>
                  </div>
                  <?php
				} // end loop section_extra_blocks
				?>
            </div>
      </div>
</section><!--    project-block-section-->
<?php } // end check section_extra_blocks ?>