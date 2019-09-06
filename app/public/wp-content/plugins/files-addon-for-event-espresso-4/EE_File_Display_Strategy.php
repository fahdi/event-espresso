<?php
class EE_File_Display_Strategy extends EE_Display_Strategy_Base
{
    /**
     * The html "type" attribute value. default is "text"
     * @var string
     */
    protected $_type;
    /**
     * @param string $type
     */
    function __construct($type = 'file')
    {
       
        $this->_type = $type;
        parent::__construct();
       
    }
    /**
     * Gets the html "type" attribute's value
     * @return string
     */
    function get_type()
    {
        return $this->_type;
    }
    /**
     *
     * @return string of html to display the field
     */
    public function display()
    {
     
       
        $id = $this->_input->html_id();
        if(is_admin())
        {
            $que_id =  $this->_input->name();
            $reg_id = isset($_REQUEST['_REG_ID'])?$_REQUEST['_REG_ID']:'';
            $id = 'ssa_ee_reg_qstn-'.$reg_id.'-'.$que_id;
        }

        $value = $this->_input->raw_value_in_form();
        $input = '<input type="'. $this->get_type() .'"';
        $input .= ' name="ssa_' . $this->_input->html_name() . '"';
        $input .= ' id="' . $id . '"';
        $class = $this->_input->required() ? $this->_input->required_css_class() . ' ' . $this->_input->html_class() : $this->_input->html_class();
        $input .= ' class="' . $class . ' ssa-file"';
        $input .= $this->_input->required() ? ' required' : '';
        $input .= ' value="' . $this->_input->raw_value_in_form() . '"';
        $input .= ' style="' . $this->_input->html_style() . '"';
        $input .= $this->_input->other_html_attributes();
        $input .= '/>';
        $input .= "<span class='ssa-remove'></span>";
        $input .= "<span class='ssa-loading'></span>";
        $input .= '<input type="hidden"';
        $input .= ' name="' . $this->_input->html_name() . '"';
        $input .= ' id="' . $id . '"';
        $class = $this->_input->required() ? $this->_input->required_css_class() . ' ' . $this->_input->html_class() : $this->_input->html_class();
        $input .= ' class="file_value ' . $class . '"';
        // add html5 required
        $input .= $this->_input->required() ? ' required' : '';
        $input .= ' value="' . $this->_input->raw_value_in_form() . '"';
        $input .= ' style="' . $this->_input->html_style() . '"';
        $input .= $this->_input->other_html_attributes();
        $input .= '/>';
        if ($value != '') {
            $filetype = wp_check_filetype($value);
            $extn =  $filetype['ext'];
            $ar = explode('/',$value);
            $len = count($ar);
            $nm = $ar[$len-1];
            $allowed =  array('gif','png' ,'jpg','jpeg','bmp');
            if(in_array($extn, $allowed))
                $input .= "<br><a href='$value' target='_blank'><img src='$value' style='padding:3px;border:1px solid #ccc;height:100px;width:100px'></a>";
            else
                $input .= "<br><a href='$value' target='_blank'>$nm</a>";
        }
        return $input;
    }
}
