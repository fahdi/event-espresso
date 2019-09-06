<?php

namespace QuickPayHelpers;

/**
 * Class Template
 *
 * @package QuickPayHelpers
 */
class Template {

	/**
	 * @param       $path
	 * @param array $args
	 */
	public static function get( $path, $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$located = self::locate_template( $path );

		if ( ! $located ) {
			return;
		}

		include( $located );
	}

	/**
	 * @param $path
	 *
	 * @return bool|string
	 */
	private static function locate_template( $path ) {
		$path = dirname( EE_QUICKPAY_PLUGIN_FILE ) . '/templates/' . trim( $path );

		if ( file_exists( $path ) ) {
			return $path;
		}

		return false;
	}
}