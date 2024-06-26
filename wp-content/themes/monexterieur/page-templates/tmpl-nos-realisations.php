<?php
/**
 * Template Name: Nos Réalisations
 */
get_header();

while ( have_posts() ) {
	the_post();
	?>
    <div class="fake-height"></div>

	<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

    <section class="section page-container nos-realisations-wrapper">
        <div class="container" id="realisations-content">
			<?php
			$realisations = new WP_Query(
				array(
					'post_type'      => 'realisations',
					'post_status'    => 'publish',
					'posts_per_page' => 3,
					'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1
				)
			);

			if ( $realisations->have_posts() ) {
				while ( $realisations->have_posts() ) {
					$realisations->the_post();
					get_template_part( 'template-parts/tmpl-nos-realisations/layout', 'one' );
					// $counter += 1;
				} // end loop $realisations
				wp_reset_postdata();

				// pagination
				if ( $next_page = get_next_posts_link( __( 'AFFICHER PLUS', 'monexterieur' ), $realisations->max_num_pages ) ) {
					?>
                    <div class="load-more" id="realisations-pagination">
						<?php echo $next_page; ?>
                    </div>
					<?php
				} // end check $next_page

			} // end check $realisations
			?>
        </div>

    </section>
	<?php
}
get_footer();
