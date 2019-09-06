<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.elsner.com
 * @since             1.0.0
 * @package           Ee_Email_Shortcode
 *
 * @wordpress-plugin
 * Plugin Name:       Event Espresso - Custom Email Template Shortcode
 * Plugin URI:        http://wordpress.org/plugins/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Elsner Technologies Pvt. Ltd.
 * Author URI:        www.elsner.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ee-email-shortcode
 * Domain Path:       /languages
 */

/*include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if( !is_plugin_active( 'event-espresso-decaf/espresso.php' ) || !is_plugin_active( 'event-espresso-core-reg/espresso.php' ) )
{
	deactivate_plugins( 'ee-email-shortcode/ee-email-shortcode.php');
}*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ee-email-shortcode-activator.php
 */
function activate_ee_email_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ee-email-shortcode-activator.php';
	Ee_Email_Shortcode_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ee-email-shortcode-deactivator.php
 */
function deactivate_ee_email_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ee-email-shortcode-deactivator.php';
	Ee_Email_Shortcode_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ee_email_shortcode' );
register_deactivation_hook( __FILE__, 'deactivate_ee_email_shortcode' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ee-email-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ee_email_shortcode() {

	$plugin = new Ee_Email_Shortcode();
	$plugin->run();

}
run_ee_email_shortcode();

function ee_email_shortcode(){
	include(  plugin_dir_path( __FILE__ )  . 'admin/partials/custom_e-shortcodes.php' );
}

function ee_email_shortcode_add_new(){
	include(  plugin_dir_path( __FILE__ )  . 'admin/partials/add_new_e-shortcodes.php' );
}

function ee_email_shortcode_about(){
	include(  plugin_dir_path( __FILE__ )  . 'admin/partials/about_e-shortcodes.php' );
}

function my_plugin_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/admin.php?page=ee_email_shortcode_about' ) ) . '">' . __( 'About', 'textdomain' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'my_plugin_action_links' );