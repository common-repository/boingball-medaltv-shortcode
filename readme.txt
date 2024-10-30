=== Boingball Medal.TV ShortCode Plugin ===
Contributors: BoingBall
Tags: MedalTV, API, Gaming, Videos
My Twitch link: http://twitch.tv/boingball
Requires at least: 5.0
Tested up to: 5.8.1
Requires PHP: 5.6
Tesed up to: 7.8
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Plugin Name: Boingball Medal.TV ShortCode Plugin
Plugin URI: https://www.boingball.uk/medaltv-get-latest-posts
Description: A plugin to connect to the Medal.TV API to get the latest posts from a UserID – Use the shortcode in a page
Author: BoingBall
Author URI: https://www.boingball.uk/
Usage : [MedalTV videos=x title=0 or 1 grid=0 or 1] - Default Values is 1 video with Grid turned off and Title turned on

his Plugin allows you to display your Medal TV Latest videos using the shortcode [medaltv]

== Description ==
Use this Plugin to get your MedalTV Clips and add them to a WordPress Page with the Shortcode [medaltv]
Settings for the Application are available under – WordPress Admin > Settings > BB MedalTV Plugin

== Installation ==

1. Upload "boingball-medaltv-shortcode-plugin.php" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Get a Medal Private API Key from https://docs.medal.tv/api.html
4. Find you Medal UserID by visiting your page and checking the URI - For instance my Medal.TV page is https://medal.tv/users/3971756 - 3971756 is my UserID
5. Enter your MedalAPI Key and UserID on the Settings Screen - WordPress Admin > Settings > BB MedalTV Plugin


== Usage ==
Options on Shortcode
videos=x (x being the number of videos to fetch from MedalTV
title=0 (0 to disable the Video title display)
grid=1 (1 turns on a 2 video per row grid)

== To-Do ==
Medals iFRAME does not work on mobile phones – I think this is a Medals.TV Problem as a Raw IFRAME test gives the same output.

== Changelog ==
= 1.2 =
* secured the inputs and outputs of the admin panel
* added Grid Format for wide pages

= 1.1.1 =
* Missing a ? causing Autoplay to start

= 1.1 =
* Settings Menu Added

= 1.0 =
* Initial release.


Hope you enjoy!

BoingBall
