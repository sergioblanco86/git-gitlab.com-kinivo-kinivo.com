<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/// Add Data to SKU Table
function AmzFBA_Woo_install_data($AmzSKU, $WooSKU)
{
$post = array(
  'post_content'	=> $AmzSKU,
  'post_name' 	=> $AmzSKU,
  'post_title' 	=> $AmzSKU,
  'post_status'	=> 'private',
  'post_type'	=> 'amazonfbasku'
);
$IDFromTitle =  get_page_by_title($AmzSKU,'','amazonfbasku');
//If SKU Already exists just update it.
if($IDFromTitle != ''){
$post['ID']  = $IDFromTitle->ID;
}
wp_insert_post($post);
}
/// Add Data to Log Table
function AmzFBA_Woo_Log($level, $category, $title, $info)
{
  $UserSettings      = get_option('woocommerce_amazonfba_settings');
  $DebugEnabled      = $UserSettings['AmzFBA_Debug'];
  if($DebugEnabled != 'no'){
    $handle =  $category;
    $message = $title . ' - ' . $level .' - '  . $info;
    $Logger = new WC_Logger('Woo2Amz_' . $handle);
    $Logger->add($handle, $message);
  }
}
// Delete From Log Table
function AmzFBA_Woo_Log_Remove($type)
{
    switch ($type) {
        case "All":
	    $Logger = new WC_Logger('Woo2Amz_Service Status');
	    $Logger->clear('Woo2Amz_Service Status');
            $Logger = new WC_Logger('Woo2Amz_Inventory');
            $Logger->clear('Woo2Amz_Inventory');
            $Logger = new WC_Logger('Woo2Amz_Order');
            $Logger->clear('Woo2Amz_Order');
            $message = 'All logs have now been cleared.';
            break;
        case "Service Status":
            $Logger = new WC_Logger('Woo2Amz_' . $type);
            $Logger->clear('Woo2Amz_' . $type);
            $message = 'All service status log entries have been cleared';
            break;
        case "Inventory":
            $Logger = new WC_Logger('Woo2Amz_' . $type);
            $Logger->clear('Woo2Amz_' . $type);
            $message = 'All inventory log entries have been cleared';
            break;
        case "Order":
            $Logger = new WC_Logger('Woo2Amz_' . $type);
            $Logger->clear('Woo2Amz_' . $type);
            $message = 'All order log entries have been cleared';
            break;
    }
    return $message;
}
// Get Product ID from post_meta
function GetIdFromSKU($sku)
{
  global $wpdb;
    $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
    return $product_id;
}
//  Check SKU exist on Amazon
function AmzFBA_is_sku_fulfillable($sku)
{
return $IDFromTitle =  get_page_by_title($sku,'','amazonfbasku');
}
// Check FBA Orders need status updating
function Amz_FBA_Check_If_Orders_Need_Confirming()
{
global $post;
$args = array(
        'post_type'         => 'shop_order',
        'post_status'       => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
              'taxonomy' => 'shop_order_status',
              'field' => 'slug',
              'terms' => array('processing')
              )
        )
);

AmzFBA_Woo_Log("Neutral", "Order", "Order Status Update", "running order query");
$loop = new WP_Query( $args );
if (!$loop->have_posts()) {
    AmzFBA_Woo_Log("Neutral", "Order", "Order Status Update", "No orders found");
}
while ($loop->have_posts()) {
    $loop->the_post();
    $ID = $loop->post->ID;
    AmzFBA_Woo_Log("Neutral", "Order", "Order Status Update", "found order id " . $ID);
    GetAmzFBAOrderDetails($ID);
}

wp_reset_postdata();
}

//Check FBA Orders that are completed - Required Tracking information
function Amz_FBA_Check_If_Orders_Need_Tracking()
{
global $wpdb;
$args = array(
          'meta_key'          => 'FBA_Tracking_ID',
          'meta_value'        => 'Waiting',
          'post_type'         => 'shop_order'
);
$OrderArray = get_posts($args);
foreach ($OrderArray as $SingleOrder){
  $ID = $SingleOrder->ID;
  GetAmzFBAOrderTracking($ID);
}
$OrderCount = count($OrderArray);
if ($OrderCount == 0){
AmzFBA_Woo_Log("Neutral", "Order", "Order Tracking Update", "No orders currently awaiting tracking information.");
}
}
function CheckForMissingConfig(){
  $UserSettings   = get_option('woocommerce_amazonfba_settings');
	if(	!isset($UserSettings['AmzFBA_Marketplace']) || ($UserSettings['AmzFBA_Marketplace'] == '') ||
		!isset($UserSettings['AmzFBA_MerchantID']) || ($UserSettings['AmzFBA_MerchantID'] == '') ||
		!isset($UserSettings['AmzFBA_AWSAccessKeyID']) || ($UserSettings['AmzFBA_AWSAccessKeyID'] == '') ||
		!isset($UserSettings['AmzFBA_SecretKey']) || ($UserSettings['AmzFBA_SecretKey'] == '')
	){
		return true; // True - It has missing configuration.
	}else{
		return false; // False - No missing configuration.
	}
}
?>
