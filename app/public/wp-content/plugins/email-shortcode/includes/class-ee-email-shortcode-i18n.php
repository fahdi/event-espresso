<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.elsner.com
 * @since      1.0.0
 *
 * @package    Ee_Email_Shortcode
 * @subpackage Ee_Email_Shortcode/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ee_Email_Shortcode
 * @subpackage Ee_Email_Shortcode/includes
 * @author     Elsner Technologies Pvt. Ltd. <info@elsner.com>
 */
class Ee_Email_Shortcode_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ee-email-shortcode',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
