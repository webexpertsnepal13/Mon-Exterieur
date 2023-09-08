<?php if ( $nos_realisations = get_sub_field( 'section_realisation_contents' ) ) { ?>
    <section class="section page-container realisations-slider-wrapper">

		<?php if ( $headline = get_sub_field( 'section_realisation_headline' ) ) { ?>
            <div class="section-title">
                <h2><?php echo $headline; ?></h2>
            </div>
		<?php } // end get_sub_field "section_realisation_headline" ?>

        <div class="realisations-slider-inner">
            <div class="container-fluid">
                <div class="res-slider">
                    <div class="row align-items-center">
                        <div class="col-md-4">

                            <div class="res-slider-left">
                                <div class="slider-text-content">
									<?php
									global $post;
									foreach ( $nos_realisations as $post ) {
										setup_postdata( $post );
										?>
                                        <a href="<?php the_permalink(); ?>" class="slider-item">
                                            <h4><?php the_title(); ?></h4>
											<?php the_content(); ?>
                                        </a>
										<?php
									} // end loop $nos_realisations
									wp_reset_postdata();
									?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="res-slider-right">
								<?php
								global $post;
								foreach ( $nos_realisations as $post ) {
									setup_postdata( $post );
									?>
                                    <a href="<?php the_permalink(); ?>" class="slider-item">
                                        <div class="img-wrap">
											<?php
											if ( has_post_thumbnail() ) {
												the_post_thumbnail( 'nos-realisations', array( 'style' => 'width:100%' ) );
											}
											?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/E-white.svg"
                                                 alt="" class="img-logo">
                                        </div>
                                    </a>
									<?php
								} // end loop $nos_realisations
								wp_reset_postdata();
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--    realisations-slider-inner-->
    </section><!-- .realisations-slider-inner-->
<?php } // end get_sub_field "section_realisation_contents" ?>