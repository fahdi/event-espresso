<?php
class EE_FILE_Validation_Strategy extends EE_Validation_Strategy_Base
{

    /**
     * @param null $validation_error_message
     */
    public function __construct($validation_error_message = null)
    {

        if (! $validation_error_message) {
            $validation_error_message = __("Please enter a valid URL", "event_espresso");
        }
        parent::__construct($validation_error_message);
    }



    /**
     * just checks the field isn't blank
     *
     * @param $normalized_value
     * @return bool
     * @throws \EE_Validation_Error
     */
    public function validate($normalized_value)
    {
        global $wpdb;
        $qid = $this->_input->name();
        
        $table = $wpdb->prefix.'esp_question_allowed_ext';
      
        $ext = $wpdb->get_var("SELECT ext FROM $table WHERE qst_id='$qid'");
        $ex = ($ext != '')? explode(',',$ext) : '';
       
        if($normalized_value != '')
        {
            $filetype = wp_check_filetype($normalized_value);
            $data = parse_url($normalized_value);
            
            $host = $data['host'];
            $server = $_SERVER['SERVER_NAME'];
            if($ex == '')
                $allowed =  array('gif','png' ,'jpg','jpeg','bmp');
            else
                $allowed = $ex;
            
            $extn =  $filetype['ext'];
            if (!in_array($extn, $allowed) || strpos($host,$server) === FALSE) {
                throw new EE_Validation_Error($this->get_validation_error_message(), 'regex');
            }
            parent::validate($normalized_value);
        }
        else
        {
            return true;
           
        }
    }
}
