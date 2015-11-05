
jQuery(function($){


	//Slide HOME
	// jQuery(".slides1").responsiveSlides({nav: true, prevText: "<", nextText: ">", manualControls: '.unique-pager',timeout: 6000});
	jQuery(".slides1").responsiveSlides({nav: true, prevText: "<", nextText: ">",timeout: 6000});
	jQuery(".slides2").responsiveSlides({pager: true,timeout: 6000});
	jQuery(".slides3").responsiveSlides({nav: true, prevText: "<", nextText: ">",pager: true,timeout: 6000});
	jQuery(".spec-slides").responsiveSlides({nav: true, prevText: "", nextText: "",auto: false});


	//Side Baer Menu
	var snapper = new Snap({
	  element: document.getElementById('snap-content')
	});

	// capture amazon addressboock click
	// jQuery("#amazon_addressbook_widget").hover( function() {
 //        console.log("entered! on the iframe :)");
 //        var url = "https://kinivo.com/wp-content/themes/kinivo/getTotals.php";
 //        var datos = '';
 //        jQuery.post(url, datos, function(result){
	// 		console.log(result);	
	// 	});
 //    }, function() {
 //        console.log("Leave on the iframe :)");
 //        var url = "https://kinivo.com/wp-content/themes/kinivo/getTotals.php";
 //        var datos = '';
 //        jQuery.post(url, datos, function(result){
	// 		console.log(result);
	// 	});
 //    });



	jQuery(".closeSnap").click(function () {
		snapper.close();
		jQuery("a.open-left").css({ 'visibility' : 'visible'});
		jQuery('body').removeClass( 'cursor' );
		jQuery('.snap-drawer').removeClass( 'normal-cursor' );
		jQuery(".snap-content").removeClass('open');
	});



	jQuery(".navSideLeft").click(function (event) {
		event.stopPropagation();
		if( snapper.state().state=="left" ){
			snapper.close('left');
		}else {
	        snapper.open('left');

	    }
	});


	//Tool tip/Dropdowns
	jQuery('.hasTooltip').click(function(event){
		event.stopPropagation();
		jQuery('.tooltiptheme').fadeOut(100, function(){
			jQuery(this).addClass('hidden');
		});
		jQuery('.hasTooltip').children('div.arrow').fadeOut(0);
		jQuery("div.product-added").fadeOut(0);

		if( jQuery(this).children('div.tooltiptheme').hasClass('hidden') ){

			jQuery(this).children('div.tooltiptheme').fadeIn(100, function(){
				jQuery(this).removeClass('hidden');
				if( jQuery(this).parents("li.hasTooltip").data('content') == 'h-carousel' ){ flexsliderHeadphones(); }

				if( jQuery(this).parents("li.hasTooltip").data('content') == 's-carousel' ){ flexsliderSpeakers() }

				if( jQuery(this).parents("li.hasTooltip").data('content') == 'o-carousel' ){ flexsliderOnTheGo() }
			});
			jQuery(this).children('div.arrow').fadeIn(110);

		}else{

			jQuery(this).children('div.tooltiptheme').fadeOut(100, function(){
				jQuery(this).addClass('hidden');
			});
			jQuery('.hasTooltip').children('div.arrow').fadeOut(0);

		}
	});

	jQuery(document).click(function(event){
		jQuery('.tooltiptheme').fadeOut(100, function(){
			jQuery(this).addClass('hidden');
		});
		jQuery('.hasTooltip').children('div.arrow').fadeOut(0);
	});

	jQuery('.tooltiptheme').click(function(event){
		event.stopPropagation();
	});

	//Flexslider header
	function flexsliderHeadphones() {
		jQuery('#carousel-headphones').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 154,
			move: 1,
		    itemMargin: 5,
		    prevText: "",           //String: Set the text for the "previous" directionNav item
			nextText: "",
			touch: true,
			start :  function(){

			}
		});
		var view_port = jQuery('#carousel-headphones').find(".flex-viewport");
		var posts = jQuery('#carousel-headphones').find(".flex-viewport ul li").length;
		var posts_width = posts * 160;
		if( view_port.width() == posts_width){
			jQuery('#carousel-headphones').find("ul.flex-direction-nav .flex-next").addClass("flex-disabled");
		}else{
			jQuery('#carousel-headphones').find("ul.flex-direction-nav .flex-next").removeClass("flex-disabled");
		}

	}

	function flexsliderSpeakers() {
		jQuery('#carousel-speakers').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 154,
			move: 1,
		    itemMargin: 5,
		    prevText: "",           //String: Set the text for the "previous" directionNav item
			nextText: "",
			touch: true
		});
		var view_port = jQuery('#carousel-speakers').find(".flex-viewport");
		var posts = jQuery('#carousel-speakers').find(".flex-viewport ul li").length;
		var posts_width = posts * 160;
		if( view_port.width() == posts_width){
			jQuery('#carousel-speakers').find("ul.flex-direction-nav .flex-next").addClass("flex-disabled");
		}else{
			jQuery('#carousel-speakers').find("ul.flex-direction-nav .flex-next").removeClass("flex-disabled");
		}
	}

	function flexsliderOnTheGo() {
		jQuery('#carousel-onthego').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 154,
			move: 1,
		    itemMargin: 5,
		    prevText: "",           //String: Set the text for the "previous" directionNav item
			nextText: "",
			touch: true
		});
		var view_port = jQuery('#carousel-onthego').find(".flex-viewport");
		var posts = jQuery('#carousel-onthego').find(".flex-viewport ul li").length;
		var posts_width = posts * 160;
		if( view_port.width() == posts_width){
			jQuery('#carousel-onthego').find("ul.flex-direction-nav .flex-next").addClass("flex-disabled");
		}else{
			jQuery('#carousel-onthego').find("ul.flex-direction-nav .flex-next").removeClass("flex-disabled");
		}
	}


	jQuery('#kinivo-instagram').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 252,
		move: 1,
	    itemMargin: 5,
	    prevText: "",
		nextText: "",
		touch: true
	});

	// jQuery('#carousel-featured').flexslider({
	// 	animation: "slide",
	// 	controlNav: false,
	// 	animationLoop: false,
	// 	slideshow: false,
	// 	itemWidth: 155,
	// 	move: 1,
	//     itemMargin:40,
	//     prevText: "",
	// 	nextText: "",
	// 	touch: true
	// });

	//Category banner IMG animagion
	jQuery( window ).resize(function() {
		var b_width = jQuery( window ).width();
		if( b_width >= 1280){
			jQuery(".banner.category-banner img").css({ "top" : "300px"});
			var html = jQuery('#carousel-featured').find("ul.slides").html();
			var wrap = jQuery("#carousel-featured").parents(".wrapper.wrap");
			jQuery('#carousel-featured').remove();
			wrap.append('<div id="carousel-featured" class="suggested-products-slide flexslider"><ul class="slides">'+html+'</ul></div>');
			jQuery('#carousel-featured').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 155,
				move: 1,
			    itemMargin:40,
			    prevText: "",
				nextText: "",
				touch: true
			});
		}
		if( b_width <= 1279 && b_width >= 960){
			jQuery(".banner.category-banner img").css({ "top" : "350px"});
			var html = jQuery('#carousel-featured').find("ul.slides").html();
			var wrap = jQuery("#carousel-featured").parents(".wrapper.wrap");
			jQuery('#carousel-featured').remove();
			wrap.append('<div id="carousel-featured" class="suggested-products-slide flexslider"><ul class="slides">'+html+'</ul></div>');
			jQuery('#carousel-featured').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 155,
				move: 1,
			    itemMargin:63,
			    prevText: "",
				nextText: "",
				touch: true
			});
		}
		if( b_width <= 959 && b_width >= 760){
			jQuery(".banner.category-banner img").css({ "top" : "350px"});
			var html = jQuery('#carousel-featured').find("ul.slides").html();
			var wrap = jQuery("#carousel-featured").parents(".wrapper.wrap");
			jQuery('#carousel-featured').remove();
			wrap.append('<div id="carousel-featured" class="suggested-products-slide flexslider"><ul class="slides">'+html+'</ul></div>');
			jQuery('#carousel-featured').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 155,
				move: 1,
			    itemMargin:72,
			    prevText: "",
				nextText: "",
				touch: true
			});
		}
		if( b_width <= 759 && b_width >= 480){
			jQuery(".banner.category-banner img").css({ "top" : "335px"});
			var html = jQuery('#carousel-featured').find("ul.slides").html();
			var wrap = jQuery("#carousel-featured").parents(".wrapper.wrap");
			jQuery('#carousel-featured').remove();
			wrap.append('<div id="carousel-featured" class="suggested-products-slide flexslider"><ul class="slides">'+html+'</ul></div>');
			jQuery('#carousel-featured').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 155,
				move: 1,
			    itemMargin:18,
			    prevText: "",
				nextText: "",
				touch: true
			});
		}
		if( b_width <= 479 && b_width >= 320){
			jQuery(".banner.category-banner img").css({ "top" : "365px"});
			var html = jQuery('#carousel-featured').find("ul.slides").html();
			var wrap = jQuery("#carousel-featured").parents(".wrapper.wrap");
			jQuery('#carousel-featured').remove();
			wrap.append('<div id="carousel-featured" class="suggested-products-slide flexslider"><ul class="slides">'+html+'</ul></div>');
			jQuery('#carousel-featured').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 155,
				move: 1,
			    itemMargin:40,
			    prevText: "",
				nextText: "",
				touch: true
			});
		}
	});
	var b_width = jQuery( window ).width();
	var b_top = 0;
	if( b_width >= 1280){
		b_top = "300px";
		jQuery('#carousel-featured').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 155,
			move: 1,
		    itemMargin:36,
		    prevText: "",
			nextText: "",
			touch: true
		});
	}
	if( b_width <= 1279 && b_width >= 960){
		b_top = "350px";
		jQuery('#carousel-featured').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 155,
			move: 1,
		    itemMargin:63,
		    prevText: "",
			nextText: "",
			touch: true
		});
	}
	if( b_width <= 959 && b_width >= 760){
		b_top = "350px";
		jQuery('#carousel-featured').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 155,
			move: 1,
		    itemMargin:72,
		    prevText: "",
			nextText: "",
			touch: true
		});
	}
	if( b_width <= 759 && b_width >= 480){
		b_top = "335px";
		jQuery('#carousel-featured').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 155,
			move: 1,
		    itemMargin:18,
		    prevText: "",
			nextText: "",
			touch: true
		});
	}
	if( b_width <= 479 && b_width >= 320){
		b_top = "365px";
		jQuery('#carousel-featured').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 155,
			move: 1,
		    itemMargin:40,
		    prevText: "",
			nextText: "",
			touch: true
		});
	}
	jQuery(".banner.category-banner img").animate({
		'top' : b_top,
		'opacity' : 1,
	},800,'swing', function(){

		jQuery(".banner.category-banner .category-addcart-banner").animate({
					'margin-right' : '0px',
					'opacity' : 1,
				},500,'swing');

	});

	//Modal
	jQuery("a.show-modal").click(function(){
	    var modal = this.getAttribute("data-modal");
	    var orderid = this.getAttribute("data-orderid");
	    if(orderid != ''){
	    	var order_html = jQuery('.order-h-detail[data-orderid='+ orderid +']').html();
	    	jQuery('.order-history-modal[data-modal='+ modal +']').html(order_html);
	    }
	    jQuery("div.mask").fadeIn(100, function(){
	    	if( !jQuery("div.modal[data-modal='"+modal+"']").hasClass('order-history-modal') &&
	    		!jQuery("div.modal[data-modal='"+modal+"']").hasClass('add-address-modal') ){
		    	var modal_width = ((jQuery("div.modal[data-modal='"+modal+"']").width() / 2) + 40) * -1;
		    	var modal_height = ((jQuery("div.modal[data-modal='"+modal+"']").height() / 2) + 40 ) * -1;
	        }else{
	        	var modal_width = (jQuery("div.modal[data-modal='"+modal+"']").width() / 2) * -1;
		    	var modal_height = 0;
	        }
	    	jQuery("div.modal[data-modal='"+modal+"']").css({
	    		'margin-left' : modal_width,
	    		'margin-top' : modal_height
	    	});
	    	jQuery("div.modal[data-modal='"+modal+"']").show();
	    });
	});

	jQuery("body").on("click",".close-modal", function(){
		jQuery("div.modal").fadeOut(10, function(){
			jQuery("div.mask").fadeOut(100);
		})
	});


	//Product Specifications tabs
	jQuery("body").on("click","div.product-specifications .tabs a", function(){
		jQuery(".tab-info.t-specifications ul li").removeClass("active");
		jQuery("div.product-specifications .tabs a").removeClass("active");
		if(jQuery(this).hasClass("specifications")){
			jQuery(".tab-info.t-specifications ul li:first-child").addClass("active");
			jQuery("div.product-specifications .tabs a.specifications").addClass("active");
		}else{
			jQuery(".tab-info.t-specifications ul li:last-child").addClass("active");
			jQuery(this).addClass("active");
			jQuery("div.product-specifications .tabs a.technical").addClass("active");
		}
	})

	var selected_color = '';
	if( jQuery("#pa_color").length > 0){
		selected_color = jQuery("#pa_color").find(':selected').text();
	}

	jQuery("#pa_color option").each(function(){
		var color = jQuery(this).val();
		var s_color = jQuery(this).text();
		var colors_split = s_color.split('/');
		if( color != ''){
			if( colors_split.length > 1){
				var name_color1 = colors_split[0].split(':');
				var name_color2 = colors_split[1].split(':');
				var color_name = name_color1[0]+'/'+name_color2[0];
				if (selected_color != '' && selected_color == s_color) {
					jQuery("ul.colors").append('<li data-col="'+color+'" class="active"><span><div style="background-color:'+name_color1[1]+';"></div><div style="background-color:'+name_color2[1]+';"></div></span><div class="color-tooltip">'+color_name+'</div></li>');
				}else{
					jQuery("ul.colors").append('<li data-col="'+color+'"><span><div style="background-color:'+name_color1[1]+';"></div><div style="background-color:'+name_color2[1]+';"></div></span><div class="color-tooltip">'+color_name+'</div></li>');
				}
			}else{
				var name_color1 = colors_split[0].split(':');
				var color_name = name_color1[0];
				if (selected_color != '' && selected_color == s_color) {
					jQuery("ul.colors").append('<li data-col="'+color+'" class="active"><span><div style="background-color:'+name_color1[1]+';"></div><div style="background-color:'+name_color1[1]+';"></div></span><div class="color-tooltip">'+color_name+'</div></li>');
				}else{
					jQuery("ul.colors").append('<li data-col="'+color+'"><span><div style="background-color:'+name_color1[1]+';"></div><div style="background-color:'+name_color1[1]+';"></div></span><div class="color-tooltip">'+color_name+'</div></li>');
				}
			}
		}
	});


	jQuery("ul.colors li .color-tooltip").each( function(){
		var new_margin_left = ( (jQuery(this).width() / 2) + 11 ) * -1;
		jQuery(this).css({'margin-left':new_margin_left});
		// console.log( jQuery(this).html() + ':' + new_width );
	});

	jQuery("body").on("click","ul.colors li", function(){
		var data_col = jQuery(this).data('col');
		jQuery("#pa_color").val(data_col).trigger("change");
		// jQuery(".reset_variations2").show();
		jQuery("ul.colors li").removeClass('active');
		jQuery(this).addClass('active');

		all_variations = eval( jQuery(".variations_form").data( 'product_variations' ) );

		var html;
		var StrippedString;
		var prices;
		var off,regular_price,sale_price;

		jQuery.each(all_variations, function(col){
			if( all_variations[col].attributes.attribute_pa_color == data_col ){
				html = all_variations[col].price_html;
				StrippedString = html.replace(/(<([^>]+)>)/ig,"");
				prices = StrippedString.split(' ');

				if(all_variations[col].is_in_stock){

					jQuery("div.offers").find("span.out-of-stock").remove();

					if (prices.length == 1) {
						jQuery("div.offers").find("span.alone-price").remove();
						jQuery(".in-stock-text").hide();
						var one_price = '<span class="price-now-title in-stock-text alone-price">Price:</span>'
									+ '<span class="price-now price green in-stock-text alone-price"><span>'+ prices[0] +'</span></span>';
						jQuery("div.offers").append(one_price);
					}else{
						jQuery(".before-price span:first-child").html(prices[0]);
					    jQuery(".price-now span:first-child").html(prices[1]);

						jQuery(".in-stock-text").show();
						jQuery("div.offers").find("span.alone-price").remove();
						regular_price = prices[0].replace("$","");
						sale_price = prices[1].replace("$","");

						discount = 100 - ((100*sale_price)/regular_price);

						jQuery("span.off").html("("+discount.toFixed(0)+"%) OFF");
					}



				}else{
					jQuery("div.offers").find("span.out-of-stock").remove();
					jQuery(".in-stock-text").hide();
					jQuery("div.offers").append('<span class="price-now price out-of-stock">Out of stock</span>');
				}










			}
		});


	});

	jQuery("body").on("click",".reset_variations2", function(){
		jQuery("#pa_color").val('').trigger("change");
		jQuery(this).hide();
		jQuery("ul.colors li").removeClass('active');
	});

	jQuery(".cart").on("change", "input", function(){
		var oThis = jQuery(this);
		var oCart = jQuery(".responsive-cart");
		name = oThis.attr("name");
		oCart.find("input[name='"+name+"']").val(oThis.val());
	});

	jQuery(".responsive-cart").on("change", "input", function(){
		var oThis = jQuery(this);
		var oCart = jQuery(".cart");
		name = oThis.attr("name");
		oCart.find("input[name='"+name+"']").val(oThis.val());
	});

	jQuery("body").on("click","a.next-step", function(){
		var step = jQuery(this).data('step');
		jQuery(".checkout-step").hide();
		jQuery(".checkout-step-"+step).show();
		jQuery("li.step").removeClass("active");
		jQuery("li.step[data-step="+step+"]").addClass("active");
	});


	//my account menu
	jQuery(".responsive-menu a.drop-menu").click( function(){
		var action = jQuery(this);
		if( action.hasClass('displayed') ){
			jQuery("ul.rest-menu-responsive").slideUp('fast', function(){
				action.removeClass('displayed');
			})
		}else{
			jQuery("ul.rest-menu-responsive").slideDown('fast', function(){
				action.addClass('displayed');
			})
		}

	});

	jQuery("div.personal-information div.header-btos a.button").click(function(){
			var cont = this.getAttribute("data-content");
			jQuery("div.user-information-content").hide();
			jQuery("div.user-information-content."+cont).show();
			jQuery("div.personal-information div.header-btos a.button").removeClass("active");
			jQuery(this).addClass("active");
		});



    var timer = null;

	jQuery(document.body).on('mouseover', '.flex-direction-nav a' ,function(){
		var el = jQuery(this);
		timer = window.setInterval(function(){ if(!el.hasClass('flex-disabled'))el.trigger("click"); } , 1100);
	});

    jQuery(document.body).on('mouseout', '.flex-direction-nav a' ,function(){
    	window.clearInterval(timer);
    });

    jQuery(document.body).on('click', '.flex-direction-nav a' ,function(){
      window.clearInterval(timer);
    });


    //Question Mark popup
    jQuery('body').on('click','div.question-mark', function(){

    	var $this = $(this);
    	if( !$this.hasClass('visible') ){
    		$("div.question-mark .pop").hide();

    		$this.find(".pop").show();

    		$('div.question-mark').removeClass('visible');
			$this.addClass('visible');
			var position = $this.find(".pop").offset();
			if (position.left <= 0) {
				$this.find(".pop").addClass("left-fix");
			}

	    }else{
	    	$this.find(".pop").fadeOut('fast');
	    	$this.removeClass('visible');
	    }

    })

    jQuery('body').on('click','div.question-mark .pop .close', function(e){
    	e.stopPropagation();
    	$(this).parents(".pop").fadeOut('fast', function(){
    		$('div.question-mark').removeClass('visible');
    	});
    })

    jQuery('body').on('click','div.question-mark .pop', function(e){
    	e.stopPropagation();
    })

    jQuery('.product-row .item').matchHeight();

    var step = 1;

    jQuery('body').on("click",".nav-bto a.next", function(){
    	if ( !jQuery(this).hasClass("deactivated") ) {
	    	step = jQuery(this).data("step") + 1;

	    	jQuery(this).data("step",step);
	    	jQuery(".nav-bto a.prev").data("step",step);

	    	jQuery(".kinivo-steps .step").removeClass("active");
	    	jQuery(".kinivo-steps .step[data-step='"+step+"']").addClass("active");

	    	if(step == 2){
				var url = "https://kinivo.com/wp-content/themes/kinivo/getTotals.php";
		        var datos = '';
		        if( $(".totals-wrap table.shop_table tr.tax-rate").length > 0 ){
		        	$(".totals-wrap table.shop_table tr.tax-rate").hide();
		        }
				jQuery.post(url, datos, function(result){
					var totals = result;
					var order_total_with_taxes = $(result).find("tr.order-total").html();

					if( $(".totals-wrap table.shop_table tr.tax-rate").length > 0 ){
			        	$(".totals-wrap table.shop_table tr.tax-rate").show();
			        }

					if( $(result).find("tr.tax-rate").length > 0 ){
						var taxes = $(result).find("tr.tax-rate").html();
						console.log("taxes: ",taxes);
						console.log("totals: ", order_total_with_taxes);

						if( $(".totals-wrap table.shop_table tr.tax-rate").length <= 0 ){
							console.log("no hay taxes!");
							$(".totals-wrap table.shop_table tr.shipping-table-totales").after('<tr class="tax-rate">'+taxes+'</tr>'); 
							$(".totals-wrap table.shop_table tr.order-total").html(order_total_with_taxes); 
						}else{
							$(".totals-wrap table.shop_table tr.tax-rate").html(taxes); 
							$(".totals-wrap table.shop_table tr.order-total").html(order_total_with_taxes); 
						}
					}else{
						console.log("totals no taxes :) ", order_total_with_taxes);
						if( $(".totals-wrap table.shop_table tr.tax-rate").length > 0 ){
							console.log("hay un tax-taxes");
							$(".totals-wrap table.shop_table tr.tax-rate").remove();
							$(".totals-wrap table.shop_table tr.order-total").html(order_total_with_taxes); 
						}
					}

				});
	    	}




	    	jQuery(".content-step.active").fadeOut("fast", function(){

	    		jQuery(this).removeClass("active");
	    		jQuery(".content-step[data-step='"+step+"']").fadeIn("fast", function(){
	    			jQuery(this).addClass("active");
	    		});
	    	});

			if(step >= 3){
	    		jQuery(this).addClass('deactivated');
					jQuery(".shipping-box").removeClass("loader");
	    	}

			if(step > 1){
	    		// jQuery(".nav-bto a.prev").removeClass("deactivated");
	    	}

	    	if(step == 2){
	    		jQuery(this).hide();
	    		// jQuery(".nav-bto a.prev").hide();
				jQuery(".shipping-box").removeClass("loader");
			}


	    }



    });

    jQuery('body').on("click",".nav-bto a.prev, .step1-head", function(){

    	if ( !jQuery(this).hasClass("deactivated") ) {
    		if (jQuery(this).hasClass("step1-head") ) {

    			if (step == 2) {

	    			step = jQuery(this).data("step");
			    	jQuery(this).data("step",step);
			    	jQuery(".nav-bto a.next").data("step",step);
			    	jQuery(".kinivo-steps .step").removeClass("active");
			    	jQuery(".kinivo-steps .step[data-step='"+step+"']").addClass("active");

			    	jQuery(".content-step.active").fadeOut("fast", function(){
			    		jQuery(this).removeClass("active");
			    		jQuery(".content-step[data-step='"+step+"']").fadeIn("fast", function(){
			    			jQuery(this).addClass("active");
			    		});
			    	});

			    	if(step <= 1){
			    		//jQuery(this).addClass("deactivated");
			    	}

			    	if(step < 3){
			    		jQuery(".nav-bto a.next").removeClass("deactivated");
			    	}


			    	if(step == 1){
			    		jQuery(".nav-bto a.next").show();
			    	}
			    }
    		}else{

		    	step = jQuery(this).data("step") - 1;
		    	jQuery(this).data("step",step);
		    	jQuery(".nav-bto a.next").data("step",step);
		    	jQuery(".kinivo-steps .step").removeClass("active");
		    	jQuery(".kinivo-steps .step[data-step='"+step+"']").addClass("active");

		    	jQuery(".content-step.active").fadeOut("fast", function(){
		    		jQuery(this).removeClass("active");
		    		jQuery(".content-step[data-step='"+step+"']").fadeIn("fast", function(){
		    			jQuery(this).addClass("active");
		    		});
		    	});

		    	if(step <= 1){
		    		jQuery(this).addClass("deactivated");
		    	}

		    	if(step < 3){
		    		jQuery(".nav-bto a.next").removeClass("deactivated");
		    	}


		    	if(step == 1){
		    		jQuery(".nav-bto a.next").show();
		    	}
		    }
	    }

    });

    // jQuery("#createaccount").on("change", function(){
    // 	jQuery(".create-account-form").slideToggle('fast');
    // 	jQuery(".create-account-form").find("input").val('');
    // });
	jQuery('.col-match').matchHeight();

	jQuery('.address .box .box-cont').matchHeight();

	/*Alert Pop Up*/
	jQuery("body").on("click", ".alert-pop a.close-alert", function(){
		jQuery(".alert-pop").fadeOut("fast");
	});

	jQuery("body").on("click", ".alert-pop-newuser a.close-alert-user", function(){
		jQuery(".alert-pop-newuser").fadeOut("fast");
	});

	if( jQuery("a.delete-can").length > 0){
		jQuery("a.delete-can").each( function(){
			$(this).append("<span class='tooltip-delete'>Delete</span>");
		});

		jQuery("a.delete-can").hover( function(){
			jQuery(this).find(".tooltip-delete").fadeIn('fast');
		}, function(){
			jQuery(this).find(".tooltip-delete").fadeOut('fast');
		});

		jQuery(".tooltip-delete").hover( function(event){
			event.stopPropagation();
			jQuery(this).fadeOut();
		});
	}



	


});



