<?php if ( $product_group = get_sub_field( 'section_product_categories' ) ) { ?>
    <section class="section page-container products-list-wrapper">
        <div class="container">
            <div class="section-title">
				<?php if ( $headline = get_sub_field( 'section_product_headline' ) ) { ?>
                    <h4><?php echo $headline; ?></h4>
				<?php } // end get_sub_field "section_product_headline" ?>
            </div><!--  .section-title-->
            <div class="product-slider-wrap">


				<?php
				foreach ( $product_group as $group ) {
					$thumbnail    = '';
					$thumbnail_id = get_term_meta( $group->term_id, 'thumbnail_id', true );
					if ( $thumbnail_id ) {
						$thumbnail = wp_get_attachment_image_url( $thumbnail_id, 'product-cat-thumb' );
					}
					?>
                        <a href="<?php echo esc_url( get_term_link( $group, $group->taxonomy ) ); ?>"
                           class="product-indv">
                            <div class="img-wrap">
                                <div class="bg"
                                     style="background: url('<?php echo $thumbnail; ?>') no-repeat center;"></div>
                                <h5><?php echo $group->name; ?></h5>
                            </div>
							<?php if ( $group->description ) { ?>
                                <p><?php echo $group->description; ?></p>
							<?php } ?>
                        </a>
				<?php } // end loop $product_group ?>
            </div><!-- .product slider wrap -->
        </div><!--    .container-->
    </section><!--    .products-list-wrapper-->
<?php } // end get_sub_field "section_product_categories" ?>