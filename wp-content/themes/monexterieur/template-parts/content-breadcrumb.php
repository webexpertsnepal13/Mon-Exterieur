<section class="inner-banner page-container">
    <div class="banner-wrap"
         style="background-color: #4D302B; background-image: url('<?php echo get_template_directory_uri(); ?>/images/inner-banner.png')">
        <div class="container">
            <div class="banner-title">
                <h1>
					<?php
					$title     = '';
					$sub_title = '';
					if ( is_shop() ) {
						$title     = __( 'Nos Produits', 'monexterieur' );
						$sub_title = 'Boutique';
					} else if ( is_archive() ) {
						$object = get_queried_object();
						$title  = $object->name;
					} else if ( is_product() ) {
						$sub_title = get_the_title();
						$title     = __( 'Nos Produits', 'monexterieur' );
					} else if ( is_search() ) {
						$title = __( 'Recherche', 'monexterieur' );
						if ( isset( $_GET['s'] ) ) {
							$title .= ': ' . esc_attr( $_GET['s'] );
						}
					} else {
						$title = get_the_title();
					}

					echo $title;
					?>
                </h1>
                <div class="breadcrumb">
                    <a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Accueil', 'monexterieur' ); ?></a>
					<?php if ( is_product() ) { ?>
                        > <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
							<?php
							$shop_page_id = wc_get_page_id( 'shop' );
							if ( $shop_page_id ) {
								echo get_the_title( $shop_page_id );
							}
							?>
                        </a>
					<?php } ?>
                    > <span>
                        <?php
                        echo ( $sub_title ) ? $sub_title : $title;
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>