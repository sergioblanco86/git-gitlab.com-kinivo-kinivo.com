<?php
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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Ideaware Co."/>
	<meta name="keywords" content="Consumer Electronics manufacturer"/>
	<meta name="description" content="Kinivo is a Consumer Electronics manufacturer. We design and develop innovative products and accessories for everyday life."/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <meta name="format-detection" content="telephone=no"/> -->
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/icn/favicon.ico"/>
	<!-- build:css css/production.min.css -->
	<link rel='stylesheet' id='snap-css'  href='<?php echo get_template_directory_uri(); ?>/css/snap.css' type='text/css' media='all' />
	<link href="<?php echo get_template_directory_uri(); ?>/css/normalize.css" rel="stylesheet"/>
	<link href="<?php echo get_template_directory_uri(); ?>/css/main.css" rel="stylesheet"/>
	<link href="<?php echo get_template_directory_uri(); ?>/css/flexslider.css" rel="stylesheet"/>
	<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet"/>
	<!-- /build -->
	<title>Kinivo</title>
	<!--[if lt IE 9]><script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
	
	<?php wp_head(); ?>
	
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.1.3.js"></script>
	<!-- /build -->



	<script>

	var yith_wcwl_plugin_ajax_web_url = '<?php echo admin_url("admin-ajax.php") ?>';
    var login_redirect_url = '<?php echo wp_login_url()."?redirect_to=".urlencode( $_SERVER["REQUEST_URI"] ); ?>';
	</script>

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
		        	<li><a href="#">Support</a></li>
		        	<?php if ( is_user_logged_in() ) { ?>
		        	<li><a href="<?php bloginfo('url'); ?>/order-history">My Account</a></li>
		        	<?php } ?>
		        	<li><a href="<?php bloginfo('url'); ?>/blog">Blog</a></li>
		        </ul>
		        <div class="left-menu-footer">
			        <ul class="contact">
			        	<li><span data-icon="&#xf003" aria-hidden="true"></span>support@kinivo.com</li>
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
									if ( $loop->have_posts() ) {
										while ( $loop->have_posts() ) : $loop->the_post();
											woocommerce_get_template_part( 'content', 'productdrop' );
										endwhile;
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
									if ( $loop->have_posts() ) {
										while ( $loop->have_posts() ) : $loop->the_post();
											woocommerce_get_template_part( 'content', 'productdrop' );
										endwhile;
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
									if ( $loop->have_posts() ) {
										while ( $loop->have_posts() ) : $loop->the_post();
											woocommerce_get_template_part( 'content', 'productdrop' );
										endwhile;
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
									<a class="button standar white" href="<?php bloginfo('url'); ?>/my-account">CREATE ACCOUNT</a>
									<input type="hidden" name="redirect_to" value="<?php bloginfo('url'); ?>" />
									<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
									<p class="status"></p>
								</form>	
							</div>
						<?php } ?>	
					</li>
					<li class="shopping-tool"><a href="<?php bloginfo('url'); ?>/cart"><span class="number"><?php echo $woocommerce->cart->cart_contents_count; ?></span><br/><span data-icon="&#xe603" aria-hidden="true"></span></a></li>
					<li class="hasTooltip language-tool">
						<a><img src="<?php echo get_template_directory_uri(); ?>/img/icn/usa.jpg" alt="usa"/>USA</a>
						<div class="language-arrow arrow"></div>
						<div class="tooltiptheme language hidden">
							<a><img src="<?php echo get_template_directory_uri(); ?>/img/icn/esp.png" alt="usa"/><span>ESP</span></a>
							<a><img src="<?php echo get_template_directory_uri(); ?>/img/icn/jpn.png" alt="usa"/><span>JPN</span></a>
						</div>
					</li>
				</ul>
			</div>
		</header>
			
		<a href="#" class="support">Click here for support</a>