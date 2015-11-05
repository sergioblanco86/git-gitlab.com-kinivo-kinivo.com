
			<footer class="main_footer">
				<div class="wrapper wrap footer-wrapper">
					<ul class="footer-menu">
						<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
						<li><a href="<?php bloginfo('url'); ?>/product-category/headphones/">Headphones</a></li>
						<li><a href="<?php bloginfo('url'); ?>/product-category/speakers/">Speakers</a></li>
						<li><a href="<?php bloginfo('url'); ?>/product-category/accessories/">Accessories</a></li>
						<li><a href="<?php bloginfo('url'); ?>/about-us">About</a></li>
						<li><a href="<?php bloginfo('url'); ?>/contact">Contact</a></li>
						<li><a href="http://support.kinivo.com/" target="blank">Support</a></li>
						<?php if ( is_user_logged_in() ) { ?>
			        		<li><a href="<?php bloginfo('url'); ?>/order-history">My Account</a></li>
			        	<?php } ?>
						<li><a href="<?php bloginfo('url'); ?>/blog">Blog</a></li>
						<li><a href="<?php bloginfo('url'); ?>/privacy-policy">Privacy Policy</a></li>
						<li><a href="<?php bloginfo('url'); ?>/warranty">Warranty</a></li>
					</ul>
					<div class="footer-newsletter">
						<h2>Subscribe To Our Monthly Newsletter</h2>
						<form action="<?php bloginfo('template_url'); ?>/send-subscriber.php" id="form-send-subscriber" method="post">
							<input type="email" name="email" placeholder="Enter your e-mail address"/>
							<input type="hidden" value="subscriber" name="type">
							<div class="response"></div>
							<input type="submit" value="Subscribe">	
							
						</form>
						<p>We do not send spam or share information with 3rd parties. View our <a href="<?php bloginfo('url'); ?>/privacy-policy">privacy policy</a>.</p>
						<ul class="email-phone">
							<li><span data-icon="&#xf003" aria-hidden="true"></span><a href="mailto:support@kinivo.com" class="support-email">support@kinivo.com</a></li>
							<li><span data-icon="&#xf095" aria-hidden="true"></span>855 454 6486 (Toll Free) </li>
						</ul>
					</div>
					<div class="footer-social">
						<h2>Latest Tweets From Kinivo</h2>
						<a class="twitter-timeline" href="https://twitter.com/<?php echo $GLOBALS['twitter']; ?>" data-widget-id="474564565317853186" data-theme="dark" width="280" height="180" data-chrome="noheader nofooter transparent" data-border-color="#2E2E2E"></a>
						

						<a class="twitter-timeline" href="https://twitter.com/<?php echo $GLOBALS['twitter']; ?>" data-widget-id="556626533234638848" width="280" height="180" data-chrome="noheader nofooter transparent" data-border-color="#2E2E2E">Tweets by @kinivo</a>

						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


						<h2>Connect With Us On Social Media</h2>
						<ul class="social">
							<?php if($GLOBALS['facebook'] != '') echo '<li><a target="blank" href="'.$GLOBALS['facebook'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/fb-icon.png"></a></li>' ?>
				        	<?php if($GLOBALS['twitter'] != '') echo '<li><a target="blank" href="https://twitter.com/'.$GLOBALS['twitter'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/tw-icon.png"></a></li>' ?>
				        	<?php if($GLOBALS['instagram'] != '') echo '<li><a target="blank" href="http://instagram.com/'.$GLOBALS['instagram'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/ig-icon.png"></a></li>' ?>
				        	<?php if($GLOBALS['youtube'] != '') echo '<li><a target="blank" href="'.$GLOBALS['youtube'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/yt-icon.png"></a></li>' ?>
				        	<?php if($GLOBALS['gplus'] != '') echo '<li><a target="blank" href="'.$GLOBALS['gplus'].'" class="tw"><img src="'.get_template_directory_uri().'/img/icn/gp-icon.png"></a></li>' ?>
				        </ul>
					</div>
				</div>
				<div class="wrapper wrap">
					<p class="rights-lecture">&copy; <?php echo date('Y');?> Kinivo, Inc. All rights reserved.</p>
				</div>
			</footer>
		</div>

	</div>
	<!-- Modal -->
	<div class="mask close-modal"></div>
	<div class="modal order-history-modal" data-modal="view-detail"></div>

	<div class="modal add-address-modal" data-modal="add-address">
		<a class="close-modal cross"></a>
		<h1>Add Shipping Information</h1>
		<form class="modal-wrap">
			<h2>Add Shipping Address</h2>
			<fieldset>
				<label for="">Full Name</label>
				<input type="text">
			</fieldset>
			<fieldset>
				<label for="">Address Line 1:</label>
				<input type="text">
			</fieldset>
			<fieldset>
				<label for="">Address Line 2:</label>
				<input type="text">
			</fieldset>
			<fieldset>
				<label for="">Country:</label>
				<select name="" id="">
					<option value="">United States</option>
					<option value="">United Kingdom</option>
				</select>
			</fieldset>
			<fieldset>
				<label for="">State/Province/Region:</label>
				<select name="" id="">
					<option value="">United States</option>
					<option value="">United Kingdom</option>
				</select>
			</fieldset>
			<fieldset>
				<label for="">City:</label>
				<select name="" id="">
					<option value="">United States</option>
					<option value="">United Kingdom</option>
				</select>
			</fieldset>
			<fieldset  class="small">
				<label for="">ZIP:</label>
				<input type="text">
			</fieldset>
			<fieldset>
				<label for="">Phone Number:</label>
				<input type="text">
			</fieldset>
			<fieldset>
				<a class="button green standar-nowidth">Save Address</a>
			</fieldset>
		</form>
	</div>
	<!-- End Modal -->

	<!-- alerts pop -->
	<div class="alert-mask alert-pop"></div>
	<div class="alert-pop-up alert-pop">
		<p>In order to complete this order, you must accept the <a href="" target="_blank">terms and conditions</a> by selecting the corresponding check box.</p>
		<a class="button green standar-nowidth close-alert">ok</a>
	</div>

	<div class="alert-mask alert-pop-newuser"></div>
	<div class="alert-pop-up alert-pop-newuser">
		<p>If you want to create a new account please fill out the form.</p>
		<a class="button green standar-nowidth close-alert-user">ok</a>
	</div>

	<?php wp_footer(); ?>
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.matchHeight-min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/responsiveslides.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/flexie.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/flexslider.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/retina.js"></script>
	<script src='<?php echo get_template_directory_uri(); ?>/js/snap.js'></script>
	<!-- /build -->
	<script>
		jQuery('.products .row').mouseenter(function(){
			jQuery(this).children("div.row-description").addClass("desc-visible");
			jQuery(this).find('a.touch-view-more').addClass("hide");
		}).mouseleave( function(){
			jQuery(this).children("div.row-description").removeClass("desc-visible");
			jQuery('a.touch-view-more').removeClass("hide");
		});

		jQuery('.products .row').on("click","a.touch-view-more", function(){
			jQuery(this).siblings("div.row-description").addClass("desc-visible");
			jQuery(this).addClass("hide");
		});
		jQuery('.row-description').on("click","a.touch-close-more", function(){
			jQuery(this).parents("div.row-description").removeClass("desc-visible");
			jQuery('a.touch-view-more').removeClass("hide");
		});

		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-58736169-1', 'auto');
		ga('send', 'pageview');
	</script>

</body>
</html>