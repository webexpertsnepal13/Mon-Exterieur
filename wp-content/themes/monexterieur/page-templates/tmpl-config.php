<?php
/**
 * Template Name: Config
 */

get_header();
?>
    <style>
        .config-breadcrumb .crumb > p {
            width: 100%;
        }
    </style>
    <div class="fake-height"></div>

    <?php echo do_shortcode( '[monexterieur_configurator]' ); ?>

<?php
get_footer();
