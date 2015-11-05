jQuery(function($){

	//Slide HOME 
	jQuery(".slides1").responsiveSlides({nav: true, prevText: "<", nextText: ">", manualControls: '.unique-pager',timeout: 6000});
	jQuery(".slides2").responsiveSlides({pager: true,timeout: 6000});
	jQuery(".slides3").responsiveSlides({nav: true, prevText: "<", nextText: ">",pager: true,timeout: 6000});
	

	//Side Baer Menu 
	var snapper = new Snap({
	  element: document.getElementById('snap-content')
	});

	jQuery(".closeSnap").click(function () {
		snapper.close();
		jQuery("a.open-left").css({ 'visibility' : 'visible'});
		jQuery('body').removeClass( 'cursor' );
		jQuery('.snap-drawer').removeClass( 'normal-cursor' );
		jQuery(".snap-content").removeClass('open');
	}); 

	

	jQuery(".navSideLeft").click(function () {
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
			touch: true
		});
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
		    itemMargin:40,
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

	jQuery("#color option").each(function(){
		var color = jQuery(this).val();
		var s_color = jQuery(this).text();
		var colors_split = s_color.split('/');
		if( color != ''){
			if( colors_split.length > 1){
				jQuery("ul.colors").append('<li data-col="'+color+'"><span><div style="background-color:'+colors_split[0]+';"></div><div style="background-color:'+colors_split[1]+';"></div></span><div class="color-tooltip">'+s_color+'</div></li>');
			}else{
				jQuery("ul.colors").append('<li data-col="'+color+'"><span><div style="background-color:'+colors_split[0]+';"></div><div style="background-color:'+colors_split[0]+';"></div></span><div class="color-tooltip">'+s_color+'</div></li>');
			}
		}
	});

	jQuery("ul.colors li .color-tooltip").each( function(){
		var new_width = ( (jQuery(this).width() / 2) + 11 ) * -1;
		jQuery(this).css({'margin-left':new_width});
		console.log( jQuery(this).html() + ':' + new_width );
	});

	jQuery("body").on("click","ul.colors li", function(){
		var data_col = jQuery(this).data('col');
		jQuery("#color").val(data_col).trigger("change");
		// jQuery(".reset_variations2").show();
		jQuery("ul.colors li").removeClass('active');
		jQuery(this).addClass('active');

		all_variations = eval( jQuery(".variations_form").data( 'product_variations' ) );

		var html;
		var StrippedString;
		var prices;
		var off,regular_price,sale_price;


		jQuery.each(all_variations, function(col){
			if( all_variations[col].attributes.attribute_color == data_col ){
				html = all_variations[col].price_html;
				StrippedString = html.replace(/(<([^>]+)>)/ig,"");
				prices = StrippedString.split(' ');

				jQuery(".before-price span:first-child").html(prices[0]);
				jQuery(".price-now span:first-child").html(prices[1]);

				regular_price = prices[0].replace("$","");
				sale_price = prices[1].replace("$","");

				discount = 100 - ((100*sale_price)/regular_price);

				jQuery("span.off").html("("+discount.toFixed(0)+"%) OFF");
				
			}
		});


	});

	jQuery("body").on("click",".reset_variations2", function(){
		jQuery("#color").val('').trigger("change");
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

});

jQuery( window ).load(function() {
   jQuery("ul.colors li:first-child").click();
   // jQuery(".checkout-step:not(.checkout-step-1)").hide();
});

