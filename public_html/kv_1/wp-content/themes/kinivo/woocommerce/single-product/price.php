<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $product_type;
// $discount = 100 - ((100*$product->sale_price)/$product->regular_price);
$discount = 0;
?>
<?php if( $product->product_type == 'simple'){ ?>
	<?php $discount = 100 - ((100*$product->get_price())/$product->get_regular_price()); ?>
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<span class="before-price-title">Price:</span>
		<span class="before-price"><span><?php echo get_woocommerce_currency_symbol().$product->get_regular_price().'</span> '.get_woocommerce_currency(); ?></span>
		<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
		<span class="price-now-title">Now:</span>
		<span class="price-now price"><span><?php echo get_woocommerce_currency_symbol().$product->get_price().'</span> '.get_woocommerce_currency(); ?> <span class="off">(<?php echo number_format($discount); ?>%) OFF</span></span>
		<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
		<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
	</div>
<?php }else{ ?>
	<?php if( $product->product_type == 'variable'){ ?>
		<?php $discount = 100 - ((100*$product->get_variation_sale_price('min',false  ))/$product->get_variation_regular_price('min',false  )); ?>
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<span class="before-price-title">Price:</span>
			<span class="before-price"><span><?php echo get_woocommerce_currency_symbol().$product->get_variation_regular_price('min',false  ).'</span> '.get_woocommerce_currency(); ?></span>
			<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
			<span class="price-now-title">Now:</span>
			<span class="price-now price"><span><?php echo get_woocommerce_currency_symbol().$product->get_variation_sale_price('min',false  ).'</span> '.get_woocommerce_currency(); ?> <span class="off">(<?php echo number_format($discount); ?>%) OFF</span></span>
			<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
			<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
		</div>
	<?php } ?>
<?php } ?>
