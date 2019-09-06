<?php
/**
 * Plugin Name: Event Espresso Smooth Integration
 * Plugin URI: http://www.wingzcommunications.com
 * Description: Designed to Integrate popular plugins properly with Event Espresso 4. Currently includes GravityForms & WordPress SEO by Yoast
 * Version: 1.0.0
 * Author: Jon Ang
 * Author URI: http://www.wingzcommunications.com
 * Requires at least: 4.0.0
 * Tested up to: 4.2.1
 * License: GPL2
 *
 * Copyright 2015 Jon Ang (email : jon@wingzcommunications.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 * Text Domain: event_espresso
 * Domain Path: /languages/
 *
 * @package Event_Espresso_Smooth_Integration
 * @category Core
 * @author Jon Ang
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Returns the main instance of EE_SEO_Integration to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object EE_Smooth_Integration
 */
function EE_Smooth_Integration() {
    return EE_Smooth_Integration::instance();
} // End EE_Smooth_Integration()
EE_Smooth_Integration();
/**
 * Main EE_SEO_Integration Class
 *
 * @class EE_Smooth_Integration
 * @version	1.0.0
 * @since 1.0.0
 * @package	EE_SEO_Integration
 * @author Jon Ang
 */
final class EE_Smooth_Integration {
    /**
     * EE_Smooth_Integration The single instance of EE_Smooth_Integration.
     * @var 	object
     * @access  private
     * @since 	1.0.0
     */
    private static $_instance = null;
    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $token;
    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $version;
    /**
     * The plugin directory URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $plugin_url;
    /**
     * The plugin directory path.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $plugin_path;
    // Admin - Start
    /**
     * The admin object.
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $admin;
    /**
     * The settings object.
     * @var     object
     * @access  public
     * @since   1.0.0
     */

    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct () {
        $this->token 			= 'event-espresso-smooth-integration';
        $this->plugin_url 		= plugin_dir_url( __FILE__ );
        $this->plugin_path 		= plugin_dir_path( __FILE__ );
        $this->version 			= '1.0.0';


        register_activation_hook( __FILE__, array( $this, 'install' ) );
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'espresso_events' ) :
            add_filter( 'wpseo_always_register_metaboxes_on_admin', array( $this, 'register_ee_yoast_seo_metaboxes' ) );
            add_filter( 'wpseo_pre_analysis_post_content', array( $this, 'allow_seo_yoast_calculations' ) );
            add_filter( 'gform_display_add_form_button', array( $this, 'register_ee_gravityforms_button' ) );
        endif;
    } // End __construct()
    /**
     * Main EE_Smooth_Integration Instance
     *
     * Ensures only one instance of EE_Smooth_Integration is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see EE_Smooth_Integration()
     * @return Main EE_Smooth_Integration instance
     */
    public static function instance () {
        if ( is_null( self::$_instance ) )
            self::$_instance = new self();
        return self::$_instance;
    } // End instance()
    /**
     * Load the localisation file.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'event_espresso', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    } // End load_plugin_textdomain()
    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __clone()
    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __wakeup()
    /**
     * Installation. Runs on activation.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function install () {
        $this->_log_version_number();
    } // End install()
    /**
     * Log the plugin version number.
     * @access  private
     * @since   1.0.0
     * @return  void
     */
    private function _log_version_number () {
        // Log the version number.
        update_option( $this->token . '-version', $this->version );
    } // End _log_version_number()

    /**
     * Register WordPress SEO by Yoast's metaboxes in, not doable by theme due to load order
     * @return bool
     */
    public function register_ee_yoast_seo_metaboxes(){
        return true;
    }

    /**
     * Lets GravityForm buttons be added to more pages
     *
     * @param $is_post_edit_page
     * @return bool
     */
    public function register_ee_gravityforms_button( $is_post_edit_page ){
        $is_post_edit_page = in_array( RG_CURRENT_PAGE, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php', 'admin.php' ) );
        return $is_post_edit_page;
    }

    /**
     * Add custom fields to Yoast SEO analysis
     */



    public function allow_seo_yoast_calculations( $content ) {
        global $post;
        $custom_content = '';
        $custom = get_post_custom( $post->ID );
        unset( $custom['_yoast_wpseo_focuskw'] ); // Don't count the keyword in the Yoast field!

        foreach( $custom as $key => $value ) :
            if ( substr( $key, 0, 1 ) != '_' && substr( $value[0], -1 ) != '}' && !is_array( $value[0] ) && !empty( $value[0] ) ) :
                $custom_content .= $value[0] . ' ';
            endif;
        endforeach;

        $content = $content . ' ' . $custom_content;
        return $content;
        //remove_filter('wpseo_pre_analysis_post_content', array( $this, 'allow_seo_yoast_calculations' ) );
    }

} // End Class