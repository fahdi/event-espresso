<?php

namespace QuickPayHelpers;

use QuickPay\QuickPay;

/**
 * Class AdminTransactions
 *
 * @package QuickPayHelpers
 */
class AdminTransactions {

	/**
	 * Hooks and filters
	 */
	public static function hooks() {
		add_action( 'add_meta_boxes', __CLASS__ . '::meta_boxes', 1000 );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::enqueue_stylesheet', 10 );
		add_action( 'wp_ajax_quickpay_manual_transaction_actions', __CLASS__ . '::ajax_quickpay_manual_transaction_actions' );
	}

	/**
	 * enqueue_stylesheet function.
	 *
	 * @access public static
	 */
	public static function enqueue_stylesheet() {
		wp_enqueue_style( 'style', EE_QUICKPAY_PLUGIN_URL . 'assets/css/espresso_quickpay_admin.css', '1.0.0' );

		wp_enqueue_script( 'quickpay-backend', EE_QUICKPAY_PLUGIN_URL . 'assets/scripts/espresso_quickpay_backend.js', array( 'jquery' ), '1.0.0' );
		wp_localize_script( 'quickpay-backend', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Adds meta boxes
	 */
	public static function meta_boxes() {
		// Insert the meta box, but only if there is a QuickPay transaction ID mapped to the transaction
		if ( self::get_admin_transaction_id() ) {
			add_meta_box( 'quickpay-payment-actions', esc_html__( 'QuickPay Actions', 'ee-quickpay' ), __CLASS__ . '::meta_box_transactions', 'event-espresso_page_espresso_transactions', 'side', 'high' );
		}
	}

	/**
	 * Gets the transaction ID based on the query params TXN_ID in wp-admin
	 *
	 * @return string - The payment ID
	 */
	private static function get_admin_transaction_id() {
		$transaction = self::get_admin_transaction();

		if ( ! $transaction ) {
			return false;
		}

		return $transaction->get_extra_meta( 'qp_payment_id', true, false );
	}

	/**
	 * @return \EE_Base_Class
	 */
	private static function get_admin_transaction() {
		$TXN = \EEM_Transaction::instance();

		if ( isset( $_GET['TXN_ID'] ) ) {
			return $TXN->get_one_by_ID( $_GET['TXN_ID'] );
		}

		return null;
	}

	/**
	 * Meta box content: Transcations
	 */
	public static function meta_box_transactions() {
		$transaction_id = self::get_admin_transaction_id();

		if ( $transaction_id ) {

			/** @var \EE_Transaction $transaction */
			$transaction = self::get_admin_transaction();

			/** @var \EE_Payment_Method $payment_method */
			$payment_method = $transaction->get_first_related( 'Payment_Method' );

			// Fetch the settings array
			$payment_method_settings = $payment_method->settings_array();

			// Get the API key
			$api_key = isset( $payment_method_settings['api_key'] ) ? $payment_method_settings['api_key'] : false;

			if ( ! $api_key ) {
				return;
			}

			$client = new QuickPay( ":{$api_key}" );

			$request = $client->request->get( sprintf( "/payments/%d", $transaction_id ) );

			if ( ! $request->isSuccess() ) {
				return;
			}

			$qp_transaction = $request->asObject();

			Template::get( 'meta_box/transaction.actions.php', array(
				'transaction'                 => $qp_transaction,
				'transaction_id'              => $transaction_id,
				'status'                      => TransactionUtil::get_current_type( $qp_transaction ),
				'is_test'                     => TransactionUtil::is_test( $qp_transaction ),
				'balance'                     => self::get_formatted_balance( $qp_transaction ),
				'remaining_balance'           => TransactionUtil::get_remaining_balance( $qp_transaction ),
				'formatted_remaining_balance' => TransactionUtil::get_formatted_remaining_balance( $qp_transaction ),
				'card_logo'                   => TransactionUtil::get_payment_type_logo( TransactionUtil::get_brand( $qp_transaction ) ),
				'currency'                    => TransactionUtil::get_currency( $qp_transaction ),
			) );
		}
	}

	/**
	 * @param $qp_transaction
	 *
	 * @return string
	 */
	private static function get_formatted_balance( $qp_transaction ) {
		$balance = TransactionUtil::get_balance( $qp_transaction );

		if ( $balance > 0 ) {
			$balance = $balance / 100;
		}

		return number_format( $balance, 2 );
	}

	/**
	 * ajax_quickpay_manual_transaction_actions function.
	 *
	 * Ajax method taking manual transaction requests from wp-admin.
	 *
	 * @access public
	 * @return void
	 */
	public static function ajax_quickpay_manual_transaction_actions() {
		if ( isset( $_REQUEST['quickpay_action'] ) AND isset( $_REQUEST['txn_id'] ) ) {
			$param_action = $_REQUEST['quickpay_action'];
			$param_txn_id = $_REQUEST['txn_id'];

			$TXN = \EEM_Transaction::instance();

			$transaction = $TXN->get_one_by_ID( $param_txn_id );

			$transaction_id = $transaction->get_extra_meta( 'qp_payment_id', true, false );

			/** @var \EE_Payment_Method $payment_method */
			$payment_method          = $transaction->get_first_related( 'Payment_Method' );
			$payment_method_settings = $payment_method->settings_array();

			// Get the API key
			$api_key = isset( $payment_method_settings['api_key'] ) ? $payment_method_settings['api_key'] : false;

			if ( ! $api_key ) {
				self::ajax_error_response( __( 'No API key found - Check your QuickPay settings.', 'ee-quickpay' ) );
			}

			$client = new QuickPay( ":{$api_key}" );

			$request    = $client->request->get( sprintf( '/payments/%d', $transaction_id ) );
			$qp_payment = $request->asObject();

			if ( ! $request->isSuccess() ) {
				self::ajax_error_response( $request->asObject()->message );
			}

			if ( ! TransactionUtil::is_action_allowed( $param_action, $qp_payment ) ) {
				self::ajax_error_response( sprintf( __( 'Could not perform a "%s" on transaction with ID: %d', 'ee-quickpay' ), $param_action, $transaction_id ) );
			}

			switch ( $param_action ) {
				case 'capture':
					$authorized_operation = TransactionUtil::get_approved_operation_of_type( $qp_payment, 'authorize' );
					$request              = $client->request->post( sprintf( '/payments/%d/capture?synchronized=true', $transaction_id ), array(
						'amount' => $authorized_operation->amount, // Capture the authorized amount
					) );

					if ( $request->isSuccess() ) {
						self::ajax_success_response( 'Payment captured' );
					} else {
						self::ajax_error_response( $request->asObject() );
					}
					break;

				case 'cancel':
					$request = $client->request->post( sprintf( '/payments/%d/cancel?synchronized=true', $transaction_id ), array() );

					if ( $request->isSuccess() ) {
						self::ajax_success_response( 'Payment captured' );
					} else {
						self::ajax_error_response( $request->asObject() );
					}
					break;
			}
		}
	}

	/**
	 * @param $message
	 */
	private static function ajax_error_response( $message ) {
		echo json_encode( array( 'status' => 'error', 'message' => $message ) );
		exit;
	}

	/**
	 * @param $message
	 */
	private static function ajax_success_response( $message ) {
		echo json_encode( array( 'status' => 'success', 'message' => $message ) );
		exit;
	}
}