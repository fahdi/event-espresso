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
?>

<div class="about-wrap eees-about">
  <h1 class="wp-heading-inline">
  	<?php esc_html_e('Email Shortcodes for Event Espresso','ee-email-shortcode'); ?>
  </h1>
  <div class="about-text">
  	Thank you for choosing Email Shortcode Plugin.
  </div>

	<div class="tab">
	  <button class="tablinks active" onclick="openTab(event, 'About')">About</button>
	  <button class="tablinks" onclick="openTab(event, 'Features')">Features</button>
	  <button class="tablinks" onclick="openTab(event, 'Help')">Help</button>
	</div>

	<div id="About" class="tabcontent" style="display:block">
	  <h3>Getting Started</h3>
		<p><strong>Step 1</strong> : <a href="?page=ee_email_shortcode_add_new">Add new E-Shortcode</a>, Which you want to add in a Message Templates of Event Espresso.</p>
		<p><strong>Step 2</strong> : Setup the value - Dynamic/Static.</p>
		<p style="padding-left:4%">In Dynamic Value you have to set Slug of Custom field.</p>
		<p style="padding-left:4%">In Static Value you have to set any Static Value.</p>
		<p><strong>Step 3</strong> : After Save this details, You are able to use this shortcode in the Message Templates of Event Espresso.</p>

	  <h3>Remember this :-</h3>
		<p><strong>1)</strong> &nbsp;Activate Event Espresso Plugin before Activate E-Shortcode Plugin. Because if Event Espresso plugin is not activated then this plugin is do nothing only saved your shortcode, And sometimes it gives some errors.</p>
	  	<p><strong>2)</strong> &nbsp;With this plugin You can activate or deactivate e-shortcodes, So if u want to use e-shortcode in Message Template of Event Espresso then You have to determine e-shortcode is activated or not.</p>
	</div>

	<div id="Features" class="tabcontent features">
	  <h3>Features</h3>
	  <div class="eees_about_two_parts">
		  <div class="eees_about_images" style="float:none; width:60%;">
		  	<img src="<?php echo plugins_url(); ?>/ee-email-shortcode/admin/img/add_new_e_shortcode.png">
		  </div>
		  <div class="eees_about_contents" style="float:none; width:40%;">
			  <p> <strong>1)</strong>&nbsp; Create Custom E-Shortcodes with custom name &amp; slug.</p> 
			  <p> <strong>2)</strong>&nbsp; Select a List, Where you want to add E-Shortcode.</p>
			  <p> <strong>3)</strong>&nbsp; Set a Static/Dynamic Value of E-Shortcode.</p>
			  <br/>
		  </div>
	  </div>

	  <div style="float:left; width:100%; padding-top: 2%">
	  	<img src="<?php echo plugins_url(); ?>/ee-email-shortcode/admin/img/e_shortcodes.png">
	  </div>

	  <div style="float:left; width:100%">
		  <div class="eees_about_images" style="float:left; width:50%">
			  <p> <strong>1)</strong>&nbsp; List of all E-shortcodes with its modified time difference.</p> 
			  <p> <strong>2)</strong>&nbsp; With the help of this List, User can determine the which E-Shortcode is for which section of Message Template.</p>
			  <p> <strong>3)</strong>&nbsp; User can Activate/Deactivate/Delete the multiple E-Shortcodes in one action.</p>
		  </div>
		  <div class="eees_about_images" style="float:right; width:50%;">
			  <p> <strong>4)</strong>&nbsp; User can Add/Edit/Delete the E-Shortcodes.</p> 
			  <p> <strong>5)</strong>&nbsp; User can Activate/Deactivate the E-Shortcodes.</p>
		  </div>
	  </div>
	  <div class="eees_about_two_parts" style="padding-top: 2%">
		  <div class="eees_about_contents" style="float:none; width:40%;">
			  <p> <strong>1)</strong>&nbsp; Update All details of Custom E-Shortcodes.</p> 
			  <p> <strong>2)</strong>&nbsp; Change a Value Dynamic to Static and also viceverse.</p>
			  <br/>
		  </div>
		  <div class="eees_about_images" style="float:none; width:60%;">
		  	<img src="<?php echo plugins_url(); ?>/ee-email-shortcode/admin/img/update_e_shortcode.png">
		  </div>
	  </div>
	</div>

	<div id="Help" class="tabcontent">
	  <h3>Help</h3>
	   <div style="float:left; width:100%;">
	  	<img src="<?php echo plugins_url(); ?>/ee-email-shortcode/admin/img/help_1.png">
	   </div>

	   <div style="float:left; width:100%; padding-top: 3%">
	  	<img src="<?php echo plugins_url(); ?>/ee-email-shortcode/admin/img/help_2.png">
	  </div>
	</div>

</div>

