<?php
namespace webaware\eway_payment_gateway;

if (!defined('ABSPATH')) {
	exit;
}

/**
* payment gateway integration for Event Espresso
*/
class MethodEventEspresso extends \EE_Addon {

	/**
	* register gateway integration
	*/
	public static function register_eway() {
		\EE_Register_Addon::register(__CLASS__, [
			'version'				=> EWAY_PAYMENTS_VERSION,
			'min_core_version'		=> '4.6.0.dev.000',
			'main_file_path'		=> EWAY_PAYMENTS_PLUGIN_FILE,
			'payment_method_paths'	=> [
				__DIR__ . '/event_espresso_eway',
			],
		]);
	}

}
