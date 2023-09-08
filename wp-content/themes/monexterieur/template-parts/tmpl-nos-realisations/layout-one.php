<div class="row align-items-center">
    <div class="col-md-7">
		<?php if ( $gallery = get_field( 'realisations_image_gallery' ) ) { ?>
            <div class="realisations-gallery-slider">
				<?php
				foreach ( $gallery as $image ) {
					$caption = $image['caption'] ? $image['caption'] : '';
					?>
                    <div class="gallery-item">
                        <div class="img-wrap">
                            <div class="img" style="background-image: url('<?php echo esc_url( $image['url'] ); ?>')">
                            </div>
							<?php if ( isset( $image['caption'] ) && $image['caption'] ) { ?>
                                <div class="text-caption" title="<?php echo esc_attr( $image['caption'] ); ?>">
                                    <p><?php echo $image['caption']; ?></p>
                                </div>
							<?php } ?>
                        </div>
                    </div>
				<?php } ?>
            </div>
		<?php } ?>
    </div>
    <div class="col-md-5">
        <div class="realisations-gallery-content">
            <h3><?php the_title(); ?></h3>
			<?php the_content(); ?>
        </div>
    </div>
</div>
