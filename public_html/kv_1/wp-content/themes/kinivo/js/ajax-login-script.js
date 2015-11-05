jQuery(document).ready(function($) {

    // Perform AJAX login on form submit
    $('form#login-form').on('submit', function(e){
        $('form#login-form p.status').show().html(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login-form #user_login').val(), 
                'password': $('form#login-form #user_pass').val(), 
                'security': $('form#login-form #security').val() },
            success: function(data){
                $('form#login-form p.status').html(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });

});