<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				the_post_navigation();

			endwhile; // End of the loop.
			?>

        </div>
    </section>

<?php
get_footer();
