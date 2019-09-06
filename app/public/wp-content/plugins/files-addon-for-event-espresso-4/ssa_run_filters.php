<?php
add_filter('FHEE__EEM_Question__construct__allowed_question_types', 'ssa_add_file_type', 10, 1);
function ssa_add_file_type($question_types)
{
    $question_types['file']= __('File', 'wordfile');
    return $question_types;
}
add_filter('FHEE__EE_SPCO_Reg_Step_Attendee_Information___generate_question_input__default', 'ssa_render_question', 9, 4);
function ssa_render_question($param1, $type, $question, $args)
{
    if ($type == 'file') {
        return new EE_SSA_FILE($args);
    } else {
        return $param1;
    }
}
add_filter('FHEE__EEH_Form_Fields__input_html', 'ssa_add_image_icon', 10, 1);
function ssa_add_image_icon($input)
{
    $pattern = '/value=\"(.*?)\"/i';
    preg_match_all($pattern, $input, $matches);
    $url = $matches[1][0];
    $len =strlen($url);
    $pos = strrpos($url, '.');
    if ($pos != -1) {
        $arr = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
         $str = substr($url, $pos+1);
        if (in_array($str, $arr)) {
            $input = "$input<br><img src='$url' style='padding:3px;border:1px solid #ccc;height:100px;width:100px'>";
        }
    }
    return $input;
    
}
