=== CommentLuv ===
Contributors: commentluv, @hishaman (css additions)
Donate link:http://comluv.com/about/donate
Tags: commentluv, comments, last blog post, linkluv, comment luv
Requires at least: 2.9.2
Tested up to: 3.1b
Stable tag: 2.81.8
	
Reward your readers by automatically placing a link to their last blog post at the end of their comment. Encourage a community and discover new posts.

== Description ==

This plugin will visit the site of the comment author while they type their comment and retrieve a selection of their last blog posts, tweets or digg submissions which they can choose to include at the bottom of their comment when they click submit.

It has been found to increase comments and the community spirit for the thousands of blogs that have installed it. With a simple install you will immediately start to find new and interesting blog posts, tweets and diggs from your own blog and community.

The plugin requires WP or WP MS version of at least 2.92 and will work with administrators and logged on users provided they have their homepage url set in their profile page in the dashboard of the site.

With a full support site where you can unlock great new features, show off your site, upgrade your urls and feed locations, view your stats for comments made and received and much more.

You can visit http://comluv.com to find out more about this plugin.

== Installation ==

Wordpress : Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

WordpressMu : Same as above (do not place in mu-plugins)

If you're upgrading from an older version, please use the 'reset to default settings' button.

== Frequently Asked Questions ==

= Does this plugin add any database tables? =

No. The link and associated data is saved to the comment meta table

= Will this plugin work with Disqus/Intense Debate/js-kit? =

Intense Debate comments have a commentluv plugin built in. Just enable from your ID dashboard

= I am having a problem getting it to work =

You can submit a support ticket at http://comluv.com

== Screenshots ==

1. settings page

2. in use

3. comments admin

4. edit post comments

== ChangeLog == 

= 2.81.8 =
* settings page notification block 

= 2.81.7 =
* added : Lithuanian translation
* added : Set nofollow on all links, no links or just unregistered users links
* fix : xhtml compliance on checkbox (thanks @winkpress)
* fix : check commentmeta data is an array

= 2.81.6 =
* added : Portuguese (Brazil) translation
* fixed : added ; to functions in js file
* added : option to enable compression compatibility for js files and move cl_settings js to footer
* added : Romanian language
* added : Arabic language
* added : Georgian language

= 2.81.5 =
* fixed : commentluv now available on pages too
* update : change click to hover for showing drop down of last blog posts that were fetched
* added : Polish translation
* update : settings page prettifying (hmm perdy!)
* update : set drop down for last blogs posts event to hover instead of click

= 2.81.4 =
* Fixed : removeluv link in comments admin would result in 404 (thanks @techpatio)

= 2.81.3 =
* Change the way to detect if on a multi site install or not
* updated one of the badges

= 2.81.2 =
* silly me, put the version number wrong!
* Set back to default settings if upgrading from less than 2.81
* Show url field for logged on user if buddypress is active 

