<?php if ( $hero_category = get_sub_field( 'section_hero_product_categories' ) ) { ?>
    <section class="hero">
        <div class="hero-slider">

			<?php
			foreach ( $hero_category as $category ) {
				$banner = get_field( 'product_category_banner_image', $category );
				?>
                <div class="hero-slider-inner"
                     style="background: url('<?php echo isset( $banner['sizes']['product-cat-banner'] ) ? $banner['sizes']['product-cat-banner'] : $banner['url']; ?>') no-repeat center; background-size: cover">
                    <div class="container">
                        <div class="hero-content">
                            <h2><?php echo $category->name; ?></h2>
                            <p><?php echo $category->description; ?></p>
                            <a href="<?php echo esc_url( get_term_link( $category, $category->taxonomy ) ); ?>" class="link"><?php _e( 'EN SAVOIR PLUS', 'monexterieur' ); ?>
                                <img class="svg" src="<?php echo get_template_directory_uri(); ?>/images/arrow-right-line.svg" alt=""></a>
                        </div>
                    </div>
                </div><!-- .hero-slider-inner -->
			<?php } // end loop $her_category ?>

        </div><!-- .hero-slider -->
    </section><!-- .hero -->
<?php } //  end get_sub_field "section_hero_product_categories" ?>