(function( $ ) {
	'use strict';
	jQuery(document).ready(function(){
		jQuery('#ee-email-shortcode-name').keyup(function(){
			var temp = jQuery.trim(jQuery("#ee-email-shortcode-name").val()).replace(/\s+/g, "_");
			jQuery("#ee-email-shortcode-slug").val(temp);
		});
		jQuery('#ee-email-shortcode-slug').change(function(){
			var temp = jQuery.trim(jQuery("#ee-email-shortcode-slug").val()).replace(/\s+/g, "_");
			jQuery("#ee-email-shortcode-slug").val(temp);
		});

		jQuery('input:radio[name="eees_value_option"]').click(function(){
			if(jQuery(this).val() == 'Dynamic'){
				jQuery("#eees_static_block").hide();
				jQuery("#eees_dynamic_block").attr("style",'width:0%; display:block');
				jQuery("#eees_dynamic_block").animate({width: "100%"});

				jQuery("#ee-email-shortcode-d-field").attr("required",'true');
				jQuery("#ee-email-shortcode-s-field").removeAttr("required");
			}else if(jQuery(this).val() == 'Static'){
				jQuery("#eees_dynamic_block").hide();
				jQuery("#eees_static_block").attr("style",'width:0%; display:block');
				jQuery("#eees_static_block").animate({width: "100%"});

				jQuery("#ee-email-shortcode-d-field").removeAttr("required");
				jQuery("#ee-email-shortcode-s-field").attr("required",'true');
			}
		});
	});

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

function openTab(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
