<?php /* CommentLuv 2.81.8
    Plugin Name: CommentLuv
    Plugin URI: http://comluv.com/download/commentluv-wordpress/
    Description: Plugin to show a link to the last post from the commenters blog by parsing the feed at their given URL when they leave a comment. Rewards your readers and encourage more comments.
    Version: 2.8.2
    Author: Andy Bailey
    Author URI: http://fiddyp.co.uk/
    */
    // Avoid name collision
    if (! class_exists ( 'commentluv' )) {
        // let class begin
        class commentluv {
            //localization domain
            var $plugin_domain = 'commentluv';
            var $plugin_url;
            var $db_option = 'commentluv_options';
            var $cl_version = 281.8;
            var $api_url;
            var $test = false;

            //initialize the plugin
            function commentluv() {
                global $wp_version, $pagenow;
                // pages where commentluv needs translation
                $local_pages = array ('plugins.php', 'commentluv.php' );
                if(isset($_GET['page'])){
                    $page = $_GET['page'];
                } else {
                    $page = '';
                }
                // check if translation needed on current page
                if (in_array ( $pagenow, $local_pages ) || in_array ( $page, $local_pages )) {
                    $this->handle_load_domain ();
                }
                $exit_msg = __ ( 'CommentLuv requires Wordpress 2.9.2 or newer.', $this->plugin_domain ) . '<a href="http://codex.wordpress.org/Upgrading_Wordpress">' . __ ( 'Please Update!', $this->plugin_domain ) . '</a>';

                // can you dig it?
                if (version_compare ( $wp_version, "2.9.2", "<" )) {
                    echo ( $exit_msg ); // no diggedy
                }
                //DebugBreak();
                // check if update changes needed
                $installed_version = get_option('cl_version', '280'); // if no version saved, use number from before css change
                if($installed_version < $this->cl_version){
                    update_option('cl_version',$this->cl_version);
                    $options = $this->get_options();
                    if(version_compare($installed_version,'281.1','<')){
                        // set new defaults for info back color and badge (made on 281.1)
                        $options['badge'] = 'CL91_White.gif';
                        $options['infoback'] = "white";
                        $installed_version = '281.1';
                    }
                    if(version_compare($installed_version,'281.7','<')){
                        $options['nofollow'] = 'all';
                        $installed_version = '281.7';
                    }
                    update_option($this->db_option,$options);
                }

                // action hooks
                $this->plugin_url = trailingslashit ( WP_PLUGIN_URL . '/' . dirname ( plugin_basename ( __FILE__ ) ) );
                if($this->test){
                    $this->api_url = 'http://firedwok.com/cl_api/commentluvapi.php';	
                } else {
                    $this->api_url = 'http://api.comluv.com/cl_api/commentluvapi.php';	
                }
                add_action ( 'admin_menu', array (&$this, 'admin_menu' ) ); // add image to admin link and setup options page
                add_action ( 'admin_print_scripts-post.php', array (&$this, 'add_removeluv_script') ); // add the removeluv script to admin page
                add_action ( 'admin_print_scripts-edit-comments.php', array (&$this, 'add_removeluv_script') ); // add the removeluv script to admin page
                add_action ( 'admin_print_scripts-settings_page_commentluv', array(&$this,'add_settings_page_script'));
                add_action ( 'wp_ajax_notify_signup', array(&$this,'notify_signup'));
                add_action ( 'wp_ajax_removeluv', array (&$this, 'cl_remove_luv') ); // handle the call to the admin-ajax for removing luv
                add_action ( 'template_redirect', array (&$this, 'commentluv_scripts' ) ); // template_redirect always called when page is displayed to user
                add_action ( 'wp_head', array (&$this, 'commentluv_style' ) ); // add style sheet to header
                add_action ( 'wp_set_comment_status', array (&$this, 'update_cl_status' ), 1, 3 ); // call when status of comment gets changed
                add_action ( 'comment_post', array (&$this, 'update_cl_status' ), 2, 3 ); // call when comment gets posted
                add_action ( 'comment_form', array (&$this, 'add_fields' ) ); // add hidden fields during comment form display time
                add_action ( 'wp_insert_comment', array (&$this, 'cl_post'),1,2); // add member id and other data to comment meta priority 1, 2 vars
                add_filter ( 'plugin_action_links', array (&$this, 'commentluv_action' ), - 10, 2 ); // add a settings page link to the plugin description. use 2 for allowed vars
                add_filter ( 'comments_array', array (&$this, 'do_shortcode' ), 1 ); // replace inserted data with hidden span on display time of comment
                add_filter ( 'comment_text', array (&$this, 'do_shortcode' ), 1 ); // add last blog post data to comment content on admin screen
                add_filter ( 'comment_row_actions', array (&$this,'add_removeluv_link')); // adds a link to remove the luv from a comment on the comments admin screen
            }
            // ajax handler for signup button in settings page
            function notify_signup(){
                global $current_user;
                $email = $current_user->user_email;
                $firstname = $current_user->first_name;
                if(!$firstname){
                    $firstname = $current_user->user_nicename;
                }
                $message = "\n Email: ".$email."\n\n Name: ".$firstname."\n\n Meta: settings_page_2817\n\n";
                $to = 'cl_notify29@aweber.com';
                $headers = 'From: '.$firstname.' <'.$email.'>'."\r\n\\";
                $mail = wp_mail($to,'cl_notify',$message,$headers);
                if($mail === true){
                    $options = $this->get_options();
                    $options['subscribed'] = true;
                    update_option($this->db_option,$options);
                }
                $return = array('success'=>$mail,'email'=>$email);
                // return response
                $response = json_encode($return);
                // response output
                header( "Content-Type: application/json" );
                echo $response;
                // IMPORTANT: don't forget to "exit"
                exit;
            }
            // hook the options page
            function admin_menu() {
                $menutitle = '<img src="' . $this->plugin_url . 'images/littleheart.gif" alt=""/> ';
                $menutitle .= 'CommentLuv';
                add_options_page ( 'CommentLuv Settings', $menutitle, 8, basename ( __FILE__ ), array (&$this, 'handle_options' ) );
            }
            function add_removeluv_script(){
                wp_enqueue_script ( 'commentluv', $this->plugin_url . 'js/adminremoveluv.js', array ('jquery' ) );
            }
            function add_settings_page_script (){
                wp_enqueue_script ('notify_signup', $this->plugin_url . 'js/notify_signup.js', array('jquery') );
            }
            // add the settings link
            function commentluv_action($links, $file) {
                $this_plugin = plugin_basename ( __FILE__ );
                if ($file == $this_plugin) {
                    $links [] = "<a href='options-general.php?page=commentluv.php'>" . '<img src="' . $this->plugin_url . 'images/littleheart.gif" alt=""/> ' . __ ( 'Settings', $this->plugin_domain ) . "</a>";
                }
                return $links;
            }
            // add removeluv link
            function add_removeluv_link($actions){
                global $post;
                $user_can = current_user_can('edit_post', $post->ID);
                $cid = get_comment_ID();
                if(get_comment_meta($cid,'cl_data') && wp_get_comment_status($cid) == 'approved'){
                    if($user_can){
                        $nonce= wp_create_nonce  ('removeluv'.get_comment_ID());
                        $actions['Remove-luv'] = '<a class="removeluv :'.get_comment_ID().':'.$nonce.'" href="'.admin_url('edit-comments.php').'">Remove Luv</a>';
                    }
                }
                return $actions;
            }
            // remove luvlink from comment when called by admin-ajax
            function cl_remove_luv(){
                // check user is allowed to do this
                $nonce=$_REQUEST['_wpnonce'];
                $cid = $_REQUEST['c'];
                if (! wp_verify_nonce($nonce, 'removeluv'.$cid) ) die("Epic fail");
                // delete meta if comment id sent with request
                if($cid){
                    // get meta and set vars if exists
                    $cmeta =get_comment_meta($cid,'cl_data','true');
                    if($cmeta) extract($cmeta);
                    // delete it and call comluv to tell it what happened
                    if(delete_comment_meta($cid,'cl_data')){
                        $url = $this->api_url.'?type=update&updatetype=delete&request_id='.$cl_requestid.'&choice_id='.$cl_choiceid.'&version='.$temp->cl_version;
                        $status = $this->call_comluv($url);
                        // return the comment id and status code for js processing to hide luv
                        echo "$cid*$status*";
                        return;
                    }
                } else {
                    echo '0';
                }
            }
            // hook the template_redirect for inserting style and javascript (using wp_head would make it too late to add dependencies)
            function commentluv_scripts() {
                // only load scripts if on a single page
                if (is_singular()) {
                    wp_enqueue_script ( 'jquery' );
                    wp_enqueue_script ( 'hoverIntent', '/' . WPINC . '/js/hoverIntent.js', array ('jquery' ) );
                    wp_enqueue_script ( 'commentluv', $this->plugin_url . 'js/commentluv.js', array ('jquery' ) );
                    // get options
                    $options = $this->get_options ();
                    foreach ( $options as $key => $value ) {
                        $$key = $value;
                    }
                    // prepare options
                    $default_on = $default_on == 'on' ? 'checked' : '';
                    // untick the box if user is admin
                    global $user_ID;
                    if ($user_ID) {
                        if (current_user_can ( 'create_users' )) {
                            $default_on = '';
                        }
                    }
                    $badge = $this->plugin_url . "images/" . $badge;
                    $badge_text = $options ['badge'] == 'text' ? 'on' : '';
                    // get permalink for refer value
                    $refer_page = get_permalink();
                    if($compat != 'on'){
                        // insert options to header
                        wp_localize_script ( 'commentluv', 'cl_settings', array ('name' => $author_name, 'url' => $url_name, 'comment' => $comment_name, 'email' => $email_name, 'prepend' => $prepend, 'badge' => $badge,
                        'show_text' => $show_text, 'badge_text' => $badge_text, 'heart_tip' => $heart_tip, 'default_on' => $default_on, 'select_text' => $select_text,
                        'cl_version' => $this->cl_version, 'images' => $this->plugin_url . 'images/', 'api_url' => $this->api_url, 'refer' => $refer_page,
                        'infoback' => $infoback,'usetemplate'=>$use_template) );
                    } else {
                        add_action('wp_footer',array(& $this, 'footer_script'));
                    }

                }
            }

            // footer script to echo out cl_settings if compression compatibility is on
            function footer_script(){
                $options = $this->get_options();
                $refer_page = get_permalink();
                echo '<script type="text/javascript">
                /* <![CDATA[ */
                var cl_settings = {
                name: "'.$options['author_name'].'",
                url: "'.$options['url_name'].'",
                comment: "'.$options['comment_name'].'",
                email: "'.$options['email_name'].'",
                prepend: "'.$options['prepend'].'",
                badge: "'.$options['badge'].'",
                show_text: "'.$options['show_text'].'",
                badge_text: "'.$options['badge_text'].'",
                heart_tip: "'.$options['heart_tip'].'",
                default_on : "'.$options['default_on'].'",
                select_text : "'.$options['select_text'].'",
                cl_version : "'.$this->cl_version.'",
                images : "'.$this->plugin_url.'images/",
                api_url : "'.$this->api_url.'",
                refer : "'.$refer_page.'",
                infoback : "'.$infoback.'",
                usetemplate : "'.$use_template.'"
                };
                /* ]]> */
                ';
                echo '</script>
                ';
            }
            // hook the head function for adding stylesheet
            function commentluv_style() {
                echo '<link rel="stylesheet" href="' . $this->plugin_url . 'style/cl_style.css" type="text/css" />';
            }

            // get plugin options
            function get_options() {
                // default values
                $options = array ('comment_text' => '[name] recently posted..[lastpost]', 'select_text' => 'choose a different post to show', 'default_on' => 'on', 'heart_tip' => 'on', 'use_template' => '', 'badge' => 'CL91_White.gif', 'show_text' => 'CommentLuv Enabled', 'author_name' => 'author', 'url_name' => 'url', 'comment_name' => 'comment', 'email_name' => 'email', 'prepend' => '', 'infoback' => 'white' , 'compat'=>'off', 'nofollow'=>'none');
                // get saved options unless reset button was pressed
                $saved = '';
                if (! isset ( $_POST ['reset'] )) {
                    $saved = get_option ( $this->db_option );
                }

                // assign values
                if (! empty ( $saved )) {
                    foreach ( $saved as $key => $option ) {
                        $options [$key] = $option;
                    }
                }
                // update the options if necessary
                if ($saved != $options) {
                    update_option ( $this->db_option, $options );
                }
                // return the options
                return $options;
            }

            // handle saving and displaying options
            function handle_options() {
                $options = $this->get_options ();
                if (isset ( $_POST ['submitted'] )) {

                    // initialize the error class
                    $errors = new WP_Error ( );

                    // check security
                    check_admin_referer ( 'commentluv-nonce' );

                    $options = array ();
                    $options ['comment_text'] = htmlspecialchars ( $_POST ['cl_comment_text'] );
                    $options ['select_text'] = htmlspecialchars ( $_POST ['cl_select_text'] );
                    $options ['default_on'] = $_POST ['cl_default_on'];
                    $options ['heart_tip'] = $_POST ['cl_heart_tip'];
                    $options ['badge'] = $_POST ['cl_badge'];
                    $options ['show_text'] = htmlspecialchars ( $_POST ['cl_show_text'] );
                    $options ['prepend'] = htmlspecialchars ( $_POST ['cl_prepend'] );
                    $options ['author_name'] = $_POST ['cl_author_name'];
                    $options ['url_name'] = $_POST ['cl_url_name'];
                    $options ['comment_name'] = $_POST ['cl_comment_name'];
                    $options ['email_name'] = $_POST ['cl_email_name'];
                    $options ['use_template'] = $_POST['cl_use_template'];
                    $options ['infoback'] = htmlspecialchars($_POST['infoback']);
                    $options ['compat'] = $_POST['cl_compat'];
                    $options ['nofollow'] = $_POST['nofollow'];

                    // check for errors
                    if (count ( $errors->errors ) > 0) {
                        echo '<div class="error"><h3>';
                        _e ( 'There were errors with your chosen settings', $this->plugin_domain );
                        echo '</h3>';
                        foreach ( $errors->get_error_messages () as $message ) {
                            echo $message;
                        }
                        echo '</div>';
                    } else {
                        //every-ting cool mon
                        update_option ( $this->db_option, $options );
                        echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
                    }

                }
                // loop through each option and assign it as key=value
                foreach ( $options as $key => $value ) {
                    $$key = $value;
                }
                // set value to checked if option is on (for showing correct status of checkbox and radio button in settings page)
                $default_on = $options ['default_on'] == 'on' ? 'checked' : '';
                $heart_tip = $options ['heart_tip'] == 'on' ? 'checked' : '';
                $compat = $options ['compat'] == 'on' ? 'checked' : '';
                $badge1 = $options ['badge'] == 'CL88_Black.gif' ? 'checked="checked"' : '';
                $badge2 = $options ['badge'] == 'CL88_White.gif' ? 'checked="checked"' : '';
                $badge3 = $options ['badge'] == 'CL91_Black.gif' ? 'checked="checked"' : '';
                $badge4 = $options ['badge'] == 'CL91_White.gif' ? 'checked="checked"' : '';
                $badge5 = $options ['badge'] == 'nothing.gif' ? 'checked="checked"' : '';
                $use_template = $options ['use_template'] == 'on' ? 'checked="checked"' : '';
                $badge_text = $options ['badge'] == 'text' ? 'checked="checked"' : '';

                // url for form submit
                $action_url = $_SERVER ['REQUEST_URI'];
                include ('commentluv-manager.php');
            }
            // shortcode for showing badge and drop down box
            function display_badge() {
                if (is_singular()) {
                    global $badgeshown;
                    $options = get_option ( $this->db_option );
                    // choose as image or as text
                    $badge_text = $options ['badge'] == 'text' ? 'on' : '';
                    $default_on = $options ['default_on'] == 'on' ? 'checked="checked"' : '';
                    // untick the box if user is admin
                    global $user_ID;
                    if ($user_ID) {
                        if (current_user_can ( 'create_users' )) {
                            $default_on = '';
                        }
                    }
                    $options ['badge'] = $this->plugin_url . 'images/' . $options ['badge'];
                    if ($badge_text == '') {
                        $badge = '<a href="http://comluv.com" target="_blank"><img src="' . $options ['badge'] . '" border="0" alt="' . $options ['show_text'] . '" title="' . $options ['show_text'] . '"/></a>';
                    } else {
                        $badge = '<a href="http://comluv.com" target="_blank">' . $options ['show_text'] . '</a>';
                    }
                    if($options['prepend']){
                        $prepend = stripslashes($options['prepend']);
                        $decodeprepend = htmlspecialchars_decode_own($prepend);
                    }
                    echo '<div id="commentluv">' . $decodeprepend . '<input type="checkbox" id="doluv" name="doluv" ' . $default_on . ' style="width:25px;"/><span id="mylastpost" style="clear: both">' . $badge . '</span><span id="showmorespan" style="width: 30px; height: 15px; cursor: pointer;"><img class="clarrow" id="showmore" src="' . $this->plugin_url . 'images/down-arrow.gif" alt="show more" style="display:none;"/></span></div><div id="lastposts" style="display: none;"></div>';
                    $badgeshown = TRUE;
                }
            }
            // hook the comment form to add fields for url for logged in users
            function add_fields($id) {
                $options = get_option ( $this->db_option );
                $cl_author_id = $options ['author_name'];
                $cl_site_id = $options ['url_name'];

                if (is_user_logged_in ()) {
                    // get options values and insert as hidden fields
                    global $userdata;
                    get_currentuserinfo ();
                    $author = $userdata->display_name;
                    $userid = $userdata->ID;
                    $url = $userdata->user_url;
                    if(!stristr($url,"http://")){
                        $url = "http://".$url;
                    }
                    // check for MU blog
                    if ($this->check_this_is_multsite()) {
                        if (! $url || $url == "http://") {
                            $userbloginfo = get_blogs_of_user ( $userid, 1 );
                            $url = $userbloginfo [1]->siteurl;
                        }
                    }

                    echo "<input type='hidden' id='$cl_author_id' name='$cl_author_id' value='$author' />";
                    // check for buddypress
                    if(function_exists('bp_core_setup_globals')){
                        $input_type = 'text';
                    } else {
                        $input_type = 'hidden';
                    }
                    echo "<input type='$input_type' id='$cl_site_id' name='$cl_site_id' value='$url' />";
                }
                global $fieldsadded;
                if(!$fieldsadded){
                    // add hidden fields for holding information about type,choice,html and request for every user
                    echo '<input type="hidden" name="cl_type" />';
                    echo '<input type="hidden" name="choice_id" />';
                    echo '<input type="hidden" name="request_id" />';
                    echo '<input type="hidden" name="cl_post_title" id="cl_post_title"/>';
                    echo '<input type="hidden" name="cl_post_url" id="cl_post_url"/>';
                    echo '<input type="hidden" name="cl_memberid" id="cl_memberid"/>';
                    $fieldsadded = TRUE;
                }
                // check if using php call comments.php or not
                global $badgeshown;
                if ($options ['use_template'] == '' && !$badgeshown) {
                    $this->display_badge ();
                }
                return $id;
            }

            // hook the pre_comment_content to add the link
            function cl_post($id,$commentdata) {
                $cl_requestid = intval($_POST['request_id']);
                if($cl_requestid > 1 && $_POST['cl_type'] != 'undefined'){
                    // only do stuff if the comment had a successful last blog post
                    // and if the meta hasn't been added yet.
                    // (request id will be -1 if error, or no posts returned.)
                    $cl_memberid = intval($_POST['cl_memberid']);
                    $cl_choiceid = intval($_POST['choice_id']);
                    $cl_post_title = apply_filters('kses',$_POST['cl_post_title']);
                    $cl_post_url = apply_filters('kses',$_POST['cl_post_url']);
                    $cl_type = apply_filters('kses',$_POST['cl_type']);
                    $data = array('cl_memberid'=>$cl_memberid,'cl_requestid'=>$cl_requestid,'cl_choiceid'=>$cl_choiceid,'cl_post_title'=>$cl_post_title,'cl_post_url'=>$cl_post_url,'cl_type'=>$cl_type);
                    add_comment_meta($id,'cl_data',$data,'true');				
                }
            }
            // hook the set comment status action
            function update_cl_status($cid, $status) {
                // get comment stuff from meta
                $data = get_comment_meta($cid,'cl_data','true');
                if($data && is_array($data)){
                    extract($data);
                    $url = $this->api_url.'?type=update&updatetype='.$status.'&request_id='.$cl_requestid.'&choice_id='.$cl_choiceid.'&version='.$this->cl_version;
                    $content = $this->call_comluv ( $url );
                    if($this->test){
                        update_option('cl_last_comment_status',"$cid was $status");
                        update_option('cl_last_response',$content);
                    }
                }
            }

            // use my own shortcode that was inserted at submission time and hide the params
            function do_shortcode($commentarray) {
                $isadminpage = 0;
                $options = get_option ( $this->db_option );
                if(!is_array($commentarray)){
                    // if it's an array then it was called by comments_array filter,
                    // otherwise it was called by comment_content (admin screen)
                    // has it been done before?
                    if(strpos($commentarray,'class="cluv"')){
                        return $commentarray;
                    }
                    // make a fake array of 1 object so below treats the comment_content filter nicely for admin screen
                    $temparray = array('comment_ID'=>get_comment_ID(),'comment_content'=>$commentarray,'comment_author'=>get_comment_author(), 'comment_author_email'=>get_comment_author_email());
                    $tempobject = (object) $temparray;
                    $commentarray = array($tempobject);
                    $isadminpage = 1;
                } 
                // step through each comment in the array and add the shizzle to the nizzle (stoopid thesis make me do this instead of filtering comment_text)
                $new_commentarray = array();
                foreach($commentarray as $comment){
                    $data = get_comment_meta($comment->comment_ID,'cl_data','true');
                    $commentcontent = $comment->comment_content;
                    if (strpos ( $commentcontent, ".-=" ) && strpos ( $commentcontent, "=-." )) {
                        // handle old 2.7691 comments with hardcoded link
                        $last_pos = $this->my_strrpos ( $commentcontent, ".-=" ); // position number for last occurence of .-=
                        $beforecltext = substr ( $commentcontent, 0, $last_pos ); // get text before last position of .-=
                        $cltext = substr ( $commentcontent, $last_pos ); // get the bit between .-= and =-.
                        $cltext = str_replace ( array (".-=", "=-." ), array ('<span class="cluv">', '' ), $cltext ); // replace .-= with span and chop off last =-.
                        $commentcontent = $beforecltext . $cltext;
                        // do heart info
                        if ($options ['heart_tip'] == 'on') {
                            $commentcontent .= '<span class="heart_tip_box"><img class="heart_tip" alt="My ComLuv Profile" border="0" width="16" height="14" src="' . $this->plugin_url . 'images/littleheart.gif"/></span>';
                        }
                        $commentcontent .= '</span>';
                        $luvedit = TRUE;
                    }
                    // handle new 2.80+ comments with hidden post data
                    if($data && is_array($data) && !$luvedit){
                        // has meta data associated
                        extract($data,EXTR_OVERWRITE);
                        //array('cl_memberid'=>$cl_memberid,'cl_requestid'=>$cl_requestid,'cl_choiceid'=>$cl_choiceid,'cl_post_title'=>$cl_post_title,'cl_post_url'=>$cl_post_url,'cl_type'=>$cl_type);
                        // add link to comment
                        // add rel="nofollow" to link?
                        $nofollow = ' rel="nofollow"';
                        if($options['nofollow'] == 'none'){
                            $nofollow = '';
                        } elseif ($options['nofollow'] == 'unreg' && get_user_by_email($comment->comment_author_email)){
                            $nofollow = '';
                        }

                        $luvlink = '<a'.$nofollow.' href="'.$cl_post_url.'">'.$cl_post_title.'</a>';
                        $prepend_text = $options ['comment_text'];
                        $search = array ('[name]', '[type]', '[lastpost]' );
                        $replace = array ($comment->comment_author, $cl_type, $luvlink );
                        $inserted = str_replace ( $search, $replace, $prepend_text );
                        // insert identifying data and insert text/link to end of comment
                        $commentcontent .= "\n<span class=\"cluv\">$inserted";
                        // prepare heart icon
                        $hearticon = '';
                        if($cl_memberid > 2 ){
                            // use PLUS heart for members
                            $hearticon = 'plus';
                        }
                        if ($options ['heart_tip'] == 'on') {
                            $commentcontent .= '<span class="heart_tip_box"><img class="heart_tip '.$cl_memberid.'" alt="My ComLuv Profile" border="0" width="16" height="14" src="' . $this->plugin_url . 'images/littleheart'.$hearticon.'.gif"/></span>';
                        }
                        $commentcontent.= '</span>';
                    }
                    // store new content in this comments comment_content cell
                    $comment->comment_content = $commentcontent;
                    // fill new array with this comment
                    $new_commentarray[] = $comment;
                }

                // admin page or public page?
                if($isadminpage){
                    // is being called by comment_text filter so expecting just content
                    return $commentcontent;
                } else {
                    // called from comments_array filter so expecting array of objects
                    return $new_commentarray;
                }
            }

            // set up default values
            function install() {
                // set default options
                $this->get_options ();
            }

            // Localization support
            function handle_load_domain() {
                // get current language
                $locale = get_locale ();

                // locate translation file
                $mofile = WP_PLUGIN_DIR . '/' . plugin_basename ( dirname ( __FILE__ ) ) . '/lang/' . $this->plugin_domain . '-' . $locale . '.mo';

                // load translation
                load_textdomain ( $this->plugin_domain, $mofile );
            }
            // call home to tell about comment submission or status
            function call_comluv($url) {
                if (function_exists ( "curl_init" )) {
                    //setup curl values
                    $curl = curl_init ();
                    curl_setopt ( $curl, CURLOPT_URL, $url );
                    curl_setopt ( $curl, CURLOPT_HEADER, 0 );
                    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
                    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);
                    curl_setopt ( $curl, CURLOPT_TIMEOUT, 7 );
                    $content = curl_exec ( $curl );
                    if (! curl_error ( $curl )) {
                        /* removed json_decode until fix from wp 2.9 is released
                        if (function_exists ( json_decode )) {
                        $data = json_decode ( $content );
                        if ($data->status != 200) {
                        // unsuccessful confirmation.
                        // have a tantrum here if you want.
                        }
                        }
                        */
                        curl_close ( $curl );

                    }
                } elseif (ini_get ( 'allow_url_fopen' )) {
                    $content = @file_get_contents ( $url );
                }
                return $content;
            }

            // from http://frumph.net/wordpress/wordpress-plugin-theme-check-for-multisitewpmu/
            // check for multisite. Returns boolean
            function check_this_is_multsite() {
                global $wpmu_version;
                if (function_exists('is_multisite')){
                    if (is_multisite()) {
                        return true;
                    }
                    if (!empty($wpmu_version)){
                        return true;
                    }
                }
                return false;
            }
            // find last occurrence of string in string (for php 4)
            function my_strrpos($haystack, $needle, $offset = 0) {
                // same as strrpos, except $needle can be a string
                // http://www.webmasterworld.com/forum88/10570.htm
                $strrpos = false;
                if (is_string ( $haystack ) && is_string ( $needle ) && is_numeric ( $offset )) {
                    $strlen = strlen ( $haystack );
                    $strpos = strpos ( strrev ( substr ( $haystack, $offset ) ), strrev ( $needle ) );
                    if (is_numeric ( $strpos )) {
                        $strrpos = $strlen - $strpos - strlen ( $needle );
                    }
                }
                return $strrpos;
            }

        }
    }

    // start commentluv class engines
    if (class_exists ( 'commentluv' )) :
        $badgeshown=FALSE;
        $fieldsadded = FALSE;
        $commentluv = new commentluv ( );

        // confirm warp capability
        if (isset ( $commentluv )) {
            // engage
            register_activation_hook ( __FILE__, array (&$commentluv, 'install' ) );

        }


        endif;


    // function for template call
    function cl_display_badge() {
        $temp = new commentluv ( );
        $temp->display_badge ();
    }

    function htmlspecialchars_decode_own($string,$style=ENT_COMPAT)
    {
        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
        if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
        return strtr($string,$translation);
    }
?>
