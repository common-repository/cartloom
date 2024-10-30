<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.yabdab.com
 * @since      1.0.0
 *
 * @package    Cartloom
 * @subpackage Cartloom/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cartloom
 * @subpackage Cartloom/public
 * @author     Yabdab Inc. <mike@cartloom.com>
 */
 
class Cartloom_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	 
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	 
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	 
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		
		$this->version = $version;
		
		add_action( 'widgets_init', array( $this, 'register_widget_name' ) );

	}
	
	/**
   * Register the Cartloom Widget
   *
   * @since   1.0.0
   *
   **/
   
  public function register_widget_name() {

      register_widget('cartloom_widget');

  }
    
    

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	 
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cartloom-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	 
	public function enqueue_scripts() {

		$cart_name = cmb2_get_option( 'cartloom_plugin_settings', 'cart_name' );

		wp_enqueue_script( $this->plugin_name, 'https://' . $cart_name .'.cartloom.com/cart.js' , array( 'jquery' ), $this->version, true );

	}
	
	/**
	 * Register all shortcodes
	 *
	 * @since    1.0.0
	 */
	 
	public function register_shortcodes() {
		
		add_shortcode('cl_button', array($this,'button_shortcode'));
		
		add_shortcode('cl_product', array($this,'product_shortcode'));
		
		add_shortcode('cl_group', array($this,'product_group_shortcode'));
		
		add_shortcode('cl_lookup', array($this,'lookup_shortcode'));
		
		add_shortcode('cl_viewcart', array($this,'viewcart_shortcode'));		
		
	}
	
	/**
	 * Build output for Buy Button shortcode.
	 *
	 * @since    1.0.0
	 */
	
	public function button_shortcode( $args, $content="") {
	

	  $args = array_change_key_case((array)$args, CASE_LOWER);
	
		$cartname = (isset($args['cartname'])) ? $args['cartname'] : '';
		
		$id = (isset($args['id'])) ? $args['id'] : '';
		
		$style = (isset($args['style'])) ? $args['style'] : 'styled'; 
			
		if(!strlen($cartname) || !strlen($id) )
		
				return '<p>Error! Missing Required Arguments cartname or id</p>';
		
		$output = '
	<!--// CartLoom Buy Button //-->
	<iframe class="cartloom-iframe cartloom-buy-' . $id . '" allowtransparency=true frameBorder=0 src="https://' . $cartname . '.cartloom.com/buy/frame/styled/' . $id . '" scrolling="no" width=100%></iframe>
	';
			
		return $output;
		
	}
	
	
	/**
	 * Build output for Product Embed shortcode.
	 *
	 * @since    1.0.0
	 */
	
	public function product_shortcode($args, $content="")
	{
		
		// normalize attribute keys, lowercase
	  $args = array_change_key_case((array)$args, CASE_LOWER);
	
		$cartname = (isset($args['cartname'])) ? $args['cartname'] : '';
		$id = (isset($args['id'])) ? $args['id'] : '';
		
		if(!strlen($cartname) || !strlen($id) )
			return '<p>Error! Missing Required Arguments cartname or id</p>';
			
		$output = '
	<!--// CartLoom Product Group  //-->
	<iframe class="cartloom-iframe cartloom-product-' . $id . '" allowtransparency=true frameBorder=0 src="https://' . $cartname . '.cartloom.com/product/embed/styled/'. $id . '" scrolling="no" width=100%></iframe>  
	 ';
	
	 
	 // return our results/html
		return $output;
	 
	 
	}
	
	/**
	 * Build output for Product Group (Store) shortcode.
	 *
	 * @since    1.0.0
	 */
	
	public function product_group_shortcode($args, $content="")
	{
		
		// normalize attribute keys, lowercase
	  $args = array_change_key_case((array)$args, CASE_LOWER);
	
		$cartname = (isset($args['cartname'])) ? $args['cartname'] : '';
		$id = (isset($args['id'])) ? $args['id'] : ''; // group id
		
		if(!strlen($cartname) || !strlen($id) )
			return '<p>Error! Missing Required Arguments cartname or id</p>';
			
		$output = '
	<!--// CartLoom Product Group  //-->
	<iframe class="cartloom-iframe cartloom-store cartloom-store-' . $id . '" allowtransparency=true frameBorder=0 src="https://' . $cartname . '.cartloom.com/store/'. $id . '" scrolling="no" width=100%></iframe>  
	 ';
	 
	 // return our results/html
		return $output;
	 
	 
	}
	
	/**
	 * Build output for Order Lookup Button shortcode.
	 *
	 * @since    1.0.0
	 */
	
	public function lookup_shortcode($args, $content="")
	{
		
	  $args = array_change_key_case((array)$args, CASE_LOWER);
	
		$cartname = (isset($args['cartname'])) ? $args['cartname'] : '';
		
		$style = (isset($args['style'])) ? $args['style'] : 'styled';
		
		if(!strlen($cartname))
		
			return '<p>Error! Missing Required Arguments cartname</p>';
			
		$output = '
	<!--// CartLoom Order Lookup //-->
	<iframe class="cartloom-iframe cartloom-lookup" allowtransparency=true frameBorder=0 src="https://' . $cartname . '.cartloom.com/order/embed/lookup" scrolling="no" width=100%></iframe>';
	 

		return $output;
	 
	 
	}
	
	/**
	 * Build output for View Cart Button shortcode.
	 *
	 * @since    1.0.0
	 */
	  
	public function viewcart_shortcode($args, $content="")
	{
		
	  $args = array_change_key_case((array)$args, CASE_LOWER);
	
		$cartname = (isset($args['cartname'])) ? $args['cartname'] : '';
		
		$style = (isset($args['style'])) ? $args['style'] : 'styled';
		
		if(!strlen($cartname))
		
			return '<p>Error! Missing Required Arguments cartname </p>';
			
		$output = '
	<!--// CartLoom View Cart //-->
	<iframe class="cartloom-iframe cartloom-viewcart" allowtransparency=true frameBorder=0 src="' . $cartname .  'cart/embed/viewcart" scrolling="no" width=100%></iframe>';
	 

		return $output;
	 
	 
	}
	
	/**
	 * Append View Cart and/or Order Lookup to Main Nav Items.
	 *
	 * @since    1.0.0
	 */
	 
	function nav_items( $items ) {
	
		$vc_menu = cmb2_get_option( 'cartloom_plugin_settings', 'view_cart_menu' );
		
		$vc_text = cmb2_get_option( 'cartloom_plugin_settings', 'view_cart_text' );
		
		$lu_menu = cmb2_get_option( 'cartloom_plugin_settings', 'lookup_menu' );
		
		$lu_text = cmb2_get_option( 'cartloom_plugin_settings', 'lookup_text' );
		
	  
	  if($vc_menu)
	  {
		  $link = array (
		        'title'            => $vc_text,
		        'menu_item_parent' => '',
		        'ID'               => 'cartloom-viewcart',
		        'db_id'            => '',
		        'url'              => '#',
		        'classes' => ['cartloom-viewcart']
		    );
		
		  $items[] = (object) $link;
		
		}
		
		if($lu_menu)
	  {
		  $link = array (
		        'title'            => $lu_text,
		        'menu_item_parent' => '',
		        'ID'               => 'cartloom-lookup',
		        'db_id'            => '',
		        'url'              => '#',
		        'classes' => ['cartloom-lookup']
		    );
		
		  $items[] = (object) $link;
		
		}
	  
	  return $items;   
	  
	}



}
