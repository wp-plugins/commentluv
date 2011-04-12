jQuery(document).ready(function(){
    jQuery('#cl_notify').click(function(){
        jQuery(this).val('Please wait...');
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {'action': 'notify_signup'},
            success: function(data){
                if(data.success == true){
                    jQuery('#cl_notify').parents('div.submit').slideUp();
                    jQuery('#notify_message').empty().html('Please check your inbox, an email will be sent to ' + data.email + ' in the next few minutes with a confirmation link');
                } else {
                    jQuery('#notify_message').empty().html('<strong>An error happened with the request. Try signing up at <a target="_blank" href="http://www.commentluv.com">www.commentluv.com</a>');
                }  
            }
        })
    });
});