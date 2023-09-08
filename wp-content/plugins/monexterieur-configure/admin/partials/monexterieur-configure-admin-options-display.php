<div class="wrap">
	<?php

	/**
	 * Provide a admin area view for the plugin
	 *
	 * This file is used to markup the admin-facing aspects of the plugin.
	 *
	 * @since      1.0.0
	 *
	 * @package    Monexterieur_Configure
	 * @subpackage Monexterieur_Configure/admin/partials
	 */

	$product_cats = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0
		)
	);

	$url         = add_query_arg( array( 'page' => 'configurature-options' ), admin_url( 'admin.php' ) );
	$current_tab = filter_input( INPUT_GET, 'tab' );
	$current_cat = '';
	?>
	<div class="wrap">
		<?php if ( $product_cats ) { ?>

			<div class="nav-tab-wrapper">
				<?php
				if ( ! $current_tab ) {
					if( 'uncategorized' == $product_cats[0]->slug ) {
						$current_tab = $product_cats[1]->slug;
					}
					else {
						$current_tab = $product_cats[0]->slug;
					}
				}

				foreach ( $product_cats as $category ) {
					if ( 'uncategorized' == $category->slug ) {
						 continue;
					}

					$classes = array( 'nav-tab', $current_tab );
					if ( $current_tab == $category->slug ) {
						$current_cat = $category;
						$classes[] = 'nav-tab-active';
					}
					?>
					<a href="<?php echo esc_url( add_query_arg( array( 'tab' => $category->slug ), $url ) ); ?>"
					   class="<?php echo implode( ' ', $classes ); ?>"><?php echo $category->name; ?></a>
				<?php } ?>
			</div>

			<div class="nav-tab-content">
				<?php
				require plugin_dir_path( dirname( __FILE__ ) ) . "partials/tab-configurature-options.php";
				?>
			</div>

		<?php } ?>
	</div>
</div>