<?php

use QuickPay\QuickPay;
use QuickPayHelpers\Countries;


if ( ! defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 *
 * @package               Event Espresso
 * @subpackage
 * @author                PerfectSolution, Patrick Tolvstein
 *
 */
class EEG_QuickPay_Offsite extends EE_Offsite_Gateway {

	const META_PAYMENT_ID = 'qp_payment_id';

	/**
	 * This gateway supports all currencies by default. To limit it to
	 * only certain currencies, specify them here
	 *
	 * @var array
	 */
	protected $_currencies_supported = EE_Gateway::all_currencies_supported;

	/**
	 * Example of site's login ID
	 *
	 * @var string
	 */
	protected $_login_id = null;

	/**
	 * Whether we have configured the gateway integration object to use a separate IPN or not
	 *
	 * @var boolean
	 */
	protected $_override_use_separate_IPN = null;

	/**
	 * @return EEG_QuickPay_Offsite
	 */
	public function __construct() {
		// Allow callback
		$this->set_uses_separate_IPN_request( true );

		/**
		 * Whether or not this gateway can support SENDING a refund request (ie, initiated by
		 * admin in EE's wp-admin page)
		 *
		 * @var boolean
		 */
		$this->_supports_sending_refunds = true;

		parent::__construct();

	}

	/**
	 * @param EE_Transaction $transaction
	 * @param EE_Payment     $payment
	 */
	public static function process_refund( $transaction, $payment ) {
		if ( 'quickpay_offsite' == $payment->payment_method()->slug() ) {

			/** @var \EE_Payment_Method $payment_method */
			$payment_method = $transaction->get_first_related( 'Payment_Method' );

			// Fetch the settings array
			$payment_method_settings = $payment_method->settings_array();

			// Get the API key
			$api_key = isset( $payment_method_settings['api_key'] ) ? $payment_method_settings['api_key'] : false;

			if ( ! $api_key ) {
				return;
			}

			// Create client
			$client = new QuickPay( ":{$api_key}" );
			// Refund through the API
			$request = $client->request->post( sprintf( '/payments/%d/refund?synchronized=true', $payment->txn_id_chq_nmbr() ), array(
				'amount' => absint( $payment->amount() * 100 ),
			) );

			if ( ! $request->isSuccess() ) {
				error_log( $request->asObject()->message );
			}
		}
	}

	/**
	 *
	 * @param arrat $update_info {
	 *
	 * @type string $gateway_txn_id
	 * @type string status an EEMI_Payment status
	 * }
	 *
	 * @param type  $transaction
	 *
	 * @return EEI_Payment
	 */
	public function handle_payment_update( $update_info, $transaction ) {
		// Get callback body
		$response_body = file_get_contents( "php://input" );

		// Decode the body into JSON
		$transaction = json_decode( $response_body );

		// Exit, the callback could not be authorized
		if ( ! $this->is_authorized_callback( $response_body ) ) {
			return null;
		}

		$payment = $this->_pay_model->get_payment_by_txn_id_chq_nmbr( $transaction->id );

		if ( $payment instanceof EEI_Payment ) {
			if ( $transaction->accepted ) {
				$status = end( $transaction->operations );

				switch ( $status->type ) {
					case 'authorize' :
						$payment->set_status( $this->_pay_model->approved_status() );
						$payment->set_gateway_response( __( 'Payment Approved', 'event_espresso' ) );
						break;
				}

			} else {
				$payment->set_status( $this->_pay_model->failed_status() );
				$payment->set_gateway_response( __( 'Payment Failed', 'event_espresso' ) );
			}
		}

		return $payment;
	}

	/**
	 * Checks if the callback is valid
	 *
	 * @param $response_body
	 *
	 * @return bool
	 */
	private function is_authorized_callback( $response_body ) {
		if ( ! isset( $_SERVER["HTTP_QUICKPAY_CHECKSUM_SHA256"] ) ) {
			return false;
		}

		return hash_hmac( 'sha256', $response_body, $this->_private_key ) == $_SERVER["HTTP_QUICKPAY_CHECKSUM_SHA256"];
	}

	/**
	 *
	 * @param EEI_Payment $payment
	 * @param array|type  $billing_info
	 * @param type        $return_url
	 * @param null        $notify_url
	 * @param type        $cancel_url
	 *
	 * @return EE_Payment|EEI_Payment
	 */
	public function set_redirection_info( $payment, $billing_info = array(), $return_url = null, $notify_url = null, $cancel_url = null ) {
		global $auto_made_thing_seed;
		if ( empty( $auto_made_thing_seed ) ) {
			$auto_made_thing_seed = rand( 1, 1000000 );
		}

		// Instantiate client
		$client = new QuickPay( ":{$this->_api_key}" );

		// Create payment
		$transaction = $payment->transaction();

		// The registration
		$registration = $transaction->primary_registration();

		// The buyer
		$attendee = $registration->attendee();

		// Get existing payment ID (if present)
		$qp_payment_id = $transaction->get_extra_meta( self::META_PAYMENT_ID, true, null );

		// Check if there already exists a payment with this ID. If not, create it.
		if ( empty( $qp_payment_id ) ) {
			$qp_payment_data = $this->create_qp_payment( $client, $payment, $registration, $transaction );
			$qp_payment_id   = $qp_payment_data->id;
		}

		// Create the payment link
		$qp_link_data = $this->create_qp_payment_link( $client, $qp_payment_id, $payment, $attendee, $notify_url, $return_url, $cancel_url );

		// Set the transaction ID on the payment
		$payment->set_txn_id_chq_nmbr( $qp_payment_id );

		// Set the redirect URL
		$payment->set_redirect_url( $qp_link_data->url );

		return $payment;
	}

	/**
	 * Creates a new payment through the QuickPay API
	 *
	 * @param QuickPay        $client
	 * @param EE_Payment      $payment
	 * @param EE_Registration $registration
	 * @param                 $transaction
	 *
	 * @return The payment data object
	 */
	private function create_qp_payment( $client, $payment, $registration, $transaction ) {
		// Payment data
		$payment_args = array(
			'order_id'         => $this->standardize_order_id( $registration->ID() ),
			'basket'           => $this->get_basket_data_for_payment( $transaction ),
			'shipping_address' => $this->get_transaction_shipping_params( $registration->attendee() ),
			'invoice_address'  => $this->get_transaction_invoice_params( $registration->attendee() ),
			'currency'         => $payment->currency_code(),
			// Invoice address
			// Shipping address
			// Basket
			// Variables
		);

		// Check text_on_statement separately to avoid problems with certain providers
		if ( ! empty( $this->_text_on_statement ) ) {
			$payment_args['text_on_statement'] = $this->_text_on_statement;
		}

		// Create the payment
		$qp_payment = $client->request->post( '/payments', $payment_args );

		// Check if the payment was created
		if ( ! $qp_payment->isSuccess() ) {
			error_log( "An error occured while creating a payment: " . json_encode( $qp_payment->asArray() ) . ", request: " . json_encode( $payment_args ) );
			exit( json_encode( $qp_payment->asArray() ) );
		}

		// The payment data
		$qp_payment_data = $qp_payment->asObject();
		$qp_payment_id   = $qp_payment_data->id;

		$transaction->update_extra_meta( self::META_PAYMENT_ID, $qp_payment_id );

		return $qp_payment_data;
	}

	/**
	 * Makes sure that the order number is at least 4 digits
	 *
	 * @param $order_id
	 *
	 * @return mixed
	 */
	private function standardize_order_id( $order_id ) {
		$minimum_length = 4;

		$order_id_length = strlen( $order_id );

		if ( $order_id_length < $minimum_length ) {
			preg_match( '/\d+/', $order_id, $digits );

			if ( ! empty( $digits ) ) {
				$missing_digits = $minimum_length - $order_id_length;
				$order_id       = str_replace( $digits[0], str_pad( $digits[0], strlen( $digits[0] ) + $missing_digits, 0, STR_PAD_LEFT ), $order_id );
			}
		}

		$prefix = apply_filters( 'ee_quickpay_transaction_order_id_prefix', '' );

		$prefixed_order_id = $prefix . $order_id;

		return apply_filters( 'ee_quickpay_transaction_order_id', $prefixed_order_id, $order_id, $prefix );
	}

	/**
	 * Creates an array of order items formatted as "QuickPay transaction basket" format.
	 *
	 * @param EE_Transaction $transaction
	 *
	 * @return array
	 */
	private function get_basket_data_for_payment( $transaction ) {
		// Contains order items in QuickPay basket format
		$basket = array();

		// Order items
		$items = $transaction->items_purchased();

		foreach ( $items as $item ) {
			/**
			 * basket[][qty]            Quantity        form    integer        true
			 * basket[][item_no]        Item reference  number    form        string        true
			 * basket[][item_name]        Item name        form    string        true
			 * basket[][item_price]    Per item price (incl. VAT)    form    integer        true
			 * basket[][vat_rate]        VAT rate
			 */

			$basket[] = array(
				'qty'        => strval( $item->quantity() ),
				'item_no'    => (string) $item->ID(),
				'item_name'  => esc_attr( $item->name() ),
				'item_price' => strval( $item->unit_price() * 100 ),
				'vat_rate'   => $item->is_taxable() ? EE_Taxes::get_total_taxes_percentage() / 100 : 0
				// Basket item VAT rate (ex. 0.25 for 25%)
			);
		}

		return $basket;
	}

	/**
	 * Creates an array of shipping data formatted as "QuickPay transaction shipping" format.
	 *
	 * @param EE_Attendee $attendee
	 *
	 * @return array
	 */
	private function get_transaction_shipping_params( $attendee ) {
		$params = array(
			'name'         => $attendee->full_name(),
			'street'       => $attendee->address(),
			'city'         => $attendee->city(),
			'region'       => $attendee->state(),
			'zip_code'     => $attendee->zip(),
			'country_code' => Countries::getAlpha3FromAlpha2( $attendee->country_ID() ),
			'phone_number' => $attendee->phone(),
			'email'        => $attendee->email(),
		);

		return apply_filters( 'ee4_quickpay_transaction_params_invoice', $params );
	}

	/**
	 * Creates an array of invoice data formatted as "QuickPay transaction invoice" format.
	 *
	 * @param EE_Attendee $attendee
	 *
	 * @return array
	 */
	private function get_transaction_invoice_params( $attendee ) {
		$params = array(
			'name'         => $attendee->full_name(),
			'street'       => $attendee->address(),
			'city'         => $attendee->city(),
			'region'       => $attendee->state(),
			'zip_code'     => $attendee->zip(),
			'country_code' => Countries::getAlpha3FromAlpha2( $attendee->country_ID() ),
			'phone_number' => $attendee->phone(),
			'email'        => $attendee->email(),
		);

		return apply_filters( 'ee4_quickpay_transaction_params_shipping', $params );
	}

	/**
	 * @param $client
	 * @param $payment
	 * @param $attendee
	 * @param $notify_url
	 * @param $return_url
	 * @param $cancel_url
	 */
	private function create_qp_payment_link( $client, $qp_payment_id, $payment, $attendee, $notify_url, $return_url, $cancel_url ) {
		$qp_link = $client->request->put( sprintf( '/payments/%d/link', $qp_payment_id ), array(
			'amount'          => $payment->amount() * 100,
			'auto_capture'    => $this->is_setting_enabled( $this->_auto_capture ),
			'customer_email'  => $attendee->email(),
			'branding_id'     => $this->_branding_id,
			'auto_fee'        => false,
			'payment_methods' => $this->_payment_methods,
			'callback_url'    => $notify_url,
			'continue_url'    => $return_url,
			'cancel_url'      => $cancel_url,
			'language'        => $this->get_language(),
		) );

		// Check if the payment link was created
		if ( ! $qp_link->isSuccess() ) {
			error_log( "Could not create payment link: " . json_encode( $qp_link->asArray() ) );
			exit( json_encode( $qp_link->asArray() ) );
		}

		// Payment link data
		return $qp_link->asObject();
	}

	/**
	 * Logic convenience wrapper
	 *
	 * @param $setting_value
	 *
	 * @return bool
	 */
	private function is_setting_enabled( $setting_value ) {
		return $setting_value == 'on';
	}

	/**
	 * @return string|void
	 */
	private function get_language() {
		$language = get_bloginfo( 'language', 'display' );
		if ( ! empty( $language ) && strpos( $language, '-' ) ) {
			list( $language, $variant ) = explode( '-', $language );

			return $language;
		}

		return 'en';
	}

	/**
	 * @return mixed
	 */
	public function get_api_key() {
		if ( ! empty( $this->_api_key ) ) {
			return $this->_api_key;
		}

		return $this->is_setting_enabled();
	}
}