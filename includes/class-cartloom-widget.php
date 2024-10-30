<?php
	

class Cartloom_Widget extends WP_Widget {
	
	/**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $Plugin_Name    The ID of this plugin.
   */
   
  protected $plugin_name = "Cartloom";

  
  /**
   * Register widget with WordPress.
   */
   
  function __construct() {

      $this->plugin_name = $this->plugin_name;

      parent::__construct( $this->plugin_name . '_widget', __( 'Cartloom Widget', $this->plugin_name ),
          array( 'description' => __( 'Widget for View Cart and Find Order', $this->plugin_name ), ) 
      );

  }

 
	// Creating widget front-end
	 
	public function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		$type = $instance['type']; 
		
		echo $args['before_widget'];
	
		if($type == 'lookup')
			
			echo '<button class="cartloom-lookup">'.$title.'</button>';
		 
		if($type == 'viewcart')
			
			echo '<button class="cartloom-viewcart">'.$title.'</button>';
	 
		echo $args['after_widget'];
		
	}
	         
	// Widget Backend 
	
	public function form( $instance ) {
		
		if ( isset( $instance[ 'title' ] ) ) { 
			
			$title = $instance[ 'title' ];
			
		} else {
			
		 $title = 'View Cart';
		 
		}
		
		if ( isset( $instance[ 'type' ] ) ) {
			
		$type = $instance[ 'type' ];
		
		}else {
			
			$type = 'viewcart';
		
		}
		
		// Widget admin form
		?>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" placeholder="View Cart" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'Type' ); ?>"><?php _e( 'Type:' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
			<option value="viewcart" <?php if( $type == 'viewcart') { echo 'selected="selected"'; } ?>>View Cart</option>
			<option value="lookup" <?php if( $type == 'lookup') { echo 'selected="selected"'; } ?>>Order Lookup</option>
		</select>
		</p>
		
		<?php 
	}
	     
	// Updating widget replacing old instances with new
	
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : ''
		;
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
		
		return $instance;
		
	}
	
	
} 


