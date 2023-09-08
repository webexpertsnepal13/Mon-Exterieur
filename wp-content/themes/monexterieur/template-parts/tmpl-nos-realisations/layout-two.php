<div class="col-sm-6">
	<?php if ( $gallery = get_field( 'realisations_image_gallery' ) ) { ?>
		<div class="realisations-gallery-slider">
			<?php foreach ( $gallery as $image ) { ?>
				<div class="gallery-item">
					<img src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>"
					     alt="<?php echo esc_attr( $image['alt'] ); ?>"/>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
<div class="col-sm-6">
	<div class="realisations-gallery-content">
		<?php the_content(); ?>
	</div>
</div>

