<?php
/**
 * Admin Tour
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

use Exception;
use Pronamic\WordPress\Pay\Plugin;

/**
 * WordPress admin tour
 *
 * @author  Remco Tolsma
 * @version 2.1.0
 * @since   1.0.0
 */
class AdminTour {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initializes an pointers object.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/4.2.4/wp-includes/js/wp-pointer.js
	 * @link https://github.com/WordPress/WordPress/blob/4.2.4/wp-admin/includes/template.php#L1955-L2016
	 * @link https://github.com/Yoast/wordpress-seo/blob/2.3.4/admin/class-pointers.php
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		if ( filter_has_var( INPUT_GET, 'pronamic_pay_ignore_tour' ) && wp_verify_nonce( filter_input( INPUT_GET, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING ), 'pronamic_pay_ignore_tour' ) ) {
			$ignore = filter_input( INPUT_GET, 'pronamic_pay_ignore_tour', FILTER_VALIDATE_BOOLEAN );

			update_user_meta( get_current_user_id(), 'pronamic_pay_ignore_tour', $ignore );
		}

		if ( ! get_user_meta( get_current_user_id(), 'pronamic_pay_ignore_tour', true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Admin enqueue scripts.
	 */
	public function admin_enqueue_scripts() {
		$min = SCRIPT_DEBUG ? '' : '.min';

		// Pointers.
		wp_register_style(
			'proanmic-pay-admin-tour',
			plugins_url( '../../css/admin-tour' . $min . '.css', __FILE__ ),
			array(
				'wp-pointer',
			),
			$this->plugin->get_version()
		);

		wp_register_script(
			'proanmic-pay-admin-tour',
			plugins_url( '../../js/dist/admin-tour' . $min . '.js', __FILE__ ),
			array(
				'jquery',
				'wp-pointer',
			),
			$this->plugin->get_version(),
			true
		);

		wp_localize_script(
			'proanmic-pay-admin-tour',
			'pronamicPayAdminTour',
			array(
				'pointers' => $this->get_pointers(),
			)
		);

		// Enqueue.
		wp_enqueue_style( 'proanmic-pay-admin-tour' );
		wp_enqueue_script( 'proanmic-pay-admin-tour' );
	}

	/**
	 * Get pointer content.
	 *
	 * @param string $file File.
	 * @return string
	 * @throws Exception When output buffering is not active.
	 */
	private function get_content( $file ) {
		$content = '';

		$path = __DIR__ . '/../../views/' . $file . '.php';

		if ( is_readable( $path ) ) {
			ob_start();

			include $path;

			$content = ob_get_clean();

			if ( false === $content ) {
				throw new Exception( 'Output buffering is not active.' );
			}
		}

		return $content;
	}

	/**
	 * Get pointers.
	 *
	 * @return array
	 */
	private function get_pointers() {
		$pointers = array();

		$page   = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$screen = get_current_screen();

		if ( null !== $screen ) {
			switch ( $screen->id ) {
				case 'toplevel_page_pronamic_ideal':
					$pointers = array(
						array(
							// @link https://github.com/WordPress/WordPress/blob/4.7/wp-admin/edit.php#L321
							'selector' => '.wrap .wp-header-end',
							'options'  => (object) array(
								'content'      => $this->get_content( 'pointer-dashboard' ),
								'position'     => (object) array(
									'edge'  => 'top',
									'align' => ( is_rtl() ) ? 'left' : 'right',
								),
								'pointerWidth' => 450,
							),
						),
					);

					break;
				case 'edit-pronamic_payment':
					$pointers = array(
						array(
							'selector' => '.wrap .wp-header-end',
							'options'  => (object) array(
								'content'      => $this->get_content( 'pointer-payments' ),
								'position'     => (object) array(
									'edge'  => 'top',
									'align' => ( is_rtl() ) ? 'left' : 'right',
								),
								'pointerWidth' => 450,
							),
						),
					);

					break;
				case 'edit-pronamic_gateway':
					$pointers = array(
						array(
							'selector' => '.wrap .wp-header-end',
							'options'  => (object) array(
								'content'      => $this->get_content( 'pointer-gateways' ),
								'position'     => (object) array(
									'edge'  => 'top',
									'align' => ( is_rtl() ) ? 'left' : 'right',
								),
								'pointerWidth' => 450,
							),
						),
					);

					break;
				case 'edit-pronamic_pay_form':
					$pointers = array(
						array(
							'selector' => '.wrap .wp-header-end',
							'options'  => (object) array(
								'content'      => $this->get_content( 'pointer-forms' ),
								'position'     => (object) array(
									'edge'  => 'top',
									'align' => ( is_rtl() ) ? 'left' : 'right',
								),
								'pointerWidth' => 450,
							),
						),
					);

					break;
			}
		}

		switch ( $page ) {
			case 'pronamic_pay_settings':
				$pointers = array(
					array(
						'selector' => '.wrap .wp-header-end',
						'options'  => (object) array(
							'content'      => $this->get_content( 'pointer-settings' ),
							'position'     => (object) array(
								'edge'  => 'top',
								'align' => ( is_rtl() ) ? 'left' : 'right',
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
			case 'pronamic_pay_tools':
				$pointers = array(
					array(
						'selector' => '.wrap .wp-header-end',
						'options'  => (object) array(
							'content'      => $this->get_content( 'pointer-tools' ),
							'position'     => (object) array(
								'edge'  => 'top',
								'align' => ( is_rtl() ) ? 'left' : 'right',
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
			case 'pronamic_pay_reports':
				$pointers = array(
					array(
						'selector' => '.wrap .wp-header-end',
						'options'  => (object) array(
							'content'      => $this->get_content( 'pointer-reports' ),
							'position'     => (object) array(
								'edge'  => 'top',
								'align' => ( is_rtl() ) ? 'left' : 'right',
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
		}

		if ( empty( $pointers ) ) {
			$pointers = array(
				array(
					'selector' => 'li.toplevel_page_pronamic_ideal',
					'options'  => (object) array(
						'content'  => $this->get_content( 'pointer-start' ),
						'position' => (object) array(
							'edge'  => 'left',
							'align' => 'center',
						),
					),
				),
			);
		}

		return $pointers;
	}

	/**
	 * Get tour close URL.
	 *
	 * @return string
	 */
	public function get_close_url() {
		return wp_nonce_url(
			add_query_arg(
				array(
					'pronamic_pay_ignore_tour' => true,
				)
			),
			'pronamic_pay_ignore_tour',
			'pronamic_pay_nonce'
		);
	}
}