= 2.81.1 =
* Prevent empty last post from being included. Also included in API
* Fixed Dutch translation (thanks Rene http://wpwebshop.com)
* Also have commentluv on pages
* updated badges to new version (thanks Byteful Traveller)

= 2.81 =
* New style.css format for info panel (thanks @Hishaman)
* Only show remove luv link for approved comments
* bug fix : sometimes showed two cluv spans (on beta version comments)

= 2.80 =
* Wordpress 3.0 Compatible
* Use comments meta table instead of hard coding into the comment content
* Drastically improved commmunication with API for comment status changes
* Near 100% accuracy for API to identify members links for info panel
* New heart icon for registered members. Improves hover rates.
* Removed depreciated function to clean old style additional data
* Added link to remove someones luvlink data in the comments admin page
* Dutch Translation by Rene wppg.me
* Added comments_array filter to make Thesis behave
* Added check to see if link already added (WP 3.0 compatibility)
* thanks to @hishaman for helping the thesis testing
* Added code to settings manager to prevent viewing outside wordpress (and fixed the typo later, thanks speedforce.org)

= 2.7691 =
* bugfix : choosing a link from an additional url's posts would result in wrong link being included

= 2.769 =
* Modified hidden post fields so only URL and title sent instead of html A href link
* Modified javascript to take account of new hidden fields.
* Temporary fix to try and fix 404 on wp-post-comments.php when commentluv enabled for logged out user
* thanks to @kwbridge @duane_scott @dannybrown @morpheas7887 for testing and feedback!

= 2.768 =
* Added nothing.gif to images (for updated error message from API)

= 2.767 =
* Added conncettimeout to curl call
* Added warning next to 'use template insert' checkbox in settings page 

= 2.766 =
* Check if function has been called before to prevent two links being added.
* updated images (supplied by http://byteful.com)

= 2.765 =
* Hollys changes. Allow user choice of colour for the info panel background.

= 2.764 =
* Removed json_decode. Some wp2.9 installs were getting errors

= 2.763 =
* Added check for hidden fields display to prevent double instances.
* Make css file valid
* Added French translation by Leo http://referenceurfreelance.com

= 2.762 =
* Added permalink as refer variable in ajax calls for better stat collecting since WP started to use paginated comments
* Added Chinese translation by Denis http://zuoshen.com/
* Added Hebrew translation by Maor http://www.maorb.info/
* Added Russian translation by FatCow http://www.fatcow.com/
* Updated readme.txt to use new features like changelog
* Check for http:// in url field before firing (to prevent errors for forms that use js hints in form fields)

= 2.761 =
* 19 Jun 2009 -  fix for htmlspecialchars decode causing error in wp < 2.8

= 2.76 = 
* 16 Jun 2009 - Bug fix, use_template checkbox not displaying when selected on settings page (breaker). typo in settings page now uses &lt;?php cl\_display\_badge(); ?&gt;
* added global variable for badgeshown to prevent mulitple instances (template contains function call AND use template check is off)
* fixed output of prepend html using decode html and stripslashes. Added green background to update settings button.

= 2.74 =
* 14 Jun 2009 - Italian translation added (and fix CR in string on manager page). Thanks go to Gianni Diurno

= 2.71 =
* 13 Jun 2009 - fix php4 from not allowing last string pos (strrpos)

= 2.7 =
* 12 Jun 2009 - small fixes for valid xhtml on images and checkbox . remove identifying .-= / =-. from inserted link on display time. 

== Upgrade Notice ==

= 2.81.7 =

Added : Lithuanian language
Added : Choice of nofollow on links for all/none/unregistered users

== Configuration ==

Display Options : Enter the text you want displayed in the comment for the link that is added.

* [name] -> replaced with comment author name

* [type] -> replaced with blog, twitter or digg depending on what type of link the author chose to include.

* [lastpost] -> replaced with the titled link.

* Text displayed in the select box -> shows in the pull down box when a user has more than one post to choose from

* CommentLuv on by default -> check this box to enable CommentLuv by default

* Show heart on links -> Shows the heart icon next to links so users can find out more about the comment author

* Use template insert to show badge and checkbox -> check this box if you want to place the badge and pull down box in a particular place on your page by using the template code.

* display badge -> choose from 4 different badges, choose no badge or use your own specified text

* CommentLuv member area -> for future use

Technical Settings:

* Authors name field name -> The name value of the field used on your comment form for the comment authors name

* Email field name -> The name value of the field used on your comment form for the comment authors email

* Authors URL field name -> The name value of the field used on your comment form for the comment authors site URL

* Comments Text Area Name -> The name value of the field used on your comment form for the comment 

* update -> updates the settings

* reset -> if you get in trouble, click this to reset to default settings

== Adding to your template ==

Use `<?php cl_display_badge(); ?>` in your comments.php file where you want the badge and checkbox to be shown

This plugin inserts fields to the comment form at run time. If you find there is no badge shown on the comment form after you first install it, please check your comments.php file for the command `<?php do_action('comment_form', $post->ID); ?>` before the `</form>` tag

For logged on users and administrators, be sure to check your profile on your own dashboard and make sure there is a url entered.