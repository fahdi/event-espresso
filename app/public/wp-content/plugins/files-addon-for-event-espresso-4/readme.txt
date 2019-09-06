=== Files Addon for Event Espresso 4 ===
Contributors: aparnascodex
Tags: Event espresso, file , upload file, questions, form, events
Requires at least: 4.1
Tested up to: WordPress 5.0.3
Stable tag: 1.0.9
License: GPL2

Files add on plugin allows to create file upload type question which can be used in event registration form. 

== Description ==

Files add on plugin allows to create file type question which can be used in event registration form. Attendees will be able to upload files while registerting for an event using this addon. Admin will be able to restrict the file type by setting file extensions for each question.

== Installation ==

= Minimum Requirements =

* WordPress 4.1 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater
* Event espresso version: 4.8.0 or greater
* This add on will not work in Internet explorer version 9 or below, because formData (javascript object used to upload file on server) is not supported in IE 9 or its below version.

= Manual installation =

Following steps should be followed to install plugin manually

1. Upload 'files-addon-for-event-espresso-4' folder to the '/wp-content/plugins/' directory or upload 'files-addon-for-event-espresso-4.1.0.8.zip' through WordPress admin panel
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You are ready to create file type question.


== Frequently Asked Questions ==

= Why my plugin is not getting activated? =

This is add on plugin of Event espresso 4. Therefore to activate this plugin Event espresso 4.x must be active and running.

= Why files are not getting uploaded? =

Check your current browser version. We have used formData object to upload files. Visit following link to check the compatibility of your browser: https://developer.mozilla.org/en/docs/Web/API/FormData

= Where can I get support or talk to other users? =

If you get stuck, you can email on aparnascodex[at]gmail[dot]com

= What file formats are supported for upload? =

Default allowed file formats are: jpeg, jpg, png, gif, bmp. 
Different file formats can be set for individual file question.



== Screenshots ==

1. Create file type question.
2. Display of registration form.
3. Uploaded file preview in dashboard.

== Changelog ==

= 1.0.9 =

1. Added functionality to edit file from dashboard

= 1.0.8 =

1. Added filter to rename file before uploading - Filter - ssa_override_filename
2. Tested plugin with latest WordPress and Event Espresso version

= 1.0.7 =

1. Fixed admin notice
2. Fixed 'invalid url on file upload' error

= 1.0.6 =

1. Fixed domain validation issue

= 1.0.5 =

1. Fixed (required) validation issue of files.

= 1.0.4 =

1. Added feature to set different file extensions for each file type question
2. If extensions are not set then uploaded file is validated against following extensions ['gif','png' ,'jpg','jpeg','bmp'] 

= 1.0.3 =

1. Added filter 'ssa_change_file_upload_path' to change directory path of uploaded file
2. Added standard code to return file type question.

= 1.0.2 =

1. Fixed error message display.
2. Added link to image.

= 1.0.1 =

Fixed file upload security issue. Added EE_FILE_Validation_Strategy class for validating uploaded file.

= 1.0.0 =
Plugin release


