<?php 
if ( ! defined( 'WP_PLUGIN_URL' ) )
define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
$commentluvdirectory = WP_PLUGIN_URL . '/' . dirname( plugin_basename(__FILE__) );
commentluv_alter_whitelist_options("");
// Add a new submenu under Options:
commentluv_activation();
add_options_page('CommentLuv', 'CommentLuv', 8, 'commentluv', 'cl_options_page');
add_option('cl_comment_text','[name]&#8217;s last blog post..[lastpost]');
add_option('cl_default_on','TRUE');
add_option('cl_heart_tip','TRUE');
add_option('cl_style','border:1px solid #ffffff; background-color: #eeeeee; display:block; padding:4px;');
add_option('cl_author_id','author');
add_option('cl_site_id','url');
add_option('cl_comment_id','comment');
add_option('cl_commentform_id','#commentform');
add_option('cl_badge','ACL88x31-white.gif');
add_option('cl_member_id','');
add_option('cl_author_type','name');
add_option('cl_url_type','name');
add_option('cl_textarea_type','name');
add_option('cl_click_track','on');
add_option('cl_showtext','CommentLuv Enabled');
add_option('cl_badge_pos','');
add_option('cl_prepend','');
add_option('cl_version','260');
add_option('cl_select_text','choose a different post to show');
add_option('cl_intense','off');
// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
commentluv_setup();

// start functions
function commentluv_activation(){
	// set version for future releases if they need to change a value
	$version=get_option('cl_version');
	if($version<261){
		update_option('cl_version','261');
		
	}
}
function commentluv_checkCheckbox( $theFieldname ){
	if( get_option( $theFieldname ) == 'on'){
		echo 'checked="true"';
	}
}

// start post checks for reset and update
if ($_POST['submit']=='reset'){
		update_option('cl_comment_text','[name]&#180;s last blog post..[lastpost]');
		update_option('cl_default_on','TRUE');
		update_option('cl_heart_tip','TRUE');
		update_option('cl_style','border:2px solid #ffffff; display:block; padding:4px;');
		update_option('cl_author_id','author');
		update_option('cl_site_id','url');
		update_option('cl_comment_id','comment');
		update_option('cl_commentform_id','');
		update_option('cl_badge','ACL88x31-white.gif');
		update_option('cl_author_type','name');
		update_option('cl_url_type','name');
		update_option('cl_textarea_type','name');
		update_option('cl_click_track','on');
		update_option('cl_showtext','CommentLuv Enabled');
		update_option('cl_reset','off');
		update_option('cl_badge_pos','');
		update_option('cl_prepend','');
		update_option('cl_select_text','Choose a different post to show');
	} 
	
if ($_POST['Submit']==__('Update Options')){
	update_option('cl_comment_text',$_POST['cl_comment_text']);
	update_option('cl_select_text',$_POST['cl_select_text']);
	update_option('cl_default_on',$_POST['cl_default_on']);
	update_option('cl_heart_tip',$_POST['cl_heart_tip']);
	update_option('cl_style',$_POST['cl_style']);
	update_option('cl_author_type',$_POST['cl_author_type']);
	update_option('cl_author_id',$_POST['cl_author_id']);
	update_option('cl_url_type',$_POST['cl_site_id']);
	update_option('cl_textarea_type',$_POST['cl_textarea_type']);
	update_option('cl_comment_id',$_POST['cl_comment_id']);
	update_option('cl_badge',$_POST['cl_badge']);
	update_option('cl_showtext',$_POST['cl_showtext']);
	update_option('cl_badge_pos',$_POST['cl_badge_pos']);
	update_option('cl_prepend',$_POST['cl_prepend']);
	update_option('cl_member_id',$_POST['cl_member_id']);
	update_option('cl_click_track',$_POST['cl_click_track']);
}


	?>
<div class="wrap">

	<form method="post" id="options">
	<?php 
	if(function_exists('wpmu_create_blog'))
	wp_nonce_field('commentluv-options');
	else
	wp_nonce_field('update-options');
