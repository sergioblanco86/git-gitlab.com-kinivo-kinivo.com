<?php
/*
Template Name: Cart
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class='load_overlay' style='position: fixed; background-color:rgba(191,191,191,0.6); widht:100%; height:100%; width:100%; ;left:0; top:0; opacity:1; z-index:9999;'>
			<div style="color:#353535;font-size: 14px; width:116px; height:auto; text-align: center; position: absolute; left:50%; top:50%; margin-left:-58px; margin-top: -20px;">
				<img style="display:inline-block; position: relative; margin-bottom:10px;" src="<?php echo get_template_directory_uri(); ?>/img/misc/circular-loading.GIF" width="40" height="40" alt="">
			</div>
		</div>
		<div class="contentWeb">
			<?php $GLOBALS['bgimg'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content();?>
				<link rel='stylesheet' id='woocommerce_chosen_styles-css'  href='<?php bloginfo('url');?>/wp-content/plugins/woocommerce/assets/css/chosen.css?ver=4.0' type='text/css' media='all' />
				<script type='text/javascript' src='<?php bloginfo('url');?>/wp-content/plugins/woocommerce/assets/js/chosen/chosen.jquery.min.js?ver=1.0.0'></script>
		        <script type='text/javascript' src='<?php bloginfo('url');?>/wp-content/plugins/woocommerce/assets/js/frontend/chosen-frontend.min.js?ver=2.2.8'></script>
			<?php endwhile; else : ?>
				<p>No content.</p>
			<?php endif;?>
			<!-- End Content! -->

<script>
waitForCouponBox = setInterval(
	function(){
		ccbox = document.getElementById("coupon_code");
		if ( !(ccbox === null) ){
			console.log(ccbox);
			clearInterval(waitForCouponBox);
			params = location.search.split("=");
			if ( params.length > 0 ){
				params[0] = params[0].replace("?","");
				if ( params[0] === "useOffer" ){
					ccbox.value = params[1];
					setTimeout(
						function(){
							document.getElementsByName("apply_coupon")[0].click();
						},100
					);
				}
			}
		}
	}
,100);
</script>



			<?php get_footer(); ?>