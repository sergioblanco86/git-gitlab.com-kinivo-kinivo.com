<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/////////////////////// Function List //////////////////////
//
//////// Inventory Service
//
// Get Service Status
// List Inventory Supply
// List Inventory Supply (ByNextToken)
//
//////// OutboundShipment Service
//
// Get Service Status
// Create Amazon Fulfilment Order
// Get Amazon Fulfilment Order Information
// Get Amazon Fulfilment Tracking Information
//
////////////////////////////////////////////////////////////
/////////////////Retreive Users Options
$UserSettings   = get_option('woocommerce_amazonfba_settings');
if(!isset($UserSettings['AmzFBA_Marketplace']) || ($UserSettings['AmzFBA_Marketplace'] == '') ||
   !isset($UserSettings['AmzFBA_MerchantID']) || ($UserSettings['AmzFBA_MerchantID'] == '') ||
   !isset($UserSettings['AmzFBA_AWSAccessKeyID']) || ($UserSettings['AmzFBA_AWSAccessKeyID'] == '') ||
   !isset($UserSettings['AmzFBA_SecretKey']) || ($UserSettings['AmzFBA_SecretKey'] == '')
){
	add_action( 'admin_notices', 'Amz2Woo_settings_required' );
}else{
	$Marketplace    = $UserSettings['AmzFBA_Marketplace'];
	$MerchantID     = $UserSettings['AmzFBA_MerchantID'];
	//$MarketplaceID  = $UserSettings[''];
	$AWSAccessKeyID = $UserSettings['AmzFBA_AWSAccessKeyID'];
	$SecretKey      = $UserSettings['AmzFBA_SecretKey'];
/////////////////Define Options For Amazon FBA
define('ACCESS_KEY_ID', $AWSAccessKeyID);
define('SECRET_ACCESS_KEY', $SecretKey);
define('APPLICATION_NAME', 'Amazon FBA WooCommerce Integration');
define('APPLICATION_VERSION', '2010-10-01');
define('SELLER_ID', $MerchantID);
}
/////////////////Function to Get Correct Endpoint
function GetMWSEndpointURL($Endpoint, $WhichMarketplace)
{
    switch ($Endpoint) {
        case 'FulfillmentInventory':
            $EndpointURL = '/FulfillmentInventory/2010-10-01/';
            break;
        case 'FulfillmentOutboundShipment':
            $EndpointURL = '/FulfillmentOutboundShipment/2010-10-01/';
            break;
        default:
            $EndpointURL = '';
    }
    switch ($WhichMarketplace) {
        case 'US':
            $MarketplaceURL = 'mws.amazonservices.com';
            break;
        case 'UK':
            $MarketplaceURL = 'mws.amazonservices.co.uk';
            break;
        case 'Germany':
            $MarketplaceURL = 'mws.amazonservices.de';
            break;
        case 'France':
            $MarketplaceURL = 'mws.amazonservices.fr';
            break;
        case 'Japan':
            $MarketplaceURL = 'mws.amazonservices.jp';
            break;
        case 'China':
            $MarketplaceURL = 'mws.amazonservices.com.cn';
            break;
        case 'Italy':
            $MarketplaceURL = 'mws.amazonservices.it';
            break;
        default:
            $MarketplaceURL = '';
    }
    return $CompleteEndpointURL = 'https://' . $MarketplaceURL . $EndpointURL;
}

////////////////////////////////////////////
// Send email to alert that order is on hold
//
function SendOnHoldEmail($order_id)
{
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $toEmailAddr = $UserSettings['AmzFBA_notif_email_addr'];
    $level = "Neutral";
    $category = "Order";
    $title = "On Hold";
    if (($toEmailAddr == NULL) || ($toEmailAddr == "")) {
        $toEmailAddr = "sharad@kinivo.com,kinivo-website-sales@kinivo.com";
    }
    AmzFBA_Woo_Log($level, $category, $title, "Sending email to " . $toEmailAddr);
    if (!wp_mail ($toEmailAddr,"[HOTLINE] Kinivo.com order on hold","Kinivo.com order id: " . $order_id . " is On Hold. Details at http://" . $_SERVER['SERVER_NAME'] . "/wp-admin/post.php?action=edit&post=" . $order_id)) {
        AmzFBA_Woo_Log($level, $category, $title, "Error in sending cancellation email to: " . $toEmailAddr);
    }
}


////////////////////////////////////////////
// Send email to alert that order is on hold
//
function SendErrorEmail($category, $msg)
{
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $toEmailAddr = $UserSettings['AmzFBA_notif_email_addr'];
    $level = "Bad";
    $category = "Order";
    $title = "Error email";
    if (($toEmailAddr == NULL) || ($toEmailAddr == "")) {
        $toEmailAddr = "sharad@kinivo.com,kinivo-website-sales@kinivo.com";
    }
    AmzFBA_Woo_Log($level, $category, $title, "Sending email to " . $toEmailAddr . " Error msg = " . $msg);
    $email_subject = "[HOTLINE] Error in woocommerce - " . $category;
    if (!wp_mail ($toEmailAddr,$email_subject,$msg)) {
        AmzFBA_Woo_Log($level, $category, $title, "Error in sending email to: " . $toEmailAddr);
    }
}

