<?php
/**
 * Template Name: Livraison Transport
 */

get_header();

while( have_posts() ) {
	the_post();
	?>
	<div class="fake-height"></div>

	<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

	<section class="section page-container livraison-transport-wrapper">
		<div class="container">
            
            <?php if( have_rows( 'livrason_transport_contents' ) ) { ?>
                <?php while( have_rows( 'livrason_transport_contents' ) ) { the_row(); ?>
                    <div class="livraison-transport-indv">
                        <div class="title">
                            <h2><?php the_sub_field( 'livrason_transport_content_title' ); ?></h2>
                        </div>
                        <div class="clearfix">
                            <div class="img-wrap">
                                <?php if( $icon = get_sub_field( 'livrason_transport_content_icon' ) ) { ?>
                                    <img src="<?php echo $icon; ?>" alt="">
                                <?php } ?>
                            </div>
                            <div class="content">
                                <?php the_sub_field( 'livrason_transport_content_text' ); ?>
                            </div>
                        </div>
                    </div>
                <?php } // end while loop have_rows( 'livrason_transport_contents' ) ?>
            <?php } // end check have_rows('livrason_transport_contents' ) ?>

		</div>
	</section>
	<?php
}

get_footer();