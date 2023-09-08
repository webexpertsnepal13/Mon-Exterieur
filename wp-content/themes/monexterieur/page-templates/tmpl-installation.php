<?php
/**
 * Template Name: Installation
 */
get_header();

while ( have_posts() ) {
	the_post();
	?>
    <div class="fake-height"></div>

	<?php get_template_part( 'template-parts/content', 'breadcrumb' ); ?>

    <section class="section page-container installation-wrapper">
        <div class="container">
			<?php the_content(); ?>

			<?php if ( have_rows( 'installations_contents' ) ) { ?>
                <div class="installation-contents">
                    <div class="row">
						<?php
						while ( have_rows( 'installations_contents' ) ) {
							the_row();
							$title = get_sub_field( 'installations_content_title' );
							$file  = get_sub_field( 'installations_content_file' );
							$image = get_sub_field( 'installations_content_image' );

							$image_data['url'] = $image['url'];
							$image_data['width'] = $image['width'];
							$image_data['height'] = $image['height'];

							if( isset( $image['sizes']['notice-installation'] ) ) {
								$image_data['url'] = $image['sizes']['notice-installation'];
								$image_data['width'] = $image['sizes']['notice-installation-width'];
								$image_data['height'] = $image['sizes']['notice-installation-height'];
                            }
							?>
                            <div class="col-md-4">
                                <div class="installation">
                                    <a href="<?php echo $file; ?>">
                                        <div class="img-wrap">
                                            <div class="img" style="background-image:url('<?php echo $image_data['url']; ?>') "></div>
                                        </div>
                                        <div class="installation-title">
                                            <h2><?php echo $title; ?></h2>
                                            <svg xmlns="http://www.w3.org/2000/svg" id="arrow-right-line" width="31.183"
                                                 height="31.183" viewBox="0 0 31.183 31.183">
                                                <path id="Path_19" data-name="Path 19" d="M31.183,0H0V31.183H31.183Z"
                                                      fill="none"></path>
                                                <path id="Path_20" data-name="Path 20"
                                                      d="M19.815,13.029H4v2.6H19.815L12.846,22.6l1.837,1.837L24.789,14.328,14.683,4.222,12.846,6.059Z"
                                                      transform="translate(1.197 1.264)" fill="#1f1f1f"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
						<?php } // end while loop ?>
                    </div>
                </div>
			<?php } // end check rows ?>

        </div>
    </section>
	<?php
}

get_footer();
