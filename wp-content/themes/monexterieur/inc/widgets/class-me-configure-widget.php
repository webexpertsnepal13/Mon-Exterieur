<?php
/**
 * Configure Widget
 */

class ME_Configure_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
		// Base ID of your widget
			'me_configure_widget',
			// Widget name will appear in UI
			__( 'ME Configure Widget', 'monexeterieur' ),
			// Widget description
			array( 'description' => __( 'Sample widget.', 'monexeterieur' ), )
		);
	}

	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
        $link = ( isset( $instance['link'] ) && $instance['link'] ) ? $instance['link'] : 'javascript:void(0)';
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		?>
        <a href="<?php echo $link; ?>" class="side-brown-box">

            <img src="<?php echo get_template_directory_uri(); ?>/images/toolbox.svg" alt="mon-exterieur">
            <?php if( ! empty( $title ) ) { ?>
                <h5><?php echo $title; ?></h5>
            <?php
            }

            echo $instance['content'] ? '<p>' . nl2br( $instance['content'] ) . '</p>' : '';
            ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/full-e.svg" alt="mon-exterieur" class="full-e">
            <img class="side-arrow" src="<?php echo get_template_directory_uri(); ?>/images/arrow-right-line-white.svg" alt="">
        </a>
        <?php

		// This is where you run the code and display the output
		echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Configure', 'monexterieur' );
		}

		$content = isset( $instance['content'] ) ? $instance['content'] : '';
		$link = isset( $instance['link'] ) ? $instance['link'] : '';
		// Widget admin form
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'monexterieur' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content', 'monexterieur' ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>"
                      name="<?php echo $this->get_field_name( 'content' ); ?>"><?php echo $content; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link', 'monexterieur' ); ?></label>
            <input class="widefat" type="url" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $link; ?>" >
        </p>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['content'] = ( ! empty( $new_instance['content'] ) ) ? sanitize_textarea_field( $new_instance['content'] ) : '';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? esc_url( $new_instance['link'] ) : '';

		return $instance;
	}
}

// Register and load the widget
function me_configure_load_widget() {
	register_widget( 'ME_Configure_Widget' );
}

add_action( 'widgets_init', 'me_configure_load_widget' );