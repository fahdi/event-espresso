<?php

namespace TKEventWeather;

class API_Google_Maps {
	// all variables and methods should be 'static'

	public static function get_debug_output() {
		$output = '';

		if ( empty( Shortcode::$debug_enabled ) ) {
			return $output;
		}

		$data = self::get_response_data();

		if ( empty( $data ) ) {
			return $output;
		}

		/**
		 * Request URI
		 *
		 * api-result-examples/google_maps.txt
		 *
		 * Example Debug Output:
		 * <!--
		 * TK Event Weather -- Google Maps Geocoding API -- Request URI
		 * https://maps.googleapis.com/maps/api/geocode/json?address=The+White+House
		 * -->
		 */
		$output .= sprintf(
			'<!--%1$s%2$s -- Google Maps Geocoding API -- Obtained from Transient: %3$s -- Request URI:%1$s%4$s%1$s -- JSON Data:%1$s%5$s%1$s-->%1$s',
			PHP_EOL,
			Setup::plugin_display_name(),
			Shortcode::$google_maps_api_transient_used,
			self::geocode_request_uri(),
			json_encode( $data, JSON_PRETTY_PRINT ) // JSON_PRETTY_PRINT option requires PHP 5.4
		);

		return $output;
	}

	private static function get_response_data() {
		// Get from transient if exists and valid
		$transient_data = self::get_transient_value();

		if ( true === self::valid_transient( $transient_data ) ) {
			Shortcode::$google_maps_api_transient_used = 'TRUE';

			return $transient_data;
		}

		// Get from API if no transient
		$response = wp_safe_remote_get( esc_url_raw( self::geocode_request_uri() ) );

		if ( is_wp_error( $response ) ) {
			return Functions::invalid_shortcode_message( 'Google Maps Geocoding API request sent but resulted in a WordPress Error. Please troubleshoot' );
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			return Functions::invalid_shortcode_message( 'Google Maps Geocoding API request sent but nothing received. Please troubleshoot' );
		}

		$data = json_decode( $body );


		if ( empty( $data ) ) {
			return Functions::invalid_shortcode_message( 'Google Maps Geocoding API response received but some sort of data inconsistency. Please troubleshoot' );
		}

		/**
		 * Set transient if enabled and API call resulted in usable data.
		 *
		 * Allowed to store for up to 30 calendar days.
		 *
		 * @link https://developers.google.com/maps/terms#10-license-restrictions
		 */
		if ( ! empty( Shortcode::$transients_enabled ) ) {
			set_transient( self::get_transient_name(), $data, 30 * DAY_IN_SECONDS );
		}

		return $data;
	}

	private static function get_transient_value() {
		return Functions::transient_get_or_delete( self::get_transient_name(), Shortcode::$transients_enabled );
	}

	private static function get_transient_name() {
		$name = sprintf(
			'%s_gmaps_%s',
			Setup::$transient_name_prepend,
			Functions::remove_all_whitespace( Shortcode::$location )
		);

		$name = Functions::sanitize_transient_name( $name );

		return $name;
	}

	/**
	 * @param      $transient
	 *
	 * @return bool
	 */
	private static function valid_transient( $transient ) {
		if ( ! empty( $transient ) ) {
			if (
				// WordPress error
				is_wp_error( $transient )

				// expected result of json_decode() of API response
				|| ! is_object( $transient )
			) {
				$transient = '';

				delete_transient( self::get_transient_name() );
			}
		}

		if ( ! empty( $transient ) ) {
			return true;
		} else {
			return false;
		}
	}

	private static function geocode_request_uri() {
		$address = Shortcode::$location;
		if ( empty( $address ) ) {
			return '';
		}

		$api_key = Functions::sanitize_key_allow_uppercase( Shortcode::$google_maps_api_key );

		$uri_base = 'https://maps.googleapis.com/maps/api/geocode/json';

		$uri_query_args = array();

		$uri_query_args['address'] = urlencode( $address );

		if ( ! empty( $api_key ) ) {
			$uri_query_args['key'] = urlencode( $api_key );
		}

		/**
		 * Filter to allow adding things like Region Biasing.
		 *
		 * @link https://developers.google.com/maps/documentation/geocoding/intro#RegionCodes
		 */
		$uri_query_args = apply_filters( TK_EVENT_WEATHER_UNDERSCORES . '_gmaps_geocode_request_uri_query_args', $uri_query_args, Shortcode::$custom_context );

		$uri = add_query_arg( $uri_query_args, $uri_base );

		return $uri;
	}

	public static function get_lat_long() {
		if ( ! empty( Shortcode::$latitude_longitude ) ) {
			return Functions::valid_lat_long( Shortcode::$latitude_longitude );
		}

		$data = self::get_response_data();

		/**
		 * @link https://developers.google.com/maps/documentation/geocoding/intro#StatusCodes
		 */
		if ( 'OK' != $data->status ) {
			return Functions::invalid_shortcode_message( 'The Google Maps Geocoding API resulted in an error: ' . $data->status, 'https://developers.google.com/maps/documentation/geocoding/intro#StatusCodes' );
		}

		$latitude_longitude = '';

		if ( ! empty ( $data->results[0]->geometry->location->lat ) ) {
			$latitude  = $data->results[0]->geometry->location->lat;
			$longitude = $data->results[0]->geometry->location->lng;

			// build comma-separated coordinates
			$latitude_longitude = sprintf( '%F,%F', $latitude, $longitude );
			$latitude_longitude = Functions::valid_lat_long( $latitude_longitude );
		}

		return $latitude_longitude;
	}


}