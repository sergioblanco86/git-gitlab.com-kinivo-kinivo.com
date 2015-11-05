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
  if($DebugEnabled){
    $handle =  $category;
    $message = $title . ' - ' . $level .' - '  . $info;
    $Logger = new WC_Logger($handle);
    $Logger->add($handle, $message);
  }
}
// Delete From Log Table
function AmzFBA_Woo_Log_Remove($type)
{
    switch ($type) {
        case "All":
      $Logger = new WC_Logger('Service Status');
      $Logger->clear('Service Status');
            $Logger = new WC_Logger('Inventory');
            $Logger->clear('Inventory');
            $Logger = new WC_Logger('Order');
            $Logger->clear('Order');
            $message = 'All logs have now been cleared.';
            break;
        case "Service Status":
            $Logger = new WC_Logger($type);
            $Logger->clear($type);
            $message = 'All service status log entries have been cleared';
            break;
        case "Inventory":
            $Logger = new WC_Logger($type);
            $Logger->clear($type);
            $message = 'All inventory log entries have been cleared';
            break;
        case "Order":
            $Logger = new WC_Logger($type);
            $Logger->clear($type);
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
global $wpdb;
$args = array(
  'meta_key'          => 'FBA_Status',
  'meta_value'        =>  array('False','RECEIVED','PLANNING','PROCESSING'),
  'post_type'	    => 'shop_order'
);
$OrderArray = get_posts($args);
foreach ($OrderArray as $SingleOrder){
  $ID = $SingleOrder->ID;
  GetAmzFBAOrderDetails($ID);
}
$OrderCount = count($OrderArray);
if ($OrderCount == 0){
AmzFBA_Woo_Log("Neutral", "Order", "Order Status Update", "No orders currently require status updates.");
}
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
  $Marketplace    = $UserSettings['AmzFBA_Marketplace'];
  $MerchantID     = $UserSettings['AmzFBA_MerchantID'];
  //$MarketplaceID  = $UserSettings[''];
  $AWSAccessKeyID = $UserSettings['AmzFBA_AWSAccessKeyID'];
  $SecretKey      = $UserSettings['AmzFBA_SecretKey'];
  if ($Marketplace == '' || $MerchantID == '' || $AWSAccessKeyID == '' | $SecretKey == '') {
      return 'missingconfig';
  } else {
      return '';
  }
}
?>
