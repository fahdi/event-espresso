=== Event Espresso Smooth Integration ===
Contributors: Kenshino
Author URI: http://www.wingzcommunications.com
Plugin URI: http://www.wingzcommunications.com
Tags: eventespresso, event espresso, gravityforms, wordpress seo, seo
Requires at least: 4.0
Tested up to: 4.2.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Developed for Event Espresso 4. (Not tested with EE3)

Event Espresso uses it's own routes to display Custom Post Types (eg. Events), in which many plugins do not directly account for.
So special meta boxes, buttons and what not sometimes do not display.

Currently it adds support for GravityForms and WordPress SEO by Yoast.

= GravityForms =
* Adds 'Add Form' button to the WYISWYG editor. Also adds in to any custom WP_Editor instances you create in the post_type editing pages of Event Espresso 4

= WordPress SEO by Yoast =
* Adds the SEO meta boxes that are missing from EE4. It also tries to make better calculations of your SEO score by taking into account the rest of the custom meta data you have in the post_type.

= More coming as I discover them =

If you would like me to integrate a plugin to Event Espresso 4, post in the support forums!

**Translations**

As I improve on this plugin, I will likely add settings in, in which translations will be welcomed. For now, stay still :)


== Installation ==

1. Upload the `event-espresso-smooth-integration` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. There will be no settings for this plugin at this stage.

== Frequently asked questions ==

= Do I need to have all the plugins that this plugin seeks to integrate with EventEspresso? =

No. I use filters where I can, and if the plugin does not exist, the filters will degrade gracefully. For example, if you only want the integration with WordPress SEO by Yoast but not with GravityForms, you do not have to do anything; the integration with GravityForms just does not do it's job.

== Changelog ==

= 1.0 =
* Initial Release