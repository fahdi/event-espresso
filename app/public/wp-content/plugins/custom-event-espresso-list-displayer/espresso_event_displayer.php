<?php
/* 

    Plugin Name: Custom Event Espresso Event Displayer

    Plugin URI: http://www.daryl-phillips.co.uk/2012/01/18/event-espresso-custom-event-display-plugin/

    Description: Plugin to use custom display methods as an expansion to Event Espresso. Use [CUSTOM_ESPRESSO_EVENT_DISPLAYER] shortcode on any given page to get the events listed.

    Author: D. Phillips

    Version: 2.0

    Author URI: http://www.daryl-phillips.co.uk/

	Copyright 2013  Daryl Phillips  (email : wp_plugins@daryl-phillips.co.uk)

	Donate: http://www.daryl-phillips.co.uk/2012/01/18/event-espresso-custom-event-display-plugin/


    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License, version 2, as 

    published by the Free Software Foundation.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

wp_enqueue_script('jquery');

/*
* Defaults for Event espresso displayer
*/
$event_displayer_defaults = array(
	'displayer_year' => '0',
	'standard_theme_font_style' => '1',
	'displayer_link' => '21759B',
	'displayer_link_hover' => '444444',
	'displayer_bg_colour' => 'EDEDED',
	'displayer_bg_color_hover' => 'CCCCCC',
	'displayer_border' => 'DEDEDE',
	'displayer_month_active' => '21759B',
	'displayer_month_active_text' => 'FFFFFF',
	'displayer_month_inactive' => 'CCCCCC',
	'displayer_month_inactive_border' => '999999',
	'displayer_month_inactive_text' => '000000',
	'displayer_events_to_show' => 0,
	'displayer_event_excerpt' => 250,
	'displayer_event_avail_header_colour' => '21759B',
	'displayer_event_avail_header_text_colour' => 'FFFFFF',
	'displayer_event_avail_bg_colour' => 'EDEDED',
	'displayer_event_avail_bg_border_colour' => 'DEDEDE',
	'displayer_event_reg_passed_header_colour' => '521D0A',
	'displayer_event_reg_passed_header_text_colour' => 'FFFFFF',
	'displayer_event_reg_passed_bg_colour' => 'EDEDED',
	'displayer_event_reg_passed_bg_border_colour' => 'DEDEDE',
	'displayer_no_events_text' => 'Sorry, there are no events planned for {MONTH} {YEAR}. Please check other months for event availability.',
	'displayer_event_link_text' => 'Register for event &raquo;',
	'displayer_event_reg_passed_text' => 'Registration for this event has passed.',
	'date_format' => array('jS M Y', 'D - j M Y')
);

/*
* Plugin Path and Plugin URL variables
*/
define("CUSTOM_ESPRESSO_EVENT_DISPLAY_PLUGINPATH", "/" . plugin_basename( dirname(__FILE__) ) . "/");
define("CUSTOM_ESPRESSO_EVENT_DISPLAY", WP_PLUGIN_URL . CUSTOM_ESPRESSO_EVENT_DISPLAY_PLUGINPATH );

function custom_event_add_inline_styles() {
	global $event_displayer_defaults;
	
	$options = wp_parse_args(get_option('custom_event_displayer'), $event_displayer_defaults);
	
	$custom_css = '/* Displaying Events */
.list_events .month {
	font-size:18px;
}

.item_left {
    width: 32%;
    float: left;
    margin-right: 10px;
    font-size: 12px !important;
    background-color: #'.$options['displayer_event_avail_bg_colour'].';
    border: 1px solid #'.$options['displayer_event_avail_bg_border_colour'].';
    overflow: hidden;
}

.item_left.passed {
    background-color: #'.$options['displayer_event_reg_passed_bg_colour'].';
    border: 1px solid #'.$options['displayer_event_reg_passed_bg_border_colour'].';
}

.item_left .event_header {
	background-color:#'.$options['displayer_event_avail_header_colour'].';
}

.item_left.passed .event_header {
	background-color:#'.$options['displayer_event_reg_passed_header_colour'].';
}

.item_left .event_header p {    
	padding:5px 0 5px 0;
}

.item_left .event_desc, .item_left .event_header p, .item_left .event_more, .item .item_left .event_price {
	padding-left:10px;
}

.item_left .event_desc {
	padding:0 10px;	
}

.event_desc p, .event_price p, .event_more p {
	margin-bottom:10px !important;
}

/* Months Display */
#months {
	height:36px;	
}

