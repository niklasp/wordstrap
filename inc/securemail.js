/* 
 * @plugin SO Ajax Shortcode
 */

jQuery( document ).ready( function( $ ) 
{ 

     var image = '<img src="' + wp_ajax.loading + '" alt="Loading ..." width="16" height="16" />';
     var r = $( '#resultmessage');
        $('#contactform').submit( function(e){
        e.preventDefault();
        r.html( image );
        
         var data = {
             action: 'handle_ajax_post',
             security: wp_ajax.ajaxnonce,
             data : jQuery("#contactform").serialize()
         };
        
        $.post(
            wp_ajax.ajaxurl, 
            data, 
            function(response) 
            {
                console.log(response);
                if (! response.success ) 
                {
                    if (!response.data) 
                        r.html( 'AJAX ERROR: no response' );  
                    else
                        r.html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
            + r.data("error-nomail") + '</div>');
                } 
                else 
                {
                    r.html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                        + r.data("success") + '</div>');
                    $('#contactform').hide(2000);
                }
            });
    }); // end submit
});