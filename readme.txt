=== CommentLuv ===
Contributors: @commentluv (concept & coding) @wpmuguru (api) @andrea_r (site design)
Donate link:http://comluv.com/about/donate
Tags: commentluv, comments, last blog post, linkluv
Requires at least: 2.6.5
Tested up to: 2.8
Stable tag: 2.7.5
	
Reward your readers by automatically placing a link to their last blog post at the end of their comment. Encourage a community and discover new posts.

== Description ==

This plugin will visit the site of the comment author while they type their comment and retrieve a selection of their last blog posts, tweets or digg submissions which they can choose one from to include at the bottom of their comment when they click submit.

It has been found to increase comments and the community spirit for the thousands of blogs that have installed it. With a simple install you will immediately start to find new and interesting blog posts, tweets and diggs from your own blog and community.

The plugin requires WP or WPMU version of at least 2.65 and will work with administrators and logged on users provided they have their homepage url set in their profile page in the dashboard of the site.

With a full support site where you can unlock great new features, start your own WP2.7 blog with CommentLuv already installed and set up, view your stats for comments made and received and much more.

You can visit http://comluv.com to find out more about this plugin.

== Details ==

CommentLuv functionality
* Works with Wordpress 2.65, 2.7.1 and 2.8
* Compatible with WPmu and logged on users
* Language support
* Communicates with remote API when comment is deleted or spammed
* Uses WP includes for jQuery and hoverIntent for improved compatibility with other plugins
* Auto configures to recognize comment form

== Installation ==

Wordpress : Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

WordpressMu : Same as above

== Configuration ==

Display Options : 
Enter the text you want displayed in the comment for the link that is added.
[name] -> replaced with comment author name
[type] -> replaced with blog, twitter or digg depending on what type of link the author chose to include.
[lastpot] -> replaced with the titled link.

Text displayed in the select box -> shows in the pull down box when a user has more than one post to choose from

CommentLuv on by default -> check this box to enable CommentLuv by default

Show heart on links -> Shows the heart icon next to links so users can find out more about the comment author

Use teamplate insert to show badge and checkbox -> check this box if you want to place the badge and pull down box in a particular place on your page by using the template code.

display badge -> choose from 4 different badges, choose no badge or use your own specified text

CommentLuv member area -> for future use

Technical Settings:
Authors name field name -> The name value of the field used on your comment form for the comment authors name
Email field name -> The name value of the field used on your comment form for the comment authors email
Authors URL field name -> The name value of the field used on your comment form for the comment authors site URL
Comments Text Area Name -> The name value of the field used on your comment form for the comment 

update -> updates the settings

reset -> if you get in trouble, click this to reset to default settings

== Adding to your template ==
Use &lt;php cl_show_badge(); ?&gt; in your comments.php file where you want the badge and checkbox to be shown

== Frequently Asked Questions ==

=Does this plugin add any database tables?=

No. The link and a small bit of associated data is appended to the comment content at the time of submission

=Will this plugin work with Disqus/Intense Debate/js-kit?=

There will be versions in the future that will work with Disqus, JS-kit and Intense debate. There is also an API available for developers if they choose to write their own versions.

=I am having a problem getting it to work=

You can submit a support ticket at http://comluv.com 

== Screenshots ==
1. settings page

2. in use
This plugin inserts fields to the comment form at run time. If you find there is no badge shown on the comment form after you first install it, please check your comments.php file for the command &lt;?php do\_action('comment\_form', $post->ID); ?&gt; before the &lt;/form> tag

For logged on users and administrators, be sure to check your profile on your own dashboard and make sure there is a url entered.