#months ul {
	list-style-type:none;
	margin:0;
	padding:0;
}

#months ul li {
	float: left;
	display: inline;
	width: 8%;
	padding: 5px 0;
	background-color: #'.$options['displayer_bg_colour'].';
	border: 1px solid #'.$options['displayer_border'].';
	text-align: center;
	margin: 0;
	border-right: 1px solid white;
}

#months ul li.last {
	margin-right:0;	
	border-right: 1px solid #'.$options['displayer_border'].';
}

#months ul li.active {
	background-color:#'.$options['displayer_month_active'].';
}

#months ul li:hover, #months ul li.inactive {
	background-color:#'.$options['displayer_month_inactive'].';
	border-color:#'.$options['displayer_month_inactive_border'].';
}

#months ul li.inactive {
	border-right: 1px solid white;
}

#months ul li.active a {
	text-decoration:none !important;
}

/* Year - Pagination */
#years {
	text-align:center;
}

#years span {
	padding:10px;	
}

#years span a.current {
	text-decoration:none;
	font-weight:bold;
}

/* Ajax Loading Bar */
#ajax-loader {
	display:none;
	width: 100%;
	text-align: center;
	margin: 50px 0;	
}';

	if($options['standard_theme_font_style'] == 1) {
		$custom_css .= '.item_left .event_header p {color:#'.$options['displayer_event_avail_header_text_colour'].';} .item_left.passed .event_header p {color:#'.$options['displayer_event_reg_passed_header_text_colour'].';} #months ul li.active a {color:#'.$options['displayer_month_active_text'].';}';
	}
	
	return $custom_css;
}

/*
* Administration Panel
*/
function displayerOptions() {
    add_menu_page('Event Displayer', 'Event Displayer', 'administrator', 'displayer_settings', 'displayer_display_settings');
}


/*
* Set the number of years to appear on the front end as a <select> dropdown
*/
function showYears($current_year) {
	$year_html = '<select id="displayer_year" name="displayer_year">';

	for($i = 0; $i < 11;$i++) {
		$active = ($current_year == $i) ? 'selected="selected"':'';
		$year_html .= '<option value="'.$i.'" '.$active.'>'.$i.'</option>';
	}
	
	$year_html .= '</select>';
	return $year_html;
}


/*
* Set the number of events to appear on the front end as a <select> dropdown
*/
function showNumEvents($val) {
	$year_html = '<select id="displayer_events_to_show" name="displayer_events_to_show">';

	for($i = 0; $i < 11;$i++) {
		$text = ($i == 0) ? 'All':$i;
		$active = ($val == $i) ? 'selected="selected"':'';
		$year_html .= '<option value="'.$i.'" '.$active.'>'.$text.'</option>';
	}
	
	$year_html .= '</select>';
	return $year_html;
}

/*
* Create input field for the various different colours used within the plugin
*/
function showLinkColour($name, $value) {
	return '#&nbsp;<input maxlength="6" id="'.$name.'" type="text" name="'.$name.'" value="'.strtoupper($value).'" class="colour" />';	
}

/*
* Create drop down of dates based on the default date variants
*/
function showDateRanges($format) {
	global $event_displayer_defaults;
	
	$html = '<select id="date_format" name="date_format">';
	
	foreach($event_displayer_defaults['date_format'] as $date_format) {
		$active = ($format == $date_format) ? 'selected="selected"':'';
		$html .= '<option value="'.$date_format.'" '.$active.'>'.$date_format.' - ('.date($date_format).')</option>';
	}
	
	$html .= '</select>';
	return $html;
}


/*
* Return the last day of the month
*/
function lastOfMonth($month) {
	return date("d", strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.date('Y').' 00:00:00'))));
}