/*Steps!!*/


var step1 = jQuery(".content-step[data-step='1']");
var step2 = jQuery(".content-step[data-step='2']");
var step3 = jQuery(".content-step[data-step='3']");
jQuery("form.checkout").append(step1);
jQuery("form.checkout").append(step2);
jQuery("form.checkout").append(step3);

var content_one = jQuery(".woocommerce-step[data-step='1']");
jQuery(".content-step[data-step='1']").append(content_one);

var review_order_table = jQuery(".shipping-process-content table.shop_table");
var payment = jQuery("div#payment");
jQuery(".content-step[data-step='2']").find(".placeorder").find(".box-cont .totals-wrap").append(review_order_table);
jQuery(".content-step[data-step='2']").find(".placeorder").find(".box-cont").append(payment);

jQuery(".shipping-process-content table.shop_table thead").hide();
jQuery(".shipping-process-content table.shop_table tbody").hide();
var head_table_totals = jQuery(".shipping-process-content table.shop_table thead").html();
// jQuery(".content-step[data-step='2']").find(".cart").find(".box-cont").find("table thead").html(head_table_totals);
var table_totals = jQuery(".shipping-process-content table.shop_table tbody").html();
// jQuery(".content-step[data-step='2']").find(".cart").find(".box-cont").find("table tbody").html(table_totals);

