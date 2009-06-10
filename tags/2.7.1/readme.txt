=== CommentLuv ===
Contributors: teamplaylotto 
Donate link:http://comluv.com/about/donate
Tags: commentluv, comments, last blog post, linkluv
Requires at least: 2.6.5
Tested up to: 2.7.1
Stable tag: 2.7.1
	
Reward your readers by automatically placing a link to their last blog post at the end of their comment. Encourage a community and discover new posts.

== Description ==

This plugin will visit the site of the comment author while they type their comment and retrieve a selection of their last blog posts, tweets or digg submissions which they can choose one from to include at the bottom of their comment when they click submit.

It has been found to increase comments and the community spirit for the thousands of blogs that have installed it. With a simple install you will immediately start to find new and interesting blog posts, tweets and diggs from your own blog and community.

The plugin requires WP or WPMU version of at least 2.7 and will work with administrators and logged on users provided they have their homepage url set in their profile page in the dashboard of the site.

With a full support site where you can unlock great new features, start your own WP2.7 blog with CommentLuv already installed and set up, view your stats for comments made and received and much more.

You can visit http://comluv.com to find out more about this plugin.

 
	
== Installation ==

Wordpress : Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

WordpressMu : Same as above
== Frequently Asked Questions ==

=Does this plugin add any database tables?=

No. The link and a small bit of associated data is appended to the comment content at the time of submission

=Will this plugin work with Disqus/Intense Debate/js-kit?=

There will be a separate version of CommentLuv for external comment systems due to the regular updates they go through
== Screenshots ==
1. settings page

2. in use
This plugin inserts fields to the comment form at run time. If you find there is no badge shown on the comment form after you first install it, please check your comments.php file for the command &lt;?php do\_action('comment\_form', $post->ID); ?&gt; before the &lt;/form> tag

For logged on users and administrators, be sure to check your profile and make sure there is a url entered.