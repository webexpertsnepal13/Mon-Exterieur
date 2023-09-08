<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mon_Exterieur
 */

get_header();
?>

    <div class="fake-height"></div>

<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

    <section class="section page-container archive-wrapper">
        <div class="container">
			<?php if ( have_posts() ) : ?>


				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					 * Include the Post-Type-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;

				the_posts_navigation();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

        </div>
    </section>

<?php
get_footer();