var create_account_ch = jQuery(".check-box-create-account");
create_account_ch.show();
jQuery(".the-create-account-box").append(create_account_ch);

var create_account = jQuery(".woo-create-account");
var checkbox_subscribe = jQuery("#subscribe-checkbox");

create_account.append(checkbox_subscribe);


// create_account.find('input[type="text"], input[type="email"], input[type="password"]').attr("required","required");
 jQuery(".totals-wrap select.shipping_method").attr("disabled", true);
// create_account.show();
jQuery(".create-account-form").append(create_account);

if (jQuery(".step3-content-order").length > 0) {
	var step3_content = jQuery(".step3-content-order");
	jQuery(".content-step[data-step='3']").append(step3_content);
	step3_content.show();
}


var shipping_actual_value = jQuery('.totals-wrap select.shipping_method option:selected').text();
jQuery('.totals-wrap td.shipping-value').html(shipping_actual_value);


jQuery( window ).load(function() {

	if (jQuery(".load_overlay").length > 0) {
		jQuery(".load_overlay").fadeOut("slow");
	}

	jQuery(".box.responsive-cart .box-cont .product-row .pic-and-number .more-less").css({"opacity":"1"});
	jQuery("ul.colors").find("li.active").click();
	jQuery(".product-page div.product-detail .details .price-now.first-price-show-v, .product-page div.product-detail .details .before-price.first-price-show-v").css({ 'visibility' : 'visible' });
	// jQuery(".reset_variations").click();
	if (jQuery(".assistly-widget > a").length > 0) {
		jQuery(".assistly-widget > a").attr("target","_blank");
	}
	if(jQuery(".paypal_box_button").length > 0){
		var new_img = 'https://'+window.location.hostname+'/wp-content/uploads/2015/04/paypal_pay.jpg';
		jQuery(".paypal_box_button").find('img').attr('src',new_img);
		var img_width = jQuery(".paypal_box_button").find('img').width();
		jQuery(".secure-payment .box-cont .pay-pal-l.legend p").css({'width':img_width+'px'});
		jQuery(".paypal_box_button").insertAfter(jQuery(".payments-or")).show();
		jQuery(".paypal_box_button").after( jQuery(".secure-payment .box-cont .pay-pal-l.legend").show() );
		jQuery(".paypal_box_button").css({'visibility':'visible'});
	}
	console.log("the page finished loading!");
});

