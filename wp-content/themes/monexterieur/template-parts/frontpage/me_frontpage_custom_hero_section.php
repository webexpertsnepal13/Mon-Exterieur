<?php if ( have_rows( 'section_custom_hero_sections' ) ) { ?>
    <section class="hero">
        <div class="hero-slider">
			<?php
			while ( have_rows( 'section_custom_hero_sections' ) ) {
				the_row();
				$banner      = get_sub_field( 'section_custom_hero_section_background_image' );
				$title       = get_sub_field( 'section_custom_hero_section_title' );
				$description = get_sub_field( 'section_custom_hero_section_small_description' );
				$linkTo      = get_sub_field( 'section_custom_hero_section_link_to' );
				?>
                <div class="hero-slider-inner"
                     style="background: url('<?php echo $banner ? esc_url( $banner ) : ''; ?>') no-repeat center; background-size: cover">
                    <div class="container">
                        <div class="hero-content">
                            <h2><?php echo esc_html( $title ); ?></h2>
                            <?php echo $description; ?>
							<?php if ( $linkTo ) { ?>
                                <a href="<?php echo esc_url( $linkTo ); ?>"
                                   class="link"><?php _e( 'EN SAVOIR PLUS', 'monexterieur' ); ?>
                                    <img class="" style="display: inline-block;"
                                         src="<?php echo get_template_directory_uri(); ?>/images/arrow-right-line-green.svg"
                                         alt="">
                                </a>
							<?php } ?>
                        </div>
                    </div>
                </div><!-- .hero-slider-inner -->
			<?php } // end loop $her_category ?>

        </div><!-- .hero-slider -->
    </section><!-- .hero -->
<?php } //  end get_sub_field "section_hero_product_categories" ?>