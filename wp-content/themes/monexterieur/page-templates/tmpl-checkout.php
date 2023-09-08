<?php
/**
 * Template Name: Checkout
 */

get_header();
?>
	<div class="fake-height"></div>

<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

	<section class="section page-container checkout-wrapper">
		<div class="container">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

			endwhile; // End of the loop.
			?>
		</div>
	</section>
<?php
get_footer();
