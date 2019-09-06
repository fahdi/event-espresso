=== Plugin Name ===
Contributors: dazza041
Donate link: http://www.daryl-phillips.co.uk/2012/01/18/event-espresso-custom-event-display-plugin
Tags: Event Espresso, event management, events, management, espresso
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


A simple plugin to adapt display methods for your Event Espresso system.

== Description ==

Event Espresso is an event registration and ticket management plugin. To access certain features you are required to pay for the basic license (basic is $89.95).

Whilst using this it became clear that one thing was missing: a bridge between the Lite and Basic licenses. The Lite version enables users to create events, take bookings and payments but viewing these events is far from ideal. You only get them in list form. For systems with more than 5 events, scrolling could be tedious.

Now if you do buy the basic license you do get access to the Event Espresso Calendar. However, I am here to introduce a custom solution which allows you to embed a shortcode into your page and display them in a different way.

I have likened it to a Monopoly-esque style with a bold header and an excerpt followed by a register link. The screenshot(s) below show what it would look like when events have passed and if events do not exist. This is only version 1 and if feedback is good I will consider building upon the foundation set.

This solution was intended for a project I was working on and that could work for others. It must be noted that I do not work for Event Espresso nor am I associated with them in any way.

== Installation ==

1. Upload 'eventespresso_displayer' folder to the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Place [CUSTOM_ESPRESSO_EVENT_DISPLAYER] in your page/post for events to be listed.

4. Amend the styles of this plugin by viewing the settings page

5. Change: the number of years to show, background and text colours, events and structuring events in the correct way for you.





== Frequently Asked Questions ==

= Can I change the header colours of the events? =

Yes. See change log and displayer settings from within your WP dashboard (requires v2.0+)

= Will there be any additional layouts. =

There are several updates from this in the pipeline and will consider implementing any ideas into future versions of the plugin. If you, or anyone else, has any suggestions please contact me on wp_plugins@daryl-phillips.co.uk.

Some updates include: including post thumbnails into the display, moving the structure of content around (i.e. Header can be modified based on merge tags)


== Screenshots ==

1. The view from within your dashboard. This corresponds to screenshot-v2-1.jpg 

2. A new display panel that is used on the front end. This corresponds to screenshot-v2-2.jpg


== Changelog ==

= 2.1 =

Bug fixes for customising the excerpt length of an event. If 0 is present then the event description is removed. 

= 2.0 =

Version 2.0 has advanced colour and setting facilties to allow you optimise this plugin to your needs. Please visit the settings page from within your WordPress dashboard.

= 1.5 =

Fixed issues with events still appearing even if they had been deleted


= 1.4 =

Generic Jquery issues sorting the "pigeon-holing" issues when using a specific wordpress theme (should have been checked!).

= 1.3 = 

Teething problems with Jquery solved. Using JQuery.noConflict();

= 1.2 =

Fixed various issues with the $year request string. Could not switch between years to show. The event URL was also not being picked up when diplaying events.

= 1.1 =

Shortcode changed to [CUSTOM_ESPRESSO_EVENT_DISPLAYER]

= 1.0 =

This is the first build version and bugs may be visible. I endeavour to remove these bugs as soon as possible.

== Upgrade Notice ==



= 1.0 =

Version 1.0 includes a basic event list displayer with Jquery and CSS files attached. You will need to us the shortcode to get this to work.


= 2.0 =

Version 2.0 has advanced colour and setting facilties to allow you optimise this plugin to your needs. Please visit the settings page from within your WordPress dashboard.


== Usage ==



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





== Disclaimer ==



I am no way associated to Event Espresso and have devised my own custom solution for displaying events and would like to share with everyone else.