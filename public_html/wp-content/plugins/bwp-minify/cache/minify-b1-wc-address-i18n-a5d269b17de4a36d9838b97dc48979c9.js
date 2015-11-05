jQuery(function(a){if("undefined"==typeof wc_address_i18n_params)return!1;var b=wc_address_i18n_params.locale.replace(/&quot;/g,'"'),c=a.parseJSON(b),d=' <abbr class="required" title="'+wc_address_i18n_params.i18n_required_text+'">*</abbr>';a("body").bind("country_to_state_changing",function(b,e,f){var g,h=f;g="undefined"!=typeof c[e]?c[e]:c["default"];var i=a.parseJSON(wc_address_i18n_params.locale_fields);a.each(i,function(a,b){var e=h.find(b);g[a]?(g[a].label&&e.find("label").html(g[a].label),g[a].placeholder&&e.find("input").attr("placeholder",g[a].placeholder),e.find("label abbr").remove(),"undefined"==typeof g[a].required&&c["default"][a].required===!0?e.find("label").append(d):g[a].required===!0&&e.find("label").append(d),"state"!==a&&(g[a].hidden===!0?e.hide().find("input").val(""):e.show())):c["default"][a]&&(c["default"][a].required===!0&&0===e.find("label abbr").size()&&e.find("label").append(d),"state"!==a&&("undefined"==typeof c["default"][a].hidden||c["default"][a].hidden===!1?e.show():c["default"][a].hidden===!0&&e.hide().find("input").val("")))});var j=h.find("#billing_postcode_field, #shipping_postcode_field"),k=h.find("#billing_city_field, #shipping_city_field"),l=h.find("#billing_state_field, #shipping_state_field");j.attr("data-o_class")||(j.attr("data-o_class",j.attr("class")),k.attr("data-o_class",k.attr("class")),l.attr("data-o_class",l.attr("class"))),g.postcode_before_city?(j.add(k).add(l).removeClass("form-row-first form-row-last").addClass("form-row-wide"),j.insertBefore(k)):(j.attr("class",j.attr("data-o_class")),k.attr("class",k.attr("data-o_class")),l.attr("class",l.attr("data-o_class")),j.insertAfter(l))})});
;jQuery( function( $ ) {

	$.blockUI.defaults.overlayCSS.cursor = 'default';

	// wc_checkout_params is required to continue, ensure the object exists
	if ( typeof wc_checkout_params === 'undefined' )
		return false;

	var updateTimer,
		dirtyInput = false,
		xhr;

	function update_checkout() {

		if ( xhr ) xhr.abort();

		var shipping_methods = [];

		$( 'select.shipping_method, input[name^=shipping_method][type=radio]:checked, input[name^=shipping_method][type=hidden]' ).each( function( index, input ) {
			shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
		} );

		var payment_method = $( '#order_review input[name=payment_method]:checked' ).val(),
			country			= $( '#billing_country' ).val(),
			state			= $( '#billing_state' ).val(),
			postcode		= $( 'input#billing_postcode' ).val(),
			city			= $( '#billing_city' ).val(),
			address			= $( 'input#billing_address_1' ).val(),
			address_2		= $( 'input#billing_address_2' ).val(),
			s_country,
			s_state,
			s_postcode,
			s_city,
			s_address,
			s_address_2;

		if ( $( '#ship-to-different-address input' ).is( ':checked' ) ) {
			s_country		= $( '#shipping_country' ).val();
			s_state			= $( '#shipping_state' ).val();
			s_postcode		= $( 'input#shipping_postcode' ).val();
			s_city			= $( '#shipping_city' ).val();
			s_address		= $( 'input#shipping_address_1' ).val();
			s_address_2		= $( 'input#shipping_address_2' ).val();
		} else {
			s_country		= country;
			s_state			= state;
			s_postcode		= postcode;
			s_city			= city;
			s_address		= address;
			s_address_2		= address_2;
		}

		$( '#order_methods, #order_review' ).block({ message: null, overlayCSS: { background: '#fff url(' + wc_checkout_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });

		var data = {
			action:						'woocommerce_update_order_review',
			security:					wc_checkout_params.update_order_review_nonce,
			shipping_method:			shipping_methods,
			payment_method:				payment_method,
			country:					country,
			state:						state,
			postcode:					postcode,
			city:						city,
			address:					address,
			address_2:					address_2,
			s_country:					s_country,
			s_state:					s_state,
			s_postcode:					s_postcode,
			s_city:						s_city,
			s_address:					s_address,
			s_address_2:				s_address_2,
			post_data:					$( 'form.checkout' ).serialize()
		};

		xhr = $.ajax({
			type:		'POST',
			url:		wc_checkout_params.ajax_url,
			data:		data,
			success:	function( response ) {
				if ( response ) {
					$( '#order_review' ).html( $.trim( response ) );
					$( '#order_review' ).find( 'input[name=payment_method]:checked' ).trigger('click');
					$( 'body' ).trigger('updated_checkout' );
				}
			}
		});

	}

	// Event for updating the checkout
	$( 'body' ).bind( 'update_checkout', function() {
		clearTimeout( updateTimer );
		update_checkout();
	});

	$( '.checkout_coupon, div.shipping_address' ).hide();

	$( 'a.showlogin' ).click( function() {
		$( 'form.login' ).slideToggle();

		return false;
	});

	$( 'a.showcoupon' ).click( function() {
		$( '.checkout_coupon' ).slideToggle( 400, function() {
			$( '#coupon_code' ).focus();
		});

		return false;
	});

	$( '#ship-to-different-address input' ).change( function() {
		$( 'div.shipping_address' ).hide();
		if ( $( this ).is( ':checked' ) ) {
			$( 'div.shipping_address' ).slideDown();
		}
	}).change();

	if ( wc_checkout_params.option_guest_checkout === 'yes' ) {

		$( 'div.create-account' ).hide();

		$( 'input#createaccount' ).change( function() {
			$( 'div.create-account' ).slideUp();

			if ( $( this ).is( ':checked' ) ) {
				$( 'div.create-account' ).slideDown();
			}
		}).change();

	}

	$( '#order_review' )

	/* Payment option selection */

	.on( 'click', '.payment_methods input.input-radio', function() {
		if ( $( '.payment_methods input.input-radio' ).length > 1 ) {
			var target_payment_box = $( 'div.payment_box.' + $( this ).attr( 'ID' ) );

			if ( $( this ).is( ':checked' ) && ! target_payment_box.is( ':visible' ) ) {
				$( 'div.payment_box' ).filter( ':visible' ).slideUp( 250 );

				if ( $( this ).is( ':checked' ) ) {
					$( 'div.payment_box.' + $( this ).attr( 'ID' ) ).slideDown( 250 );
				}
			}
		} else {
			$( 'div.payment_box' ).show();
		}

		if ( $( this ).data( 'order_button_text' ) ) {
			$( '#place_order' ).val( $( this ).data( 'order_button_text' ) );
		} else {
			$( '#place_order' ).val( $( '#place_order' ).data( 'value' ) );
		}
	})

	// Trigger initial click
	.find( 'input[name=payment_method]:checked' ).click();

	// Used for input change events below
	function input_changed() {
		var update_totals = true;

		if ( $( dirtyInput ).size() ) {

			$required_inputs = $( dirtyInput ).closest( 'div' ).find( '.address-field.validate-required' );

			if ( $required_inputs.size() ) {
				$required_inputs.each( function() {
					if ( $( this ).find( 'input.input-text' ).val() === '' ) {
						update_totals = false;
					}
				});
			}

		}

		if ( update_totals ) {
			dirtyInput = false;
			$( 'body' ).trigger( 'update_checkout' );
		}
	}

	$( 'form.checkout' )

	/* Update totals/taxes/shipping */
	// Inputs/selects which update totals instantly
	.on( 'input change', 'select.shipping_method, input[name^=shipping_method], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type=radio]', function() {
		clearTimeout( updateTimer );
		dirtyInput = false;
		$( 'body' ).trigger( 'update_checkout' );
	})

	// Address-fields which refresh totals when all required fields are filled
	.on( 'change', '.address-field input.input-text, .update_totals_on_change input.input-text', function() {
		if ( dirtyInput ) {
			input_changed();
		}
	})

	.on( 'input change', '.address-field select', function() {
		dirtyInput = this;
		input_changed();
	})

	.on( 'input keydown', '.address-field input.input-text, .update_totals_on_change input.input-text', function( e ){
		var code = e.keyCode || e.which || 0;

		if ( code === 9 ) {
			return true;
		}

		dirtyInput = this;
		clearTimeout( updateTimer );
		updateTimer = setTimeout( input_changed, '1000' );
	})

	/* Inline validation */

	.on( 'blur input change', '.input-text, select', function() {
		var $this = $( this ),
			$parent = $this.closest( '.form-row' ),
			validated = true;

		if ( $parent.is( '.validate-required' ) ) {
			if ( $this.val() === '' ) {
				$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
				validated = false;
			}
		}

		if ( $parent.is( '.validate-email' ) ) {
			if ( $this.val() ) {

				/* http://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
				var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

				if ( ! pattern.test( $this.val()  ) ) {
					$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-email' );
					validated = false;
				}
			}
		}

		if ( validated ) {
			$parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field' ).addClass( 'woocommerce-validated' );
		}
	} )

	/* AJAX Form Submission */

	.submit( function() {
		if( $("#terms-and-conditions").is(':checked') ){
			clearTimeout( updateTimer );

			var $form = $( this );

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
					type:		'POST',
					url:		wc_checkout_params.checkout_url,
					data:		$form.serialize(),
					success:	function( code ) {
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
		}else{
			jQuery(".alert-pop").fadeIn("fast");
		}

		return false;
	});

	/* AJAX Coupon Form Submission */
	$( 'form.checkout_coupon' ).submit( function() {
		var $form = $( this );

		if ( $form.is( '.processing' ) ) return false;

		$form.addClass( 'processing' ).block({ message: null, overlayCSS: {background: '#fff url(' + wc_checkout_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });

		var data = {
			action:			'woocommerce_apply_coupon',
			security:		wc_checkout_params.apply_coupon_nonce,
			coupon_code:	$form.find( 'input[name=coupon_code]' ).val()
		};

		$.ajax({
			type:		'POST',
			url:		wc_checkout_params.ajax_url,
			data:		data,
			success:	function( code ) {
				$( '.woocommerce-error, .woocommerce-message' ).remove();
				$form.removeClass( 'processing' ).unblock();

				if ( code ) {
					$form.before( code );
					//$form.slideUp();

					$( 'body' ).trigger( 'update_checkout' );
				}
			},
			dataType: 'html'
		});

		return false;
	});

	$( 'body' )

	// Init trigger
	.bind( 'init_checkout', function() {
		$( '#billing_country, #shipping_country, .country_to_state' ).change();
		$( 'body' ).trigger( 'update_checkout' );
	});

	// Update on page load
	if ( wc_checkout_params.is_checkout === '1' ) {
		$( 'body' ).trigger( 'init_checkout' );
	}

});

;/*
Based on jQuery Credit Card Validator (Copyright 2012 Pawel Decowski)
*/function get_card_type(e){e=e.replace(/[ -]/g,"");var t,n,r;for(n=0,r=card_types.length;n<r;n++){t=card_types[n];if(e.match(t.pattern))return t.name}return null}function is_valid_card(e){e=e.replace(/[ -]/g,"");var t,n,r,i,s,o;r=0;o=e.split("").reverse();for(n=i=0,s=o.length;i<s;n=++i){t=o[n];t=+t;if(n%2){t*=2;t<10?r+=t:r+=t-9}else r+=t}return r%10===0}var card_types=[{name:"amex",pattern:/^3[47]/,valid_length:[15]},{name:"diners_club_carte_blanche",pattern:/^30[0-5]/,valid_length:[14]},{name:"diners_club_international",pattern:/^36/,valid_length:[14]},{name:"jcb",pattern:/^35(2[89]|[3-8][0-9])/,valid_length:[16]},{name:"laser",pattern:/^(6304|670[69]|6771)/,valid_length:[16,17,18,19]},{name:"visa_electron",pattern:/^(4026|417500|4508|4844|491(3|7))/,valid_length:[16]},{name:"visa",pattern:/^4/,valid_length:[16]},{name:"mastercard",pattern:/^5[1-5]/,valid_length:[16]},{name:"maestro",pattern:/^(5018|5020|5038|6304|6759|676[1-3])/,valid_length:[12,13,14,15,16,17,18,19]},{name:"discover",pattern:/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/,valid_length:[16]}];
;icl_lang=icl_vars.current_language;icl_home=icl_vars.icl_home;function addLoadEvent(func){var oldonload=window.onload;if(typeof window.onload!='function'){window.onload=func;}else{window.onload=function(){if(oldonload){oldonload();}
func();}}}
addLoadEvent(function(){var lhid=document.createElement('input');lhid.setAttribute('type','hidden');lhid.setAttribute('name','lang');lhid.setAttribute('value',icl_lang);src=document.getElementById('searchform');if(src){src.appendChild(lhid);src.action=icl_home;}});function icl_retry_mtr(a){var id=a.getAttribute('id');spl=id.split('_');var loc=location.href.replace(/#(.*)$/,'').replace(/(&|\?)(retry_mtr)=([0-9]+)/g,'').replace(/&nonce=([0-9a-z]+)(&|$)/g,'');if(-1==loc.indexOf('?')){url_glue='?';}else{url_glue='&';}
location.href=loc+url_glue+'retry_mtr='+spl[3]+'&nonce='+spl[4];return false;}