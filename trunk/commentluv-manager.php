<?php
    global $wpdb;
    if(!$wpdb){
        // not run from wordpress
    ?>
    <h1>CommentLuv </h1>
    <p>This is the settings page for CommentLuv and should not be viewed outside of the Wordpress dashboard</p>
    <p>You can download the latest version version of CommentLuv at Wordpress.org</p>
    <?php	exit;
    }
?>
<div class="wrap" >
    <h2>CommentLuv <?php echo $this->cl_version/100; ?></h2>
    <div id="poststuff" style="margin-top:10px;">
        <div id="mainblock" style="float: left; width:710px">
            <form action="<?php echo $action_url ?>" method="POST">
                <input type="hidden" name="submitted" value="1" />
                <?php wp_nonce_field('commentluv-nonce');?>
                <div class="dbx-content">
                <table class="widefat">
                    <thead>
                        <tr><th scope="col">CommentLuv</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>
                                <?php _e('This plugin takes the url from the comment form and tries to parse the feed of the site and display the last entry made. If you have any questions, comments or if you have a good idea that you would like to see in the next version of CommentLuv, please visit http://comluv.com and let me know.',$this->plugin_domain);?></p>
                            </td></tr>
                    </tbody>
                </table>
                <br/>



                <table class="widefat">
                    <thead>
                        <tr><th scope="col"><h3><?php _e('Display Options',$this->plugin_domain);?></h3></th></tr>
                    </thead>
                    <tbody>
                        <tr><td>
                                <label for="cl_comment_text"><?php _e('Enter the text you want displayed in the comment',$this->plugin_domain);?></label> <br/>
                                <input type="text" size="50" name="cl_comment_text" value="<?php echo stripslashes($comment_text);?>"/>
                            </td></tr><tr><td>
                                <label for="cl_select_text"><?php _e('Text displayed in the select box',$this->plugin_domain);?></label> <br/>
                                <input type="text" size="50" name="cl_select_text" value="<?php echo stripslashes($select_text);?>"/>
                            </td></tr><tr><td>
                                <input type="checkbox" name="cl_default_on" <?php echo $default_on;?>/>
                                <label for="cl_default_on"><?php _e('CommentLuv on by default?',$this->plugin_domain);?></label>
                            </td></tr><tr><td>
                                <input type="checkbox" name="cl_heart_tip" <?php echo $heart_tip;?>/>
                                <label for="cl_heart_tip"><?php _e('Show heart on links?',$this->plugin_domain);?></label>
                            </td></tr><tr><td>
                                <label for="infoback"><?php _e('Info panel background color',$this->plugin_domain);?></label><br>
                                <input type="text" size ="8" name="infoback" value="<?php echo stripslashes($infoback);?>"/>
                                <span style="background-color:<?php echo stripslashes($infoback);?>">....</span>
                            </td></tr><tr><td>
                                <input type="checkbox" name="cl_use_template" <?php echo $use_template;?>/>
                                <label for="cl_use_template"><?php _e('Use template insert to show badge and checkbox?',$this->plugin_domain);?>
                                    <br><strong><?php _e('Do NOT check this unless you have manually inserted the code into your comments.php file ',$this->plugin_domain);?> </strong><br>( &lt;?php cl_display_badge(); ?&gt; )</label>
                            </td></tr>
                    </tbody></table>
                <br/>
                <table class="widefat">
                    <thead>
                        <tr><th scope="col"><h3><?php _e('Display Badge',$this->plugin_domain);?></h3></th></tr>
                    </thead>
                    <tbody>
                        <tr><td>
                                <?php _e('Many thanks to <a href="http://byteful.com">Byteful Traveller</a> for creating these images.',$this->plugin_domain);?>
                            </td></tr>
                        <tr><td>
                                <table class="form-table">
                                    <tr><td><?php _e('Choose badge to display','commentluv')?> </td>
                                        <td><label><input type="radio" <?php echo $badge1; ?> name="cl_badge" value="CL88_Black.gif"><img src="<?php echo $this->plugin_url;?>images/CL88_Black.gif"/></label></td>
                                        <td><label><input type="radio" <?php echo $badge2; ?> name="cl_badge" value="CL88_White.gif"><img src="<?php echo $this->plugin_url;?>images/CL88_White.gif"/></label></td>
                                        <td><label><input type="radio" <?php echo $badge3; ?> name="cl_badge" value="CL91_Black.gif"><img src="<?php echo $this->plugin_url;?>images/CL91_Black.gif"/></label></td>
                                        <td><label><input type="radio" <?php echo $badge4; ?> name="cl_badge" value="CL91_White.gif"><img src="<?php echo $this->plugin_url;?>images/CL91_White.gif"/></label></td>
                                        <td><label><input type="radio" <?php echo $badge5; ?> name="cl_badge" value="nothing.gif"><?php _e('Show nothing',$this->plugin_domain);?></label></td></tr>
                                </table>
                                <table class="form-table">
                                    <tr><td><label><input type="radio" <?php echo $badge_text;?> name="cl_badge" value="text"><?php _e('Show text','commentluv')?></label> <p><input class="form-table" type="text" name="cl_show_text" value="<?php echo stripslashes($show_text);?>"></input></td><td><label><?php _e('Prepend html before badge or text (optional)',$this->plugin_domain);?></label><input class="form-table" type="text" name="cl_prepend" value="<?php echo stripslashes($prepend);?>"></input></td></tr>
                                </table> 
                            </td></tr>
                    </tbody>
                </table>
                <br/>
                <table class="widefat">
                <thead>
                    <tr><th scope="col"><h3><?php _e('Technical Settings',$this->plugin_domain);?></h3></th></tr>
                </thead>
                <tbody>
                <tr><td>
                        <small><?php _e('In most cases you shouldn\'t need to change these settings unless you have a customized comment form',$this->plugin_domain);?></small><br />
                        <small><?php _e('These are the name="" values used in the HTML source for the fields on your comment form.',$this->plugin_domain);?></small>
                    </td></tr>
                <tr><td>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <td><?php _e('Authors Name field name',$this->plugin_domain);?></td>
                                    <td><input type="text" value="<?php echo $author_name;?>" name="cl_author_name"/></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Email field name',$this->plugin_domain);?></td>
                                    <td><input value="<?php echo $email_name;?>" type="text" name="cl_email_name"/></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Authors URL field name',$this->plugin_domain);?></td>
                                    <td><input value="<?php echo $url_name;?>" type="text" name="cl_url_name"/></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Comment Text Area name',$this->plugin_domain);?></td>
                                    <td><input value="<?php echo $comment_name;?>" type="text" name="cl_comment_name"/></td>
                                </tr>
                            </tbody></table>
                </td></tr>
                </tbody>
                </table>
                <div class="submit" style="width: 70px; background-color: green; padding-left: 5px;"><input type="submit" name="Submit" value="update" /></div>


            </form>


            <div style=" background-color: #ff0000; width: 200px; text-align: center;"><?php _e('Reset to Default Settings',$this->plugin_domain);?>
                <form action="<?php echo $action_url ?>" method="POST">

                <?php wp_nonce_field('commentluv-nonce');?>
                <input type="hidden" name="reset" value="reset"/>
                <?php $javamsg =  __('Are you sure you want to reset your settings? Press OK to continue',$this->plugin_domain);?>
                <input type="submit" style="width: 150px;" onclick="<?php echo 'if(confirm(\''.$javamsg.'\') != true) { return false; } else { return true; } ';?>" value="reset" name="submit"/></div>
            </form>
        </div>         
    </div>
    </div>
    
    <div style="float:left">
            <table class="widefat" style="width: 230px; margin-left: 10px;">
                <thead>
                    <tr><th scope="col"><?php _e('Plugin Info',$this->plugin_domain);?></th><th>&nbsp;</th></tr>
                </thead>
                <tbody>
                    <tr><td><strong><?php _e('Author',$this->plugin_domain);?>:</strong></td><td>Andy Bailey</td></tr>
                    <tr><td><strong><?php _e('Home Page',$this->plugin_domain);?>:</strong></td><td><a title="<?php _e('Visit ComLuv to register your site for more luv!',$this->plugin_domain);?>" href="http://comluv.com/" target="_blank">ComLuv.com</a></td></tr>
                    <tr><td><strong><?php _e('Social',$this->plugin_domain);?>:</strong></td><td><a title="Follow CommentLuv on Twitter" href="http://twitter.com/commentluv/" target="_blank"><img src="<?php echo $this->plugin_url;?>images/twitter.png"/></a> <a title="Join me on LinkedIn" href="http://uk.linkedin.com/in/commentluv" target="_blank"><img src="<?php echo $this->plugin_url;?>images/linkedin.png"/></a> <a title="Join me on Facebook" href="http://www.facebook.com/commentluv" target="_blank"><img src="<?php echo $this->plugin_url;?>images/facebook.png"/></a></td></tr>
                    <tr><td><strong><?php _e('Help',$this->plugin_domain);?>:</strong></td><td><a href="http://comluv.com/help-desk/" target="_blank"><?php _e('Help Desk',$this->plugin_domain);?></a></td></tr>
                    <tr class="alt"><td colspan="2"><?php _e('News',$this->plugin_domain);?>:</td></tr>
                    <tr><td colspan="2">
                            <?php 
                                include_once(ABSPATH . WPINC . '/rss.php');
                                wp_rss('http://comluv.com/category/newsletter/feed',3);?>
                        </td></tr>
                    <tr class="alt"><td colspan="2"><?php _e('Thanks to the following for translations',$this->plugin_domain);?>:</td></tr>
                    <tr><td><img src="<?php echo $this->plugin_url;?>images/it.png"/> <?php _e('Italian',$this->plugin_domain);?></td><td><a target="_blank" href="http://gidibao.net/">Gianni Diuno</a></td></tr>
                    <tr><td><img src="<?php echo $this->plugin_url;?>images/ru.png"/> <?php _e('Russian',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.fatcow.com/">Fatcow</a></td></tr>
                    <tr><td><img src="<?php echo $this->plugin_url;?>images/cn.png"/> <?php _e('Chinese',$this->plugin_domain);?></td><td><a target="_blank" href="http://zuoshen.com/">Donald</a></td></tr>
                    <tr><td><img src="<?php echo $this->plugin_url;?>images/il.png"/> <?php _e('Hebrew',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.maorb.info/">Maor Barazany</a></td></tr>
                    <tr><td><img src="<?php echo $this->plugin_url;?>images/fr.png"/> <?php _e('French',$this->plugin_domain);?></td><td><a target="_blank" href="http://referenceurfreelance.com/">Leo</a></td></tr>  
                    <tr><td><img src="<?php echo $this->plugin_url;?>images/nl.png"/> <?php _e('Dutch',$this->plugin_domain);?></td><td><a target="_blank" href="http://wpwebshop.com/">Rene</a></td></tr>
                    <tr><td><img src="<?php echo $this->plugin_url;?>images/pl.png"/> <?php _e('Polish',$this->plugin_domain);?></td><td><a target="_blank" href="http://techformator.pl/">Mariusz Kolacz</a></td></tr>
                </tbody>
            </table>
        </div>
    
	</div>
</div>
<?php // end ?>