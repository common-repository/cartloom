<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.yabdab.com
 * @since      1.0.0
 *
 * @package    Cartloom
 * @subpackage Cartloom/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cartloom
 * @subpackage Cartloom/admin
 * @author     Yabdab Inc. <mike@cartloom.com>
 */
 
class Cartloom_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	 
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	 
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cartloom-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	 
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cartloom-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Setup Settings Page
	 *
	 * @since    1.0.0
	 */
	 
	public function register_options() {
		
		register_setting('cartloom_plugin_options', 'cartloom_plugin_options');
		
	}
	
	/**
	 * Add Cartloom Settings to WP Admin Side Nav
	 *
	 * @since    1.0.0
	 */
	 
	public function admin_menus() {
		
		$top_menu_item = 'cartloom_settings_page';
		    
		add_menu_page( '', 'Cartloom', 'manage_options', 'cartloom_settings_page', '');
	    
		$settings_page  = add_submenu_page( 'cartloom_settings_page', '', 'Dashboard', 'manage_options', 'cartloom_settings_page', array($this ,'settings_page' ) );
		
		// Include CMB CSS in the head to avoid FOUC.
		
		add_action( "admin_print_styles-{$settings_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
		    
	
	}

	/**
	 * Build Cartloom Media (Dropdown) Button 
	 *
	 * @since    1.0.0
	 */

	public function media_buttons( $editor_id ) {	
	
		$cart_name = cmb2_get_option( 'cartloom_plugin_settings', 'cart_name' );
		
		$api_key = cmb2_get_option( 'cartloom_plugin_settings', 'api_key' );
		
		$error = '';
		
		if( $cart_name && $api_key ) {
		
			$response = wp_remote_get('https://'.$cart_name.'.cartloom.com/api/products/wp/format/json/X-API-KEY/'.$api_key);
		
			$cl_rs = json_decode($response['body']);
		
			$cl_products = $cl_rs->products;
		
			$cl_groups = $cl_rs->groups;
			
		} else{
			
			$error = 'Invalid or Incomplete Settings';
			
		}
	
		
		
		ob_start();
		
		?>
		

			
			<div class="cl_embed_btn_wrap">
				<a href="#" class="button button-secondary cartloom-add-shortcode cl_embed_btn cartloom-popup"><span class="cl-icon cl-icon-logo-o"></span> <?php esc_html_e( 'Add Product', 'cartloom' ); ?></a>
				<div class="cl_embeds_out" id="cartloom-popup-html">
	
					<div class="cl_search_bar larger">
						<div class="cl_search_dropdown" style="width: 16px;">
							<span>All</span>
							<ul>
								<li class="all selected">All</li>
								<li class="products">Products</li>
								<li class="groups">Groups</li>
							</ul>
							</div>
						<input type="text" placeholder="Search..." id="cl_search_input" />
					</div>
	
					<div class="cl_embeds_in">
						
						<div class="cl_embed_results">
							
							<ul>
							<?php if($error): ?>
							<li class="cl-error"><span class="cl-icon cl-icon-bomb"></span><a href="<?php echo admin_url('admin.php?page=cartloom_settings_page'); ?>"><em><?php echo $error; ?></em></a></li>
							<?php endif; ?>
							<?php if(count($cl_products)): ?>
							<?php foreach($cl_products as $p): ?>
							<li class="cl-product">
							<span class="cl-icon cl-icon-plus-o"></span>
							<a draggable="true" 
								role="button" 
							   		href="#" 
								   class="cl-embed-item" data-cartname="<?php echo $cart_name; ?>"
									   data-id="<?php echo $p->id; ?>" data-type="button" title="<?php echo esc_html($p->name); ?>"><?php echo esc_html( $this->_truncate($p->name, 18) ); ?> <span class="cl-badge btn">button</span></a>
							</li>
							<li class="cl-product">
							<span class="cl-icon cl-icon-plus-o"></span>
							<a draggable="true"  role="button" 
							   		href="#" 
								   class="cl-embed-item" data-cartname="<?php echo $cart_name; ?>"
									   data-id="<?php echo $p->id; ?>" data-type="product" title="<?php echo esc_html($p->name); ?>"><?php echo esc_html( $this->_truncate($p->name, 18) ); ?> <span class="cl-badge embed">embed</span></a>
							</li>	
							<?php endforeach; ?>
							<?php else: ?>
							<li class="cl-product empty"><span class="cl-icon cl-icon-question-o"></span><a><em>No Products Found</em></a></li>
							<?php endif; ?>
							
							<?php if(count($cl_groups)): ?>
							<?php foreach($cl_groups as $g): ?>
							<li class="cl-group">
							<span class="cl-icon cl-icon-plus-o"></span> 
							<a draggable="true" role="button" 
							   		href="#" 
								   class="cl-embed-item" data-cartname="<?php echo $cart_name; ?>"
									   data-id="<?php echo $g->id; ?>" data-type="group" title="<?php echo esc_html($g->name); ?>"><?php echo esc_html( $this->_truncate($g->name, 18) ); ?> <span class="cl-badge embed">embed</span></a>
							</li>	
							<?php endforeach; ?>
							<?php else: ?>
							<li class="cl-group empty"><span class="cl-icon cl-icon-question-o"></span><a><em>No Groups Found</em></a></li>
							<?php endif; ?>
							
							</ul>
							
						</div>
						
					</div>
					
				</div>
				
			</div>

		<?php
			
		echo ob_get_clean();
	
	
	}

	/**
	 * Build Settings Page (Inputs) using CMB2.
	 *
	 * @since    1.0.0
	 */

	public function settings_page() {
		
		add_action( "cmb2_save_options-page_fields_cartloom_plugin_metabox", array($this,'settings_notices'), 10, 2 );
		
		
		$cmb = new_cmb2_box( array(
				'id'         => 'cartloom_plugin_metabox',
				'hookup'     => false,
				'context'       => 'normal',
				'priority'      => 'high',
				'title' => 'Cartloom Settings',
				'show_on'    => array(
					'key'   => 'options-page',
					'value' => array( 'cartloom_plugin_settings' ),
				),
			) );
			
		$cmb->add_field( array(
			'name' => 'Required Settings',
			//'desc' => 'This is a title description',
			'type' => 'title',
			'id'   => 'cartloom_required_title',
			'after' => array($this,'cmb_after_cb')
		) );
			
		$cmb->add_field( array(
				'name'    => __( 'Cart Name', 'cartloom' ),
				'id'      => 'cart_name',
				'desc'    => 'The unique name you gave your cart, used at the beginning of your Cartloom URL.',
				'type'    => 'text',
				'default' => __( 'Your Cart Name', 'cartloom' ),
			) );
			
		$cmb->add_field( array(
				'name'    => __( 'API Key', 'cartloom' ),
				'id'      => 'api_key',
				'desc'    => 'Find your API key at Information -> RESTful API.',
				'type'    => 'text',
				'default' => __( 'Your API Key', 'cartloom' ),
			) );
			
			
		$cmb->add_field( array(
			'name' => 'Optional Settings',
			//'desc' => 'This is a title description',
			'type' => 'title',
			'id'   => 'cartloom_optional_title',
			'after' => array($this,'cmb_after_cb')
		) );
			
		$cmb->add_field( array(
				'name' => 'Add View Cart to Menu',
				'desc' => 'Enable to include View Cart in Main Nav Menu',
				'id'   => 'view_cart_menu',
				'type' => 'checkbox',
			) );
			
		$cmb->add_field( array(
				'name'    => __( 'View Cart Text', 'cartloom' ),
				'id'      => 'view_cart_text',
				'desc'    => 'Text used in Menu',
				'type'    => 'text',
				'default' => __( 'View Cart', 'cartloom' ),
			) );	
			
		$cmb->add_field( array(
				'name' => 'Add Order Lookup to Menu',
				'desc' => 'Enable to include Order Lookup in Main Nav Menu',
				'id'   => 'lookup_menu',
				'type' => 'checkbox',
			) );
			
		$cmb->add_field( array(
				'name'    => __( 'Order Lookup Text', 'cartloom' ),
				'id'      => 'lookup_text',
				'desc'    => 'Text used in Menu',
				'type'    => 'text',
				'default' => __( 'Order Lookup', 'cartloom' )
			) );
		
		ob_start();
		
		?>
		
			<div class="wrap">
			<div><img src="<?php echo esc_url( plugins_url( 'img/combo_logo.png', __FILE__ ) ); ?>" style="max-width:200px; height: auto;"></div>
			<div class="cartloom-settings-wrap">
				<?php cmb2_metabox_form( 'cartloom_plugin_metabox', 'cartloom_plugin_settings' ); ?>
			</div>
			</div>
		
		<?php
			
		echo ob_get_clean();
		
		
	}
	
		
	/**
	 * Special callback used to underline titles on Settings page.
	 *
	 * @since    1.0.0
	 */
	 
	public function cmb_after_cb( $field_args, $field ) {
		
			echo '<hr />';
		
	}
	
	/**
	 * Build Settings notice after an update.
	 *
	 * @since    1.0.0
	 */	

	public function settings_notices( $object_id, $updated ) {
		
		if ( $object_id !== 'cartloom_plugin_settings' || empty( $updated ) ) {
			
			return;
			
		}
		
		add_settings_error( 'cartloom_plugin_settings-notices', '', __( 'Cartloom Settings Updated.', 'cartloom' ), 'updated' );
		
		settings_errors('cartloom_plugin_settings-notices' );
		
	}
	
	/**
	 * Helper function for shortening long product names
	 *
	 * @since    1.0.0
	 */
	 
	private function _truncate($string, $limit) {
	
	  // return with no change if string is shorter than $limit
	
	  if(strlen($string) <= $limit) return $string;

	
	  $str = substr($string, 0, $limit).'...';
	
	    
	  return $str;
	
	}



}
