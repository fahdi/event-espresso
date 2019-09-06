<?php
/*
Plugin Name: Event Espresso (Spanish)
Plugin URI: http://www.closemarketing.es/servicios/wordpress-plugins/event-espresso-es/
Description: Extends the Event Espresso plugin and add-ons with the Spanish language

Version: 0.1
Requires at least: 3.0

Author: Closemarketing
Author URI: http://www.closemarketing.es/

Text Domain: event_espresso
Domain Path: /languages/

License: GPL
*/

class EventEspressoESPlugin {
	/**
	 * The plugin file
	 *
	 * @var string
	 */
	private $file;

	////////////////////////////////////////////////////////////

	/**
	 * The current langauge
	 *
	 * @var string
	 */
	private $language;

	/**
	 * Flag for the spanish langauge, true if current langauge is spanish, false otherwise
	 *
	 * @var boolean
	 */
	private $is_spanish;

	////////////////////////////////////////////////////////////

	/**
	 * Construct and intialize
	 */
	public function __construct( $file ) {
		$this->file = $file;

		// Priority is set to 8, beceasu the Signature Add-On is using priority 9
		add_action( 'init', array( $this, 'init' ), 8 );

		add_filter( 'load_textdomain_mofile', array( $this, 'load_textdomain_mofile' ), 10, 2 );

		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );

		add_action( 'activated_plugin', array( $this, 'activated_plugin' ) );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Activated plugin
	 */
	public function activated_plugin() {
		$path = str_replace( WP_PLUGIN_DIR . '/', '', $this->file );

		if ( $plugins = get_option( 'active_plugins' ) ) {
			if ( $key = array_search( $path, $plugins ) ) {
				array_splice( $plugins, $key, 1 );
				array_unshift( $plugins, $path );

				update_option( 'active_plugins', $plugins );
			}
		}

		if ( $plugins = get_site_option( 'active_sitewide_plugins' ) ) {
			if ( $key = array_search( $path, $plugins ) ) {
				array_splice( $plugins, $key, 1 );
				array_unshift( $plugins, $path );

				update_site_option( 'active_sitewide_plugins', $plugins );
			}
		}
	}

	////////////////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public function init() {
		$rel_path = dirname( plugin_basename( $this->file ) ) . '/languages/';

		// Determine language
		if ( $this->language == null ) {
			$this->language = get_option( 'WPLANG', WPLANG );
			$this->is_spanish = ( $this->language == 'es' || $this->language == 'es_ES' );
		}

		// The ICL_LANGUAGE_CODE constant is defined from an plugin, so this constant
		// is not always defined in the first 'load_textdomain_mofile' filter call
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$this->is_spanish = ( ICL_LANGUAGE_CODE == 'es' );
		}

		// Load plugin text domain - Event Espresso (es)
		load_plugin_textdomain( 'event_espresso', false, $rel_path );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Load text domain MO file
	 *
	 * @param string $moFile
	 * @param string $domain
	 */
	public function load_textdomain_mofile( $mo_file, $domain ) {
		// First do quick check if an Spanish .MO file is loaded
		if ( strpos( $mo_file, 'es_ES.mo' ) !== false ) {
			$domains = array(
				'event_espresso'               => array(
					'languages/event_espresso-es_ES.mo'                 => 'event-espresso/es_ES.mo'
				)/*,
				'gravityformscampaignmonitor'  => array(
					'languages/gravityformscampaignmonitor-es_ES.mo'  => 'gravityformscampaignmonitor/es_ES.mo'
				),
				'gravityformsmailchimp'        => array(
					'languages/gravityformsmailchimp-es_ES.mo'        => 'gravityformsmailchimp/es_ES.mo'
				),
				'gravityformspaypal'           => array(
					'languages/gravityformspaypal-es_ES.mo'           => 'gravityformspaypal/es_ES.mo'
				),
				'gravityformspolls'            => array(
					'languages/gravityformspolls-es_ES.mo'            => 'gravityformspolls/es_ES.mo'
				),
				'gravityformssignature'        => array(
					'languages/gravityformssignature-es_ES.mo'        => 'gravityformssignature/es_ES.mo'
				),
				'gravityformsuserregistration' => array(
					'languages/gravityformsuserregistration-es_ES.mo' => 'gravityformsuserregistration/es_ES.mo'
				)*/
			);

			if ( isset( $domains[$domain] ) ) {
				$paths = $domains[$domain];

				foreach ( $paths as $path => $file ) {
					if ( substr( $mo_file, -strlen( $path ) ) == $path ) {
						$new_file = dirname( $this->file ) . '/languages/' . $file;

						if ( is_readable( $new_file ) ) {
							$mo_file = $new_file;
						}
					}
				}
			}
		}

		return $mo_file;
	}

}

global $gravityforms_es_plugin;

$gravityforms_es_plugin = new EventEspressoESPlugin( __FILE__ );