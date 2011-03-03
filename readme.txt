=== Open Web Analytics Plugin===
Contributors: chrische
Tags: owa, openwebanalytics, analytics, stats, statistics, open web analytics, statistics
Requires at least: 2.5
Tested up to: 3.1
Stable tag: 1.3

This plugin adds the Open Web Analytics javascript code into the footer of
your website. It has several useful options.

== Description ==

This is a basic wordpress plugin for the excellent Open Web Analytics (OWA) tool.
It adds the open web analytics javascript code into every page of your weblog, so
you don't have to code PHP to add it to your templates.

It is based quite heavily on the Piwik Analytics plugin by Jules Stuifbergen, 
which is based heavily on the Google Analytics wordpress plugin by Joost de Valk.

The following options are supported:

* owa hostname
* owa path
* site ID
* option to control download tracking
* option to exclude the admin user (probably you)

Please note, this plugin requires a running Open Web Analytics installation somewhere under
your control. It does not include Open Web Analytics itself.

See also [The plugin URL](http://www.chrische.de/open-web-analytics-wordpress-plugin), and
[The OWA website](http://openwebanalytics.com/)


== Installation ==

1. Upload the open-web-analytics-plugin directory containing `openwebanalyticsplugin.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make sure your template has a call to wp_footer() somewhere in the footer.
1. Configure the Plugin: enter site ID, and the path to your OWA-Installation (for example /owa/)

== Changelog ==
= 1.3 =
* fixed naming issues resulting in conflict with official OWA plugin again

= 1.2 =
* fixed naming issues resulting in conflict with official OWA plugin

= 1.1 =
* typo fixed in the settings page
* added to official wordpress plugin repository


= 1.0 =
* first release
* no need for a 0.x release, considered stable enough, as based on popular plugins


== Frequently Asked Questions ==

Q: My OWA code does not show up.
A1: Make sure your theme has a call to wp_footer() in the footer.php file
A2: Make sure you're not logged in as admin.

== Screenshots ==

1. Settings of the Plugin

== Upgrade Notice ==
= 1.3 =
- As the Plugin is renamed, you'll have to reactivate it after Upgrading.

= 1.2 =
- As the Plugin is renamed, you'll have to reactivate it after Upgrading.

= 1.1 =
-

= 1.0 =
First release, so no upgrade notes right now


