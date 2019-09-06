<?php

if ( ! defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 *
 * EE_PMT_Onsite
 *
 *
 * @package               Event Espresso
 * @subpackage
 * @author                PerfectSolution, Patrick Tolvstein
 *
 */
class EE_PMT_quickpay_offsite extends EE_PMT_Base {

	/**
	 *
	 * @param EE_Payment_Method $pm_instance
	 *
	 * @return EE_PMT_QuickPay_Offsite
	 */
	public function __construct( $pm_instance = null ) {
		require_once( $this->file_folder() . 'EEG_quickpay_offsite.gateway.php' );
		$this->_gateway            = new EEG_QuickPay_Offsite();
		$this->_pretty_name        = __( "QuickPay", 'ee-quickpay' );
		$this->_default_button_url = EE_QUICKPAY_PLUGIN_URL . 'assets/images/quickpay-logo.png';

		parent::__construct( $pm_instance );
	}

	/**
	 * Adds the help tab
	 *
	 * @see EE_PMT_Base::help_tabs_config()
	 * @return array
	 */
	public function help_tabs_config() {
		return array(
			$this->get_help_tab_name() => array(
				'title'         => __( 'QuickPay Settings', 'ee-quickpay' ),
				'filename'      => 'quickpay_offsite',
				'template_args' => array(
					'variable_x' => 'VARIABLE X',
				),
			),
		);
	}


	/**
	 * Creates the billing form for this payment method type
	 *
	 * @param \EE_Transaction $transaction
	 *
	 * @return NULL
	 */
	public function generate_new_billing_form( EE_Transaction $transaction = null ) {
		return null;
	}

	/**
	 * Gets the form for all the settings related to this payment method type
	 *
	 * @return EE_Payment_Method_Form
	 */
	public function generate_new_settings_form() {
		$form = new EE_Payment_Method_Form( array(
			'extra_meta_inputs' => array(
				'private_key'       => new EE_Text_Input( array(
					'html_label_text' => sprintf( __( "Private Key %s", "ee-quickpay" ), $this->get_help_tab_link() ),
				) ),
				'api_key'           => new EE_Text_Input( array(
					'html_label_text' => sprintf( __( "API Key %s", "ee-quickpay" ), $this->get_help_tab_link() ),
				) ),
				'text_on_statement' => new EE_Text_Input( array(
					'html_label_text' => sprintf( __( "Text on Statement %s", "ee-quickpay" ), $this->get_help_tab_link() ),
				) ),
				'branding_id'       => new EE_Text_Input( array(
					'html_label_text' => sprintf( __( "Branding ID %s", "ee-quickpay" ), $this->get_help_tab_link() ),
				) ),
				'payment_methods'   => new EE_Text_Input( array(
					'html_label_text' => sprintf( __( "Payment Methods %s", "ee-quickpay" ), $this->get_help_tab_link() ),
					'default'         => 'creditcard',
				) ),
				'auto_capture'      => new EE_Select_Input( array(
					'off' => __( "Disabled", "ee-quickpay" ),
					'on'  => __( "Enabled", "ee-quickpay" ),
				), array(
					'html_label_text' => sprintf( __( "Auto Capture %s", "ee-quickpay" ), $this->get_help_tab_link() ),
				) ),
			),
			'include'           => array(
				'PMD_ID',
				'PMD_name',
				'PMD_desc',
				'PMD_admin_name',
				'PMD_admin_desc',
				'PMD_type',
				'PMD_slug',
				'PMD_button_url',
				'PMD_scope',
				'Currency',
				'PMD_order',
				'private_key',
				'api_key',
				'text_on_statement',
				'branding_id',
				'payment_methods',
				'auto_capture',
			),
		) );

		return $form;
	}

}

// End of file EE_PMT_Onsite.php