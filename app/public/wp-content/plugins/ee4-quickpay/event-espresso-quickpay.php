<?php
/*
  Plugin Name: Event Espresso - QuickPay (EE 4.x+)
  Plugin URI: https://perfect-solution.dk
  Description: The Event Espresso QuickPay Payment Method allows you to accept credit cards with QuickPay.
  Version: 1.0.0
  Author: PerfectSolution
  Author URI: http://www.perfect-solution.dk
  Text Domain: ee-quickpay
  License: GPL2
 */

use QuickPayHelpers\AdminTransactions;

/**
 * Autoloader for package helper classes
 */
spl_autoload_register( function ( $class ) {
	$class     = str_replace( '\\', '/', $class );
	$file_path = __DIR__ . "/payment_methods/includes/{$class}.php";
	if ( file_exists( $file_path ) ) {
		require_once( $file_path );
	}
} );

define( 'EE_QUICKPAY_VERSION', '1.0.0' );
define( 'EE_QUICKPAY_PLUGIN_FILE', __FILE__ );
define( 'EE_QUICKPAY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function load_espresso_quickpay_payment_method() {
	if ( class_exists( 'EE_Addon' ) ) {
		// new_payment_method version
		require_once( plugin_dir_path( __FILE__ ) . 'EE_QuickPay.class.php' );
		EE_QuickPay::register_addon();

		// Admin hooks
		AdminTransactions::hooks();
	}
}

add_action( 'AHEE__EE_System__load_espresso_addons', 'load_espresso_quickpay_payment_method' );