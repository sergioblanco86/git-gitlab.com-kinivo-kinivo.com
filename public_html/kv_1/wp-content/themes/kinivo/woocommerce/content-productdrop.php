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
<li>
	<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); ?>
	<img src="<?php echo $image[0]; ?>" alt="" width="120" height="120">
	<span><?php the_title(); ?></span>
	<a class="prod-info" href="<?php the_permalink(); ?>">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
		<!-- <span class="icon" data-icon="&#xe602">10 Hours</span>
		<span class="icon" data-icon="&#xe605">Bluetooth</span> -->
		<span class="read-more">Read More</a> 
	</a>
</li>