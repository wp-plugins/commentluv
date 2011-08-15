// commentluv 2.89
jQuery(document).ready(function(){
    // get the form object and fields
    var formObj = jQuery('#cl_post_title').parents('form');
    var urlObj = cl_settings['urlObj'] = jQuery("input[name='" + cl_settings['url'] + "']",formObj);
    var comObj = cl_settings['comObj'] = jQuery("textarea[name='" + cl_settings['comment'] + "']",formObj);
    var autObj = jQuery("input[name='" + cl_settings['name'] + "']",formObj);
    var emaObj = jQuery("input[name='" + cl_settings['email'] + "']",formObj);
    // setup localized object with temporary vars
    cl_settings['url_value'] = urlObj.val();
    cl_settings['fired'] = 'no';
    // set event listener for textarea focus
    comObj.focus(function(){
        cl_dostuff();
    });
    // set the event listener for the click of the checkbox
    jQuery('#doluv').click(function(){
        jQuery('#lastposts').hide();
        if(jQuery(this).is(":checked")){
            // was unchecked, now is checked
            jQuery('#mylastpost').fadeTo("fast",1);
            cl_settings['fired'] = 'no';
            cl_dostuff();
        } else {
            // was checked, user unchecked it so empty hidden fields in form
            jQuery('input[name="cl_post_title"]').val("");
            jQuery('input[name="cl_post_url"]').val("");
            jQuery('#mylastpost').fadeTo("slow",0.3);
            jQuery('#lastposts').empty();
        }
    });
    // hide/show showmore
    jQuery(document.body).click(function(){
        if(cl_settings['lastposts'] == 'showing'){
            jQuery('#lastposts').slideUp('',function(){cl_settings['lastposts'] = 'not'}); 
        }
    });
    jQuery('#showmorespan img').click(function(){
        if(cl_settings['lastposts'] == 'not'){
            jQuery('#lastposts').slideDown('',function(){cl_settings['lastposts'] = 'showing'}); 
        } 
    });
});

/**
* checks everything is in place for doing stuff
* returns string 'ok' if, um, ok
*/
function cl_docheck(){
    var url = cl_settings['urlObj'];
    var msg = jQuery('#cl_messages');
    msg.empty();
    url.removeClass('cl_error');
    // check that there is a value in the url field
    if(url.val().length > 1){
        // is value just http:// ?
        if(url.val() == 'http://'){
            url.addClass('cl_error');
            cl_message(cl_settings['no_url_message']);
            return;
        }
        // is the http:// missing?
        if(url.val().substring(0,7) != 'http://'){
            url.addClass('cl_error');
            cl_message(cl_settings['no_http_message']);
            return;
        }
    } else {
        // there is no value
        url.addClass('cl_error');
        cl_message(cl_settings['no_url_message']);
        return;
    }
    // if we are here, all is cool mon
    return 'ok';
}
/**
* tries to fetch last blog posts for a url
*/
function cl_dostuff(){
    if(cl_docheck() != 'ok'){
        return;
    }
    var url = cl_settings['urlObj'];
    if(cl_settings['fired'] == 'yes'){
        // already fired, fire again if current url is different to last fired
        if(url.val() == cl_settings['url_value']){
            return;
        }          
        jQuery('#lastposts,#mylastpost').empty();
    }
    // fire the request to admin
    jQuery('#cl_messages').append('<img src="' + cl_settings['images'] + 'loader.gif' + '"/>').show();
    jQuery.ajax({
        url: cl_settings['api_url'],
        type: 'post',
        dataType: 'json',
        data: {'url':url.val(),'action':'cl_fetch'},
        success: function(data){
            if(data.error == ''){
                // no error, fill up lastposts div with items returned
                jQuery('#cl_messages').empty().hide();
                jQuery.each(data.items,function(j,item){
                    var title = item.title;
                    var link = item.link;
                    var count = '';
                    jQuery('#lastposts').append('<span id="' + item.link + '" class="choosepost ' + item.type + '">' + title + '</span>');
                });
                // setup first link and hidden fields
                jQuery('#mylastpost').html('<a href="' + data.items[0].link +'"> ' + data.items[0]['title'] + '</a>').fadeIn(1000);
                jQuery('#cl_post_title').val(data.items[0].title);
                jQuery('#cl_post_url').val(data.items[0].link);
                // setup look and show dropdown
                jQuery('span.message').css({'backgroundColor':jQuery('body').css('background-color'),'color':jQuery('body').css('color')});
                jQuery('#showmorespan img').show();
                jQuery('#lastposts').css('width',cl_settings['comObj'].width()).slideDown('',function(){ cl_settings['lastposts'] = 'showing'});
                // bind click action
                jQuery('.choosepost:not(.message)').click(function(){
                    jQuery('#cl_post_title').val(jQuery(this).text());
                    jQuery('#cl_post_url').val(jQuery(this).attr('id'));
                    jQuery('#mylastpost').html('<a href="' + jQuery(this).attr('id') +'"> ' + jQuery(this).text() + '</a>').fadeIn(1000); 
                });
            } else {
                cl_message(data.error);
            }
        }
    })



    // save what url used and that we checked already
    cl_settings['fired'] = 'yes';
    cl_settings['url_value'] = url.val(); 
}
/**
* adds a message to tell the user something in the cl_message div and then slides it down
* @param string message - the message to show
*/
function cl_message(message){
    jQuery('#cl_messages').empty().hide().text(message).slideDown();
}