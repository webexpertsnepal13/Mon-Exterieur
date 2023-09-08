<?php
/**
 * Template Name: Contact
 */
get_header();

while ( have_posts() ) {
	the_post();
	?>
    <div class="fake-height"></div>

	<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

    <section class="section page-container contact-wrapper">
        <div class="container">
            <div class="row">
                
				<?php if ( $contact_form = get_field( 'contact_form_shortcode' ) ) { ?>
                    <div class="col-md-6">
                        <div class="form-wrapper">
							<?php echo do_shortcode( $contact_form ); ?>
                        </div>
                    </div>
				<?php } ?>

				<?php if ( $contact_map = get_field( 'contact_google_map_iframe' ) ) { ?>
                    <div class="col-md-6">
                        <div class="map-wrap">
                            <?php echo $contact_map; ?>
                        </div>
                    </div>
				<?php } ?>

            </div>
        </div>
    </section>

	<?php
}

get_footer();