?>
	<h2><?php _e('CommentLuv Wordpress Plugin','commentluv')?></h2>
	<p><?php _e('This plugin takes the url from the comment form and tries to parse the feed of the site and display the last entry made','commentluv')?></p>
	<p><?php _e('If you have any questions, comments or if you have a good idea that you would like to see in the next version of CommentLuv, please visit','commentluv')?> <a href="http://www.fiddyp.co.uk" target="_blank">FiddyP Blog</a> <?php _e('or','commentluv')?> <a href="http://www.fiddyp.co.uk/support/"><?php _e('support forum','commentluv')?></a> <?php _e('and let me know','commentluv')?>.</p>
	<h3><?php _e('Options','commentluv')?></h3>
	<p><?php _e('Enter the text you want displayed in the comment.','commentluv')?></p>
	<table class="form-table">
  <tr>
    <td colspan="2">
      <input class="form-table" name="cl_comment_text" value="<?php echo get_option('cl_comment_text');?>">
    </td>
    </tr>
    <tr>
    <td colspan="2">
    <?php _e('Text displayed in the select box','commentluv');?>
      <input class="form-table" name="cl_select_text" value="<?php echo get_option('cl_select_text');?>">
    </td>
    </tr>
  <tr>
    <td width="29%"><?php _e('Choose to have CommentLuv on by default?','commentluv')?></td>
    <td width="71%"><select name="cl_default_on">
      <option <?php if(get_option('cl_default_on')=="TRUE") {echo "selected=selected";}?> >TRUE</option>
      <option <?php if(get_option('cl_default_on')=="FALSE") { echo "selected=selected";}?> >FALSE</option>
    </select></td>
  </tr>
  <tr>
    <td width="29%"><?php _e('Choose to have CommentLuv Info box?','commentluv')?></td>
    <td width="71%"><select name="cl_heart_tip">
      <option <?php if(get_option('cl_heart_tip')=="TRUE") {echo "selected=selected";}?> >TRUE</option>
      <option <?php if(get_option('cl_heart_tip')=="FALSE") { echo "selected=selected";}?> >FALSE</option>
    </select></td>
  </tr>
  
  </table>
  <h3><?php _e('Styling')?></h3>
  <p><?php _e('Wordpress doesn\'t allow a class to be applied to a paragraph in the comment area so we have to wrap the last blog post text in nested tags and apply styling to that instead.','commentluv')?></p>
  <p><?php _e('Enter css styling to apply to comment','commentluv')?></strong> (<em><?php _e('inserted as','commentluv')?></em> &lt;style type="text/css"&gt;abbr em { border:2px; etc }&lt;/style&gt;)</p>
  <table class="form-table">
  <tr> 
    <td valign="top" colspan="2"><input class="form-table" name="cl_style" value="<?php echo get_option('cl_style');?>"></td>
  </tr>
  </table>
  <h3><?php _e('Comment Form Identification','commentluv')?></h3>
<p><?php _e('Enter the ID or NAME value for the input fields on your comment form.','commentluv')?></p>
<p><?php _e('Check your comment form fields to see if they use ID= or NAME= and select the appropriate type below','commentluv')?><br/>
<?php _e('Visit CommentLuv.com if you need instructions','commentluv')?></p>
  <table class="form-table">
  <tr ><td colspan="3">These settings shouldn't need changing unless you use a non standard form.</td></tr>
  <tr>
    <td><?php _e('Authors Name field ID','commentluv')?></td>
    <td><select name="cl_author_type">
    	<option <?php if(get_option('cl_author_type')=="ID" ){echo "selected=selected";}?> >ID</option>
    	<option <?php if(get_option('cl_author_type')=="name") {echo "selected=selected";}?> >name</option>
    	</td>
    <td><input name="cl_author_id" value="<?php echo get_option('cl_author_id');?>"></td>
  </tr>
  <tr>
    <td><?php _e('Authors URL field ID','commentluv')?></td>
    <td><select name="cl_url_type">
    	<option <?php if(get_option('cl_url_type')=="ID") {echo "selected=selected";}?> >ID</option>
    	<option <?php if(get_option('cl_url_type')=="name") {echo "selected=selected";}?> >name</option>
    	</td>
    <td><input name="cl_site_id" value="<?php echo get_option('cl_site_id');?>"></td>
  </tr>
  <tr>
    <td><?php _e('Comment Text Area ID','commentluv')?></td>
    <td><select name="cl_textarea_type">
    	<option <?php if(get_option('cl_textarea_type')=="ID") {echo "selected=selected";}?> >ID</option>
    	<option <?php if(get_option('cl_textarea_type')=="name" ){echo "selected=selected";}?> >name</option>
    	</td>
    <td><input name="cl_comment_id" value="<?php echo get_option('cl_comment_id');?>"></td>
  </tr>
