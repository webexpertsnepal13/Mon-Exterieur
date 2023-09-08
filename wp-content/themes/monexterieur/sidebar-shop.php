<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mon_Exterieur
 */

if ( ! is_active_sidebar( 'shop-sidebar' ) ) {
	return;
}
?>

<aside id="shop-sidebar" class="widget-area" style="z-index: 99;">
    <div class="mobile-category-select">
        <img src="<?php echo get_template_directory_uri();?>/images/filter.svg"
             alt="" class="filter-ico">
        Afficher tout
        <img src="<?php echo get_template_directory_uri();?>/images/ic-filter-list.svg"
             alt="">
    </div>
    <div class="for-mobile">
        <div class="mobile-category-select">
            <img src="<?php echo get_template_directory_uri();?>/images/filter.svg"
                 alt="" class="filter-ico">
            Filtre des produit
            <img src="<?php echo get_template_directory_uri();?>/images/ic-filter-list.svg"
                 alt="">
        </div>
        <?php dynamic_sidebar( 'shop-sidebar' ); ?>
    </div>
</aside><!-- #secondary -->
