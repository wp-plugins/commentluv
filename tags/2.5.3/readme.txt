=== CommentLuv ===
Contributors: Andy Bailey
Tags: comments, linkluv
Requires at least: 2.5
Tested up to: 2.6.2
Stable tag: 2.5.3

Appends a titled link using AJAX to the authors last blog post on their comment giving back some luv to the people that join your discussion. Compatible with logged on users and Wordpress MU. Visit CommentLuv.com for detailed instructions on features. 

== Description ==

Pass a bit of luv onto your commenters by providing a titled link to their last blog post. This plugin attempts to parse the feed of the comment author by visiting their site and looking for their feed while they type their comment and appends it once they submit.

Entice them even more with the new info box ajax popup showing their descript, gravatar and other sites they visit.

It will start to work with any new comments posted after installation (it will not add last post links to comments made before CommentLuv has been activated). You can set the plugin to track the clicks made on the links received and see the stats at commentluv.com (free registration)

It will not add your own last post if you are logged in as admin. You can test it by adding a comment to one of your posts (after loggin out) and using a different URL for the author site field (use http://www.fiddyp.co.uk if you want!)

This plugin now has a support site where you can specify a feed location, see who's clicked on your links and more. Registration is free and verification is simple. Registered users get to choose from their last 10 posts instead of just 1.

== Installation ==

1. Upload the `commentluv` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
Wordpress MU users can activate it from their own blog.
3. Check for settings on the dashboard/settings/commentluv page and enter the name or ID values for your comment form. Default values will work with the default themes. View your comments.php file to see if your form uses ID or name values and enter them in the settings page.

You can see if it has enabled correctly by logging out and looking for the badge displayed under the comment form. For detailed instructions with screen shots, visit CommentLuv.com

You may want to test it after activation by logging out and adding a comment to one of your posts, you can use http://www.fiddyp.co.uk if you want and see if it apends my last blog post.

== Frequently Asked Questions ==

= I don't see the badge in the comment form =
The badge will not show if your are logged in as admin. If you are not logged in but still cannot see the badge, check the settings for your comment form ID. You can see what it is set to in your themes `comments.php` file

= All my comments are now being held in moderation, why? =

You probably have your wordpress set to hold comments in moderation if they contain a link.
Go to your wordpress dashboard and go to Options/discussion and change the comment moderation option to "Hold a comment in the queue if it contains 2 links" (or more)

= I can see the settings in the dashboard but there isn't an image below the comment form, why?

Check the settings page has the correct values for your form name and fields. Visit CommentLuv.com or the support forum http://www.fiddyp.co.uk/support and post a query there if you get stuck.

= Why doesn't my plugin show anyone's last post? =

You might be having a compatibility issue with one of your other comment plugins. This version of CommentLuv currently works with AJAX edit comments, subscribe to comments and SpamKarma 2. 

= Logged on users aren't getting their last blog post displayed =

The url used to find the last blog post is taken from the users profile. If they have a url there then check your comments.php file for the commentform action using this code
< ? php do_action('comment_form', $post->ID); ?>
You need to place this (if it isn't there already) just before the &lt;/form> tag

= CommentLuv is picking a post that is not the latest one =

If you are not a registered member of CommentLuv.com and you are using a feedburner feed service, the plugin wont be able to use a cache for your feed so feedburner will return the feed they hold in their cache which doesn't get updated as often. Register your url at CommentLuv.com and verify it so caching is enabled. This will ensure your very latest post is always retrieved.

= Does this plugin increase page load times? =

The last post link is added at the time of the comment being typed, no database writes are made other than the standard comment save so it shouldn't affect the loading time of your blog at all.

= Does this plugin remove nofollow tags from the links it creates if I am using a Dofollow plugin? =

You will need to use a dofollow plugin such as the excellent Lucias Linky Luv (http://money.bigbucksblogger.com/lucias-linky-love-a-dofollow-plugin-to-foil-human-comment-spammers/) to remove the nofollow from the links.

= I still see rel="nofollow" in the last blog post links when I am notified by Admin = 
Wordpress puts in the nofollow when it sends you an email about comments made, if you look at the source of the page, you should see that the nofollow isn't there. Let me know if it is!

= How can a user help CommentLuv find their feed? =
They can make sure that they have an entry in the blog &lt;head> section like this:
&lt;link rel="alternate" type="application/rss+xml" href="http://www.fiddyp.co.uk/feed/" title="FiddyP Posts RSS feed" /&gt; or they can register at CommentLuv.com and specify their feed there.

= I am having problems, what do I do? =
You can visit the support forum at http://www.fiddyp.co.uk/support or you can come to CommentLuv.com and leave me a message.

= I don't want my last blog post showing on my comment =
Select Do Not Show or a blank entry from the pull down box

== Screenshots ==
1. Settings Page
2. In the comment


== Arbitrary section ==


== A brief Markdown Example ==


You can change the text that is output by the plugin in its settings page. use [name] to output the comment authors name and [lastpost] to output the link.
ie.
[name] has just posted: [lastpost]

[name]'s last blog post...[lastpost]

Styling
Wordpress doesn't allow a class to be set for a comment paragraph so the text that is added is enclosed in <abbr><em> tags and style applied to that like this in the style settings

abbr em { border:2px solid #ffffff; display:block; padding:4px; background-color: #eeffee; }