function HandleGenericException($category,$ex)
{
    $level    = "Bad";
    $title    = "Exception";
    $info     = "Caught Exception: " . $ex->getMessage() ; 
    AmzFBA_Woo_Log($level, $category, $title, $info);
    SendErrorEmail ($info);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////AMAZON Inventory Functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
/////////////////Get Service Status
////////////////////////////////////////////////////////////
function GetServiceStatusInventory()
{
    AmzFBA_Woo_Log("DEBUG","Inventory",__FUNCTION__,"Entering");
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL    = GetMWSEndpointURL("FulfillmentInventory", $ChosenMarketplace);
    $config            = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service           = new FBAInventoryServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);
    $request           = new FBAInventoryServiceMWS_Model_GetServiceStatusRequest();
    $request->setSellerId(SELLER_ID);
    $returnmessage = invokeGetServiceStatusInventory($service, $request);
    AmzFBA_Woo_Log("DEBUG","Inventory",__FUNCTION__,"Exiting");
    return $returnmessage;
}
function invokeGetServiceStatusInventory(FBAInventoryServiceMWS_Interface $service, $request)
{
    try {
        $response = $service->getServiceStatus($request);
        if ($response->isSetGetServiceStatusResult()) {
            $getServiceStatusResult = $response->getGetServiceStatusResult();
            if ($getServiceStatusResult->isSetStatus()) {
                $theResult = $getServiceStatusResult->getStatus();
                if ($theResult == "GREEN") {
                    $level = "Neutral";
                } else {
                    $level = "Bad";
                }
                $category = "Service Status";
                $title    = "Inventory Service Status";
                AmzFBA_Woo_Log($level, $category, $title, $theResult);
            }
        }
    }
    catch (FBAInventoryServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Service Status";
        $title    = "Inventory Service Error";
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
        $badResult = "Error getting service status - please see logs. ";
        SendErrorEmail ($category,$info);
    }
    catch (Exception $e) {
        HandleGenericException ("Service Status", $e);
    }

    if (isset($theResult)) {
        $returnmessage = "Current service status: " . $theResult;
    } elseif (isset($badResult)) {
        $returnmessage = $badResult;
    } else {
        $returnmessage = "Could not initiate 'GetServiceStatus'";
    }
    return $returnmessage;
}
////////////////////////////////////////////////////////////
/////////////////List Inventory Supply
////////////////////////////////////////////////////////////
function ListInventorySupply()
{
    AmzFBA_Woo_Log("DEBUG","Inventory",__FUNCTION__,"Entering");
    $MissingConfig = CheckForMissingConfig(); 
    if ($MissingConfig == true) {
        $returnmessage = 'Inventory retrieval failed. Amazon FBA configuration is not completed in your settings.';
        $level         = "Bad";
        $category      = "Inventory";
        $title         = "Failed - Update Inventory";
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL    = GetMWSEndpointURL('FulfillmentInventory', $ChosenMarketplace);
    $config            = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service           = new FBAInventoryServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);
    $request           = new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest();
    $request->setSellerId(SELLER_ID);
    $request->setQueryStartDateTime(2001 - 10 - 10);
    invokeListInventorySupply($service, $request);
    $returnmessage = "Retrieving inventory from Amazon process begun. Please see logs for progress.";
    AmzFBA_Woo_Log("DEBUG","Inventory",__FUNCTION__,"Exiting");
    return $returnmessage;
}
function invokeListInventorySupply(FBAInventoryServiceMWS_Interface $service, $request)
{
    try {
        $response = $service->listInventorySupply($request);
        if ($response->isSetListInventorySupplyResult()) {
            $listInventorySupplyResult = $response->getListInventorySupplyResult();
            if ($listInventorySupplyResult->isSetInventorySupplyList()) {
                $inventorySupplyList = $listInventorySupplyResult->getInventorySupplyList();
                $memberList          = $inventorySupplyList->getmember();
                $countResult         = count($memberList);
                $level               = "Neutral";
                $category            = "Inventory";
                $title               = "Update Inventory";
                if ($listInventorySupplyResult->isSetNextToken()) {
                    $info = $countResult . " Products Retrieved, Getting more with next token...";
                } else {
                    $info = $countResult . " Products Retrieved.";
                }
                AmzFBA_Woo_Log($level, $category, $title, $info);
                foreach ($memberList as $member) {
                    $inventory_level = $member->getInStockSupplyQuantity();
                    $sku             = $member->getSellerSKU();
                    AmzFBA_Woo_install_data($sku, $inventory_level);
                    $ProductID = GetIdFromSKU($sku);
                    if ($ProductID != '') {
                        // Sharad: adding logs
                        AmzFBA_Woo_Log($level, $category, $title, "New level for [" . $sku . "] is " . $inventory_level);
                        wc_update_product_stock($ProductID, $inventory_level);
                    }
                }
            }
            if ($listInventorySupplyResult->isSetNextToken()) {
                $Token = $listInventorySupplyResult->getNextToken();
                ListInventorySupplyByNextToken($Token);
            }
        }
    }
    catch (FBAInventoryServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Inventory";
        $title    = "Failed - Update Inventory";
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
        SendErrorEmail ($category, $info);
    }
    catch (Exception $e) {
        HandleGenericException ("Inventory",$e);
    }
}
////////////////////////////////////////////////////////////
/////////////////List Inventory Supply (By Next Token)
////////////////////////////////////////////////////////////
function ListInventorySupplyByNextToken($Token)
{
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL    = GetMWSEndpointURL('FulfillmentInventory', $ChosenMarketplace);
    $config            = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service           = new FBAInventoryServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);
    $request           = new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest();
    $request->setSellerId(SELLER_ID);
    $request->setNextToken($Token);
    invokeListInventorySupplyByNextToken($service, $request);
}
function invokeListInventorySupplyByNextToken(FBAInventoryServiceMWS_Interface $service, $request)
{
    try {
        $response = $service->listInventorySupplyByNextToken($request);
        if ($response->isSetListInventorySupplyByNextTokenResult()) {
            $listInventorySupplyByNextTokenResult = $response->getListInventorySupplyByNextTokenResult();
            if ($listInventorySupplyByNextTokenResult->isSetInventorySupplyList()) {
                $inventorySupplyList = $listInventorySupplyByNextTokenResult->getInventorySupplyList();
                $memberList          = $inventorySupplyList->getmember();
                $countResult         = count($memberList);
                $level               = "Neutral";
                $category            = "Inventory";
                $title               = "Update Inventory (Next Token)";
                if ($listInventorySupplyByNextTokenResult->isSetNextToken()) {
                    $info = $countResult . " Products Retrieved, Getting more with next token...";
                } else {
                    $info = $countResult . " Products Retrieved.";
                }
                AmzFBA_Woo_Log($level, $category, $title, $info);
                foreach ($memberList as $member) {
                    $inventory_level = $member->getInStockSupplyQuantity();
                    $sku             = $member->getSellerSKU();
                    AmzFBA_Woo_install_data($sku, $inventory_level);
                    $ProductID = GetIdFromSKU($sku);
                    if ($ProductID != '') {
                        // Sharad: adding logs
                        AmzFBA_Woo_Log($level, $category, $title, "New level for [" . $sku . "] is " . $inventory_level);
                        wc_update_product_stock($ProductID, $inventory_level);
                    }
                }
            }
            if ($listInventorySupplyByNextTokenResult->isSetNextToken()) {
                $NextToken = $listInventorySupplyByNextTokenResult->getNextToken();
                ListInventorySupplyByNextToken($NextToken);
            }
        }
    }
    catch (FBAInventoryServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Inventory";
        $title    = "Retrieve Inventory Error (Next Token)";
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
        SendErrorEmail ($category,$info);
    }
    catch (Exception $e) {
        HandleGenericException ("Inventory",$e);
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////AMAZON Outbound Service Functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
/////////////////Get Service Status
////////////////////////////////////////////////////////////
function GetServiceStatusOutboundShipment()
{
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL    = GetMWSEndpointURL("FulfillmentOutboundShipment", $ChosenMarketplace);
    $config            = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service           = new FBAOutboundServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);
    $request           = new FBAOutboundServiceMWS_Model_GetServiceStatusRequest();
    $request->setSellerId(SELLER_ID);
    $returnmessage = invokeGetServiceStatusOutboundService($service, $request);
    return $returnmessage;
}
function invokeGetServiceStatusOutboundService(FBAOutboundServiceMWS_Interface $service, $request)
{
    try {
        $response = $service->getServiceStatus($request);
        if ($response->isSetGetServiceStatusResult()) {
            $getServiceStatusResult = $response->getGetServiceStatusResult();
            if ($getServiceStatusResult->isSetStatus()) {
                $theResult = $getServiceStatusResult->getStatus();
                if ($theResult == "GREEN") {
                    $level = "Neutral";
                } else {
                    $level = "Bad";
                }
                $category = "Service Status";
                $title    = "Outbound Shipment Service Status";
                AmzFBA_Woo_Log($level, $category, $title, $theResult);
            }
        }
    }
    catch (FBAOutboundServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Service Status";
        $title    = "Outbound Shipment Service Error";
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
        $badResult = "Error getting service status - please see logs. ";
        SendErrorEmail ($category,$info);
    }
    catch (Exception $e) {
        HandleGenericException ("Service Status",$e);
    }
    if (isset($theResult)) {
        $returnmessage = "Current service status: " . $theResult;
    } elseif (isset($badResult)) {
        $returnmessage = $badResult;
    } else {
        $returnmessage = "Could not initiate 'GetServiceStatus'";
    }
    return $returnmessage;
}
////////////////////////////////////////////////////////////
/////////////////Create Amazon Fulfilment Order
////////////////////////////////////////////////////////////
function CreateAmzFBAOrder($order_id)
{
    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Entering");
    $MissingConfig = CheckForMissingConfig();
    $level = "Good";
    $category = "Order";
    if ($MissingConfig == true) {
        $returnmessage = 'Failed sending order to Amazon FBA. Required configuration missing in settings.';
        $order->add_order_note($returnmessage, 0);
        //Add to Log
        $level    = "Bad";
        $category = "Order";
        $title    = "Failed - Order ID:" . $order_id;
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }

    $FBAOrderID = get_post_meta($order_id, "FBA_OrderId", TRUE);
    if (!empty($FBAOrderID) && $FBAOrderID != "") {
        $returnmessage = 'Create FBA Order: FBA Order id is already there: ' .  $FBAOrderID;
        $order->add_order_note($returnmessage, 0);
        //Add to Log
        $level    = "Bad";
        $title    = "Create FBA Order - Order ID:" . $order_id;
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }

    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL           = GetMWSEndpointURL('FulfillmentOutboundShipment', $ChosenMarketplace);
    $config                   = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service                  = new FBAOutboundServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);
    $order                    = new WC_Order($order_id);
    $dateObj                  = new DateTime($order->order_date, new DateTimeZone('UTC'));
    $order_date_converted     = $dateObj->format(DateTime::ISO8601);
    $datetimeasINT            = date("YmdHis");
    $uniqueid_foramazon       = "KINIVO-" . $datetimeasINT . "-" . $order_id;
    AmzFBA_Woo_Log($level,$category,$title,"FBA order id - " . $uniqueid_foramazon);
    //set Variables
    $shipping_name            = $order->shipping_first_name . ' ' . $order->shipping_last_name;
    $shipping_address_line_1  = $order->shipping_address_1;
    $shipping_address_line_2  = $order->shipping_address_2;
    $shipping_city            = $order->shipping_city;
    $shipping_state           = $order->shipping_state;
    $shipping_postcode        = $order->shipping_postcode;
    $shipping_country         = $order->shipping_country;
    $shipping_speed           = GetAmazonShippingSpeed($order);

    $UserSettings             = get_option('woocommerce_amazonfba_settings'); //Gets all settings from this App
    $FulfillmentPolicy        = $UserSettings['AmzFBA_FulfillmentPolicy'];
    $OrderComment             = $UserSettings['AmzFBA_OrderComment'];
    $EmailNotificationAddress = $UserSettings['AmzFBA_EmailNotifyAddress'];


    ////////////////////////////// //Standard Stuff
    $request                  = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest();
    $request->setSellerId(SELLER_ID);
    $request->setSellerFulfillmentOrderId($uniqueid_foramazon);
    $request->setDisplayableOrderId($order_id);
    $request->setDisplayableOrderDateTime($order_date_converted);
    $request->setDisplayableOrderComment($OrderComment);
    //     $request->setNotificationEmailList($EmailNotificationAddress);
    $request->setShippingSpeedCategory($shipping_speed); //Standard, Expedited, Priority
    $request->setFulfillmentPolicy($FulfillmentPolicy); //FillOrKill, FillAll, FillAllAvailable
    ////////////////////////////// //Address
    $requestaddress = new FBAOutboundServiceMWS_Model_Address();
    $requestaddress->setName($shipping_name);
    $requestaddress->setLine1($shipping_address_line_1);
    $requestaddress->setLine2($shipping_address_line_2);
    $requestaddress->setLine3('');
    $requestaddress->setCity($shipping_city);
    $requestaddress->setDistrictOrCounty($shipping_country);
  if($shipping_state != ''){
      $requestaddress->setStateOrProvinceCode($shipping_state);
    }
    $requestaddress->setCountryCode($shipping_country);
    $requestaddress->setPostalCode($shipping_postcode);
    $requestaddress->setPhoneNumber('');
    $request->setDestinationAddress($requestaddress);
    ////////////////////////////// //Order Item List
    $requestlist = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList();
    $request->setItems($requestlist);
    ////////////////////////////// //Order Items
    $items              = $order->get_items();
    $itemnumber         = 0;
    $itemnumber_unful   = 0;
    $UnfulfillableItems = '';
    $success            = 0;
    $unsuccess          = 0;
    foreach ($items as $item) {
        //Get the SKU of the Item
        $Quantity = $item['qty'];
        if (($item['variation_id'] == '') || ($item['variation_id'] == NULL) || ($item['variation_id'] == '0')) {
            $IDForSKU = $item['product_id'];
        } else {
            $IDForSKU = $item['variation_id'];
        }
        $GetSKU = new WC_Product($IDForSKU);
        $SKU    = $GetSKU->get_sku();
        //Check if available for Amazon Fulfilment
        $fulfillable = false;
        if ((AmzFBA_is_sku_fulfillable($SKU)) == NULL || (AmzFBA_is_sku_fulfillable($SKU)) == '') {
            $UnfulfillableItems[$itemnumber_unful]['SKU']      = $SKU;
            $UnfulfillableItems[$itemnumber_unful]['Quantity'] = $Quantity;
            $unsuccess                                   = $unsuccess + $Quantity;
            $itemnumber_unful++;
            $note = "SKU : IDForSKU = " . $IDForSKU . " SKU = " . $SKU . " is not fulfillable by Amazon. Please check product details";
            $order->add_order_note($note, 0);
        } else {
            $ItemArray[$itemnumber] = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem();
            $ItemArray[$itemnumber]->setSellerSKU($SKU);
            $ItemArray[$itemnumber]->setSellerFulfillmentOrderItemId($itemnumber);
            $ItemArray[$itemnumber]->setQuantity($Quantity);
            $success = $success + $Quantity;
            $itemnumber++;
            $fulfillable = true;
        }
        $level    = "Neutral";
        $category = "Order";
        $title    = "Item details for Order ID:" . $order_id;
        $info     = "SKU details: IDForSKU = " . $IDForSKU . " SKU = " . $SKU . " Fulfillable = " . $fulfillable;
        AmzFBA_Woo_Log($level, $category, $title, $info);
    }
    $percentfulfilled = round((($success / ($success + $unsuccess)) * 100), 2);
    //Load Array of Items into Order
    if($percentfulfilled != 0){
      $requestlist->setmember($ItemArray);
    }
    //Check if we now need to fulfil them.
    //If 0% fulfillled
    if ($percentfulfilled == '0') {
        $returnmessage = "Order not submitted. No SKUs in WooCommerce available on Amazon FBA";
        $note          = "No SKUs in this order available for Amazon Fulfillment.";
        $order->add_order_note($note, 0);
        $order->update_status('on-hold');
        update_post_meta($order_id, 'PercentFulfilledByAmazon', $percentfulfilled);
        //Add to Log
        $level    = "Neutral";
        $category = "Order";
        $title    = "Unfulfillable - Order ID:" . $order_id;
        $info     = "No SKUs in this order available for Amazon Fulfillment.";
        AmzFBA_Woo_Log($level, $category, $title, $info);
    }
    //If 1-99% fulfilled
    elseif ($percentfulfilled < '100' && $percentfulfilled > '0') {
        $returnmessage = "Order not submitted. Only " . $percentfulfilled . "% of the Order is available on Amazon. Did not send anything to Amazon."; 
        $note = $returnmessage;
        $order->add_order_note($note, 0);
        $order->update_status('on-hold');
        //Add to Log
        $level    = "Neutral";
        $category = "Order";
        $title    = "Unfulfillable - Order ID:" . $order_id;
        $info     = "Only some sku's were available for Amazon Fulfillment. Did not send anything to Amazon.";
        AmzFBA_Woo_Log($level, $category, $title, $info);

        /*
        $returnmessage = "Order submitted. Sent " . $percentfulfilled . "% of the Order to Amazon. For unfulfillable items, see order notes.";
        $note          = $percentfulfilled . "% of Order sent to Amazon for Fulfillment : FBA_OrderId " . $uniqueid_foramazon;
        $order->add_order_note($note, 0);
        foreach ($UnfulfillableItems as $UnfilItem) {
            $theitems .= $UnfilItem['Quantity'] . " X " . $UnfilItem['SKU'] . ".  ";
        }
        $note = "Unfulfillable Items : " . $theitems;
        $order->add_order_note($note, 0);
        update_post_meta($order_id, 'PercentFulfilledByAmazon', $percentfulfilled);
        //Add meta data of Unique ID sent to Amazon
        update_post_meta($order_id, 'FBA_OrderId', $uniqueid_foramazon);
        //Send Order to Amazon
        invokeCreateFulfillmentOrder($service, $request, $order_id, $percentfulfilled);
        */
    }
    //If 100% fulfilled
    elseif ($percentfulfilled == '100') {
        $returnmessage = "Order submitted. Sent" . $percentfulfilled . "% of Order to Amazon.";
        $note          = "100% of Order sent to Amazon for Fulfillment: FBA_OrderId " . $uniqueid_foramazon;
        $order->add_order_note($note, 0);
        update_post_meta($order_id, 'PercentFulfilledByAmazon', $percentfulfilled);
        //Add meta data of Unique ID sent to Amazon
        update_post_meta($order_id, 'FBA_OrderId', $uniqueid_foramazon);
        //Send Order to amazon
        if (!invokeCreateFulfillmentOrder($service, $request, $order_id, $percentfulfilled)) {
            // It seems creating FBA order failed... Putting the order on hold
            $note = "FBA order failed. Check logs and sellercentral for order id - " . $uniqueid_foramazon;
            $returnmessage = $note;
            $order->add_order_note($note, 0);
            $order->update_status('on-hold');
            AmzFBA_Woo_Log("Bad", $category, "FBA order creation", "Failed - not sure what happened. Check sellercentral as well");
        }
    }

    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Exiting");
    return $returnmessage;
}

