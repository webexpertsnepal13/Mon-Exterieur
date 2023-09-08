<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mon_Exterieur
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" style="z-index: 1; position: relative; background: #fff;">
        <div class="container">
            <div class="row">
                <?php dynamic_sidebar( 'footer-sidebar'); ?>
            </div>
        </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
    (function($) {
        // Trigger checkout update manually. 
        $( document.body ).on( 'change', 'input[name="extra-shipping"]', function() {
            $( document.body ).trigger( 'update_checkout' );
        });
    })(jQuery);
</script>

</body>
</html>
