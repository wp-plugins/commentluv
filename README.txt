=== WP Twitip ID ===
Tags: twitter, tweet, integration, comment, 
Contributors: @commentluv(coding), @styletime(beta testing), @problogger(inspiration), @saphod(update_options), @clearskynet(db table creation)
Requires at least: 2.6.3
Tested up to: 2.7
Stable tag: 1.0

WP Twitip ID adds a field to the comment form for a user to add their twitter username. Once published, their comment will display a link to follow them on twitter wherever you want by adding a line to your themes' comments.php

== Details ==

WP Twitip ID functionality:

* Adds an extra field to the comment form for user to enter their twitter username
* Echo out the twitter id associated with the comment being displayed
* Echo out a html link pointing to the users twitter page using "Click Here To Follow Me On Twitter"
* Display link as an image
* Ouput just the @ username
* Return the twitter id as a variable to be used by php
* Automatically display Twitter: @username
* Display html before the extra field
* Display html after the extra field
* Add a class name to the extra field
* disable javascript field addition for manual form editing


== Installation ==

1. Download the plugin archive and expand it (you've likely already done this).
2. Put the 'wp-twitip-id' folder and all files inside into your wp-content/plugins/ directory.
3. Go to the Plugins page in your WordPress Administration area and click 'Activate' for WP Twitip ID.
4. Go to the WP Twitip ID Options page (Settings > WP Twitip ID) to set where you want the field to appear (defaults to after the url field) and add your chosen anchor text or image url


== Configuration ==

Put field after object ID
Enter the ID of the field or object that you want the extra field to be displayed after
defaults to url

Labels are before fields?
If your comments.php file puts the label before the field check this box
defaults to off

Don't use Javascript 
If you edit your comments.php file and add a field manually, you need to select this (manual adding requires field to have name="atf_twitter_id")
defaults to off

HTML before field
Custom html you want to add before the field is added
defaults to <br/>

HTML after Field
Custom html you want to add after the field is added
defaults to <br/><label for="atf_twitter_id">Twitter ID</label> 
(if you check labels are before fields, you can enter this html into the html before field

Field class
the class you want to apply to the field 
defaults to textarea

Twitter Image URI
Image url for image output

Twitter anchor text
Text to display in link for text output

== Adding to your template ==

Open your themes comments.php file and add this line
if(function_exists(wp_twitip_id_show)) { 
        	 wp_twitip_id_show("auto");
}

You can use "image" or "text" or "user"

"image" = show linked image using image url in settings page
"text" = show text link using anchor text in settings page
"user" = show just the @username
"return" = return the twitter id as a variable for use in custom php
"auto" = show Twitter: @commentluv in a span with class twitter_id 

example:
within the comments loop (foreach($comments as $comment)) I put this below the date output in comments.php
<?php if(function_exists(wp_twitip_id_show)) { 
        	 wp_twitip_id_show("auto");
         }?>
         
"return" = return the twitter id to be used as a variable
example:
<?php if(function_exists(wp_twitip_id_show)) {
		$twitter=wp_twitip_id_show("return");
		if($twitter){
			// has a twitter id
		?>I has a <a href="http://twitter.com/<?php echo $twitter;?>">Twitter Account</a>
		<?php } 
		

== Frequently Asked Questions ==

= The added field doesn't look like the others on the form =
Inspect the html of your form and see what class has been applied to the field and use that value in the settings page under "field class"

= No field is being added =
Make sure there are no carriage returns (new line breaks) in the code for html before and after field in the settings page

= Can it work with logged on users? =
Yes, as long as they comment at least once and add their ID to the field whilst logged out.

= I was using the beta and now all the previous comments have lost their twitter link =
The beta version saved the values differently. As long as the person makes another comment and add their ID then all comments by them using that email will be updated.

= Do I have to use javascript to add the field? =
No. You can manually add the field to your comment form and select the checkbox on the settings page to not use javascript. (the field needs to have name="atf_twitter_id")

= Anything else? =

Hope you enjoy this plugin!

--Andy Bailey

http://www.fiddyp.co.uk