function invokeCreateFulfillmentOrder(FBAOutboundServiceMWS_Interface $service, $request, $order_id, $percentfulfilled)
{
    try {
        $response = $service->createFulfillmentOrder($request);
        if ($response->isSetResponseMetadata()) {
            $responseMetadata = $response->getResponseMetadata();
            if ($responseMetadata->isSetRequestId()) {
                $RequestID = $responseMetadata->getRequestId();
                update_post_meta($order_id, 'FBA_Status', 'False');
                $order = new WC_Order($order_id);
                $order->update_status("processing");
                $note  = "Order submitted. Amazon Request ID:" . $RequestID;
                $order->add_order_note($note, 0);
                if ($percentfulfilled == '100') {
                    $level = "Good";
                    // Sharad (June 25, 2015): Not sure why we were completing the order here (before its shipped by Amazon)
                    // commenting the update_status for now
                    // $order->update_status("completed");
                } else {
                    $level = "Neutral";
                }
                $category = "Order";
                $title    = "Sent to Amazon - Order ID:" . $order_id;
                $info     = $percentfulfilled . " Percent Fulfilled | RequestID " . $RequestID . " | Response Meta Data : " . $ResponseMetaData;
                AmzFBA_Woo_Log($level, $category, $title, $info);
                return true;
            }
        }
        return false;
    }
    catch (FBAOutboundServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        //Add some logging (if it's switched on)
        $level    = "Bad";
        $category = "Order";
        $title    = "Export Error - Order ID:" . $order_id;
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
        //Lets put the order on hold as we have an issue with it.
        $order = new WC_Order($order_id);
        $order->update_status('on-hold');
        //Now lets Add a messsage to the order
        $note  = "Error submitting order to Amazon. Error Message:" . $ex->getMessage() . ' . Error Code:' . $ex->getErrorCode();
        $order->add_order_note($note, 0);

        SendErrorEmail ($category,$info);
    }
    catch (Exception $e) {
        HandleGenericException ("Order",$e);
        //Lets put the order on hold as we have an issue with it.
        $order = new WC_Order($order_id);
        $order->update_status('on-hold');
        //Now lets Add a messsage to the order
        $note  = "Error submitting order to Amazon. Error Message:" . $ex->getMessage() . ' . Error Code:' . $ex->getErrorCode();
        $order->add_order_note($note, 0);
    }
}

