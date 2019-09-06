<?php
if (! defined('EVENT_ESPRESSO_VERSION')) {
    exit();
}


define('EE_FILE_BASENAME', plugin_basename(EE_FILE_PLUGIN_FILE));
define('EE_FILE_PATH', plugin_dir_path(__FILE__));
define('EE_FILE_URL', plugin_dir_url(__FILE__));


class EE_File extends EE_Addon
{

    public static function register_addon()
    {
        // register addon via Plugin API
        EE_Register_Addon::register(
            'EE_File',
            array(
                'version'               => EE_FILE_VERSION,
                'plugin_slug'           => 'ssa_ee_file',
                'min_core_version'      => EE_FILE_CORE_VERSION_REQUIRED,
                'main_file_path'        => EE_FILE_PLUGIN_FILE,
                'autoloader_paths' => array(
                    'EE_File'   => EE_FILE_PATH . 'EE_File.class.php',
                    
                ),
               
                                    
            )
        );
        add_action('admin_enqueue_scripts', array('EE_File','ssa_load_scripts'));
        require_once "EE_FILE_Validation_Strategy.php";
        require_once "EE_File_Display_Strategy.php";

        require_once "EE_SSA_FILE.php";
        include_once "ssa_run_filters.php";
    }
    public static function ssa_load_scripts($hook)
    {
       
        global $wpdb;
        $table = $wpdb->prefix.'esp_question_allowed_ext';
        $ext ='';
        if ($hook == 'event-espresso_page_espresso_registration_form') {
            if (isset($_GET['QST_ID']) && $_GET['QST_ID'] != '') {
                $que_id = $_GET['QST_ID'];
                $ext = $wpdb->get_var("SELECT ext FROM $table WHERE qst_id='$que_id'");
            }
           
            wp_enqueue_style('admin-file-css', plugins_url('css/admin-screen.css', __FILE__));
            wp_enqueue_script('file-js', plugins_url('js/admin-js.js', __FILE__), array('jquery'));
            wp_localize_script('file-js', 'ssa_var_ds', array('ext'=>$ext));
        }
    }
}
add_action('AHEE__Extend_Registration_Form_Admin_Page___redirect_after_action__before_redirect_modification_insert_question', 'update_ext_function', 10, 1);

add_action('AHEE__Extend_Registration_Form_Admin_Page___redirect_after_action__before_redirect_modification_update_question', 'update_ext_function', 10, 1);

function update_ext_function($args)
{
    global $wpdb;
    $table = $wpdb->prefix.'esp_question_allowed_ext';
    $question_id = $args['QST_ID'];
    if($_POST['QST_type'] == 'file')
    {
      $count = $wpdb->get_var("SELECT COUNT(id) FROM $table WHERE qst_id='{$question_id}'");
      $ext = $_POST['question_ext'];
      
      
      if ($count > 0) {
          
            $wpdb->update(
                $table,
                array('ext'=>$ext),
                array('qst_id'=>$question_id),
                array('%s'),
                array('%d')
            );
      } else {
          
           $wpdb->insert(
               $table,
               array('ext'=>$ext,
                      'qst_id'=>$question_id),
               array( '%s',
                      '%d')
           );
      }
    }
  
   
}
