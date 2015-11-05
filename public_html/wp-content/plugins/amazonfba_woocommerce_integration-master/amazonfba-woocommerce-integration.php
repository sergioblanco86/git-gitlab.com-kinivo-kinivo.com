<?php

/*
Plugin Name: Amazon FBA WooCommerce Integration
Plugin URI: http://www.fulfilr.co
Description: Integrates Amazon Fulfilment with WooCommerce for automatic fulfilment of orders & inventory synchronisation.
Author: Matt Horner
Version: 2.12
Author URI: http://www.matthorner.co.uk
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function woocommerce_not_installed_notice(){
	?>
		<div class="error">
				<p>Amazon FBA Integration Plugin <strong>requires</strong> Woocommerce to be installed first.</p>
		</div>
	<?php
}
function Amz2Woo_settings_required(){
	?>
                <div class="error">
                                <p>Amazon FBA Integration Plugin <strong>requires</strong> Amazon MWS credentials to be completed on the settings page to function correctly.</p>
                </div>
	<?php
}
/**
 * Check if WooCommerce is active
 **/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Put your plugin code here

/**
* Include require abstracted files.
*/
function IncludeAmzFba2WooFiles(){
	include 'includes/lib/amazon/FBAInventoryServiceMWS.php';
	include 'includes/lib/amazon/FBAOutboundServiceMWS.php';
	include 'includes/functions-woocommerce.php';
	include 'includes/functions-amazonfba.php';
}
        include 'includes/hooks.php';
        include 'includes/tasks.php';
        include 'includes/orders.php';
add_action( 'woocommerce_init', 'IncludeAmzFba2WooFiles' );
/**
* Settings Page (via Woocommerce Integration API)
*/
class AmazonFBA_WC_Integration {

	/**
	* Construct the plugin.
	*/
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	* Initialize the plugin.
	*/
	public function init() {

		// Checks if WooCommerce is installed.
		if ( class_exists( 'WC_Integration' ) ) {
			// Include our integration class.
			include_once 'includes/wc-settings-integration.php';

			// Register the integration.
			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
		} else {
			add_action( 'admin_notices', 'woocommerce_not_installed_notice' );
		}
	}

	/**
	 * Add a new integration to WooCommerce.
	 */
	public function add_integration( $integrations ) {
		$integrations[] = 'AmazonFBA_WC_Integration_Settings';
		return $integrations;
	}

}

$AmzFBA_WC_Integration = new AmazonFBA_WC_Integration( __FILE__ );
/**
* Plugin Activation
*/
register_activation_hook(__FILE__, 'Install_Default_Cron');
function Install_Default_Cron(){
		$settings = get_option('woocommerce_amazonfba_settings');
		if(empty($settings['AmzFBA_Schedule_Inv']) || !isset($settings['AmzFBA_Schedule_Inv'])){
			$settings['AmzFBA_Schedule_Inv'] = '30';
		}
		if(empty($settings['AmzFBA_Schedule_Ord']) || !isset($settings['AmzFBA_Schedule_Ord'])){
			$settings['AmzFBA_Schedule_Ord'] = '60';
		}
		if(empty($settings['AmzFBA_Schedule_Tra']) || !isset($settings['AmzFBA_Schedule_Tra']) ){
			$settings['AmzFBA_Schedule_Tra'] = '120';
		}
		update_option('woocommerce_amazonfba_settings', $settings);
}

/*
* Plugin De-Activation
*/
//Remove tasks
register_deactivation_hook(__FILE__, 'Deactivated_Remove_Tasks');
function Deactivated_Remove_Tasks()
{
		wp_clear_scheduled_hook('Woo_AmazonFBA_Retrieve_Inventory');
		wp_clear_scheduled_hook('Woo_AmazonFBA_Update_Order_Statuses');
		wp_clear_scheduled_hook('Woo_AmazonFBA_Get_Tracking_Info');
}
/**
* Products Mapping Page
*/
function AmzFBA_Woo_ProductsPage(){
	include 'includes/products.php';
}
function AmzFBA_Woo_MenuItems()
{
		add_submenu_page("edit.php?post_type=product", "Amazon FBA", "Amazon FBA", 10, "AmazonFBA", "AmzFBA_Woo_ProductsPage");
}
add_action('admin_menu', 'AmzFBA_Woo_MenuItems');
}else{
	add_action( 'admin_notices', 'woocommerce_not_installed_notice' );
}

?>
