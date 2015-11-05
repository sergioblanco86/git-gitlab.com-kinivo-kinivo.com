<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

global $wpdb, $yith_wcwl, $woocommerce;

if( isset( $_GET['user_id'] ) && !empty( $_GET['user_id'] ) ) {
    $user_id = $_GET['user_id'];
} elseif( is_user_logged_in() ) {
    $user_id = get_current_user_id();
}

$current_page = 1;
$limit_sql = '';
$pagination = 'yes';
$per_page = 5;

if( $pagination == 'yes' ) {
    $count = array();

    if( is_user_logged_in() || ( isset( $user_id ) && !empty( $user_id ) ) ) {
        $count = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) as `cnt` FROM `' . YITH_WCWL_TABLE . '` WHERE `user_id` = %d', $user_id  ), ARRAY_A );
        $count = $count[0]['cnt'];
    } elseif( yith_usecookies() ) {
        $count[0]['cnt'] = count( yith_getcookie( 'yith_wcwl_products' ) );
    } else {
        $count[0]['cnt'] = count( $_SESSION['yith_wcwl_products'] );
    }

    $total_pages = ceil($count/$per_page);
    if( $total_pages > 1 ) {
        $current_page =  max( 1, get_query_var( 'page' ) );

        // $page_links = paginate_links( array(
        //     'base' => get_pagenum_link( 1 ) . '%_%',
        //     'format' => '?page=%#%',
        //     'current' => $current_page,
        //     'total' => $total_pages,
        //     'show_all' => true
        // ) );
        $prev_link = '';
        $next_link = '';
        if($current_page > 1){
            $prev_p = $current_page - 1;
            $prev_link = '<a class="prev" href="'.get_bloginfo('url').'/wishlist/?page='.$prev_p.'" ></a>';
        }
        if($current_page < $total_pages){
            $next_p = $current_page + 1;
            $next_link = '<a class="next" href="'.get_bloginfo('url').'/wishlist/?page='.$next_p.'" ></a>';
        }
        
        $page_links = $prev_link.'<span class="pages">Page '.$current_page.' - '.$total_pages.'</span>'.$next_link;
    }

    $limit_sql = "LIMIT " . ( $current_page - 1 ) * 1 . ',' . $per_page;
}

if( is_user_logged_in() || ( isset( $user_id ) && !empty( $user_id ) ) )
{ $wishlist = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . YITH_WCWL_TABLE . "` WHERE `user_id` = %s" . $limit_sql, $user_id ), ARRAY_A ); }
elseif( yith_usecookies() )
{ $wishlist = yith_getcookie( 'yith_wcwl_products' ); }
else
{ $wishlist = isset( $_SESSION['yith_wcwl_products'] ) ? $_SESSION['yith_wcwl_products'] : array(); }

// Start wishlist page printing
if( function_exists('wc_print_notices') ) {
    wc_print_notices();
}else{
    $woocommerce->show_messages();
}

// Retrieve The Post's Author ID
$author_id = $GLOBALS['current_user']->ID;
// Set the image size. Accepts all registered images sizes and array(int, int)
$size = 'thumbnail';

