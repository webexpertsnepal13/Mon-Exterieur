<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Mon_Exterieur
 */

get_header();
?>

    <div class="fake-height"></div>

<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

    <section class="section page-container search-wrapper">
        <div class="container">
			<?php
			if ( have_posts() ) {
				?>
                <div class="row" id="shop-product-contain">
					<?php
					/* Start the Loop */
					while ( have_posts() ) {
						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						if ( 'product' == get_post_type() ) {
							wc_get_template_part( 'content', 'product' );
						} else {
							get_template_part( 'template-parts/content', 'search' );
						}

					}
					?>

					<?php if ( $next_page = get_next_posts_link( __( 'AFFICHER PLUS', 'monexterieur' ) ) ) { ?>
                        <div class="col-12 woocommerce-pagination" style="width: 100%;">
                            <?php echo $next_page; ?>
                        </div>
					<?php } ?>
                </div>
				<?php
			} else {
				get_template_part( 'template-parts/content', 'none' );
			}
			?>
        </div>
    </section>

<?php
get_footer();