//
// Get Shipping speed to be used for the Amazon shipment
// based on what user selected for our order
//
function GetAmazonShippingSpeed($order)
{
    $wooShippingSpeed = $order->get_shipping_method();
    $words = explode(" ", $wooShippingSpeed);
    $level = "Good";
    $category = "Order";
    $title    = "Shipping speed " . $order->get_order_number();
    AmzFBA_Woo_Log($level, $category, $title, "1st word =  " . $words[0]);

    $fbaShippingSpeed = "Standard";  // default value
    switch ($words[0]) {
        case 'Expedited':
            $fbaShippingSpeed = "Expedited";
            break;
        case 'Priority':
            $fbaShippingSpeed = "Priority";
            break;
    }
    AmzFBA_Woo_Log($level, $category, $title, "Amazon shipping speed = " . $fbaShippingSpeed);
    return $fbaShippingSpeed;
}

////////////////////////////////////////////////////////////
// Cancel Amazon Fulfillment (FBA) order
// Input: order_id = Woocommerce Order id
//
function CancelAmzFBAOrder($order_id)
{
    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Entering");
    $level = "Neutral";
    $category = "Order";
    $title = "FBA Cancel Order - Woo ID:" . $order_id;
    AmzFBA_Woo_Log($level, $category, $title, "Canceling FBA order for Woo id " . $order_id);

    $MissingConfig = CheckForMissingConfig(); 
    if ($MissingConfig == true) {
        $returnmessage = 'Cancel FBA Order: Required configuration missing in Woo settings.';
        $order->add_order_note($returnmessage, 0);
        //Add to Log
        $level    = "Bad";
        $title    = "Cancel FBA Order - Order ID:" . $order_id;
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }
    
    $FBAOrderID = get_post_meta($order_id, "FBA_OrderId", TRUE);
    AmzFBA_Woo_Log($level, $category, $title, "FBA order id = " . $FBAOrderID);
    if (empty($FBAOrderID) || $FBAOrderID == "") {
        $returnmessage = 'Cancel FBA Order: FBA Order id is missing.';
        $order->add_order_note($returnmessage, 0);
        //Add to Log
        $level    = "Bad";
        $title    = "Cancel FBA Order - Order ID:" . $order_id;
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }

    $FBA_Status = get_post_meta($order_id, "FBA_Status", TRUE);
    AmzFBA_Woo_Log($level, $category, $title, "FBA  status = " . $FBA_Status);

/*
    $fba_status_cancelable={'RECEIVED','PLANNING','PROCESSING','COMPLETED'};

    if (!in_array($FBA_Status, $fba_status_cancelable)) {
        $returnmessage = 'Cancel FBA Order: FBA Status is not cancelable';
        $order->add_order_note($returnmessage, 0);
        //Add to Log
        $level    = "Bad";
        $title    = "Cancel FBA Order - Order ID:" . $order_id;
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }
*/
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL           = GetMWSEndpointURL('FulfillmentOutboundShipment', $ChosenMarketplace);
    $config = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service = new FBAOutboundServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);
    $request = new FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest();
    $request->setSellerId(SELLER_ID);
    $request->setSellerFulfillmentOrderId($FBAOrderID);
    AmzFBA_Woo_Log($level, $category, $title, "Calling Amazon to cancel FBA order id " . $FBAOrderID);
    invokeCancelFulfillmentOrder ($service, $request, $order_id, $FBAOrderID);
    update_post_meta($order_id, "FBA_Status", "Cancelling");
    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Exiting");
}

