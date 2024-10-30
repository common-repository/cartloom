<?php

/**
 *
 * @link              https://www.cartloom.com
 * @since             1.0.0
 * @package           Cartloom
 *
 * @wordpress-plugin
 * Plugin Name:       Cartloom
 * Plugin URI:        https://www.cartloom.com/features/wordpress
 * Description:       Easily add Buy Buttons, Product Groups and Shopping Cart snippets to Wordpress Pages and Posts.
 * Version:           1.0.1
 * Author:            Mike Yrabedra @ Cartloom
 * Author URI:        https://www.cartloom.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       cartloom
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( CARTLOOM_PLUGIN_VERSION, '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cartloom-activator.php
 */
function activate_cartloom() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cartloom-activator.php';
	Cartloom_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cartloom-deactivator.php
 */
function deactivate_cartloom() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cartloom-deactivator.php';
	Cartloom_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cartloom' );
register_deactivation_hook( __FILE__, 'deactivate_cartloom' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cartloom.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cartloom() {

	$plugin = new Cartloom();
	$plugin->run();

}
run_cartloom();
