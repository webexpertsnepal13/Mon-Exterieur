<?php get_header(); ?>
    <div class="fake-height"></div><!-- .fake-height -->

<?php
// Check value exists.
if ( have_rows( 'me_frontpage_section' ) ) {

	// Loop through rows.
	while ( have_rows( 'me_frontpage_section' ) ) {
		the_row();

		get_template_part( 'template-parts/frontpage/' . get_row_layout() );
	}
}

get_footer();