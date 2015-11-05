
			<footer class="main_footer">
				<div class="wrapper wrap footer-wrapper">
					<ul class="footer-menu">
						<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
						<li><a href="#">Headphones</a></li>
						<li><a href="#">Speakers</a></li>
						<li><a href="#">On-The-Go</a></li>
						<li><a href="about-us">About</a></li>
						<li><a href="contact">Contact</a></li>
						<li><a href="#">Support</a></li>
						<li><a href="#">My Account</a></li>
						<li><a href="blog">Blog</a></li>
					</ul>
					<div class="footer-newsletter">
						<h2>Subscribe To Our Newsletter</h2>
						<input type="text" placeholder="Enter Your E-mail Address"/>
						<input type="submit" value="Subscribe">
						<p>We will never send spam and you can unsubscribe any time.</p>
						<ul>
							<li><span data-icon="&#xf003" aria-hidden="true"></span>support@kinivo.com</li>
							<li><span data-icon="&#xf095" aria-hidden="true"></span>855 454 6486 (Toll Free) </li>
						</ul>
					</div>
					<div class="footer-social">
						<h2>Latest tweets from Kinivo</h2>
						<a class="twitter-timeline" href="https://twitter.com/kinivo" data-widget-id="474564565317853186" data-theme="dark" width="280" height="180" data-chrome="noheader nofooter transparent" data-border-color="#2E2E2E"></a>
						<h2>Follow Us</h2>
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
					<p class="rights-lecture">&copy; <?php echo date('Y');?> Kinivo, Inc. all rights reserved</p>
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

	<?php wp_footer(); ?>

	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<!-- build:js <?php echo get_template_directory_uri(); ?>/js/production.min.js -->
	
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
	</script>
</body>
</html>