<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

?>
<div class="images">
	<div class="right-side">
		<a class="product-arrows right-arrow"></a>
	</div>
	<div class="left-side">
		<a class="product-arrows left-arrow"></a>
	</div>
	
	
		<ul class="products-slide">
	<?php

		if ( has_post_thumbnail() ) {

			$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
			$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title' => $image_title
				) );

			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery2]';
			} else {
				$gallery = '';
			}

			// echo  '<li class="active"><span></span>'.apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $post->ID ).'</li>';

			echo '<li class="active"><span></span><a href="'.$image_link.'" itemprop="image" class="woocommerce-main-image" data-rel="prettyPhoto' . $gallery . '"><img src="'.$image_link.'" /></a></li>';

			$attachment_ids = $product->get_gallery_attachment_ids();

			echo '<div class="gal_big_pretty">';

			if ( $attachment_ids ) {

				$loop = 0;
				$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

				foreach ( $attachment_ids as $attachment_id ) {

					$classes = array( 'zoom' );

					if ( $loop == 0 || $loop % $columns == 0 )
						$classes[] = 'first';

					if ( ( $loop + 1 ) % $columns == 0 )
						$classes[] = 'last';

					$image_link2 = wp_get_attachment_url( $attachment_id );

					if ( ! $image_link )
						continue;

					$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
					$image_class = esc_attr( implode( ' ', $classes ) );
					$image_title = esc_attr( get_the_title( $attachment_id ) );

					
					echo '<a href="'.$image_link2.'" itemprop="image" class="woocommerce-main-image" data-rel="prettyPhoto' . $gallery . '"></a>';
					$loop++;
				}

			}

			echo '</div>';

		} else {

			echo  '<li class="active"><span></span>'.apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID ).'</li>';

		}
	?>
		</ul>

	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>