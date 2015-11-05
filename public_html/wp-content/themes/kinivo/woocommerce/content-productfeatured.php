<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $product, $woocommerce_loop, $flatsome_opt;

$attachment_ids = $product->get_gallery_attachment_ids();

// Ensure visibilty
if ( ! $product->is_visible() )
	return;

// Get avability
$post_id = $post->ID;
$stock_status = get_post_meta($post_id, '_stock_status',true) == 'outofstock';
?>
<?php if( $product->product_type == 'simple'){ ?>
	<li>
		<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' ); ?>
		<a href="<?php the_permalink(); ?>"><img src="<?php echo $image[0]; ?>" alt=""></a>
		<a class="sugg-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		<?php the_content(); ?>
		<p class="prices">
			<?php if ($product->get_regular_price() != $product->get_price()) {?>
				<span><?php echo get_woocommerce_currency_symbol().$product->get_regular_price().' '.get_woocommerce_currency(); ?></span><br />
			    <span><b class="red">Sale:</b> <?php echo get_woocommerce_currency_symbol().$product->get_price().' '.get_woocommerce_currency(); ?></span><br />
			<?php }else{?>
				<span class="no-line">Price: <?php echo get_woocommerce_currency_symbol().$product->get_regular_price().' '.get_woocommerce_currency(); ?></span><br />
			<?php }?>
		</p>
	</li>
<?php }else{ ?>
	<?php if( $product->product_type == 'variable'){ ?>
		<li>
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' ); ?>
			<a href="<?php the_permalink(); ?>"><img src="<?php echo $image[0]; ?>" alt=""></a>
			<a class="sugg-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<?php the_content(); ?>
			<p class="prices">
				<?php if ($product->get_variation_regular_price('min',false  ) != $product->get_variation_sale_price('min',false  ) ) {?>
					<span><?php echo get_woocommerce_currency_symbol().$product->get_variation_regular_price('min',false  ).' '.get_woocommerce_currency(); ?></span><br />
					<span><b class="red">Sale:</b> <?php echo get_woocommerce_currency_symbol().$product->get_variation_sale_price('min',false  ).' '.get_woocommerce_currency(); ?></span><br />
				<?php }else{?>
					<span class="no-line">Price: <?php echo get_woocommerce_currency_symbol().$product->get_variation_regular_price('min',false  ).' '.get_woocommerce_currency(); ?></span><br />	
				<?php }?>
			</p>
		</li>
	<?php } ?>
<?php } ?>