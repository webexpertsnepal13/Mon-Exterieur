<?php
/**
 * Template Name: Notre Magasin
 */

get_header();

while( have_posts() ) {
	the_post();
	?>
	<div class="fake-height"></div>

	<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

	<section class="section page-container notre-magasin-wrapper">
		<div class="container">
            <?php the_content();?>
		</div>
	</section>
	<?php
}

get_footer();