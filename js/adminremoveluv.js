// removeluv link click action
jQuery(document).ready(function($) {
        $('a.removeluv').click(function(){
            var data = {
            	action: 'removeluv'
            };
            var vars = $(this).attr('class').split(':');
            data.c = vars[1];
            data._wpnonce = vars[2];
            jQuery.post(ajaxurl, data, function(response) {
            	if(response == '0'){
                    alert('Luv meta does not exist');
                    return;
                }
                var stuff = response.split('*');
                var cid = stuff[0];
                var js = stuff[1];
                var del_status =(new Function("return " + js))(); 
                var statuscode = del_status.meta[0].status;
                if(statuscode == 200){
                    var msg = 'Luv removed from WP DB and from ComLuv.com';
                } else  {
                    var msg = 'had a booboo :(';
                }
                
                $('#comment-'+cid+' td.comment .cluv').text(msg).fadeOut("slow").fadeIn("slow");
		$('#comment-'+cid+' span.Remove-luv').text('');
                
            });
            
            return false;
        });
});