////////////////////////////////////////////////////////////
// Invoke Cancel FBA order : Call MWS
//
function invokeCancelFulfillmentOrder(FBAOutboundServiceMWS_Interface $service, $request, $order_id, $FBAOrderID)
{
    $order = new WC_Order($order_id);
    try {
        $response = $service->cancelFulfillmentOrder($request);
        // cancel fulfillment does not return anything.
        $level = "Neutral";
        $category = "Order";
        $title = "FBA Cancel Order - Woo ID:" . $order_id;
        $cancel_message = "Cancellation request sent to Amazon for FBAOrder id: " . $FBAOrderID;
        $order->add_order_note($cancel_message, 0);
        AmzFBA_Woo_Log($level, $category, $title, $cancel_message);
    }
    catch (FBAOutboundServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Order";
        $title    = "Cancel FBA order - Order ID:" . $order_id;
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        $order->add_order_note ("FBA order cancellation failed. Check logs", 0);
        AmzFBA_Woo_Log($level, $category, $title, $info);
        SendErrorEmail ("Order",$info);
    }
    catch (Exception $e) {
        $order->add_order_note ("FBA order cancellation failed. Check logs", 0);
        HandleGenericException ("Order",$e);
    }
}

////////////////////////////////////////////////////////////
/////////////////Get Amazon Fulfilment Order Information
////////////////////////////////////////////////////////////
function GetAmzFBAOrderDetails($order_id)
{
    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Entering");
    $level = "Neutral";
    $category = "Order";
    $title = "Order Status - ID:" . $order_id;
    $MissingConfig = CheckForMissingConfig(); 
    if ($MissingConfig == true) {
        $returnmessage = 'Failed getting order information from Amazon FBA. Required configuration missing in settings.';
        $order->add_order_note($returnmessage, 0);
        //Add to Log
        $level    = "Bad";
        $category = "Order";
        $title    = "Get Info Error - Order ID:" . $order_id;
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL    = GetMWSEndpointURL('FulfillmentOutboundShipment', $ChosenMarketplace);
    $config            = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service           = new FBAOutboundServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);

    $request = new FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest();
    $request->setSellerId(SELLER_ID);
    $FBAOrderID = get_post_meta($order_id, "FBA_OrderId", TRUE);
    if (empty ($FBAOrderID)) {
        AmzFBA_Woo_Log("Error", $category, __FUNCTION__, "Order does not have FBA Order id - putting it on hold");
        $order = new WC_Order($order_id);
        $order->update_status('on-hold');
        return;
    }
    $FBAStatus = get_post_meta($order_id, "FBA_Status", TRUE);
    if (!in_array(strtolower($FBAStatus), array('false','received','planning','processing','cancelling'))) {
        AmzFBA_Woo_Log("Error", $category, __FUNCTION__, "FBA Status = " . $FBAStatus . " is unexpected - putting order on hold");
        $order = new WC_Order($order_id);
        $order->update_status('on-hold');
        return;
    }
    AmzFBA_Woo_Log($level, $category, $title, "Checking order status for " . $order_id . " FBA order id = " . $FBAOrderID);
    $request->setSellerFulfillmentOrderId($FBAOrderID);
    invokeGetFulfillmentOrder($service, $request, $order_id);
}

