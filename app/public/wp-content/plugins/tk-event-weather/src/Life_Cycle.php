<?php

namespace TKEventWeather;

/*
	"WordPress Plugin Template" Copyright (C) 2016 Michael Simpson	(email : michael.d.simpson@gmail.com)

	This file is part of WordPress Plugin Template for WordPress.

	WordPress Plugin Template is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	WordPress Plugin Template is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Contact Form to Database Extension.
	If not, see https://www.gnu.org/licenses/gpl-3.0.html
*/

class Life_Cycle extends Install_Indicator {

	public function install() {
		// Other Plugin initialization - for the plugin writer to override as needed
		$this->other_install();

		// Record the installed version
		$this->save_installed_version();

		// To avoid running install() more then once
		$this->mark_as_installed();
	}

	/**
	 * Override to add any additional actions to be done at install time
	 * See: http://plugin.michael-simpson.com/?page_id=33
	 * @return void
	 */
	protected function other_install() {
	}

	/**
	 * Uninstall all options and settings from this core plugin as well as
	 * those of all add-ons.
	 *
	 * This should only ever get called from the Freemius uninstall hook.
	 */
	public function uninstall() {
		$this->other_uninstall();
		$this->delete_saved_options();
		$this->mark_as_uninstalled();
	}

	/**
	 * Override to add any additional actions to be done at uninstall time
	 * See: http://plugin.michael-simpson.com/?page_id=33
	 * @return void
	 */
	protected function other_uninstall() {
	}

	/**
	 * Perform any version-upgrade activities prior to activation (e.g. database changes)
	 * @return void
	 */
	public function upgrade() {
	}

	/**
	 * See: http://plugin.michael-simpson.com/?page_id=105
	 * @return void
	 */
	public function activate() {
	}

	/**
	 * See: http://plugin.michael-simpson.com/?page_id=105
	 * @return void
	 */
	public function deactivate() {
	}

	public function add_actions_and_filters() {
	}

	/**
	 * Puts the configuration page in the Plugins menu by default.
	 * Override to put it elsewhere or create a set of submenus
	 * Override with an empty implementation if you don't want a configuration page
	 * @return void
	 */
	public function add_settings_submenu_page() {
		$this->add_settings_submenu_page_to_settings_menu();
	}

	protected function add_settings_submenu_page_to_settings_menu() {
		$this->require_extra_plugin_files();
		$display_name = $this->get_plugin_display_name();
		add_options_page(
			$display_name,
			$display_name,
			required_capability(),
			$this->get_settings_slug(),
			array( $this, 'settings_page' )
		);
	}

	protected function require_extra_plugin_files() {
		require_once( ABSPATH . 'wp-includes/pluggable.php' );
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	/**
	 * Must be lower-case and hyphenated to increase compatibility with Freemius.
	 *
	 * @return string Slug name for the URL to the Setting page
	 * (i.e. the page for setting options).
	 */
	public function get_settings_slug() {
		return TK_EVENT_WEATHER_HYPHENS . '-settings';
	}

	/**
	 * Add a "Settings" link to the plugin actions in the wp-admin Plugins List.
	 *
	 * @since 1.5.4
	 *
	 * @param $actions
	 *
	 * @return array
	 */
	public function custom_plugin_action_links( $actions ) {
		if ( current_user_can( required_capability() ) ) {
			array_unshift( $actions, '<a href=\'' . menu_page_url( $this->get_settings_slug(), false ) . '\'>' . esc_html__( 'Settings', 'tk-event-weather' ) . '</a>' );
		}

		return $actions;
	}


	/**
	 * Convenience function for creating AJAX URLs.
	 *
	 * @param $action_name string the name of the ajax action registered in a call like
	 *                     add_action('wp_ajax_action_name', array($this, 'function_name'));
	 *                     and/or
	 *                     add_action('wp_ajax_nopriv_action_name', array($this, 'function_name'));
	 *
	 * If have an additional parameters to add to the Ajax call, e.g. an "id" parameter,
	 * you could call this function and append to the returned string like:
	 *        $url = $this->get_ajax_url('myaction&id=') . urlencode($id);
	 * or more complex:
	 *        $url = sprintf($this->get_ajax_url('myaction&id=%s&var2=%s&var3=%s'), urlencode($id), urlencode($var2), urlencode($var3));
	 *
	 * @return string URL that can be used in a web page to make an Ajax call to $this->function_name
	 */
	public function get_ajax_url( $action_name ) {
		return admin_url( 'admin-ajax.php' ) . '?action=' . $action_name;
	}
}