jQuery('#form-send-subscriber').submit(function(event){
	event.preventDefault();
	var boton=event.target.name;
	var url = jQuery(this).attr('action');
	var datos = jQuery(this).serialize();
	jQuery.post(url, datos, function(result){
		jQuery('#form-send-subscriber .response').css({display: "none"});

		if(result == 1){
			jQuery('#form-send-subscriber .response').html("<span style='color:#95b002'>You have successfully subscribed to the Kinivo Newsletter.</span>");
			document.getElementById("form-send-subscriber").reset();
		}else{
			if(result == 3){
				jQuery('#form-send-subscriber .response').html('<span style="color:rgb(219, 68, 68);">Please, type your e-mail</span>');
			}else{
				jQuery('#form-send-subscriber .response').html('<span style="color:rgb(219, 68, 68);">Something went wrong, please contact us <a href="./contact">here</a></span>');
			}
		}
		jQuery('#form-send-subscriber .response').slideDown("slow");
	});
});

jQuery('#form-contactus').submit(function(event){
	event.preventDefault();
	var boton=event.target.name;
	var url = jQuery(this).attr('action');
	var datos = jQuery(this).serialize();
	jQuery.post(url, datos, function(result){
		jQuery('#form-contactus .response').css({display: "none"});

		if(result == 1){
			jQuery('#form-contactus .response').html("Thank you for contacting us,  we'll get in touch with you soon!");
			document.getElementById("form-contactus").reset();
		}else{
			if(result == 3){
				jQuery('#form-contactus .response').html('Please, fill all the required(*) fields.');
			}else{
				jQuery('#form-contactus .response').html('Something went wrong, please contact us <a href="./contact">here</a>');
			}
		}
		jQuery('#form-contactus .response').fadeIn("slow");
	});
});


