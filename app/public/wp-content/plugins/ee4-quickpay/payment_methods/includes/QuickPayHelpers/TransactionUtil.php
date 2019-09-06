<?php

namespace QuickPayHelpers;

/**
 * Class TransactionUtil
 *
 * @package QuickPayHelpers
 */
class TransactionUtil {

	/**
	 * @param $action - the action, ie. "capture"
	 * @param $json   - the transaction JSON dump
	 *
	 * @return bool
	 */
	public static function is_action_allowed( $action, $json ) {
		$state             = self::get_current_type( $json );
		$remaining_balance = self::get_remaining_balance( $json );

		$allowed_states = array(
			'capture'          => array( 'authorize', 'recurring' ),
			'cancel'           => array( 'authorize' ),
			'refund'           => array( 'capture', 'refund' ),
			'renew'            => array( 'authorize' ),
			'splitcapture'     => array( 'authorize', 'capture' ),
			'recurring'        => array( 'subscribe' ),
			'standard_actions' => array( 'authorize', 'recurring' ),
		);

		// We wants to still allow captures if there is a remaining balance.
		if ( 'capture' == $state && $remaining_balance > 0 ) {
			return true;
		}

		return in_array( $state, $allowed_states[ $action ] );
	}

	/**
	 * get_current_type function.
	 *
	 * Returns the current payment type
	 *
	 * @access public
	 *
	 * @param $json - the transaction JSON dump
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function get_current_type( $json ) {
		$last_operation = self::get_last_operation( $json );

		if ( ! is_object( $last_operation ) ) {
			throw new \Exception( "Malformed operation response", 0 );
		}

		return $last_operation->type;
	}

	/**
	 * get_last_operation function.
	 *
	 * Returns the last transaction operation
	 *
	 * @access public
	 *
	 * @param $json - the transaction JSON dump
	 *
	 * @return mixed
	 */
	public static function get_last_operation( $json ) {
		$last_operation = end( $json->operations );

		return $last_operation;
	}

	/**
	 * get_remaining_balance function
	 *
	 * Returns a remaining balance
	 *
	 * @param $json - the transaction JSON dump
	 *
	 * @return mixed
	 */
	public static function get_remaining_balance( $json ) {
		$balance = self::get_balance( $json );

		$amount = $json->operations[0]->amount;

		$remaining = $amount;

		if ( $balance > 0 ) {
			$remaining = $amount - $balance;
		}

		return $remaining;
	}

	/**
	 * get_balance function
	 *
	 * Returns the transaction balance
	 *
	 * @param $json - the transaction JSON dump
	 *
	 * @return mixed
	 */
	public static function get_balance( $json ) {
		return isset( $json->balance ) ? $json->balance : null;
	}

	/**
	 * is_test function.
	 *
	 * Tests if a payment was made in test mode.
	 *
	 * @access public
	 *
	 * @param $json - the transaction JSON dump
	 *
	 * @return bool
	 */
	public static function is_test( $json ) {
		return isset( $json->test_mode ) && $json->test_mode;
	}

	/**
	 * @param        $json
	 * @param string $type
	 *
	 * @return mixed
	 */
	public static function get_approved_operation_of_type( $json, $type = 'authorize' ) {
		$approved_operations = array_filter( $json->operations, function ( $operation ) use ( $json, $type ) {
			return $operation->type == $type && self::is_operation_approved( $operation, $json );
		} );


		return reset( $approved_operations );
	}

	/**
	 * Checks if either a specific operation or the last operation was successful.
	 *
	 * @param null $operation
	 *
	 * @param null $json - the transaction JSON dump
	 *
	 * @return bool
	 */
	public static function is_operation_approved( $operation = null, $json ) {
		if ( $operation === null ) {
			$operation = self::get_last_operation( $json );
		}

		return $json->accepted && $operation->qp_status_code == 20000 && $operation->aq_status_code == 20000;
	}

	/**
	 * get_currency function
	 *
	 * Returns a transaction currency
	 *
	 * @since  4.5.0
	 *
	 * @param $json - the transaction JSON dump
	 *
	 * @return mixed
	 */
	public static function get_currency( $json ) {
		return $json->currency;
	}

	/**
	 * get_cardtype function
	 *
	 * Returns the payment type / card type used on the transaction
	 *
	 * @since  4.5.0
	 * @return mixed
	 */
	public static function get_brand( $json ) {
		return $json->metadata->brand;
	}

	/**
	 * @param $brand - the card type
	 *
	 * @return null
	 */
	public static function get_payment_type_logo( $brand ) {
		$logos = array(
			"american-express" => "americanexpress.png",
			"dankort"          => "dankort.png",
			"diners"           => "diners.png",
			"edankort"         => "edankort.png",
			"fbg1886"          => "forbrugsforeningen.png",
			"jcb"              => "jcb.png",
			"maestro"          => "maestro.png",
			"mastercard"       => "mastercard.png",
			"mastercard-debet" => "mastercard.png",
			"mobilepay"        => "mobilepay.png",
			"visa"             => "visa.png",
			"visa-electron"    => "visaelectron.png",
			"paypal"           => "paypal.png",
			"sofort"           => "sofort.png",
			"viabill"          => "viabill.png",
			"klarna"           => "klarna.png",
		);

		if ( array_key_exists( trim( $brand ), $logos ) ) {
			return EE_QUICKPAY_PLUGIN_URL . 'assets/images/cards/' . $logos[ $brand ];
		}

		return null;
	}

	/**
	 * @param $json - the transaction JSON dump
	 *
	 * @return string
	 */
	public static function get_formatted_remaining_balance( $json ) {
		$balance = self::get_remaining_balance( $json );

		return number_format( $balance / 100, 2 );
	}
}