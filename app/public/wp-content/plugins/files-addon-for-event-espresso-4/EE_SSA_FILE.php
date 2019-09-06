<?php
class EE_SSA_FILE extends EE_Form_Input_Base
{

    /**
     * @param array $options
     */
    function __construct($options = array())
    {
        
        $this->_set_display_strategy(new EE_File_Display_Strategy());
        $this->_set_normalization_strategy(new EE_Text_Normalization());
        $this->_add_validation_strategy(new EE_FILE_Validation_Strategy());
        parent::__construct($options);
        EE_SSA_FILE::ssa_enqueue_scripts();
    }
    /*
    * Enqueue scripts and styles
    */
    static function ssa_enqueue_scripts()
    {
        global $wpdb;
        $table = $wpdb->prefix.'esp_question_allowed_ext';
        $ext = $wpdb->get_results("SELECT * FROM $table");
        $arr = array();
        foreach ($ext as $extension) {
            $id = $extension->qst_id;
            $et = $extension->ext;
            $arr[$id] = $et;
        }
        $d = json_encode($arr);
        wp_enqueue_style('ssa_file_css', plugins_url('css/ssa_style.css', __FILE__));
        $year = date('Y');
        $month= date('m');
        $path = wp_upload_dir();
        $uploads = $path['basedir']."/$year/$month/";
        $uploads_url = $path['baseurl']."/$year/$month/";
        $ajax_path = admin_url('admin-ajax.php');
        wp_enqueue_script('ssa_file_upload', plugins_url('js/ssa_file_upload.js', __FILE__), array('jquery'));
        wp_localize_script(
            'ssa_file_upload',
            'ssa_var_ds',
            array(
                'ajax_path' =>$ajax_path,
                'ssa_upload'=>$uploads,
                'ssa_url'=>$uploads_url,
                'extensions'=> $d)
        );
    }
}
