<?php /*

 * Custom Event Espresso - Event Display v1.0.0 Stylesheet

 *

 * Feel free to edit this file to customize the look of the event display.

 * If any updates are made please make sure that once you have upgraded this version you update

 * this file too.

 *

 * Date: Wed 18 Jan 19:34:00 2012 GMT

 *

 */?>

/* Displaying Events */
.list_events .month {
	font-size:18px;
}

.item_left {
    width: 32%;
    float: left;
    margin-right: 10px;
    font-size: 12px !important;
    background-color: #<?=$options['displayer_event_avail_bg_colour']?>;
    border: 1px solid #<?=$options['displayer_event_avail_bg_border_colour'];?>
    overflow: hidden;
}

.item_left.passed {
    background-color: #<?=$options['displayer_event_reg_passed_bg_colour']?>;
    border: 1px solid #<?=$options['displayer_event_reg_passed_bg_border_colour'];?>
}

.item_left .event_header {
	background-color:#<?=$options['displayer_event_avail_header_colour']?>;
}

.item_left.passed .event_header {
	background-color:#<?=$options['displayer_event_reg_passed_header_colour']?>;
}

.item_left .event_header p {
	<?php if($options['standard_theme_font_style'] == 1) {?>
		color:#<?=$options['displayer_event_avail_header_text_colour']?>;
    <?php }?>
    
	padding:5px 0 5px 0;
}

<?php if($options['standard_theme_font_style'] == 1) {?>
    .item_left.passed .event_header p {
        color:#<?=$options['displayer_event_reg_passed_header_text_colour']?>;
    }
<?php }?>

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
	background-color: #<?=$options['displayer_bg_colour']?>;
	border: 1px solid #<?=$options['displayer_border']?>;
	text-align: center;
	margin: 0;
	border-right: 1px solid white;
}

#months ul li.last {
	margin-right:0;	
	border-right: 1px solid #<?=$options['displayer_border']?>;;
}

#months ul li.active {
	background-color:#<?=$options['displayer_month_active']?>;
}

#months ul li:hover, #months ul li.inactive {
	background-color:#<?=$options['displayer_month_inactive']?>;
	border-color:#<?=$options['displayer_month_inactive_border']?>;
}

#months ul li.inactive {
	border-right: 1px solid white;
}

#months ul li.active a {
	<?php if($options['standard_theme_font_style'] == 1) {?>
		color:#<?=$options['displayer_month_active_text']?>;
    <?php }?>
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
}