jQuery('body').on("click", ".pp_place_order",function(event){
	event.preventDefault();
	var the_parent_form = jQuery(this);
	jQuery("#new_user_messages").slideUp('fast');
	jQuery("#new_user_messages").html('');

	if( jQuery("#terms-and-conditions").is(':checked') ){
		if ( jQuery("#createaccount").is(':checked') ) {
			var email= jQuery("#billing_email").val();
			var username= jQuery("#account_username").val();
			var password= jQuery("#account_password").val();
			if( email.trim() != '' && username.trim() != '' && password.trim() != ''){

				var url = jQuery("#create_user_url").val();
				//var datos = "new_user_email="+email.trim()+"&new_user_name="+username.trim()+"&new_user_password="+password.trim();

				jQuery.ajax({
                    url: "https://kinivo.com/wp-content/themes/kinivo/send-subscriber.php",
                    type: 'POST',
                    data: {'email': email.trim() , 'type': 'subscriber'},
                })
                .done(function(data) {
                    jQuery.post(url, { new_user_email : email.trim(), new_user_name : username.trim(), new_user_password : password.trim() }, function(result){
						var res = JSON.parse(result);
						console.log('here: ' + res.response );
						if (res.response == "ok") {
							the_parent_form.attr('disabled', 'disabled');
							the_parent_form.parents('form').submit();
						}else{
							the_parent_form.val('Place order');
							jQuery("#new_user_messages").html(res.msj);
							jQuery("#new_user_messages").slideDown('fast');
						}
					});
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });

				

			}else{
				jQuery(".alert-pop-newuser").fadeIn("fast");
			}
		}else{
			jQuery(this).attr('disabled', 'disabled').val('Processing'); jQuery(this).parents('form').submit();
		}
	}else{
		jQuery(".alert-pop").fadeIn("fast");
	}

	return false;
});






jQuery( window ).resize(function() {
	jQuery('.product-row .item').matchHeight();
	jQuery('.col-match').matchHeight();
	var img_width = jQuery("#pay_with_amazon").find('img').width();
	jQuery(".secure-payment .box-cont .amazon-l.legend p").css({'width':img_width+'px'});
});
