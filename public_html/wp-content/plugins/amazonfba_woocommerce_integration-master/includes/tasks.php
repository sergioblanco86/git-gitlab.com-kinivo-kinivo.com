<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
* Function to add manaul user schedules for sync of data.
*/
function AmzFBA_cron_tasks($schedules)
{
    $settings = get_option('woocommerce_amazonfba_settings');
    $Inventory = $settings['AmzFBA_Schedule_Inv'] * 60;
    $schedules['AmazonFBA_Inventory'] = array(
        'interval' => $Inventory,
        'display' => __('Every ' . $settings['AmzFBA_Schedule_Inv'] . ' Minutes')
    );
    $OrderStatus = $settings['AmzFBA_Schedule_Ord'] * 60;
    $schedules['AmazonFBA_OrderStatus'] = array(
        'interval' => $OrderStatus,
        'display' => __('Every ' . $settings['AmzFBA_Schedule_Ord'] . ' Minutes')
    );
    $TrackingInfo = $settings['AmzFBA_Schedule_Tra'] * 60;
    $schedules['AmazonFBA_TrackingInfo'] = array(
        'interval' => $TrackingInfo,
        'display' => __('Every ' . $settings['AmzFBA_Schedule_Tra'] . ' Minutes')
    );
    return $schedules;
}
function Amz_cron_jobs(){
  wp_schedule_event(time(), 'AmazonFBA_Inventory', 'Woo_AmazonFBA_Retrieve_Inventory');
  wp_schedule_event(time()+120, 'AmazonFBA_OrderStatus', 'Woo_AmazonFBA_Update_Order_Statuses');
  wp_schedule_event(time()+240, 'AmazonFBA_TrackingInfo', 'Woo_AmazonFBA_Get_Tracking_Info');
}
 add_action('Woo_AmazonFBA_Retrieve_Inventory', 'ListInventorySupply');
 add_action('Woo_AmazonFBA_Update_Order_Statuses', 'Amz_FBA_Check_If_Orders_Need_Confirming');
 add_action('Woo_AmazonFBA_Get_Tracking_Info','Amz_FBA_Check_If_Orders_Need_Tracking');

function Amz_cron_jobs_clear(){
  wp_clear_scheduled_hook( 'Woo_AmazonFBA_Retrieve_Inventory' );
  wp_clear_scheduled_hook( 'Woo_AmazonFBA_Update_Order_Statuses' );
  wp_clear_scheduled_hook( 'Woo_AmazonFBA_Get_Tracking_Info' );
}
