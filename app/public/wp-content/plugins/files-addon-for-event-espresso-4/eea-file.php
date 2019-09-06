<?php
/*
 * Plugin Name: Files Addon for Event Espresso 4
 * Description: Files add on plugin allows to create file type question in event registration form. Attendees will be able to upload files while registering for an event. 
 * Author: aparnascodex
 * Author URI: http://aparnascodex.com/
 * License: GPL2
 * Version: 1.0.9
 * TextDomain:  wordfile
 */
// define versions and this file
define('EE_FILE_CORE_VERSION_REQUIRED', '4.8.0.rc.0000');
define('EE_FILE_VERSION', '1.0.7');
define('EE_FILE_PLUGIN_FILE', __FILE__);

/**
 *    captures plugin activation errors for debugging
 */
function ssa_file_plugin_activation_errors()
{

    if (WP_DEBUG) {
        echo $activation_errors = ob_get_contents();
        
    }
}
add_action('activated_plugin', 'ssa_file_plugin_activation_errors');

/**
 *    registers addon with EE core
 */
function ssa_load_espresso_file()
{
    if (class_exists('EE_Addon')) {
        // new_addon version
        require_once(plugin_dir_path(__FILE__) . 'EE_File.class.php');
        EE_File::register_addon();
           
    } else {
        add_action('admin_notices', 'ssa_file_activation_error');
    }
}
add_action('AHEE__EE_System__load_espresso_addons', 'ssa_load_espresso_file', 11);
 
/**
 *    verifies that addon was activated
 */
function ssa_file_activation_check()
{
    if (! did_action('AHEE__EE_System__load_espresso_addons')) {
        add_action('admin_notices', 'ssa_file_activation_error');
    }
}
add_action('init', 'ssa_file_activation_check', 1);



/**
 *    displays activation error admin notice
 */
function ssa_file_activation_error()
{
    unset($_GET[ 'activate' ]);
    unset($_REQUEST[ 'activate' ]);
    if (! function_exists('deactivate_plugins')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    deactivate_plugins(plugin_basename(EE_FILE_PLUGIN_FILE));
    ?>
  <div class="error">
    <p><?php printf(__('<b>"Event Espresso - Files" </b> add on could not be activated. Please ensure that Event Espresso version %1$s or higher is active', 'wordfile'), EE_FILE_CORE_VERSION_REQUIRED); ?></p>
  </div>
<?php
}


add_action('wp_ajax_ssa_upload_file', 'ssa_upload_file');
add_action('wp_ajax_nopriv_ssa_upload_file', 'ssa_upload_file');
function ssa_upload_file()
{
    global $wpdb;

    $table = $wpdb->prefix.'esp_question_allowed_ext';
    $data = array();
    if (isset($_FILES)) {
        if (! function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
       
        $uploadedfile = $_FILES['file'];
        $qid = $_POST['qst_id'];
        $ext = $wpdb->get_var("SELECT ext FROM $table WHERE qst_id='$qid'");
        $allowed =  array('gif','png' ,'jpg','jpeg','bmp');
        if ($ext !='') {
            $allowed = explode(',', $ext);
        }

        $allowed=array_map('trim', $allowed);
       
        $filename = $_FILES['file']['name'];
        $extn = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($extn, $allowed)) {
            $data = array('error' => __("Only formats are allowed : ".implode(', ', $allowed),'wordfile'));
        }
        
        $upload_overrides = array( 'test_form' => false ,'unique_filename_callback' => 'ssa_change_filename'  );

        add_filter('upload_dir', 'ssa_change_uploads_directory_path');
          
        $ssafile = wp_handle_upload($uploadedfile, $upload_overrides);

        remove_filter('upload_dir', 'ssa_change_uploads_directory_path');
       
        if ($ssafile && ! isset($ssafile['error'])) {
            $data =  array('files' => $ssafile);

        } else {
            $data =  array('error' => $ssafile['error']) ;
        }
    } else {
        $data = array('error' => __('No file submitted','wordfile'));
    }

    echo json_encode($data);
    die();
}

//override filename
function ssa_change_filename($dir, $name, $ext){
    $filename = $name.$ext;
    $filename = apply_filters('ssa_override_filename',$filename);
    return $filename;
}

function ssa_change_uploads_directory_path($path)
{
  
   
    $path = apply_filters('ssa_change_file_upload_path', $path);
   
    return $path;
}


function ssa_create_ext_tbl()
{
    $val = get_option('file_db_created');
    if($val != 1)
    {
    global $wpdb;
    $tbl = $wpdb->prefix.'esp_question_allowed_ext';
 
    
    if ($wpdb->get_var("show tables like '$tbl'") != $tbl) {
        $sql = "CREATE TABLE " . $tbl . " (
        id int NOT NULL AUTO_INCREMENT,
        qst_id int,
        ext varchar(100),
        UNIQUE KEY id (id)
        );";
 
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    update_option('file_db_created',1);
    }
}
add_action('admin_head', 'ssa_create_ext_tbl');
