=== Plugin Name ===
Author: António Andrade
Author URI: http://antonioandra.de/
Plugin URI: http://antonioandra.de/
Contributors: antonioandra.de
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=antonio%40antonioandra%2ede&lc=US&item_name=WP%20Tag%20This%20%28Antonio%20Andrade%29&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest
Tags: tag, taxonomy, tagging, voting
Requires at least: 3.0
Tested up to: 3.9
Stable tag: 1.3

Enables your blog readers to suggest new post tags or upvote/downvote existing ones.

== Description ==

Enables your blog readers to suggest new post tags or upvote/downvote existing ones.
Downvoted tags are eventually removed.
A few more features are on the works as is the expansion of this readme document.

== Installation ==

1. Download **WP Tag This!**;
1. Extract its content;
1. Upload the **wp-tag-this** folder to **wp-content/plugins**;
1. Activate it under **Plugins**;

**Usage**

To output the Table of Contents use the following snippet, inside your post loop:

`<?php if( function_exists( 'TagThis' ) ){ TagThis(); } ?>`

== Changelog ==

* Added blocked words;
* Added synonyms (poor interface).

= 1.3 (19/04/2014) =
* Fixed many bugs;
* Tested under WordPress 3.9.

= 1.2.1 (10/07/2012) =
* Removed debug messages.

= 1.2 (02/07/2012) =
* Added autocomplete suggestions;
* Added "lowercase tags" option;
* Added "show vote count" option;
* Smaller fixes.

= 1.1 (01/07/2012) =
* Added ajax to the frontend.

= 1.0 (01/07/2012) =
* Initial release.

== RoadMap ==

Add some frontend feedback;
Add support for custom taxonomy tagging;
Synonym dictionary;
Better documentation, ui and automatic integration.