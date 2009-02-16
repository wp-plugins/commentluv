<?php
// version 1.0
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
$addtwitterfielddirectory = WP_PLUGIN_URL . '/' . dirname( plugin_basename(__FILE__) );

// function for dealing with a checkbox in settings. Checks if it was on in the options and sends back checked=true if it is.
function atf_checkCheckbox( $theFieldname ){
		if( get_option( $theFieldname ) == 'on'){
			echo 'checked="true"';
		}
		return;
	}

// update settings from posted variables
if ($_POST['submit']=='update'){
	update_option('atf_swap',$_POST['atf_swap']);
	update_option('atf_afterID',$_POST['atf_afterID']);
	update_option('atf_prehtml',atf_slashit($_POST['atf_prehtml']));
	update_option('atf_psthtml',atf_slashit($_POST['atf_psthtml']));
	update_option('atf_field_class',$_POST['atf_field_class']);
	update_option('atf_image_url',$_POST['atf_image_url']);
	update_option('atf_anchor_text',$_POST['atf_anchor_text']);
	update_option('atf_nolabels',$_POST['atf_nolabels']);
	update_option('atf_nojava',$_POST['atf_nojava']);
}
?>
<style type="text/css">
<!-- 
#addtwitterfield fieldset {
margin: 0;
padding:0;
border: none;
}
#addtwitterfield form p {
background: #eaf3fa;
padding: 10px 5px;
margin: 4px 0;
border: 1px solid #eee;
}
#addtwitterfield form .error p {
background: none;
border: none;
}
.floatright {
float: right;
}
-->
</style>
<div class="wrap" id="addtwitterfield">
<h3>Usage:</h3>
<p>Put this in your comments.php file where you want to output the link<br/>
"image" : outputs image pointed to by image url field (uses anchor text as title attribute)<br/>
"text"  : outputs text link using anchor text<br/>
"return": returns twitter id as variable to be used in php<br/>
"auto"  : returns Twitter: @commentluv (linked username in a span with class="twitter_id")</p>
example:
<code>&lt;?php if(function_exists(wp_twitip_id_show)) { 
        	 wp_twitip_id_show("auto");
}?&gt;</code>
<p>returns:<br/> Twitter: <a href="http://twitter.com/commentluv">@CommentLuv</a></p>
For styling use 
.twitter_id {
	font-size: 10px;
}

<h1>WP-Twitip-ID Options</h1>
Version: <?php echo get_option($atf_db_version);?>
	<form method="post">
	<div>
		<fieldset>
			
			<p>
				<label for="atf_afterID">Put field after object ID</label><br/>
				<input type="text" name="atf_afterID" id="atf_afterID" size="20" maxlength="30" value="<?php echo get_option('atf_afterID') ?>" />	
				<br/><small>Enter the value of the ID attribute of the object you want the extra field to appear after.<br/>Default: url</small>
			</p><p>
				<input type="checkbox" name="atf_swap" id="atf_swap" <?php atf_checkCheckbox('atf_swap')?> />
				<label for="atf_swap">Labels are before fields?</label>
				<br/><small>If your form uses the &lt;label tag before the &lt;input tag select this<br/>Default: off</small>
				<br/><input type="checkbox" name="atf_nolabels" id="atf_nolabels" <?php atf_checkCheckbox('atf_nolabels')?> />
				<label for="atf_nolabels">No Labels</label>
				<br/><small>If your form doesn't use labels check here<br/>Default: off</small>
				<br/><input type="checkbox" name="atf_nojava" id="atf_nojava" <?php atf_checkCheckbox('atf_nojava')?> />
				<label for="atf_nojava">Do not use javascript to add the field</label>
				<br/><small>If you have edited your comments.php file to manually add a field with name="atf_twitter_id" then select this box<br/>Default: off</small>
			</p>
			<p>
				<label for="atf_prehtml">HTML before Field</label><br />
				<textarea name="atf_prehtml" id="atf_prehtml" cols="40" rows="5"><?php echo atf_deslashit(get_option('atf_prehtml')); ?></textarea>
				<br/><small>The HTML you want to put before the extra field (if you have checked the box above then you should put your label html here)<br/>Default: &lt;br/&gt;</small>
			</p>
			<p>
				<label for="atf_psthtml">HTML after Field</label><br />
				<textarea name="atf_psthtml" id="atf_psthtml" cols="40" rows="5"><?php echo atf_deslashit(get_option('atf_psthtml')); ?></textarea>
				<br/><small>The HTML you want to put after the extra field (if you have checked the box above then you should not put the label html here)<br/>Default: &lt;br/&gt;&lt;label for="atf_twitter_id"&gt;Twitter ID&lt;/label&gt; </small>
			</p>
			<p>
				<label for="atf_field_class">Field Class</label><br/>
				<input type="text" name="atf_field_class" id="atf_field_class" size="20" maxlength="35" value="<?php echo get_option('atf_field_class') ?>" />
				<br/><small>You can enter the class of your current fields here to match your theme<br/>Default: textarea</small>
			</p>
			<p>
				<label for="atf_image_url">Twitter Image URI</label><br/>
				<input type="text" name="atf_image_url" id="atf_image_url" size="40" maxlength="150" value="<?php echo get_option('atf_image_url') ?>" />
				<br/><small>URL to the image you want displayed when using "image" as output<br/>Default: <?php echo WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__)).'/twitter_48.png';?></small>
			</p>
			<p>
				<label for="atf_anchor_text">Link anchor text</label><br/>
				<input type="text" name="atf_anchor_text" id="atf_anchor_text" size="40" maxlength="150" value="<?php echo get_option('atf_anchor_text') ?>" />
				<br/><small>Text to use as link when using "text" as output (also is used for the title attribute of the image if using "image")<br/>Default: null</small>
			</p>
		<input type="submit" name="submit" value="update" />
	</fieldset>

	</div>
	</form>
</div>