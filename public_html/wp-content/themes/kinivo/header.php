<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");

global $woo_options;
global $woocommerce;

$args = array(
 		'post_type' => 'social'
		);
$the_query = new WP_Query( $args );
if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
	$GLOBALS['facebook'] = get_field('facebook');
	$GLOBALS['twitter'] = get_field('twitter');
	$GLOBALS['instagram'] = get_field('instagram');
	$GLOBALS['instagram_id'] = get_field('instagram_id');
	$GLOBALS['youtube'] = get_field('youtube');
	$GLOBALS['gplus'] = get_field('gplus');
endwhile; else:
endif;


$args = array(
 		'post_type' => 'cart_checkout'
		);
$the_query = new WP_Query( $args );
if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
	$GLOBALS['cart_title'] = get_field('cart_title');
	$GLOBALS['cart_subtotal'] = get_field('cart_subtotal');
	$GLOBALS['cart_shipping'] = get_field('cart_shipping');
	$GLOBALS['cart_shipping_question_mark'] = get_field('cart_shipping_question_mark');
	$GLOBALS['cart_total'] = get_field('cart_total');
	$GLOBALS['cart_total_question_mark'] = get_field('cart_total_question_mark');
	$GLOBALS['promo_code_title'] = get_field('promo_code_title');
	$GLOBALS['cart_payment_title'] = get_field('cart_payment_title');
	$GLOBALS['cart_payment_question_mark'] = get_field('cart_payment_question_mark');
	$GLOBALS['payment_general_legend'] = get_field('payment_general_legend');
	$GLOBALS['amazon_legend'] = get_field('amazon_legend');
	$GLOBALS['paypal_legend'] = get_field('paypal_legend');
endwhile; else:
endif;


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Ideaware Co."/>
	<meta name="keywords" content="Consumer Electronics manufacturer"/>

	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!-- <meta name="format-detection" content="telephone=no"/> -->
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/icn/fav1.png"/>
	<!-- build:css css/production.min.css -->
	<link rel='stylesheet' id='snap-css'  href='<?php echo get_template_directory_uri(); ?>/css/snap.css' type='text/css' media='all' />
	<link href="<?php echo get_template_directory_uri(); ?>/css/normalize.css" rel="stylesheet"/>
	<link href="<?php echo get_template_directory_uri(); ?>/css/flexslider.css" rel="stylesheet"/>
	<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet"/>

	<!-- /build -->
	<!--[if lt IE 9]><script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
	
	<?php wp_head(); ?>
	<link href='https://fonts.googleapis.com/css?family=Raleway:500,600,800' rel='stylesheet' type='text/css'>
	<!--Main Stylesheet-->
	<link href="<?php echo get_template_directory_uri(); ?>/css/main.css?ver=1.1" rel="stylesheet"/>
	
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.1.3.js"></script>
	<!-- /build -->



	<script>

	var yith_wcwl_plugin_ajax_web_url = '<?php echo admin_url("admin-ajax.php") ?>';
    var login_redirect_url = '<?php echo wp_login_url()."?redirect_to=".urlencode( $_SERVER["REQUEST_URI"] ); ?>';
	</script>

	<meta name="msvalidate.01" content="CD73F9B10759BF5259ADC22FD4E2F3D6" />
	<meta name="language" content="english">
	<meta name="description" content="Kinivo offers innovative consumer electronics products that enhance life’s best experiences in the home, at work or when you’re on the move">
        <meta name="google-site-verification" content="Deo9_MvPrpBnatXC3PTw-RA4-NbF6_wvFlfopi7XLLc" />

	<title>Electronics For Everyday Life</title>

