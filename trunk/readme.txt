=== CommentLuv ===
Contributors: Andy Bailey
Tags: comments, linkluv
Requires at least: 2.1
Tested up to: 2.3
Stable tag: 0.9.8

Appends a titled link to the authors last blog post on their comment giving back some luv to the people that join your discussion.

== Description ==

Pass a bit of luv onto your commenters by providing a titled link to their last blog post. This plugin attempts to parse the feed of the comment author by visiting their site and looking in the standard locations for a feed.  If no default feed is found, it will attempt to parse the users page for a feed link and parse that instead.

It will start to work with any new comments posted after installation (it will not add last post links to comments made before CommentLuv has been activated)

It will not add your own last post if you are logged in as admin or use your own blog url as the comment author url. You can test it by adding a comment to one of your posts (after loggin out) and using a different URL for the author site field (use http://www.fiddyp.co.uk if you want!)

== Installation ==

1. Upload `commentluv.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. No template editing required

You may want to test it after activation by logging out and adding a comment to one of your posts but use a different URL as the comment author site. you can use http://www.fiddyp.co.uk if you want and see if it apends my last blog post.

== Frequently Asked Questions ==

= All my comments are now being held in moderation, why? =

You probably have your wordpress set to hold comments in moderation if they contain a link.
Go to your wordpress dashboard and go to Options/discussion and change the comment moderation option to "Hold a comment in the queue if it contains 2 links"

= Why doesn't my plugin show anyone's last post? =

You might be having a compatibility issue with one of your other comment plugins. This version of CommentLuv currently works with AJAX edit comments, subscribe to comments and SpamKarma 2. 
If your commentluv plugin isn't working for you, please send me an email to andy <at> teamplaylotto.com with a list of your other comment type plugins and I'll do what I can to fix it!

= I know a blog has a feedburner link on it's page but a last post link doesn't appear, why? =

there are some issues where a users feedburner link has an extra subdirectory after their feedburner username, this doesn't happen very often and should be resolved by the next release

= Does this plugin increase page load times? =

The last post link is added at the time of the comment being submitted, no other database writes are made so it shouldn't affect the loading time of your blog at all.

= Can a commenter manually add their feed url to the comment? =

Yes, just enclose the full feed url within [feed] [/feed] on the end of the comment

ie. "great point! I'll add that too.. [feed]http://feeds.feedburner.com/Fiddyp[/feed]"

= Does this plugin remove nofollow tags from the links it creates if I am using a Dofollow plugin? =

In most cases it will, unless you have other dofollow plugins that use a high priority and therefore act before CommentLuv adds the last post link

= How can a user help CommentLuv find their feed? =
They can either manually add their feed url to the comment (see above) or they can make sure that they have an entry in the blog <head> section like this:
&lt;link rel="alternate" type="application/rss+xml" href="http://www.fiddyp.co.uk/feed/" title="FiddyP Posts RSS feed" /&gt;

= I am having problems, what do I do? =
You can switcht the debug option on by including "[debugon]" in the comment (no quotes) and the plugin will output the things that it tried to locate the feed. Copy and paste the debug output in to an email to me (along with the author's url) and I'll look into it for you.


== Screenshots ==


== Arbitrary section ==


== A brief Markdown Example ==


no code required. Automatic after activation.

to specify a feed, use this at the end of the comment content
[feed]http://www.feedburner.com/yourfeed[/feed]

to switch on debug, use this somewhere in the comment content
[debugon]