// Get the image URL using the author ID and image size params
$imgURL = get_cupp_meta($author_id, $size);

 ?>
 <div class="my-account-content">
    <div class="wrapper wrap">
        <div class="bread">Home / My Account</div>
        <div class="dash">
            <div id="yith-wcwl-messages"></div>
            <div class="top">
                <?php get_template_part('content','menuresponsive-myaccount'); ?>
                <!-- <h1>My Wish List</h1> -->
                <?php
                do_action( 'yith_wcwl_before_wishlist_title' );

                $wishlist_title = get_option( 'yith_wcwl_wishlist_title' );
                $wishlist_title = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_wishlist_title_text', $wishlist_title ) : $wishlist_title;

                if( !empty( $wishlist_title ) )
                { echo apply_filters( 'yith_wcwl_wishlist_title', '<h1>' . $wishlist_title . '</h1>' ); }

                do_action( 'yith_wcwl_before_wishlist' );
                ?>
                <div class="search">
                    <input type="text" placeholder="TITLE, DEPARTMENT, RECIPIENT">
                    <input type="submit" value="search">
                </div>
            </div>
            <?php get_template_part('content','menu-myaccount'); ?>
            <form id="yith-wcwl-form" action="<?php echo esc_url( $yith_wcwl->get_wishlist_url() ) ?>" method="post">
                <table class="shop_table cart wishlist_table information-table wish-list" cellspacing="0">
                    <thead>
                        <tr>
                            <th colspan="2">Products On The List</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if( count( $wishlist ) > 0 ) :
                        
                        foreach( $wishlist as $values ) :
                            if( !is_user_logged_in() && !isset( $_GET['user_id'] ) ) {
                                if( isset( $values['add-to-wishlist'] ) && is_numeric( $values['add-to-wishlist'] ) ) {
                                    $values['prod_id'] = $values['add-to-wishlist'];
                                    $values['ID'] = $values['add-to-wishlist'];
                                } else {
                                    $values['prod_id'] = $values['product_id'];
                                    $values['ID'] = $values['product_id'];
                                }
                            }

                            $product_obj = get_product( $values['prod_id'] );

                            if( $product_obj !== false && $product_obj->exists() ) : ?>
                                <tr id="yith-wcwl-row-<?php echo $values['ID'] ?>">
                                    <?php $remove_wishlist = esc_attr( "remove_item_from_wishlist( '" . esc_url( $yith_wcwl->get_remove_url( $values['ID'] ) ) . "', 'yith-wcwl-row-" . $values['ID'] ."');" ); ?>
                                    <!-- <td class="product-remove"></td> -->
                                    <td class="product-thumbnail" align="center">
                                        <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $values['prod_id'] ) ) ) ?>">
                                            <?php echo $product_obj->get_image() ?>
                                        </a>
                                        <div>
                                    </td>
                                    <td class="product-name">
                                        <div class="product-info">
                                            <h2>
                                                <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $values['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product_obj->get_title(), $product_obj ) ?></a>
                                            </h2>
                                            <p>
                                                <?php echo $product_obj->post->post_excerpt;?>
                                                <br />
                                                <span class="bold">
                                                    <?php if( get_option( 'yith_wcwl_price_show' ) == 'yes' ) : ?>
                                            
                                                            <?php
                                                            if( $product_obj->price != '0' ) {
                                                                $wc_price = function_exists('wc_price') ? 'wc_price' : 'woocommerce_price';

                                                                if( get_option( 'woocommerce_tax_display_cart' ) == 'excl' )
                                                                    { echo apply_filters( 'woocommerce_cart_item_price_html', $wc_price( $product_obj->get_price_excluding_tax() ), $values, '' ); }
                                                                else
                                                                    { echo apply_filters( 'woocommerce_cart_item_price_html', $wc_price( $product_obj->get_price() ), $values, '' ); }
                                                            } else {
                                                                echo apply_filters( 'yith_free_text', __( 'Free!', 'yit' ) );
                                                            }
                                                            ?>

                                                    <?php endif ?>
                                                </span>
                                                <br />
                                                Stock Status - 
                                                <?php if( get_option( 'yith_wcwl_stock_show' ) == 'yes' ) : ?>
                                            
                                                        <?php
                                                        $availability = $product_obj->get_availability();
                                                        $stock_status = $availability['class'];

                                                        if( $stock_status == 'out-of-stock' ) {
                                                            $stock_status = "Out";
                                                            echo '<span class="wishlist-out-of-stock">' . __( 'Out of Stock', 'yit' ) . '</span>';
                                                        } else {
                                                            $stock_status = "In";
                                                            echo '<span class="wishlist-in-stock">' . __( 'In Stock', 'yit' ) . '</span>';
                                                        }
                                                        ?>
                                                  
                                                <?php endif ?>
                                                
                                            </p>
                                        </div>
                                        
                                        
                                        
                                        <?php if( get_option( 'yith_wcwl_add_to_cart_show' ) == 'yes' ) : ?>
                                            
                                                <?php if(isset($stock_status) && $stock_status != 'Out'): ?>
                                                    <?php echo YITH_WCWL_UI::add_to_cart_button( $values['prod_id'], isset($availability['class']) ); ?>
                                                <?php endif ?>
                                            
                                        <?php endif ?>

                                        <a href="javascript:void(0)" onclick="<?php echo $remove_wishlist ?>" class="remove wishlist-remove" title="<?php _e( 'Remove this product', 'yit' ) ?>">&times;</a>

                                    </td>
                                    
                                </tr>
                            <?php
                            endif;
                        endforeach;
                    else: ?>
                        <tr>
                            <td colspan="6" class="wishlist-empty" ><?php _e( 'No products were added to the wishlist', 'yit' ) ?></td>
                        </tr>
                    <?php
                    endif;?>

                   
                    </tbody>
                </table>
            </form>
            <div class="top">
                <?if( isset( $page_links ) ) : ?>
                    <tr>
                        <td colspan="2" class="wishlist-notification"><?php echo $page_links ?></td>
                    </tr>
                <?php endif ?>
                <!-- <a class="prev"></a>
                <span class="pages">Page 1 - 2</span>
                <a class="next"></a> -->
            </div>
        </div>
    </div>
</div>


