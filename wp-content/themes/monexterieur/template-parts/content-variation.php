<?php
// print_pre( $_POST );
if ( isset( $args['options'] ) ) {
	?>
    <strong><?php _e( 'Option Couleur', 'monexterieur' ); ?></strong>
	<?php
	foreach ( $args['options'] as $option ) {
		$term = get_term_by( 'slug', $option, $args['attribute'] );
		if ( ! $term ) {
			continue;
		}
		$color = get_field( 'mon_color_attribute', $term );
		?>
        <label for="">
            <input type="radio" style="background: <?php echo $color; ?>" name="mon_attribute_pa_color"
                   value="<?php echo $option; ?>"
				<?php checked( $args['selected'], $option ); ?>
            >
            <span></span>
        </label>
	<?php } // end foreach options loop ?>

    <div class="hidden" style="display: none;">
		<?php wc_dropdown_variation_attribute_options( $args ); ?>
    </div>
<?php } // end check color options ?>