/*
* Administration Panel Display
* Displaying the configuration form for editing and managing how the displayer is presented on screen.
*/
function displayer_display_settings() {
	global $event_displayer_defaults;
	
	if ($_POST["action"] == 'Update') {
		
		//do the saving	
		if($_POST['Submit'] == 'Reset to default') {
			$options = $event_displayer_defaults; //reset settings back to defaults
		}
		else {
			//customised settings
			$options['displayer_year'] = $_POST['displayer_year'];
			$options['standard_theme_font_style'] = $_POST['standard_theme_font_style'];
			$options['displayer_link'] = $_POST['displayer_link'];
			$options['displayer_link_hover'] = $_POST['displayer_link_hover'];
			$options['displayer_bg_colour'] = $_POST['displayer_bg_colour'];
			$options['displayer_bg_color_hover'] = $_POST['displayer_bg_color_hover'];
			$options['displayer_border'] = $_POST['displayer_border'];
			$options['displayer_month_active'] = $_POST['displayer_month_active'];
			$options['displayer_month_active_text'] = $_POST['displayer_month_active_text'];
			$options['displayer_month_inactive'] = $_POST['displayer_month_inactive'];
			$options['displayer_month_inactive_border'] = $_POST['displayer_month_inactive_border'];
			$options['displayer_month_inactive_text'] = $_POST['displayer_month_inactive_text'];
			$options['displayer_events_to_show'] = $_POST['displayer_events_to_show'];
			$options['displayer_event_excerpt'] = $_POST['displayer_event_excerpt'];
			$options['displayer_event_avail_header_colour'] = $_POST['displayer_event_avail_header_colour'];
			$options['displayer_event_avail_header_text_colour'] = $_POST['displayer_event_avail_header_text_colour'];
			$options['displayer_event_avail_bg_colour'] = $_POST['displayer_event_avail_bg_colour'];
			$options['displayer_event_avail_bg_border_colour'] = $_POST['displayer_event_avail_bg_border_colour'];
			$options['displayer_event_reg_passed_header_colour'] = $_POST['displayer_event_reg_passed_header_colour'];
			$options['displayer_event_reg_passed_header_text_colour'] = $_POST['displayer_event_reg_passed_header_text_colour'];
			$options['displayer_event_reg_passed_bg_colour'] = $_POST['displayer_event_reg_passed_bg_colour'];
			$options['displayer_event_reg_passed_bg_border_colour'] = $_POST['displayer_event_reg_passed_bg_border_colour'];
			$options['displayer_no_events_text'] = $_POST['displayer_no_events_text'];
			$options['displayer_event_link_text'] = $_POST['displayer_event_link_text'];
			$options['displayer_event_reg_passed_text'] = $_POST['displayer_event_reg_passed_text'];
			$options['date_format'] = $_POST['date_format'];
		}

		//Update options
		update_option('custom_event_displayer', $options);
	}
	
	//vars	
	$options = wp_parse_args(get_option('custom_event_displayer'), $event_displayer_defaults);
	
	/* HTML BUILDING */?>

	</pre>
	
	<?php if($_POST) echo '<div id="message" class="updated">Settings saved</div>';?>
    
	<div class="wrap">
    	<div id="icon-options-general" class="icon32"></div>
        
		<h2>
			<?php _e('Custom Event Displayer: Settings', 'displayerOptions'); ?>
		</h2>
        
		<form method="post" action="#save">
        	<h3><?php _e('Month Switcher Styles', 'displayerOptions'); ?></h3>
            
			<table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Years to show:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Years to show:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_year">
                                    <?=showYears($options['displayer_year'])?>
                                </label>
                                <p style="color:#999;">Number of years to show. For example, 3 will give you <em><strong>2013 | 2014 | 2015</strong></em>.</p>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Use standard theme font styles?', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Use standard theme font styles?', 'displayerOptions'); ?></span></legend>
                                <label for="standard_theme_font_style">
                                    <input type="checkbox" name="standard_theme_font_style" value="1" id="standard_theme_font_style" <?php if($options['standard_theme_font_style'] == 1) echo 'checked="checked"';?> />
                                </label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="fonts">
                        <th scope="row"><?php _e('Link Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Link Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_link">
                                    <?=showLinkColour('displayer_link', $options['displayer_link'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top" class="fonts">
                        <th scope="row"><?php _e('Link Hover Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Link Hover Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_link_hover">
                                    <?=showLinkColour('displayer_link_hover', $options['displayer_link_hover'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>  
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Month Background Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Background Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_bg_colour">
                                    <?=showLinkColour('displayer_bg_colour', $options['displayer_bg_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                                      
                    <tr valign="top">
                        <th scope="row"><?php _e('Month Hover Background Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Hover Background Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_bg_color_hover">
                                    <?=showLinkColour('displayer_bg_color_hover', $options['displayer_bg_color_hover'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Month Border:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Border:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_border">
                                    <?=showLinkColour('displayer_border', $options['displayer_border'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Month Active Background Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Active  Background Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_month_active">
                                    <?=showLinkColour('displayer_month_active', $options['displayer_month_active'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top" class="fonts">
                        <th scope="row"><?php _e('Month Active Text Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Active  Text Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_month_active_text">
                                    <?=showLinkColour('displayer_month_active_text', $options['displayer_month_active_text'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Month Inactive Background Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Inactive  Background Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_month_inactive">
                                    <?=showLinkColour('displayer_month_inactive', $options['displayer_month_inactive'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Month Inactive Border Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Inactive  Border Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_month_inactive_border">
                                    <?=showLinkColour('displayer_month_inactive_border', $options['displayer_month_inactive_border'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top" class="fonts">
                        <th scope="row"><?php _e('Month Inactive Text Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Month Inactive  Text Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_month_inactive_text">
                                    <?=showLinkColour('displayer_month_inactive_text', $options['displayer_month_inactive_text'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
			</table>
            
            <br />
            
            <hr />
            
            <br />
            
        	<h3><?php _e('Viewing events', 'displayerOptions'); ?></h3>
            
			<table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Number of events to show:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Number of events to show:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_events_to_show">
                                	<?=showNumEvents($options['displayer_events_to_show']);?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Excerpt Length:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Excerpt Length:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_excerpt">
                                	<input id="displayer_event_excerpt" type="text" name="displayer_event_excerpt" value="<?=$options['displayer_event_excerpt']?>" />
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"></th>
                        <td></td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Available - Header Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Available - Header Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_avail_header_colour">
                                	<?=showLinkColour('displayer_event_avail_header_colour', $options['displayer_event_avail_header_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Available - Header Text Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Available - Header Text Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_avail_header_text_colour">
                                	<?=showLinkColour('displayer_event_avail_header_text_colour', $options['displayer_event_avail_header_text_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php _e('Event Available - Background Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Available - Background Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_avail_bg_colour">
                                	<?=showLinkColour('displayer_event_avail_bg_colour', $options['displayer_event_avail_bg_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Available - Border Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Available - Border Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_avail_bg_border_colour">
                                	<?=showLinkColour('displayer_event_avail_bg_border_colour', $options['displayer_event_avail_bg_border_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"></th>
                        <td></td>
                    </tr>
                    
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Registration Passed - Header Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Registration Passed - Header Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_avail_header_colour">
                                	<?=showLinkColour('displayer_event_reg_passed_header_colour', $options['displayer_event_reg_passed_header_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Registration Passed - Header Text Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Registration Passed - Header Text Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_avail_header_text_colour">
                                	<?=showLinkColour('displayer_event_reg_passed_header_text_colour', $options['displayer_event_reg_passed_header_text_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Registration Passed - Background Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Registration Passed - Background Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_avail_bg_colour">
                                	<?=showLinkColour('displayer_event_reg_passed_bg_colour', $options['displayer_event_reg_passed_bg_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Registration Passed - Border Colour:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Registration Passed - Border Colour:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_reg_passed_bg_border_colour">
                                	<?=showLinkColour('displayer_event_reg_passed_bg_border_colour', $options['displayer_event_reg_passed_bg_border_colour'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
               	</tbody>
            </table>
            
            <br />
            
            <hr />
            
            <br />
            
        	<h3><?php _e('Viewing events - Structure', 'displayerOptions'); ?></h3>
            
            <?php /*<p>You can use the following merge tags to move content around. If none of these are added, they will be ignored.</p>
            
            <ul>
            	<li><strong>{TITLE}</strong> - Shows event title</li>
                <li><strong>{DATE}</strong> - Shows date</li>
                <li><strong>{DESCRIPTION}</strong> - Shows event description</li>
                <li><strong>{PRICE}</strong> - Shows price</li>
            </ul>
            
			<table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Header Structure:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Header Structure:', 'displayerOptions'); ?></span></legend>
                                <label for="displaying_events_header">
                                	<input id="displaying_events_header" type="text" name="displaying_events_header" value="<?=$options['displaying_events_header']?>" style="width:75%;" />
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Content Structure:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Content Structure:', 'displayerOptions'); ?></span></legend>
                                <label for="displaying_events_content">
                                	<input id="displaying_events_content" type="text" name="displaying_events_content" value="<?=$options['displaying_events_content']?>" style="width:75%;" />
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    */?>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Date Format:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Date Format:', 'displayerOptions'); ?></span></legend>
                                <label for="displaying_events_date_format">
                                	<?=showDateRanges($options['date_format'])?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
               	</tbody>
            </table>
            
            <br />
            
            <hr />
            
            <br />
            
        	<h3><?php _e('Messages', 'displayerOptions'); ?></h3>
            
			<table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('No events available:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('No events available:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_no_events_text">
                                	<textarea name="displayer_no_events_text" id="displayer_no_events_text" cols="100"><?=$options['displayer_no_events_text']?></textarea>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Registration Link Text:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Registration Link Text', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_link_text">
                                	<textarea name="displayer_event_link_text" id="displayer_event_link_text" cols="100"><?=$options['displayer_event_link_text']?></textarea>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Registration Passed Text:', 'displayerOptions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Event Registration Passed Text:', 'displayerOptions'); ?></span></legend>
                                <label for="displayer_event_reg_passed_text">
                                	<textarea name="displayer_event_reg_passed_text" id="displayer_event_reg_passed_text" cols="100"><?=$options['displayer_event_reg_passed_text']?></textarea>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
               	</tbody>
            </table>
            
            <br />
            
            <hr />
            
            <br />
            
            <input type="hidden" name="action" value="Update" />
            
            <input class="button-primary" type="submit" name="Submit" value="<?php _e('Save Options', 'displayerOptions'); ?>" /> | <input class="button-primary" type="submit" name="Submit" value="<?php _e('Reset to default', 'displayerOptions'); ?>" />
        </form>
   	</div>

	<link rel="stylesheet" href="<?=CUSTOM_ESPRESSO_EVENT_DISPLAY?>/resources/colorpicker/css/colorpicker.css" />

	<script type="text/javascript" src="<?=CUSTOM_ESPRESSO_EVENT_DISPLAY?>/resources/colorpicker/js/colorpicker.js" /></script>
	
    <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.colour').ColorPicker({
				onSubmit: function(hsb, hex, rgb, el) {
					jQuery(el).val(hex);
					jQuery(el).ColorPickerHide();
				},
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).find('.colorpicker_submit').trigger('click');
					return false;
				},
				onBeforeShow: function () {
					jQuery(this).ColorPickerSetColor(this.value);
				}
			});
										
			jQuery('#standard_theme_font_style').click(function() {
				jQuery('.fonts').toggle('slow', 'linear');
			});
			
			<?php if($options['standard_theme_font_style'] == 1) {?>
				jQuery('.fonts').hide();
			<?php }?>
		});
	</script>
<?php }


//Creates the pagination buttons at the top of the page - front end
function createYearsPagination($current_year, $years_to_show) {
	$string = '';
	
	if($years_to_show > 0) {
		for($i = date("Y");$i<date("Y")+$years_to_show;$i++) {
			$class = ($current_year == $i) ? 'current':'';
			$string .= '<span><a href="#" class="'.$class.' showYear" data-year="'.$i.'">'.$i.'</a></span> |';
		}

		$string = substr($string, 0, strlen($string) - 2);	
	}
	
	return $string;
}

// Starts preparing the displayer by getting the data from the Event Espresso database
function getEspressoEvents($current_year, $month, $month_end, $month_name, $options) {
	global $wpdb, $org_options;	
	
	//set vars - month start/end
	$month_start_date = date("Y-m-d", strtotime($current_year."-".$month."-01"));
	$month_end_date = date("Y-m-d", strtotime($current_year."-".$month."-".$month_end));
										   
	//set limit - if needed
	$limit = ($options['displayer_events_to_show'] == 0) ? '':'LIMIT 0,'.$options['displayer_events_to_show'];
	
	//Build query for getting events
	$events = sprintf("SELECT e.* FROM %s e WHERE start_date >= '%s' AND end_date <= '%s' AND event_status != 'D' ORDER BY id ASC %s",
	mysql_real_escape_string(EVENTS_DETAIL_TABLE),			
	mysql_real_escape_string($month_start_date),
	mysql_real_escape_string($month_end_date),
	mysql_real_escape_string($limit));
	
	//get events data
	$events_data = $wpdb->get_results($events);	
	$event_html .= '<div id="month_'.$month_name.'" class="list_events"';
	$event_html .= (date("F") != $month_name) ? ' style="display:none;">':'>';

	//if we have any events
	if($events_data) {			
		$event_html .= '<div class="month"><h3>'.$month_name.' '.$current_year.'</h3></div><div class="item">';
		
		//foreach event - build a panel
		foreach($events_data as $event) {
			//Add a class for passed events
			$finished = (time() > strtotime($event->registration_end)) ? 'passed':'';

			//get event cost
			$price_query = sprintf("SELECT event_cost FROM %s WHERE event_id = '%d'",
			mysql_real_escape_string(EVENTS_PRICES_TABLE),
			mysql_real_escape_string($event->id));
			$price_result = $wpdb->get_row($price_query);
			
			//show price - if we have one
			if($price_result) {
				$price = $price_result->event_cost;
				$price = ($price > 0) ? $org_options['currency_symbol'].$price:"<strong>FREE</strong>";
			}
			
			//event title
			$title = $event->event_name. " - ".date($options['date_format'], strtotime($event->start_date));
			$title_size = strlen($title); //length of title

			//event URL - building
			$event_url = '?page_id=' . $org_options['event_page_id'] . '&regevent_action=register&event_id=' . $event->id;
			
			//Build panel
			$event_html .= '<div class="item_left '.$finished.'">';
			$event_html .= '<div class="event_header"><p>'.stripslashes($title).'</p></div>';
			$event_html .= createEventDesc($event->event_desc, $options['displayer_event_excerpt']);

			//If event registration has not passed.
			if($finished == '') {
				$event_html .= '<div class="event_price"><p>Price: '.$price.'</p></div>';
				$event_html .= '<div class="event_more"><p><a href="'.$event_url.'" title="'.$options['displayer_event_link_text'].'">'.$options['displayer_event_link_text'].'</a></p></div>';
			}
			else {
				$event_html .= '<div class="event_more"><p><strong>'.$options['displayer_event_reg_passed_text'].'</strong></p></div>';
			}
			
			$event_html .= '</div>';
		}

		$event_html .= '</div></div>';
	}
	else {			
		$event_html .= '<div class="month"><h3>'.$month_name.' '.$current_year.'</h3></div>';

		//process data from admin panel
		$no_events_html = $options['displayer_no_events_text'];
		$no_events_html = str_replace("{MONTH}", $month_name, $no_events_html);
		$no_events_html = str_replace("{YEAR}", $current_year, $no_events_html);
		
		//append to the html for the front end
		$event_html .= $no_events_html.'</div>';
	}
	
	//return data
	return $event_html;
}


/* Function to display the events - used on load and AJAX request */
function display_events(){
	echo '<div id="displayer_holder">';
	displaying_events();
	addAjaxLoadingBar();
	echo '</div>';
}

/* Display events and month rotator for a given year */
function displaying_events($this_year = null) {
	global $wpdb, $org_options, $event_displayer_defaults;	
	
	//get vars
	$options = wp_parse_args(get_option('custom_event_displayer'), $event_displayer_defaults);

	//base html variables
	$years_html = '<div id="years"><p><!--YEAR_LOOP--></p></div>';
	$month_html = '<div id="months"><ul>';
	$current_year = ($this_year != null) ? $this_year:date("Y");

	$event_html = '';

	//Years - Show x years in advance (if set)
	$years_html = str_replace("<!--YEAR_LOOP-->", createYearsPagination($current_year, $options['displayer_year']), $years_html);
	
	//Months - Show months
	for($i = 1; $i < 13; $i++) {
		$month = $i; //set month
		$month_name = date("F", strtotime($current_year."-".$month."-01")); //get month name
		$month_name_abr = date("M", strtotime($current_year."-".$month."-01")); //month abbre
		$month_end = lastOfMonth($month); //last day of the month

		$month_html .= '<li class="<!--class_name-->"><a id="month'.$i.'" href="#month_'.$month_name.'" title="View '.$month_name.' &raquo;">'.$month_name_abr.'</a></li>';

		//Current viewable month
		if(date("F") == $month_name) {
			$class = '';
			
			//set class based on first and last elements
			switch($month) {
				case 1:
					$class = " first";
					break;
				case 12:
					$class = " last";
					break;
			}
			
			$month_html = str_replace("<!--class_name-->", "active".$class, $month_html);
		}
		else {
			$class = '';
			
			//set class based on first and last elements
			switch($month) {
				case 1:
					$class = " first";
					break;
				case 12:
					$class = " last";
					break;
			}
			
			//If we are in the future (or past) invalid months will not be shown or clickable
			if(date("Y") == $current_year) {
				$class = ($month > date("m")) ? $class:$class." inactive";
			}

			$month_html = str_replace("<!--class_name-->", $class, $month_html);
		}

		$event_html .= getEspressoEvents($current_year, $month, $month_end, $month_name, $options);
	}

	//End of For loop
	$event_html = '<div id="month_holder">'.$event_html.'</div>';

	if($event_html != '') {

		echo $years_html.$month_html."</ul></div>".$event_html;
	}
	else {
		echo "<p>I'm sorry no events have been found please try again later.</p>";
	}
	
	echo '<style type="text/css">'.custom_event_add_inline_styles().'</style>';
}

/* Add loading bar - When switching years */
function addAjaxLoadingBar() {
	echo '<div id="ajax-loader"><span class="start_preload"><img src="'.CUSTOM_ESPRESSO_EVENT_DISPLAY.'resources/loading.gif" alt=""><br>Please wait...</span></div>';	
}

/* Get and print return the price for a specific event */
function calculatePrice($event_id) {
	global $wpdb, $org_options;

	//get event
	$price_query = sprintf("SELECT event_cost FROM %s WHERE event_id = '%d'",
	mysql_real_escape_string(EVENTS_PRICES_TABLE),
	mysql_real_escape_string($event_id));
	$price_result = $wpdb->get_row($price_query);

	//Do we have an event?
	if($price_result) {
		$price = $price_result->event_cost;
		
		//Set the style of $price
		$price = ($price > 0) ? $org_options['currency_symbol'].$price:"<strong>FREE</strong>";
	}

	return $price;
}

//Generate the event description
function createEventDesc($desc, $excerpt) {
	if(strlen($excerpt) > 0) {
		$desc = preg_replace('/<img[^>]+./','',$desc);
		$desc = strip_tags($desc);
		$desc_size = strlen($desc);
		
		$excerpt = ($desc_size < $excerpt) ? $desc_size:$excerpt;
		
		$desc = substr($desc, 0, $excerpt);
		$desc .= ($desc_size > $excerpt) ? '...':'';

		return '<div class="event_desc"><p>'.stripslashes($desc).'</p></div>';
	}
	return;
}

//Ajax request to display the new events - based on the selection from the user
function display_new_events() {
	global $wpdb, $org_options;
	
	//Handle request then generate response using WP_Ajax_Response
	$ajax_year = ($_POST['year'] != '') ? $_POST['year']:date("Y"); //get year in question
	$ajax_month = ($_POST['month'] != '') ? str_replace("month", "", $_POST['month']):date("m"); //get current month

	echo displaying_events($ajax_year);
	echo addAjaxLoadingBar();
	exit;
}


/* ########### CUSTOM EVENTS ##############*/

add_action('admin_menu', 'displayerOptions');
add_shortcode('CUSTOM_ESPRESSO_EVENT_DISPLAYER', 'display_events');

// this hook is fired if the current viewer is/not logged in
do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
do_action( 'wp_ajax_' . $_POST['action'] );

// if both logged in and not logged in users can send this AJAX request,
// add both of these actions, otherwise add only the appropriate one
add_action( 'wp_ajax_nopriv_update-events-submit', 'display_new_events' );
add_action( 'wp_ajax_update-events-submit', 'display_new_events' );

//include files - js/css
wp_enqueue_script( 'custom-events-displayer-js', CUSTOM_ESPRESSO_EVENT_DISPLAY . 'scripts/eventdisplayer.js' );


// embed the javascript file that makes the AJAX request
wp_enqueue_script( 'update-events', plugin_dir_url( __FILE__ ) . 'scripts/ajax.js', array( 'jquery' ) );

// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
wp_localize_script( 'update-events', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );