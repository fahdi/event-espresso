<?php if ( ! defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit();
}

// define the plugin directory path and URL
define( 'EE_QUICKPAY_BASENAME', plugin_basename( EE_QUICKPAY_PLUGIN_FILE ) );
define( 'EE_QUICKPAY_PATH', plugin_dir_path( __FILE__ ) );
define( 'EE_QUICKPAY_URL', plugin_dir_url( __FILE__ ) );

/**
 * ------------------------------------------------------------------------
 *
 * Class  EE_Quickpay
 *
 * @package               Event Espresso
 * @author                PerfectSolution, Patrick Tolvstein
 * @ version            $VID:$
 *
 * ------------------------------------------------------------------------
 */
Class EE_QuickPay extends EE_Addon {

	/**
	 * class constructor
	 */
	public function __construct() {
	}

	/**
	 * Registers addon gateway
	 */
	public static function register_addon() {
		// register addon via Plugin API
		EE_Register_Addon::register( 'QuickPay', array(
			'version'              => EE_QUICKPAY_VERSION,
			'min_core_version'     => '4.6.0.dev.000',
			'main_file_path'       => EE_QUICKPAY_PLUGIN_FILE,
			'admin_callback'       => 'additional_admin_hooks',
			'payment_method_paths' => array(
				EE_QUICKPAY_PATH . 'payment_methods' . DS . 'quickpay_offsite',
			),
		) );

		add_action( 'AHEE__Transactions_Admin_Page__apply_payments_or_refund__after_recording', 'EEG_QuickPay_Offsite::process_refund', 10, 2 );

		add_action( 'init', __CLASS__ . '::load_i18n' );
	}

	/**
	 * Loads I18n
	 */
	public static function load_i18n() {
		load_plugin_textdomain( 'ee-quickpay', false, dirname( plugin_basename( EE_QUICKPAY_PLUGIN_FILE ) ) . '/languages/' );
	}


	/**
	 *    additional_admin_hooks
	 *
	 * @access    public
	 * @return    void
	 */
	public function additional_admin_hooks() {
		// is admin and not in M-Mode ?
		if ( is_admin() && ! EE_Maintenance_Mode::instance()->level() ) {
			add_filter( 'plugin_action_links', array( $this, 'plugin_actions' ), 10, 2 );
		}
	}


	/**
	 * plugin_actions
	 *
	 * Add a settings link to the Plugins page, so people can go straight from the plugin page to the settings page.
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function plugin_actions( $links, $file ) {
		if ( $file == EE_QUICKPAY_BASENAME ) {
			// before other links
			array_unshift( $links, '<a href="admin.php?page=espresso_payment_settings">' . __( 'Settings' ) . '</a>' );
		}

		return $links;
	}


}
