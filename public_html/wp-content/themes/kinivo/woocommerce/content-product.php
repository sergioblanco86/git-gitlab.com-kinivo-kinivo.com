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
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'shop_catalog' ); ?>
<a class="cat-item-product" style="background-image:url(<?php echo $image[0]; ?>);" href="<?php the_permalink(); ?>">
	<!-- <img src="<?php echo $image[0]; ?>" alt=""> -->
	<div class="product-info">
		<h1><?php the_title(); ?></h1>
		<?php //do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
		<?php //the_content(); ?>
		<span class="cat-view-prod"><span data-icon="&#xf061" aria-hidden="true"></span></span>
	</div>
</a>