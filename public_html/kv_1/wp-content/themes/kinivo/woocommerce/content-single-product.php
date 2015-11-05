<?php

    /**
     * The template for displaying lookbook product style content within loops.
     *
     * Override this template by copying it to yourtheme/woocommerce/content-product.php
     *
     * @author      WooThemes
     * @package     WooCommerce/Templates
     * @version     1.6.4
     */

 
    global $post, $product;

    // Get category permalink
    $permalinks     = get_option( 'woocommerce_permalinks' );
    $category_slug  = empty( $permalinks['category_base'] ) ? _x( 'product-category', 'slug', 'woocommerce' ) : $permalinks['category_base'];
    $amazon = get_post_meta($product->id, 'amazon_link', true );
 
?>


		<div class="contentWeb">
			<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>> 
				<?php
		            /**
		             * woocommerce_before_single_product hook
		             *
		             * @hooked woocommerce_show_messages - 10
		             */
		             do_action( 'woocommerce_before_single_product' );
		        ?>   
				<div class="product-page">
					<div class="wrapper wrap">
						 	<?php 
							/** Output the WooCommerce Breadcrum  */
						    $defaults = array(
						        'delimiter'  => ' / ',
						        'wrap_before'  => '<div class="bread">',
						        'wrap_after' => '</div>',
						        'before'   => '',
						        'after'   => '',
						        'home'    => true
						    );
						    $args = wp_parse_args(  $defaults  );
						    woocommerce_get_template( 'global/breadcrumb-single-product.php', $args );
						    ?>
						 <!-- </div> -->
						 <div class="product-detail">
						 	<div class="photos">
						 		<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
						 	</div>
						 	<div class="details">
						 		<?php do_action( 'woocommerce_single_product_summary' ); ?>
						 		<?php if($amazon){ ?>
						 		<a href="<?php echo $amazon; ?>" target="blank" class="amazon-buy-link"><img src="https://images-na.ssl-images-amazon.com/images/G/01/associates/remote-buy-box/buy4._V192207739_.gif" /></a>
						 		<?php } ?>
							</div>
						 </div>
					</div>
				</div>

				<script>
				jQuery( document ).ready(function() {

					jQuery('body').on("mouseenter","div.photos ul.products-thumbs a.thumb-img", function(){
						var href = jQuery(this).data("href");
						jQuery(this).parents("div.photos").find("ul.products-slide li.active").html("").append("<span></span><a href='"+href +"' itemprop='image' class='woocommerce-main-image' data-rel='prettyPhoto[product-gallery]'><img src='"+href +"'' /></a>");
						jQuery(this).parents('ul.products-thumbs').find('li').removeClass('active');
						jQuery(this).parents('li').addClass('active');
						jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
							hook: 'data-rel',
							social_tools: false,
							theme: 'pp_woocommerce',
							horizontal_padding: 20,
							opacity: 0.8,
							deeplinking: false
						});
					});

					jQuery('body').on("click","a.product-arrows.right-arrow", function(){
						var active_thumb = jQuery("div.photos").find("ul.products-thumbs li.active");
						var first_thumb = jQuery("div.photos").find("ul.products-thumbs li:first-child");
						if(active_thumb.length){
							if( active_thumb.next("li").length ){
								var next_li = active_thumb.next("li");
								active_thumb.removeClass("active");
								next_li.addClass("active");
								var href = next_li.find("a.thumb-img").data("href");
								next_li.parents("div.photos").find("ul.products-slide li.active").html("").append("<span></span><a href='"+href +"' itemprop='image' class='woocommerce-main-image' data-rel='prettyPhoto[product-gallery]'><img src='"+href +"'' /></a>");
							}else{
								active_thumb.removeClass("active");
								first_thumb.addClass("active");
								var href = first_thumb.find("a.thumb-img").data("href");
								first_thumb.parents("div.photos").find("ul.products-slide li.active").html("").append("<span></span><a href='"+href +"' itemprop='image' class='woocommerce-main-image' data-rel='prettyPhoto[product-gallery]'><img src='"+href +"'' /></a>");
							}
						}else{
							first_thumb.addClass("active");
							var href = first_thumb.find("a.thumb-img").data("href");
							first_thumb.parents("div.photos").find("ul.products-slide li.active").html("").append("<span></span><a href='"+href +"' itemprop='image' class='woocommerce-main-image' data-rel='prettyPhoto[product-gallery]'><img src='"+href +"'' /></a>");
						}

						jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
							hook: 'data-rel',
							social_tools: false,
							theme: 'pp_woocommerce',
							horizontal_padding: 20,
							opacity: 0.8,
							deeplinking: false
						});
					});

					jQuery('body').on("click","a.product-arrows.left-arrow", function(){
						var active_thumb = jQuery("div.photos").find("ul.products-thumbs li.active");
						var last_thumb = jQuery("div.photos").find("ul.products-thumbs li:last-child");
						if(active_thumb.length){
							if( active_thumb.prev("li").length ){
								var next_li = active_thumb.prev("li");
								active_thumb.removeClass("active");
								next_li.addClass("active");
								var href = next_li.find("a.thumb-img").data("href");
								next_li.parents("div.photos").find("ul.products-slide li.active").html("").append("<span></span><a href='"+href +"' itemprop='image' class='woocommerce-main-image' data-rel='prettyPhoto[product-gallery]'><img src='"+href +"'' /></a>");
							}else{
								active_thumb.removeClass("active");
								last_thumb.addClass("active");
								var href = last_thumb.find("a.thumb-img").data("href");
								last_thumb.parents("div.photos").find("ul.products-slide li.active").html("").append("<span></span><a href='"+href +"' itemprop='image' class='woocommerce-main-image' data-rel='prettyPhoto[product-gallery]'><img src='"+href +"'' /></a>");
							}
						}else{
							last_thumb.addClass("active");
							var href = last_thumb.find("a.thumb-img").data("href");
							last_thumb.parents("div.photos").find("ul.products-slide li.active").html("").append("<span></span><a href='"+href +"' itemprop='image' class='woocommerce-main-image' data-rel='prettyPhoto[product-gallery]'><img src='"+href +"'' /></a>");
						}

						jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
							hook: 'data-rel',
							social_tools: false,
							theme: 'pp_woocommerce',
							horizontal_padding: 20,
							opacity: 0.8,
							deeplinking: false
						});
					});

					

				});
				</script>
				

				

			<?php do_action( 'woocommerce_after_single_product' ); ?>