function invokeGetFulfillmentOrder(FBAOutboundServiceMWS_Interface $service, $request, $order_id)
{
    $level = "Neutral";
    $category = "Order";
    $title = "Order Status - ID:" . $order_id;
    $order = new WC_Order($order_id);
    $check_order_items = true;
    try {
        $response = $service->getFulfillmentOrder($request);
        if ($response->isSetGetFulfillmentOrderResult()) {
            $getFulfillmentOrderResult = $response->getGetFulfillmentOrderResult();
            if ($getFulfillmentOrderResult->isSetFulfillmentOrder()) {
                $fulfillmentOrder = $getFulfillmentOrderResult->getFulfillmentOrder();
                if ($fulfillmentOrder->isSetFulfillmentOrderStatus()) {
                    $OrderStatus = $fulfillmentOrder->getFulfillmentOrderStatus();
                    AmzFBA_Woo_Log($level, $category, $title, "FBA Order status from MWS is " . $OrderStatus);
                    switch ($OrderStatus) {
                        case 'RECEIVED':
                            update_post_meta($order_id, "FBA_Status", "Received");
                            break;
                        case 'INVALID':
                            $note = "Amazon FBA Status - Invalid. No further action will be taken. Putting order on hold";
                            $order->add_order_note($note, 0);
                            update_post_meta($order_id, "FBA_Status", "Invalid");
                            $order->update_status('on-hold');
                            break;
                        case 'PLANNING':
                            update_post_meta($order_id, "FBA_Status", "Planning");
                            break;
                        case 'PROCESSING':
                            update_post_meta($order_id, "FBA_Status", "Processing");
                            break;
                        case 'CANCELLED':
                            $note = "Amazon FBA Status - Cancelled. No further action will be taken.";
                            $order->add_order_note($note, 0);
                            if (!update_post_meta($order_id, "FBA_Status", "Cancelled")) {
                                $level    = "Bad";
                                $category = "Order";
                                $title    = "Get order status - Order ID:" . $order_id;
                                AmzFBA_Woo_Log($level, $category, $title, "Error in updating FBA_Status");
                            }
                            $order->update_status('on-hold');
                            $check_order_items = false;
                            break;
                        case 'COMPLETE':
                            $percentfulfilled = get_post_meta($order_id, "PercentFulfilledByAmazon", true);
                            if ($percentfulfilled == '100') {
                                $orderComplete = true;
                                $note = "Amazon FBA Status - Complete. Entire order fulfilled successfully.";
                                $order->add_order_note($note, 0);
                            } else {
                                $note = "Amazon FBA Status - Complete WooCommerce Order status not changed to complete as not all of the order was sent to Amazon FBA.";
                                $order->add_order_note($note, 0);
                                // Putting order on hold
                                $order->update_status('on-hold');
                            }
                            update_post_meta($order_id, "FBA_Status", "Completed");
                            update_post_meta($order_id, "FBA_Tracking_ID", "Waiting");
                            break;
                        case 'COMPLETEPARTIALLED':
                            $note = "Amazon FBA Status - Partially Complete. Some items were cancelled or unfulfillable";
                            $order->add_order_note($note, 0);
                            // Putting order on hold
                            $order->update_status('on-hold');
                            update_post_meta($order_id, "FBA_Status", "Partially Complete");
                            update_post_meta($order_id, "FBA_Tracking_ID", "Unknown");
                            break;
                        case 'UNFULFILLABLE':
                            $note = "Amazon FBA Status - Unfulfillable. No further action will be taken.";
                            $order->add_order_note($note, 0);
                            update_post_meta($order_id, "FBA_Status", "Unfulfillable");
                            $order->update_status('on-hold');
                            break;
                    }
                }
            }
            if ($check_order_items && $getFulfillmentOrderResult->isSetFulfillmentOrderItem()) {
                $fulfillmentOrderItem = $getFulfillmentOrderResult->getFulfillmentOrderItem();
                $member1List          = $fulfillmentOrderItem->getmember();
                $note                 = "";
                foreach ($member1List as $member1) {
                    if ($member1->isSetUnfulfillableQuantity()) {
                        $UnfulfillableCount = $member1->getUnfulfillableQuantity();
                        $SKU                = $member1->getSellerSKU();
                        if ($UnfulfillableCount > 0) {
                            $note .= $UnfulfillableCount . " X " . $SKU . ".  ";
                        }
                    }
                }
                if ($note != '') {
                    $newnote = "Unfulfillable By AmazonFBA: " . $note;
                    $order->add_order_note($newnote, 0);
                }
            }
            if ($getFulfillmentOrderResult->isSetFulfillmentShipment()) {
                //echo ("                FulfillmentShipment\n");
                $fulfillmentShipment = $getFulfillmentOrderResult->getFulfillmentShipment();
                $member2List         = $fulfillmentShipment->getmember();
                foreach ($member2List as $member2) {
                    if ($member2->isSetFulfillmentShipmentStatus()) {
                        $ShipmentStatus = $member2->getFulfillmentShipmentStatus();
                        update_post_meta($order_id, "FBA_Shipment_Status", $ShipmentStatus);
                        AmzFBA_Woo_Log($level, $category, $title, "FBA Shipment status from MWS is " . $ShipmentStatus);
                    }
                    if ($member2->isSetShippingDateTime()) {
                        $ShippingDate = $member2->getShippingDateTime();
                        update_post_meta($order_id, "FBA_Shipping_Date", $ShippingDate);
                        // Update shipping date in plugin WooCommerce Shipment Tracking
                        update_post_meta($order_id, '_date_shipped', $ShippingDate);
                    }
                    if ($member2->isSetEstimatedArrivalDateTime()) {
                        $EstimatedArrival = $member2->getEstimatedArrivalDateTime();
                        update_post_meta($order_id, "FBA_Estimated_Arrival_Date", $EstimatedArrival);
                    }
                    if ($member2->isSetFulfillmentShipmentPackage()) {
                        $fulfillmentShipmentPackage = $member2->getFulfillmentShipmentPackage();
                        $member4List                = $fulfillmentShipmentPackage->getmember();
                        foreach ($member4List as $member4) {
                            if ($member4->isSetPackageNumber()) {
                                $PackageNumber = $member4->getPackageNumber();
                                update_post_meta($order_id, "FBA_Package_Number", $PackageNumber);
                            }
                            if ($member4->isSetCarrierCode()) {
                                $DeliveryCompany = $member4->getCarrierCode();
                                update_post_meta($order_id, "FBA_Carrier", $DeliveryCompany);
                                // Update shipping provider in plugin WooCommerce Shipment Tracking
                                update_post_meta($order_id, '_tracking_provider', $DeliveryCompany);
                                AmzFBA_Woo_Log($level, $category, $title, "FBA Shipment Carrier is " . $DeliveryCompany);
                            }
                            if ($member4->isSetTrackingNumber()) {
                                $TrackingIDNumber = $member4->getTrackingNumber();
                                update_post_meta($order_id, "FBA_Tracking_ID", $TrackingIDNumber);
                                // Update tracking number in plugin WooCommerce Shipment Tracking
                                update_post_meta($order_id, '_tracking_number', $TrackingIDNumber);
                                AmzFBA_Woo_Log($level, $category, $title, "FBA Shipment tracking number is " . $TrackingIDNumber);
                                $trackingAvailable = true;
                            }
                        }
                    }
                }
            }

            if ($orderComplete) {
                if ($trackingAvailable) {
                    MarkOrderAsComplete ($order, $order_id);
                }
                else {
                    // mark order on hold as Order id complete with no tracking info
                    $note = "Hold: Amazon has completed FBA order but has not provided tracking information";
                    $order->add_order_note($note, 0);
                    $order->update_status('on-hold');
                }
            }
        }
    }
    catch (FBAOutboundServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Order";
        $title    = "Get Info Error - Order ID:" . $order_id;
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
        SendErrorEmail ("Order",$info);
    }
    catch (Exception $e) {
        HandleGenericException ("Order",$e);
    }
    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Exiting");
}