</head>
<body>
	<div id="snap-drawers">
	    <div class="snap-drawer snap-drawer-left">
	    	<div class="snap-drawer-wrap">
		        <ul class="menu">
		        	<li><a class="close2 closeSnap"><span data-icon="&#xe604" aria-hidden="true"></span></a></li>
		        	<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
		        	<li><a href="<?php bloginfo('url'); ?>/product-category/headphones/">Headphones</a></li>
		        	<li><a href="<?php bloginfo('url'); ?>/product-category/speakers/">Speakers</a></li>
		        	<li><a href="<?php bloginfo('url'); ?>/product-category/accessories/">Accessories</a></li>
		        	<li><a href="<?php bloginfo('url'); ?>/about-us">About</a></li>
		        	<li><a href="<?php bloginfo('url'); ?>/contact">Contact</a></li>
		        	<li><a href="http://support.kinivo.com/" target="blank">Support</a></li>
		        	<?php if ( is_user_logged_in() ) { ?>
		        	<li><a href="<?php bloginfo('url'); ?>/my-account">My Account</a></li>
		        	<?php } ?>
		        	<li><a href="<?php bloginfo('url'); ?>/blog">Blog</a></li>
		        	<?php if ( !is_user_logged_in() ) { ?>
		        	<li><a href="<?php bloginfo('url'); ?>/my-account"><strong>Log In</strong></a></li>
		        	<?php } ?>
		        	<?php if ( is_user_logged_in() ) { ?>
		        	<li><a href="<?php echo wp_logout_url( home_url() ); ?>"><strong>Log Out</strong></a></li>
		        	<?php } ?>
				</ul>
		        <div class="left-menu-footer">
			        <ul class="contact">
			        	<li><span data-icon="&#xf003" aria-hidden="true"></span><a href="mailto:support@kinivo.com" class="support-email">support@kinivo.com</a></li>
			        	<li><span data-icon="&#xf095" aria-hidden="true"></span>855 454 6486 (Toll Free)</li>
			        </ul>
			        <ul class="social">
			        	<?php if($GLOBALS['facebook'] != '') echo '<li><a target="blank" href="'.$GLOBALS['facebook'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/fb-icon.png"></a></li>' ?>
			        	<?php if($GLOBALS['twitter'] != '') echo '<li><a target="blank" href="https://twitter.com/'.$GLOBALS['twitter'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/tw-icon.png"></a></li>' ?>
			        	<?php if($GLOBALS['instagram'] != '') echo '<li><a target="blank" href="http://instagram.com/'.$GLOBALS['instagram'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/ig-icon.png"></a></li>' ?>
			        	<?php if($GLOBALS['youtube'] != '') echo '<li><a target="blank" href="'.$GLOBALS['youtube'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/yt-icon.png"></a></li>' ?>
			        	<?php if($GLOBALS['gplus'] != '') echo '<li><a target="blank" href="'.$GLOBALS['gplus'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/gp-icon.png"></a></li>' ?>
			        </ul>
			    </div>
			</div>
	    </div>
	    <div class="snap-drawer snap-drawer-right">
		</div>
	</div>
	<div class="snap-content" id="snap-content">
		
		<header class="main_header">
			<div class="wrapper wrap">
				<div class="lside_menu">
					<a class="navSideLeft open-left"><span data-icon="&#xe604" aria-hidden="true"></span></a>
					<h1><a href="<?php bloginfo('url'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/misc/logo.png" alt="Kinivo"/></a></h1>
				</div>
				<ul class="middle_menu">
					<li class="hasTooltip" data-content="h-carousel">
						<a><span data-icon="&#xf025" aria-hidden="true"></span><br/>Headphones</a>
						<div class="headphones-arrow arrow"></div>
						<div id="carousel-headphones" class="tooltiptheme products-tool flexslider hidden">
							<ul class="slides">
								<?php
									$args = array(
										'post_type' => 'product',
										'posts_per_page' => 12,
										'product_cat' => 'headphones'
										);
									$loop = new WP_Query( $args );
									$items_cant = 0;
									if ( $loop->have_posts() ) {
										while ( $loop->have_posts() ) : $loop->the_post();
											woocommerce_get_template_part( 'content', 'productdrop' );
											$items_cant++;
										endwhile;
										$final_width = $items_cant*160;
										echo '<style>
											.hasTooltip #carousel-headphones .flex-viewport{
												width: '.$final_width.'px;
											}
										</style>';
									} else {
								?>	
									<li>No products here</li>	
								<?php
									}
									wp_reset_postdata();
								?>
							</ul>
						</div>
					</li>
					<li class="hasTooltip" data-content="s-carousel">
						<a><span data-icon="&#xe600" aria-hidden="true"></span><br/>Speakers</a>
						<div class="speakers-arrow arrow"></div>
						<div id="carousel-speakers" class="tooltiptheme products-tool flexslider hidden">
							<ul class="slides">
								<?php
									$args = array(
										'post_type' => 'product',
										'posts_per_page' => 12,
										'product_cat' => 'speakers'
										);
									$loop = new WP_Query( $args );
									$items_cant = 0;
									if ( $loop->have_posts() ) {
										while ( $loop->have_posts() ) : $loop->the_post();
											woocommerce_get_template_part( 'content', 'productdrop' );
											$items_cant++;
										endwhile;
										$final_width = $items_cant*160;
										echo '<style>
											.hasTooltip #carousel-speakers .flex-viewport{
												width: '.$final_width.'px;
											}
										</style>';
									} else {
								?>	
									<li>No products here</li>	
								<?php
									}
									wp_reset_postdata();
								?>
							</ul>
						</div>
					</li>
					<li class="hasTooltip" data-content="o-carousel">
						<a><span data-icon="&#xf0f2" aria-hidden="true"></span><br/>Accessories</a>
						<div class="on-the-go-arrow arrow"></div>
						<div id="carousel-onthego" class="tooltiptheme products-tool flexslider hidden">
							<ul class="slides">
								<?php
									$args = array(
										'post_type' => 'product',
										'posts_per_page' => 12,
										'product_cat' => 'accessories'
										);
									$loop = new WP_Query( $args );
									$items_cant = 0;
									if ( $loop->have_posts() ) {
										while ( $loop->have_posts() ) : $loop->the_post();
											woocommerce_get_template_part( 'content', 'productdrop' );
											$items_cant++;
										endwhile;
										$final_width = $items_cant*160;
										echo '<style>
											.hasTooltip #carousel-onthego .flex-viewport{
												width: '.$final_width.'px;
											}
										</style>';
									} else {
								?>	
									<li>No products here</li>	
								<?php
									}
									wp_reset_postdata();
								?>
							</ul>
						</div>
					</li>
				</ul>
				<ul class="rside_menu">	
					<li class="hasTooltip user-login">
						<?php if ( is_user_logged_in() ) { ?>
						<?php $GLOBALS['current_user'] = wp_get_current_user(); ?>
							<a class="logged-in"><span data-icon="&#xe601" aria-hidden="true"></span> <?php echo $GLOBALS['current_user']->user_firstname; ?></a>
							<div class="login-arrow arrow"></div>
							<div class="tooltiptheme login hidden">
								<a class="button standar green" href="<?php bloginfo('url'); ?>/my-account">MY ACCOUNT</a>
								<a class="button standar green" href="<?php echo wp_logout_url( home_url() ); ?>">LOG OUT</a>
							</div>
						<?php } else { ?>
							<a><span data-icon="&#xe601" aria-hidden="true"></span></a>
							<div class="login-arrow arrow"></div>
							<div class="tooltiptheme login hidden">
								<h2>Log In to your Profile</h2>
								<form name="loginform" id="login-form" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">
									<input type="text" name="log" id="user_login" placeholder="YOUR E-MAIL ADDRESS" />
									<input type="password" name="pwd" id="user_pass" placeholder="PASSWORD" value="" size="20" />
									<fieldset>
										<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
										<label for="rememberme">Remember me</label>
									</fieldset>
									<input type="submit" name="wp-submit" id="wp-submit" value="Log In" />
									<a class="button standar white no-padding-ex" href="<?php bloginfo('url'); ?>/my-account">CREATE ACCOUNT</a>
									<!-- <a class="button standar white no-padding-ex" href="<?php bloginfo('url'); ?>/my-account">Use your social account</a> -->
									<a class="button standar white no-padding-ex" href="<?php bloginfo('url'); ?>/my-account/lost-password">LOST YOUR PASSWORD?</a>
									<input type="hidden" name="redirect_to" value="<?php bloginfo('url'); ?>" />
									<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
									<p class="status"></p>
								</form>	
							</div>
						<?php } ?>	
					</li>
					<li class="shopping-tool"><a href="<?php bloginfo('url'); ?>/cart"><span class="number"><?php echo WC()->cart->cart_contents_count; ?></span><br/><span data-icon="&#xe603" aria-hidden="true"></span></a><div class="product-added">Product Added to your Cart!<br /><a href="<?php bloginfo('url'); ?>/cart">View cart</a></div></li>
					<li class="hasTooltip language-tool">
						<?php 
							
								// $languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
								// $inactive = '';
								// $active = '';
								// if(1 < count($languages)){
								// 	foreach($languages as $l){
								// 	  if(!$l['active']){
								//   		$inactive.= '<a href="'.$l['url'].'"><img src="'.$l['country_flag_url'].'" alt="'.$l['language_code'].'"/><span>'.$l['language_code'].'</span></a>';
								// 	  }else{
								//   		$active = '<a><img src="'.$l['country_flag_url'].'" alt="'.$l['language_code'].'" class="first-flag"/>'.$l['language_code'].'</a>';
								// 	  }
								// 	}
								// }

						?>
					    <?php //echo $active; ?>
						<!-- <div class="language-arrow arrow"></div>
						<div class="tooltiptheme language hidden"> -->
							<?php //echo $inactive; ?>
						<!-- </div> -->


						<a><img src="<?php echo get_template_directory_uri(); ?>/img/icn/usa.jpg" alt="usa"/>USA</a>
						<div class="language-arrow arrow"></div>
						<div class="tooltiptheme language hidden">
							<a><img src="<?php echo get_template_directory_uri(); ?>/img/icn/usa.jpg" alt="usa"/><span>USA</span></a>
						</div>
					</li>
				</ul>
			</div>
		</header>
			
		<a href="<?php bloginfo('url'); ?>/contact/" class="support">Contact us</a>