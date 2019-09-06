<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.elsner.com
 * @since      1.0.0
 *
 * @package    Ee_Email_Shortcode
 * @subpackage Ee_Email_Shortcode/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ee_Email_Shortcode
 * @subpackage Ee_Email_Shortcode/admin
 * @author     Elsner Technologies Pvt. Ltd. <info@elsner.com>
 */
class Ee_Email_Shortcode_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ee_Email_Shortcode_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ee_Email_Shortcode_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ee-email-shortcode-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ee_Email_Shortcode_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ee_Email_Shortcode_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ee-email-shortcode-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function change_footer_admin () {
		echo 'Developed by <a href="http://profiles.wordpress.org/kadiwala" target="_blank">Aakif Kadiwala</a> at <a href="http://www.elsner.com" target="_blank">Elsner Technologies Pvt. Ltd.</a></p>';
	}

	public function eees_admin_menu(){
		$capability = apply_filters( 'ee_email_shortcode_required_capabilities', 'manage_options' );
		$parent_slug = 'ee_email_shortcode_main_menu';
		global $wpdb;

		add_menu_page( __( 'E-Shortcode', 'ee-email-shortcode' ), __( 'E-Shortcode', 'ee-email-shortcode' ), $capability, $parent_slug, 'ee_email_shortcode_main_menu', '', '102');

		add_submenu_page( $parent_slug, __( 'Custom E-Shortcodes', 'ee-email-shortcode' ), __( 'Custom E-Shortcodes', 'ee-email-shortcode' ), $capability, 'ee_email_shortcode', 'ee_email_shortcode' );
		add_submenu_page( $parent_slug, __( 'Add New E-Shortcode', 'ee-email-shortcode' ), __( 'Add New E-Shortcode', 'ee-email-shortcode' ), $capability, 'ee_email_shortcode_add_new', 'ee_email_shortcode_add_new' );


		add_submenu_page( $parent_slug, __( 'Extras', 'ee-email-shortcode' ), __( '<a class="eees_menu-title-tag"><b>Extras</b></a>', 'ee-email-shortcode' ), $capability, 'ee_email_shortcode_about', 'ee_email_shortcode_about' );
		add_submenu_page( $parent_slug, __( 'About', 'ee-email-shortcode' ), __( 'About', 'ee-email-shortcode' ), $capability, 'ee_email_shortcode_about', 'ee_email_shortcode_about' );


		remove_submenu_page( $parent_slug, 'ee_email_shortcode_main_menu');


	}

	public function eees_create_e_shortcode( $shortcodes, EE_Shortcodes $lib ){
		
		global $wpdb;

        $result=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name LIKE 'eees_%'");
    
        $total_rec = $wpdb->num_rows;
    
        for($i=0; $i<$total_rec; $i++){
        	$eees_temp_id[$i] = $result[$i]->option_id;
    		$eees_temp[$i] = maybe_unserialize( get_option($result[$i]->option_name) );
        }


        for($i=0; $i<$total_rec; $i++){
        	$temp_id = $eees_temp_id[$i];
        	$temp_name = $eees_temp[$i]['eees_name'];
        	$temp_slug = '['.$eees_temp[$i]['eees_slug'].'_eees]';
        	$temp_slug_2 = $eees_temp[$i]['eees_slug'];
        	$temp_status = $eees_temp[$i]['eees_status'];
        	$temp_list = $eees_temp[$i]['eees_list'];
        	$temp_modified = date("Y/m/d g:i:s a");
        	$temp_desc = $eees_temp[$i]['eees_description'];

        	$eees_option_name = 'eees_'.$temp_slug_2.'_'.$temp_list;

			$eees_data = array( 
				'eees_name' => $temp_name,
				'eees_slug' => $temp_slug_2,
				'eees_list' => $temp_list,
				'eees_status' => 0,
				'eees_modified' => $temp_modified,
				'eees_description' => $temp_desc,
			);

			$eees_serialized_data = maybe_serialize( $eees_data );

        	if($temp_status == 1){
	        	if($eees_temp[$i]['eees_list'] == 'main'){
		        	if ( $lib instanceof EE_Shortcodes ) {
						$shortcodes[$temp_slug] = _($temp_desc);
					}
				} elseif($eees_temp[$i]['eees_list'] == 'event'){
		        	if ( $lib instanceof EE_Event_Shortcodes ) {
						$shortcodes[$temp_slug] = _($temp_desc);
					}
				} elseif($eees_temp[$i]['eees_list'] == 'attendee'){
		        	if ( $lib instanceof EE_Attendee_Shortcodes ) {
						$shortcodes[$temp_slug] = _($temp_desc);
					}
				} elseif($eees_temp[$i]['eees_list'] == 'ticket'){
		        	if ( $lib instanceof EE_Ticket_Shortcodes ) {
						$shortcodes[$temp_slug] = _($temp_desc);
					}
				} elseif($eees_temp[$i]['eees_list'] == 'datetime'){
		        	if ( $lib instanceof EE_Datetime_Shortcodes ) {
						$shortcodes[$temp_slug] = _($temp_desc);
					}
				}
			}
        }
        return $shortcodes;
/*
         if ( $lib instanceof EE_Event_Shortcodes ) {
		    $shortcodes['[Event_ppp_eeeees]'] = _('This is Custom Shortcode for Event Location!');
		    $shortcodes['[Event_DDate]'] = _('This is Custom Shortcode for Event Start Date!');
		  }
		  return $shortcodes;
		  */
	}

	


	public function eees_register_e_shortcode_parser( $parsed, $shortcode, $data, $extra_data, EE_Shortcodes $lib ){
/*
		if ( $lib instanceof EE_Event_Shortcodes  && $data instanceof EE_Event ) {
		          
		    $evt_id = $data->get('EVT_ID');

		    if ( $shortcode == '[Event_ppp_eeeees]' ) {
		      $evt_phone = '9999999999999';
		      return $evt_phone;
		    }
		  }


		if ( $lib instanceof EE_Event_Shortcodes  && $data instanceof EE_Event ) {
		          
		    $evt_id = $data->get('EVT_ID');

		    if ( $shortcode == '[Event_DDate]' ) {
		      $evt_phone = '88-88-8888';
		      return $evt_phone;
		    }
		  }

*/
	
		global $wpdb;

		$result=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name LIKE 'eees_%'");
    
        $total_rec = $wpdb->num_rows;
    
        for($i=0; $i<$total_rec; $i++){
        	$eees_temp_id[$i] = $result[$i]->option_id;
    		$eees_temp[$i] = maybe_unserialize( get_option($result[$i]->option_name) );
        }

        for($i=0; $i<$total_rec; $i++){
        	if($eees_temp[$i]['eees_status'] == 1){

        		$eees_temp_slug = '['.$eees_temp[$i]['eees_slug'].'_eees]';

        		if($eees_temp[$i]['eees_field_status'] == 'Static'){
        			if($eees_temp[$i]['eees_list'] == 'main'){
	        			if ( $lib instanceof EE_Shortcodes ) {
	        				if ( $shortcode == $eees_temp_slug ) {
						      $eees_return = $eees_temp[$i]['eees_field'];
						      return $eees_return;
						    }
	        			}
	        		}
        			if($eees_temp[$i]['eees_list'] == 'event'){
	        			if ( $lib instanceof EE_Event_Shortcodes  && $data instanceof EE_Event ) {
	        				$eees_evt_id = $data->get('EVT_ID');
	        				if ( $shortcode == $eees_temp_slug ) {
						      $eees_return = $eees_temp[$i]['eees_field'];
						      return $eees_return;
						    }
	        			}
	        		}
        			if($eees_temp[$i]['eees_list'] == 'attendee'){
	        			if ( $lib instanceof EE_Attendee_Shortcodes  && $data instanceof EE_Attendee ) {
	        				$eees_evt_id = $data->get('EVT_ID');
	        				if ( $shortcode == $eees_temp_slug ) {
						      $eees_return = $eees_temp[$i]['eees_field'];
						      return $eees_return;
						    }
	        			}
	        		}
        			if($eees_temp[$i]['eees_list'] == 'ticket'){
	        			if ( $lib instanceof EE_Ticket_Shortcodes  && $data instanceof EE_Ticket ) {
	        				$eees_evt_id = $data->get('EVT_ID');
	        				if ( $shortcode == $eees_temp_slug ) {
						      $eees_return = $eees_temp[$i]['eees_field'];
						      return $eees_return;
						    }
	        			}
	        		}
        			if($eees_temp[$i]['eees_list'] == 'datetime'){
	        			if ( $lib instanceof EE_Datetime_Shortcodes  && $data instanceof EE_Datetime ) {
	        				$eees_evt_id = $data->get('EVT_ID');
	        				if ( $shortcode == $eees_temp_slug ) {
						      $eees_return = $eees_temp[$i]['eees_field'];
						      return $eees_return;
						    }
	        			}
	        		}
        		}

        		elseif($eees_temp[$i]['eees_field_status'] == 'Dynamic'){
    			
        			if ( $lib instanceof EE_Event_Shortcodes  && $data instanceof EE_Event ) {
        				$eees_evt_id = $data->get('EVT_ID');
						$temp = get_post_meta($eees_evt_id);

						// print_r($temp['alternate_number'][0]);
						$temp_val = $eees_temp[$i]['eees_field'];
        				if ( $shortcode == $eees_temp_slug ) {
					      $eees_event = $temp[$temp_val][0];
					      return $eees_event;
					    }
        			}
        			if ( $lib1 instanceof EE_Attendee_Shortcodes  && $data1 instanceof EE_Attendee ) {
        				$eees_evt_id = $data1->get('EVT_ID');
						$temp = get_post_meta($eees_evt_id);

						// print_r($temp['alternate_number'][0]);
						$temp_val = $eees_temp[$i]['eees_field'];
        				if ( $shortcode == $eees_temp_slug ) {
					      $eees_attendee = $temp[$temp_val][0];
					      return $eees_attendee;
					    }
        			}
        			if ( $lib2 instanceof EE_Ticket_Shortcodes  && $data2 instanceof EE_Ticket ) {
        				$eees_evt_id = $data2->get('EVT_ID');
						$temp = get_post_meta($eees_evt_id);

						// print_r($temp['alternate_number'][0]);
						$temp_val = $eees_temp[$i]['eees_field'];
        				if ( $shortcode == $eees_temp_slug ) {
					      $eees_ticket = $temp[$temp_val][0];
					      return $eees_ticket;
					    }
        			}
        			if ( $lib3 instanceof EE_Datetime_Shortcodes  && $data3 instanceof EE_Datetime ) {
        				$eees_evt_id = $data3->get('EVT_ID');
						$temp = get_post_meta($eees_evt_id);

						// print_r($temp['alternate_number'][0]);
						$temp_val = $eees_temp[$i]['eees_field'];
        				if ( $shortcode == $eees_temp_slug ) {
					      $eees_datetime = $temp[$temp_val][0];
					      return $eees_datetime;
					    }
        			}
        		}
        	}
        }
        return $parsed;
	}
}
	
