<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Footer logo Widget
	Description: A widget for Footer logo
	Version: 1.0

-----------------------------------------------------------------------------------*/


// load widget
add_action( 'widgets_init', 'mts_logo_widgets' );

// Register widget
function mts_logo_widgets() {
	register_widget( 'mts_logo_Widget' );
}

// Widget class
class mts_logo_widget extends WP_Widget {


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
function __construct() {

	// Widget settings
	$widget_ops = array(
		'classname' => 'mts_logo_widget',
		'description' => __('A widget for Footer Logo', 'mythemeshop')
	);

	// Widget control settings
	$control_ops = array(
		'width' => 182,
		'height' => 28,
		'id_base' => 'mts_logo_widget'
	);

	// Create the widget
	parent::__construct( 'mts_logo_widget', __('MyThemeShop: Footer Logo', 'mythemeshop'), $widget_ops, $control_ops );
	
}

/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/
	
function widget( $args, $instance ) {
	extract( $args );

	// Variables from the widget settings
	$title = apply_filters('widget_title', $instance['title'] );
	$flogo = $instance['flogo'];
	$link = $instance['link'];

	// Before widget (defined by theme functions file)
	echo $before_widget;

	// Display the widget title if one was input
	if ( $title )
		echo $before_title . $title . $after_title;
		
	// Display a containing div       

	echo '<div class="logo-wrap">';

	// Display Ad
	if ( $link )
		echo '<a href="' . esc_url( $link ) . '"><img src="' . esc_attr( $flogo ) . '" width="182" height="28" alt="" /></a>';
		
	elseif ( $flogo )
	 	echo '<img src="' . esc_attr( $flogo ) . '" width="182" height="28" alt="" />';
		
	echo '</div>';

	// After widget (defined by theme functions file)
	echo $after_widget;
}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/
	
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	// Strip tags to remove HTML (important for text inputs)
	$instance['title'] = strip_tags( $new_instance['title'] );

	// No need to strip tags
	$instance['flogo'] = $new_instance['flogo'];
	$instance['link'] = $new_instance['link'];

	return $instance;
}

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings (Displays the widget settings controls on the widget panel)
/*-----------------------------------------------------------------------------------*/
	
function form( $instance ) {

	// Set up some default widget settings
	$defaults = array(
		'title' => '',
		'flogo' => get_template_directory_uri()."/images/logo.png",
		'link' => 'http://mythemeshop.com/',
	);
	
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<!-- Widget Title: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mythemeshop') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
	</p>

	<!-- Ad image url: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'flogo' ); ?>"><?php _e('flogo image url:', 'mythemeshop') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'flogo' ); ?>" name="<?php echo $this->get_field_name( 'flogo' ); ?>" value="<?php echo esc_attr( $instance['flogo'] ); ?>" />
	</p>
	
	<!-- flogo link url: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e('Footer Logo link url:', 'mythemeshop') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>" />
	</p>
	
	<?php
	}
}
?>