<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.elsner.com/
 * @since      1.0.0
 *
 * @package    Manage_Custom_Post_Types
 * @subpackage Manage_Custom_Post_Types/admin/partials
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

  if(isset($_POST['ee-email-shortcode-publish']))
	{
		if ( !isset( $_REQUEST['_wpnonce'] ) 
		    || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'ee-email-shortcode-nonce' )
		) {
		?>
		   <div class="error notice">
				<p><?php esc_html_e('Error : Direct access may be harm your content...', 'ee-email-shortcode'); ?></p>
			</div>
		<?php
		} else {
			if(isset($_POST['ee-email-shortcode-list']) && $_POST['ee-email-shortcode-list']!='0' ){
				$eees_option_name = esc_html('eees_'.$_POST['ee-email-shortcode-slug'].'_'.$_POST['ee-email-shortcode-list'], 'ee-email-shortcode');

				$eees_exists_check = get_option( $eees_option_name );

        if(isset($_POST['eees_value_option']) && $_POST['eees_value_option']=='Dynamic'){
          $temp_value_option = $_POST['ee-email-shortcode-d-field'];
          $temp_value_option_status = "Dynamic";
        }
        elseif(isset($_POST['eees_value_option']) && $_POST['eees_value_option']=='Static'){
          $temp_value_option = $_POST['ee-email-shortcode-s-field'];
          $temp_value_option_status = "Static";
        }

				if(!$eees_exists_check ){
					$eees_current = date("Y/m/d g:i:s a");
					$eees_data = array( 
						'eees_name' => sanitize_text_field($_POST['ee-email-shortcode-name']),
						'eees_slug' => sanitize_text_field($_POST['ee-email-shortcode-slug']),
						'eees_list' => sanitize_text_field($_POST['ee-email-shortcode-list']),
            'eees_field'  => sanitize_text_field($temp_value_option),
            'eees_field_status'  => sanitize_text_field($temp_value_option_status),
						'eees_status' => sanitize_text_field('1'),
						'eees_modified' => sanitize_text_field($eees_current),
						'eees_description' => sanitize_text_field($_POST['ee-email-shortcode-desc']),
					);
/*
					echo "<pre>";
					print_r($eees_data);
					echo "</pre>";
*/
					$eees_serialized_data = maybe_serialize( $eees_data );

					$temp = add_option( $eees_option_name, $eees_serialized_data, '', 'yes' );
					if($temp == 1){
						$eees_nonce_field = wp_create_nonce( 'ee-email-shortcode-nonce-2' );

            $header_url = admin_url()."admin.php?page=ee_email_shortcode&eees_msg=".$_POST['ee-email-shortcode-name']."&eees_status=Updated&_wpnonce=".$eees_nonce_field;
    ?>
          <script>
            window.location.href = "<?php echo $header_url; ?>";
          </script>
			<?php
					}else{
			?>
						<div class="error notice">
			    			<p><?php esc_html_e('Error : Something went wrong...', 'ee-email-shortcode'); ?></p>
						</div>
			<?php
					}
				}else{
				?>
					<div class="error notice">
		    			<p><?php esc_html_e('Error : Already Exists...', 'ee-email-shortcode'); ?></p>
					</div>
				<?php
				}
			}
			else if($_POST['ee-email-shortcode-list']=='0'){
			?>
			   	<div class="error notice">
					<p><?php esc_html_e('Error : Please select valid list...', 'ee-email-shortcode'); ?></p>
				</div>
			<?php
			}
		}
	}

  elseif(isset($_REQUEST['action']) &&$_REQUEST['action'] == 'edit' && isset($_REQUEST['id'])){
    global $wpdb;
    $result=$wpdb->get_results("SELECT option_name FROM ".$wpdb->prefix."options WHERE option_id = ".$_REQUEST['id']);
    $total_rec = $wpdb->num_rows;
    if($total_rec > 0){
      $eees_option_name = $result[0]->option_name;
      $eees_get_shortcode = maybe_unserialize( get_option( $eees_option_name ) );
    }
  }

  elseif(isset($_POST['ee-email-shortcode-update'])){
    if ( !isset( $_REQUEST['_wpnonce'] ) 
        || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'ee-email-shortcode-nonce' )
    ) {
    ?>
       <div class="error notice">
        <p><?php esc_html_e('Error : Direct access may be harm your content...', 'ee-email-shortcode'); ?></p>
      </div>
    <?php
    } else {
      global $wpdb;
      if(isset($_POST['ee-email-shortcode-list']) && $_POST['ee-email-shortcode-list']!='0' ){
        $eees_option_name = esc_html('eees_'.$_POST['ee-email-shortcode-slug'].'_'.$_POST['ee-email-shortcode-list'], 'ee-email-shortcode');

        if($_POST['eees_value_option']=='Dynamic'){
          $temp_value_option = $_POST['ee-email-shortcode-d-field'];
          $temp_value_option_status = "Dynamic";
        }
        elseif($_POST['eees_value_option']=='Static'){
          $temp_value_option = $_POST['ee-email-shortcode-s-field'];
          $temp_value_option_status = "Static";
        }

        $eees_current = date("Y/m/d g:i:s a");
        $eees_data = array( 
          'eees_name' => sanitize_text_field($_POST['ee-email-shortcode-name']),
          'eees_slug' => sanitize_text_field($_POST['ee-email-shortcode-slug']),
          'eees_list' => sanitize_text_field($_POST['ee-email-shortcode-list']),
          'eees_field'  => sanitize_text_field($temp_value_option),
          'eees_field_status'  => sanitize_text_field($temp_value_option_status),
          'eees_status' => sanitize_text_field('1'),
          'eees_modified' => sanitize_text_field($eees_current),
          'eees_description' => sanitize_text_field($_POST['ee-email-shortcode-desc']),
        );
/*
        echo "<pre>";
        print_r($eees_data);
        echo "</pre>";
*/
        $eees_serialized_data = maybe_serialize( $eees_data );

        $table = $wpdb->prefix."options";
        $values = array(
          'option_name' => $eees_option_name,
          'option_value' => $eees_serialized_data,
          );
        $where = array(
          'option_id' => $_POST['eees_edit_id'],
          );
          
        if($wpdb->update( $table, $values, $where )){
          $eees_nonce_field = wp_create_nonce( 'ee-email-shortcode-nonce-2' );
          $header_url = admin_url()."admin.php?page=ee_email_shortcode&eees_msg=".$_POST['ee-email-shortcode-name']."&eees_status=Updated&_wpnonce=".$eees_nonce_field;
    ?>
          <script>
            window.location.href = "<?php echo $header_url; ?>";
          </script>
      <?php
          }else{
      ?>
            <div class="error notice">
                <p><?php esc_html_e('Error : Something went wrong...', 'ee-email-shortcode'); ?></p>
            </div>
      <?php
          }
      }
      else if($_POST['ee-email-shortcode-list']=='0'){
      ?>
          <div class="error notice">
          <p><?php esc_html_e('Error : Please select valid list...', 'ee-email-shortcode'); ?></p>
        </div>
      <?php
      }
    }
  }
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
  <h1 class="wp-heading-inline">
    <?php 
      if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
        esc_html_e('Update E-Shortcode','ee-email-shortcode');
      else
        esc_html_e('Add New E-Shortcode','ee-email-shortcode'); ?>
  </h1>

  <form name="post" action="<?php echo admin_url(); ?>admin.php?page=ee_email_shortcode_add_new" method="post" id="post">
    <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content" style="position: relative;">
          <div id="titlediv">
          	<div id="titlewrap">
              <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){ ?>
                <input type="hidden" value="<?php echo $_REQUEST['id']; ?>" name="eees_edit_id"  id="eees_edit_id">
      	 		    <input class="ee-email-shortcode-form" name="ee-email-shortcode-name" id="ee-email-shortcode-name" type="text" placeholder="<?php esc_html_e(' Shortcode Name','ee-email-shortcode'); ?>" value="<?php echo $eees_get_shortcode['eees_name']; ?>" required>&nbsp;
              <?php } else{ ?>
                <input class="ee-email-shortcode-form" name="ee-email-shortcode-name" id="ee-email-shortcode-name" type="text" placeholder="<?php esc_html_e(' Shortcode Name','ee-email-shortcode'); ?>" required>&nbsp;
              <?php } ?>
      			</div>

      			<div id="titlewrap">
              <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){ ?>
                <input class="ee-email-shortcode-form" name="ee-email-shortcode-slug" id="ee-email-shortcode-slug" type="text" placeholder="<?php esc_html_e(' Shortcode Slug','ee-email-shortcode'); ?>" value="<?php echo $eees_get_shortcode['eees_slug']; ?>" required>&nbsp;
              <?php } else{ ?>
                <input class="ee-email-shortcode-form" name="ee-email-shortcode-slug" id="ee-email-shortcode-slug" type="text" placeholder="<?php esc_html_e(' Shortcode Slug','ee-email-shortcode'); ?>" required>&nbsp;
              <?php } ?>
      			</div>

      			<div id="titlewrap">
      			  <select class="ee-email-shortcode-form" style="height: 100%;" name="ee-email-shortcode-list" id="ee-email-shortcode-list">
                <?php
                  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){ 
                    if($eees_get_shortcode['eees_list']=='main'){
                      //echo '<option value="main" SELECTED> Main Content</option>';
                      echo '<option value="event"> Event List</option>
                            <option value="attendee"> Attendee List</option>
                            <option value="ticket"> Ticket List</option>
                            <option value="datetime"> Date-Time List</option>';
                    }
                    elseif($eees_get_shortcode['eees_list']=='event'){
                      //echo '<option value="main" SELECTED> Main Content</option>';
                      echo '<option value="event" SELECTED> Event List</option>
                            <option value="attendee"> Attendee List</option>
                            <option value="ticket"> Ticket List</option>
                            <option value="datetime"> Date-Time List</option>';
                    }
                    elseif($eees_get_shortcode['eees_list']=='attendee'){
                      //echo '<option value="main" SELECTED> Main Content</option>';
                      echo '<option value="event"> Event List</option>
                            <option value="attendee" SELECTED> Attendee List</option>
                            <option value="ticket"> Ticket List</option>
                            <option value="datetime"> Date-Time List</option>';
                    }
                    elseif($eees_get_shortcode['eees_list']=='ticket'){
                      //echo '<option value="main" SELECTED> Main Content</option>';
                      echo '<option value="event"> Event List</option>
                            <option value="attendee"> Attendee List</option>
                            <option value="ticket" SELECTED> Ticket List</option>
                            <option value="datetime"> Date-Time List</option>';
                    }
                    elseif($eees_get_shortcode['eees_list']=='datetime'){
                      //echo '<option value="main" SELECTED> Main Content</option>';
                      echo '<option value="event"> Event List</option>
                            <option value="attendee"> Attendee List</option>
                            <option value="ticket"> Ticket List</option>
                            <option value="datetime" SELECTED> Date-Time List</option>';
                    }
                  } else{ ?>
          			    <option value="0" SELECTED> Select List </option>
                 		<!-- <option value="main"> Main Content</option> -->
                		<option value="event"> Event List</option>
                		<option value="attendee"> Attendee List</option>
                		<option value="ticket"> Ticket List</option>
                		<option value="datetime"> Date-Time List</option>
                <?php } ?>
              </select>&nbsp;
      			</div>

            <div id="titlewrap">
              <table>
              <?php
                if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit'){
                  if($eees_get_shortcode['eees_field_status']=='Dynamic'){
              ?>
                <tr>
                  <td id="eees_dynamic_block" style="display:block;">
                    <input class="ee-email-shortcode-form" name="ee-email-shortcode-d-field" id="ee-email-shortcode-d-field" value="<?php echo $eees_get_shortcode['eees_field']; ?>" type="text" placeholder="<?php esc_html_e(' Enter slug of custom field','ee-email-shortcode'); ?>" required>
                  </td>
                  <td style="width:30%;" align="left">
                    &nbsp;&nbsp;<input type="radio" name="eees_value_option" id="eees_dynamic_option" value="Dynamic" checked><label class="ee-email-shortcode-form"> Dynamic Value </label>
                  </td>
                </tr>
                <tr>
                  <td id="eees_static_block" style="display:none;">
                    <input class="ee-email-shortcode-form" name="ee-email-shortcode-s-field" id="ee-email-shortcode-s-field" type="text" placeholder="<?php esc_html_e(' Enter static value','ee-email-shortcode'); ?>">
                  </td>
                  <td style="width:30%;" align="left">
                    &nbsp;&nbsp;<input type="radio" name="eees_value_option" id="eees_static_option" value="Static"><label class="ee-email-shortcode-form"> Static Value </label>
                  </td>
                </tr>
              <?php
                  } elseif($eees_get_shortcode['eees_field_status']=='Static'){
              ?>
                <tr>
                  <td id="eees_dynamic_block" style="display:none;">
                    <input class="ee-email-shortcode-form" name="ee-email-shortcode-d-field" id="ee-email-shortcode-d-field" type="text" placeholder="<?php esc_html_e(' Enter slug of custom field','ee-email-shortcode'); ?>" >
                  </td>
                  <td style="width:30%;" align="left">
                    &nbsp;&nbsp;<input type="radio" name="eees_value_option" id="eees_dynamic_option" value="Dynamic"><label class="ee-email-shortcode-form"> Dynamic Value </label>
                  </td>
                </tr>
                <tr>
                  <td id="eees_static_block" style="display:block;">
                    <input class="ee-email-shortcode-form" name="ee-email-shortcode-s-field" id="ee-email-shortcode-s-field" value="<?php echo $eees_get_shortcode['eees_field']; ?>" type="text" placeholder="<?php esc_html_e(' Enter static value','ee-email-shortcode'); ?>" required>
                  </td>
                  <td style="width:30%;" align="left">
                    &nbsp;&nbsp;<input type="radio" name="eees_value_option" id="eees_static_option" value="Static" checked><label class="ee-email-shortcode-form"> Static Value </label>
                  </td>
                </tr>
              <?php
                  }
                } else{
              ?>
                <tr>
                  <td id="eees_dynamic_block" style="display:block;">
                    <input class="ee-email-shortcode-form" name="ee-email-shortcode-d-field" id="ee-email-shortcode-d-field" type="text" placeholder="<?php esc_html_e(' Enter slug of custom field','ee-email-shortcode'); ?>" required>      
                  </td>
                  <td style="width:30%;" align="left">
                    &nbsp;&nbsp;<input type="radio" name="eees_value_option" id="eees_dynamic_option" value="Dynamic" checked><label class="ee-email-shortcode-form"> Dynamic Value </label>
                  </td>
                </tr>
                <tr>
                  <td id="eees_static_block" style="display:none;">
                    <input class="ee-email-shortcode-form" name="ee-email-shortcode-s-field" id="ee-email-shortcode-s-field" type="text" placeholder="<?php esc_html_e(' Enter static value','ee-email-shortcode'); ?>">      
                  </td>
                  <td style="width:30%;" align="left">
                    &nbsp;&nbsp;<input type="radio" name="eees_value_option" id="eees_static_option" value="Static"><label class="ee-email-shortcode-form"> Static Value </label>
                  </td>
                </tr>
              <?php
                }
              ?>
              </table>
              <br/>
            </div>

      			<div id="titlewrap">
              <?php
                if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit'){
              ?>
                  <textarea class="ee-email-shortcode-form" rows="2" autocomplete="off" name="ee-email-shortcode-desc" id="ee-email-shortcode-desc" aria-hidden="true" placeholder="<?php esc_html_e(' Short Description','ee-email-shortcode'); ?>"><?php echo $eees_get_shortcode['eees_description']; ?></textarea>&nbsp;
              <?php
                } else{
              ?>
                  <textarea class="ee-email-shortcode-form" rows="2" autocomplete="off" name="ee-email-shortcode-desc" id="ee-email-shortcode-desc" aria-hidden="true" placeholder="<?php esc_html_e(' Short Description','ee-email-shortcode'); ?>"></textarea>&nbsp;
              <?php
                }
              ?>
      			</div>

          </div>
        </div>
        <!-- /post-body-content -->
        <div id="postbox-container-1" class="postbox-container">
          <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
            <div id="submitdiv" class="postbox ">
              <h2 class="hndle ui-sortable-handle">
                <span>
                  <?php 
                    if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit'){
                      esc_html_e('Update','ee-email-shortcode');
                    } else{
                      esc_html_e('Publish','ee-email-shortcode');                      
                    }
                  ?>
                </span>
              </h2>
              <div class="inside">
                <div class="submitbox" id="submitpost">
                  <div id="major-publishing-actions">
                    <div id="publishing-action">
                      <?php wp_nonce_field( 'ee-email-shortcode-nonce', '_wpnonce'); ?>

                      <?php 
                        if( isset($_REQUEST['action']) && $_REQUEST['action']=='edit'){
                      ?>
                      <input name="ee-email-shortcode-update" id="ee-email-shortcode-update" class="button button-primary button-large" value="Update" onCLick="return confirm('Are you sure you want to Update?')" type="submit">
                      <?php
                        } else{
                      ?>
                      <input name="ee-email-shortcode-publish" id="ee-email-shortcode-publish" class="button button-primary button-large" value="Publish" type="submit">
                      <?php
                        }
                      ?>
                    </div>
                    <div class="clear">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--
            <div id="formatdiv" class="postbox">
              <h2 class="hndle ui-sortable-handle">
                <span>
                  <?php //esc_html_e('Dashicons List','ee-email-shortcode'); ?>
                </span>
              </h2>
              <div class="inside">
                <div id="post-formats-select">
                  <fieldset>
                    <legend class="screen-reader-text">
                      <?php //esc_html_e('Help', 'ee-email-shortcode'); ?>
                    </legend>
                    <span class="dashicons dashicons-admin-post">
                    </span>
                    <a>&nbsp;&nbsp;dashicons-admin-post 
                    </a>
                    <br>
                    <span class="dashicons dashicons-admin-page">
                    </span>
                    <a>&nbsp;&nbsp;dashicons-admin-page 
                    </a>
                    <br>
                    <span class="dashicons dashicons-admin-users">
                    </span>
                    <a>&nbsp;&nbsp;dashicons-admin-users 
                    </a>
                    <br>
                    <span class="dashicons dashicons-video-alt2">
                    </span>
                    <a>&nbsp;&nbsp;dashicons-video-alt2 
                    </a>
                    <br>
                    <span class="dashicons dashicons-cart">
                    </span>
                    <a>&nbsp;&nbsp;dashicons-cart 
                    </a>
                    <br>
                    <span class="dashicons dashicons-editor-help">
                    </span>
                    <a>&nbsp;&nbsp;dashicons-editor-help 
                    </a>
                    <br>
                    <div style="text-align: right;">
                      <br>
                      <a style="text-decoration: none;" href="https://developer.wordpress.org/resource/dashicons/#admin-post">
                        <?php //esc_html_e('More Dashicons...', 'ee-email-shortcode'); ?>
                      </a>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      -->
    </div>
    <br class="clear">
  </form>
</div>  