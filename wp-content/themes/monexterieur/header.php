<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mon_Exterieur
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text"
       href="#content"><?php esc_html_e( 'Skip to content', 'monexterieur' ); ?></a>

    <header id="masthead" class="site-header">
        <div class="container clearfix">
            <div class="nav-col-wrap clearfix">

                <div class="top-header-wrap row clearfix">

                    <div class="site-branding col">
						<?php
						the_custom_logo();
						if ( is_front_page() && is_home() ) :
							?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                   rel="home"><?php bloginfo( 'name' ); ?></a>
                            </h1>
						<?php
						else :
							?>
                            <p class="site-title">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                   rel="home"><?php bloginfo( 'name' ); ?></a>
                            </p>
						<?php
						endif;
						$monexterieur_description = get_bloginfo( 'description', 'display' );
						if ( $monexterieur_description || is_customize_preview() ) :
							?>
                            <p class="site-description"><?php echo $monexterieur_description; /* WPCS: xss ok. */ ?></p>
						<?php endif; ?>
                    </div><!-- .site-branding -->

                    <div class="search-wrap col">
						<?php
                        $search_for = filter_input( INPUT_GET, 's' );
                        ?>
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <label>
                                <span class="screen-reader-text"><?php _e( 'Recherche', 'monexterieur' ); ?></span>
                                <input type="search" class="search-field" placeholder="<?php _e( 'Recherche', 'monexterieur' ); ?>" value="<?php echo ( $search_for ) ? esc_attr( $search_for ) : ''; ?>" name="s">
                            </label>
                            <input type="submit" class="search-submit" value="<?php _e( 'Recherche', 'monexterieur' ); ?>">
                        </form>
                    </div><!-- .search-wrap -->

                        <div class="location-link col">
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                                <span class="cart-before-icon">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/cart.svg" />
                                    <span class="cart-item-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                </span>

								<span><?php _e( 'Panier', 'monexterieur' ); ?> <span class="show-mobile cart-item-count-mobile"><?php echo WC()->cart->get_cart_contents_count(); ?></span></span>

                                <span class="cart-after-icon">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/cart.svg" />
                                    <span class="cart-item-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>

                                </span>
							</a>
                        </div><!-- .location-link -->

                </div><!-- .top-header-wrap -->

                <div class="bottom-nav-wrap">
                    <nav id="site-navigation" class="main-navigation">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'walker'         => new ME_Menu_Walker()
						) );
						?>
                    </nav><!-- #site-navigation -->

                </div><!-- .bottom-nav-wrap -->

            </div><!-- .nav-col-wrap -->

            <?php
            if ( function_exists( 'monexterieur_configure_get_configurator_page' ) ) {
	            if ( $configure = monexterieur_configure_get_configurator_page() ) { ?>
                    <a class="configure-wrap" href="<?php echo esc_url( get_permalink( $configure ) ); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/toolbox.svg" alt="Configurateur">
                        <span><?php _e( 'Configurateur', 'monexterieur' ); ?></span>
                    </a><!-- .configure-wrap -->
		            <?php
	            }
            }
            ?>

            <div class="ham-icon">
                <div class="ham-wrap">
                    <img class="menu-bar" src="<?php echo get_template_directory_uri();?>/images/menu.svg"
                         alt="">
                    <img class="menu-cross" src="<?php echo get_template_directory_uri();?>/images/close.svg"
                         alt="">
                </div>
            </div>
            <div class="mobile-nav-wrap">
                <div class="mobile-menu">
                    <div class="mobile-logo">
                        <?php
                        the_custom_logo();
                        if ( is_front_page() && is_home() ) :
                            ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                   rel="home"><?php bloginfo( 'name' ); ?></a>
                            </h1>
                        <?php
                        else :
                            ?>
                            <p class="site-title">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                   rel="home"><?php bloginfo( 'name' ); ?></a>
                            </p>
                        <?php
                        endif;
                        $monexterieur_description = get_bloginfo( 'description', 'display' );
                        if ( $monexterieur_description || is_customize_preview() ) :
                            ?>
                            <p class="site-description"><?php echo $monexterieur_description; /* WPCS: xss ok. */ ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="menu-inner">
                        <nav id="site-navigation" class="main-navigation">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'menu-1',
                                'menu_id'        => 'primary-menu',
                                'walker'         => new ME_Menu_Walker()
                            ) );
                            ?>
                        </nav><!-- #site-navigation -->
                    </div>
                    <div class="search-inner">
                        <div class="search-wrap">
                            <?php // get_search_form(); ?>
                            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <label>
                                    <span class="screen-reader-text"><?php _e( 'Recherche', 'monexterieur' ); ?></span>
                                    <input type="search" class="search-field" placeholder="<?php _e( 'Rechercher dans Mon Extérieur', 'monexterieur' ); ?>" value="" name="s">
                                </label>
                                <input type="submit" class="search-submit" value="<?php _e( 'Recherche', 'monexterieur' ); ?>">
                            </form>
                        </div><!-- .search-wrap -->
                    </div>
                </div>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">
