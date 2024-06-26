<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Mon_Exterieur
 */

get_header();
?>

    <div class="fake-height"></div>

<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

    <section class="section page-container error-404-wrapper">
        <div class="container">

            <section class="error-404 not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'monexterieur' ); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <p><?php esc_html_e( 'It looks like nothing was found at this location.', 'monexterieur' ); ?></p>

					<?php
					//get_search_form();

					//the_widget( 'WP_Widget_Recent_Posts' );
					?>

                    <div class="widget widget_categories">
                        <h2 class="widget-title"><?php //esc_html_e( 'Most Used Categories', 'monexterieur' ); ?></h2>
                        <ul>
							<?php
							//wp_list_categories( array(
//								'orderby'    => 'count',
//								'order'      => 'DESC',
//								'show_count' => 1,
//								'title_li'   => '',
//								'number'     => 10,
							//) );
							?>
                        </ul>
                    </div><!-- .widget -->

					<?php
					/* translators: %1$s: smiley */
					//$monexterieur_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'monexterieur' ), convert_smilies( ':)' ) ) . '</p>';
					//the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$monexterieur_archive_content" );

					//the_widget( 'WP_Widget_Tag_Cloud' );
					?>

                </div><!-- .page-content -->
            </section><!-- .error-404 -->

        </div>
    </section>

<?php
get_footer();