</table>
<h3><?php _e('Display Badge','commentluv')?></h3>
<p>Many thanks to <a href="http://byteful.com">Byteful Traveller</a> for creating these images.</p>
	<table class="form-table">
	<tr>
      <td><?php _e('Choose badge to display','commentluv')?> </td>
      <?php $badge=get_option('cl_badge');?>
        <td><label><input type="radio" <?php if($badge=="CL91x17-white.gif"){echo "checked ";}?> name="cl_badge" value="CL91x17-white.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/CL91x17-white.gif"/></label></td>
        <td><label><input type="radio" <?php if($badge=="CL91x17-black.gif"){echo "checked ";}?> name="cl_badge" value="CL91x17-black.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/CL91x17-black.gif"/></label></td>
		<td><label><input type="radio" <?php if($badge=="ACL88x31-white.gif"){echo "checked ";}?> name="cl_badge" value="ACL88x31-white.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/ACL88x31-white.gif"/></label></td>
		<td><label><input type="radio" <?php if($badge=="ACL88x31-black.gif"){echo "checked ";}?> name="cl_badge" value="ACL88x31-black.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/ACL88x31-black.gif"/></label></td>
		<td><label><input type="radio" <?php if($badge=="nothing.gif"){echo "checked ";}?> name="cl_badge" value="nothing.gif"><?php _e('Show nothing','commentluv')?></label></td>
  </tr></table>
  <table class="form-table">
  <tr><td><label><input type="radio" <?php if($badge=="text"){echo "checked ";}?> name="cl_badge" value="text"><?php _e('Show text','commentluv')?></label> <input class="form-table" type="text" name="cl_showtext" value="<?php echo get_option('cl_showtext');?>"></input></td><td></td><td><label><?php _e('Append badge to (DIV or object ID) optional','commentluv')?><input class="form-table" type="text" name="cl_badge_pos" value="<?php echo get_option('cl_badge_pos');?>"></input></td><td></td><td><label><?php _e('Prepend html before badge or text (optional)','commentluv')?></label><input class="form-table" type="text" name="cl_prepend" value="<?php echo htmlspecialchars(get_option('cl_prepend'));?>"></input></tr>
    </table>
    <h3><?php _e('CommentLuv Member ID','commentluv')?></h3>
    <p><?php _e('If you register your site for free at','commentluv')?> <a href="http://www.commentluv.com">CommentLuv.com</a> <?php _e('you will be able to open up lots of features that are for members only like link tracking so you can see which of the comments you make on CommentLuv blogs are getting the last blog post clicked. Do NOT enter a number if you do not have one','commentluv')?></p>
    <table class="form-table">
    <tr><td><?php _e('Your CommentLuv.com member ID','commentluv')?></td>
	<td><input name="cl_member_id" value="<?php echo get_option('cl_member_id');?>"></td>
    </tr>
    <tr><td><?php _e('Enable click tracking?','commentluv')?></td>
    <td><input type="checkbox" name="cl_click_track" <?php if(get_option('cl_click_track')=="on"){echo "checked";};?> /></td>
    </tr>
    </table>
	  <input type="hidden" name="page_options" value="cl_comment_text,cl_default_on,cl_style,cl_author_id,cl_site_id,cl_comment_id,cl_commentform_id,cl_badge,cl_member_id,cl_click_track,cl_form_type,cl_author_type,cl_url_type,cl_textarea_type,cl_reset,cl_showtext,cl_badge_pos,cl_prepend,cl_select_text,cl_heart_tip" />
	  <!-- //<input type="hidden" name="action" value="update" />
	  //<input type="hidden" name="option_page" value="commentluv" />-->
	<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options') ?>" /></p>
	<div style="float: right;"><?php _e('Reset to Default Settings','commentluv')?><input type="submit" name="submit" value="reset" onclick="if(confirm('<?php _e('Are you sure you want to reset your settings? Press OK to continue','commentluv')?>') == true) { return true; } else { return false; }"/></div>
	</form>
<p>Andy Bailey<br/>
Fiddyp.co.uk
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="admin@commentluv.com">
<input type="hidden" name="item_name" value="CommentLuv">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="GB">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>

</div>