////////////////////////////////////////////////////////////
/////////////////Get Amazon Fulfilment Tracking Information
////////////////////////////////////////////////////////////
function GetAmzFBAOrderTracking($order_id)
{
    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Entering");
    $MissingConfig = CheckForMissingConfig(); 
    if ($MissingConfig == true) {
        $returnmessage = 'Failed getting order tracking information from Amazon FBA. Required configuration missing in settings.';
        $order->add_order_note($returnmessage, 0);
        //Add to Log
        $level    = "Bad";
        $category = "Order";
        $title    = "Get Info Error - Order ID:" . $order_id;
        AmzFBA_Woo_Log($level, $category, $title, $returnmessage);
        return $returnmessage;
    }
    $UserSettings      = get_option('woocommerce_amazonfba_settings');
    $ChosenMarketplace = $UserSettings['AmzFBA_Marketplace'];
    $MWSEndpointURL    = GetMWSEndpointURL('FulfillmentOutboundShipment', $ChosenMarketplace);
    $config            = array(
        'ServiceURL' => $MWSEndpointURL,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'MaxErrorRetry' => 3
    );
    $service           = new FBAOutboundServiceMWS_Client(ACCESS_KEY_ID, SECRET_ACCESS_KEY, $config, APPLICATION_NAME, APPLICATION_VERSION);

    $request = new FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsRequest();
    $request->setSellerId(SELLER_ID);
    $PackageNumber = get_post_meta($order_id, "FBA_Package_Number", TRUE);
    $request->setPackageNumber($PackageNumber);
    invokeGetPackageTrackingDetails($service, $request);
    AmzFBA_Woo_Log("DEBUG","Order",__FUNCTION__,"Exiting");
}
function invokeGetPackageTrackingDetails(FBAOutboundServiceMWS_Interface $service, $request)
{
    try {
        $response = $service->getPackageTrackingDetails($request);
        if ($response->isSetGetPackageTrackingDetailsResult()) {
            $getPackageTrackingDetailsResult = $response->getGetPackageTrackingDetailsResult();
            if ($getPackageTrackingDetailsResult->isSetTrackingNumber()) {
                $TrackingNumber = $getPackageTrackingDetailsResult->getTrackingNumber();
                update_post_meta($order_id, "FBA_Carrier_Tracking_Code", $TrackingNumber);
            }
            if ($getPackageTrackingDetailsResult->isSetCarrierCode()) {
                $DeliveryCompany = $getPackageTrackingDetailsResult->getCarrierCode();
                update_post_meta($order_id, "FBA_Carrier", $DeliveryCompany);
            }
            if ($getPackageTrackingDetailsResult->isSetCarrierPhoneNumber()) {
                $CarrierPhoneNumber = $getPackageTrackingDetailsResult->getCarrierPhoneNumber();
                update_post_meta($order_id, "FBA_Carrier_Phone_Number", $CarrierPhoneNumber);
            }
            if ($getPackageTrackingDetailsResult->isSetCarrierURL()) {
                $CarrierURL = $getPackageTrackingDetailsResult->getCarrierURL();
                update_post_meta($order_id, "FBA_Carrier_URL", $CarrierURL);
            }
        }

    }
    catch (FBAOutboundServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Order";
        $title    = "Get Tracking Error - Order ID:" . $order_id;
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
        SendErrorEmail ("Order",$info);
    }
    catch (Exception $e) {
        HandleGenericException ("Order",$e);
    }
}

# Mark order as complete - trigger any notifications as well
#
function MarkOrderAsComplete($order, $order_id)
{
    $level = "Neutral";
    $category = "Order";
    $title = "Order completion";
    AmzFBA_Woo_Log($level, $category, $title, "Marking the order as complete : " . $order->get_order_number() . " " . $order_id);
    # $order = new WC_Order($order_id);
    $order->update_status('completed');

    /*
    global $woocommerce;
    $mailer = $woocommerce->mailer();
    $orderCompleteEmail = $mailer->emails['WC_Email_Customer_Completed_Order'];
    AmzFBA_Woo_Log($level, $category, $title, "Enabled : " . $orderCompleteEmail->is_Enabled());

    $level = "Neutral";
    $category = "Order";
    $title = "Order complete email";
    AmzFBA_Woo_Log($level, $category, $title, "Enabled : " . $orderCompleteEmail->is_Enabled() . ", Order id: " . $order->get_order_number());
    $orderCompleteEmail->trigger($order_id);
    
    #if ($orderCompleteEmail->is_Enabled()) {
    #    $orderCompleteEmail->trigger($order_id);
    #}
    AmzFBA_Woo_Log($level, $category, $title, "Marking the order as complete : " . $order->get_order_number());
    */
}

?>
