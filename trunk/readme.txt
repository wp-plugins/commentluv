=== CommentLuv ===
Contributors: Andy Bailey
Tags: comments, linkluv
Requires at least: 2.2
Tested up to: 2.1
Stable tag: 0.9.1

Appends a titled link to the authors last blog post on their comment.

== Description ==

Pass a bit of luv onto your commenters by providing a titled link to their last blog post. This plugin attempts to parse the feed of the comment author by visiting their site and looking in the standard locations for a feed. (wordpress - /feed/ , Blogspot - /feeds/posts/default , TypePad - /atom.xml) If no default feed is found, it will attempt to parse the users page for a feedburner link. 

It has short timeouts so the user isn't waiting for too long to see their comment appear.


ChangeLog:

0.91 - fix: compatibility with some other comment enhancing plugins so the link isn't repeated
0.9 - now wont output emptry string if no last post found (blogspot blog with own domain)
0.8 - now prevents parsing on a trackback, pingback or admin comment
0.71 - trying to prevent showing last post on trackbacks
0.7 - prevented admin from having feed parsed when replying to comments
(thanks thesmocklady.com/blog/)
0.6 - fixed problem where it wouldn't find the feed if the blog was in a subdirectory
(found by http://thesmocklady.com/blog/)
0.51 - fiddled with timeout. Some feeds were not showing due to it taking too long to load the
commenter's page. Testing done by thedivanetwork.com and lalla-mira.com/. Thanks!
0.5 - typepad,blogspot,wordpress all working, tries to find a feedburner feed in the authors page
if it's not found in a default location. pretty robust, can now work with script links to
feedburner.
0.4 - try and find users feed if they don't have a default wordpress/blogger/typepad blog
0.3 - works with typepad blogs feed, default and feedburner
0.2 - works with feedburner feed for wordpress and blogger default location
0.1 - works with wordpress default feed at default location

== Installation ==

1. Upload `commentluv.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why doesn't my plugin show anyone's last post? =

Your hosting may not support file open PHP commands such as 'file()' or 'fopen()'.

= I know a blog has a feedburner link on it's page but a last post link doesn't appear, why? =

Your hosting may not support the PHP command 'curl_exec()'

== Screenshots ==


== Arbitrary section ==


== A brief Markdown Example ==


no code required. Automatic after activation.
