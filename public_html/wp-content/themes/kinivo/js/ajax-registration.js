jQuery(document).ready(function($) {

  
    /**
     * When user clicks on button...
     *
     */
    $('#placeholder-bto').click( function(event) {

      if ($('#create-an-account').is(':checked')) {
      /**
       * Prevent default action, so when user clicks button he doesn't navigate away from page
       *
       */
      if (event.preventDefault) {
          event.preventDefault();
      } else {
          event.returnValue = false;
      }

      // Show 'Please wait' loader to user, so she/he knows something is going on
      $('.indicator').show();

      // If for some reason result field is visible hide it
      $('.result-message').hide();

      // Collect data from inputs
      var reg_nonce = 'true';
      var reg_user  = $('#vb_email').val();
      var reg_pass  = $('#vb_pass').val();
      var reg_mail  = $('#vb_email').val();
      var reg_name  = reg_user.substr(0, reg_user.indexOf('@')); ;
      var reg_nick  = $('#vb_email').val();

      /**
       * AJAX URL where to send data 
       * (from localize_script)
       */
      var ajax_url = vb_reg_vars.vb_ajax_url;

      // Data to send
      data = {
        action: 'register_user',
        nonce: reg_nonce,
        user: reg_user,
        pass: reg_pass,
        mail: reg_mail,
        name: reg_name,
        nick: reg_nick,
      };

      // Do AJAX request
      $.post( ajax_url, data, function(response) {

        // If we have response
        if( response ) {

          // Hide 'Please wait' indicator
          $('.indicator').hide();

          if( response === '1' ) {
            // If user is created
            $('.result-message').html('Your submission is complete.'); // Add success message to results div
            $('.result-message').addClass('alert-success'); // Add class success to results div
            $('.result-message').show(); // Show results div

            // $( "form[name='checkout']" ).submit();

            /*Send Order*/
            var $form = $( "form[name='checkout']" );

            if ( $form.is( '.processing' ) ) {
              return false;
            }

            // Trigger a handler to let gateways manipulate the checkout if needed
            if ( $form.triggerHandler( 'checkout_place_order' ) !== false && $form.triggerHandler( 'checkout_place_order_' + $( '#order_review input[name=payment_method]:checked' ).val() ) !== false ) {

              $form.addClass( 'processing' );

              var form_data = $form.data();

              if ( form_data["blockUI.isBlocked"] != 1 ) {
                $form.block({ message: null, overlayCSS: { background: '#fff url(' + wc_checkout_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });
              }

              $.ajax({
                type:   'POST',
                url:    wc_checkout_params.checkout_url,
                data:   $form.serialize(),
                success:  function( code ) {
                  var result = '';

                  try {
                    // Get the valid JSON only from the returned string
                    if ( code.indexOf( '<!--WC_START-->' ) >= 0 )
                      code = code.split( '<!--WC_START-->' )[1]; // Strip off before after WC_START

                    if ( code.indexOf( '<!--WC_END-->' ) >= 0 )
                      code = code.split( '<!--WC_END-->' )[0]; // Strip off anything after WC_END

                    // Parse
                    result = $.parseJSON( code );

                    if ( result.result === 'success' ) {
                      if ( result.redirect.indexOf( "https://" ) != -1 || result.redirect.indexOf( "http://" ) != -1 ) {
                        window.location = result.redirect;
                      } else {
                        window.location = decodeURI( result.redirect );
                      }
                    } else if ( result.result === 'failure' ) {
                      throw 'Result failure';
                    } else {
                      throw 'Invalid response';
                    }
                  }

                  catch( err ) {

                    if ( result.reload === 'true' ) {
                      window.location.reload();
                      return;
                    }

                    // Remove old errors
                    $( '.woocommerce-error, .woocommerce-message' ).remove();

                    // Add new errors
                    if ( result.messages ) {
                      $form.prepend( result.messages );
                      
                    } else {
                      $form.prepend( code );
                    }

                    // Cancel processing
                    $form.removeClass( 'processing' ).unblock();

                    // Lose focus for all fields
                    $form.find( '.input-text, select' ).blur();

                    // Scroll to top
                    $( 'html, body' ).animate({
                      scrollTop: ( $( 'form.checkout' ).offset().top - 100 )
                    }, 1000 );

                    // Trigger update in case we need a fresh nonce
                    if ( result.refresh === 'true' )
                      $( 'body' ).trigger( 'update_checkout' );

                    $( 'body' ).trigger( 'checkout_error' );
                  }
                },
                dataType: 'html'
              });

            }
            /*End Send Order*/
          } else {
            
            $('.result-message').html( response ); // If there was an error, display it in results div result-message
            $('.result-message').addClass('alert-danger'); // Add class failed to results div
            $('.result-message').show(); // Show results div
          }
        }
      });

      }else{
        $( "form[name='checkout']" ).submit();
      }
      
    });
  
});