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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////AMAZON Inventory Functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
/////////////////Get Service Status
////////////////////////////////////////////////////////////
function GetServiceStatusInventory()
{
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
    if (CheckForMissingConfig() != '') {
        $returnmessage = 'Inventory retrieval failed. Amazon FBA configuration is not completed in your settings.';
        $level         = "Bad";
        $category      = "Inventory";
        $title         = "Failed - Update Inventory";
        $info          = "Inventory retrieval failed. Amazon FBA configuration is not completed in your settings.";
        AmzFBA_Woo_Log($level, $category, $title, $info);
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
                    $ProductID = GetProductIDFromSKU($sku);
                    if ($ProductID != '') {
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
    if (CheckForMissingConfig() != '') {
        $returnmessage = 'Failed sending order to Amazon FBA. Required configuration missing in settings.';
        $note          = "Failed sending order to Amazon FBA. Required configuration missing in settings.";
        $order->add_order_note($note, 0);
        //Add to Log
        $level    = "Bad";
        $category = "Order";
        $title    = "Failed - Order ID:" . $order_id;
        $info     = "Failed sending order to Amazon FBA. Required configuration missing in settings..";
        AmzFBA_Woo_Log($level, $category, $title, $info);
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
    $uniqueid_foramazon       = $datetimeasINT . $order_id;
    //set Variables
    $shipping_name            = $order->shipping_first_name . ' ' . $order->shipping_last_name;
    $shipping_address_line_1  = $order->shipping_address_1;
    $shipping_address_line_2  = $order->shipping_address_2;
    $shipping_city            = $order->shipping_city;
    $shipping_state           = $order->shipping_state;
    $shipping_postcode        = $order->shipping_postcode;
    $shipping_country         = $order->shipping_country;
    $FulfillmentPolicy        = get_option('AmzFBA_FulfillmentPolicy');
    $OrderComment             = get_option('AmzFBA_OrderComment');
    $EmailNotificationAddress = get_option('AmzFBA_EmailNotifyAddress');
    ////////////////////////////// //Standard Stuff
    $request                  = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest();
    $request->setSellerId(SELLER_ID);
    $request->setSellerFulfillmentOrderId($uniqueid_foramazon);
    $request->setDisplayableOrderId($order_id);
    $request->setDisplayableOrderDateTime($order_date_converted);
    $request->setDisplayableOrderComment($OrderComment);
    //     $request->setNotificationEmailList('');
    $request->setShippingSpeedCategory('Standard'); //Standard, Expedited, Priority
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
    $UnfulfillableItems = '';
    $success            = 0;
    $unsuccess          = 0;
    foreach ($items as $item) {
        //Get the SKU of the Item
        $Quantity = $item['qty'];
        if ($item['variation_id'] == '') {
            $IDForSKU = $item['product_id'];
        } else {
            $IDForSKU = $item['variation_id'];
        }
        $GetSKU = new WC_Product($IDForSKU);
        $SKU    = $GetSKU->get_sku();
        //Check if available for Amazon Fulfilment
        if ((AmzFBA_is_sku_fulfillable($SKU)) == NULL) {
            $UnfulfillableItems[$itemnumber]['SKU']      = $SKU;
            $UnfulfillableItems[$itemnumber]['Quantity'] = $Quantity;
            $unsuccess                                   = $unsuccess + $Quantity;
        } else {
            $ItemArray[$itemnumber] = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem();
            $ItemArray[$itemnumber]->setSellerSKU($SKU);
            $ItemArray[$itemnumber]->setSellerFulfillmentOrderItemId($itemnumber);
            $ItemArray[$itemnumber]->setQuantity($Quantity);
            $success = $success + $Quantity;
            $itemnumber++;
        }
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
        $returnmessage = "Order submitted. Sent " . $percentfulfilled . "% of the Order to Amazon. For unfulfillable items, see order notes.";
        $note          = $percentfulfilled . "% of Order sent to Amazon for Fulfillment";
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
    }
    //If 100% fulfilled
        elseif ($percentfulfilled == '100') {
        $returnmessage = "Order submitted. Sent" . $percentfulfilled . "% of Order to Amazon.";
        $note          = "100% of Order sent to Amazon for Fulfillment";
        $order->add_order_note($note, 0);
        update_post_meta($order_id, 'PercentFulfilledByAmazon', $percentfulfilled);
        //Add meta data of Unique ID sent to Amazon
        update_post_meta($order_id, 'FBA_OrderId', $uniqueid_foramazon);
        //Send Order to amazon
        invokeCreateFulfillmentOrder($service, $request, $order_id, $percentfulfilled);
    }
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
                $note  = "Order submitted. Amazon Request ID:" . $RequestID;
                $order->add_order_note($note, 0);
                if ($percentfulfilled == '100') {
                    $level = "Good";
                    $order->update_status("Completed", "100% Sent to Amazon. Order status changed to Completed.");
                } else {
                    $level = "Neutral";
                }
                $category = "Order";
                $title    = "Sent to Amazon - Order ID:" . $order_id;
                $info     = $percentfulfilled . " Percent Fulfilled | RequestID " . $RequestID . " | Response Meta Data : " . $ResponseMetaData;
                AmzFBA_Woo_Log($level, $category, $title, $info);
            }
        }
    }
    catch (FBAOutboundServiceMWS_Exception $ex) {
        // echo("XML: " . $ex->getXML() . "\n");
        $level    = "Bad";
        $category = "Order";
        $title    = "Export Error - Order ID:" . $order_id;
        $info     = "Caught Exception: " . $ex->getMessage() . " | Response Status Code: " . $ex->getStatusCode() . " | Error Code: " . $ex->getErrorCode() . " | Error Type: " . $ex->getErrorType() . " | Request ID: " . $ex->getRequestId();
        AmzFBA_Woo_Log($level, $category, $title, $info);
    }
}
////////////////////////////////////////////////////////////
/////////////////Get Amazon Fulfilment Order Information
////////////////////////////////////////////////////////////
function GetAmzFBAOrderDetails($order_id)
{
    if (CheckForMissingConfig() != '') {
        $returnmessage = 'Failed getting order information from Amazon FBA. Required configuration missing in settings.';
        $note          = "Failed getting order information from Amazon FBA. Required configuration missing in settings.";
        $order->add_order_note($note, 0);
        //Add to Log
        $level    = "Bad";
        $category = "Order";
        $title    = "Get Info Error - Order ID:" . $order_id;
        $info     = "Failed getting order information from Amazon FBA. Required configuration missing in settings..";
        AmzFBA_Woo_Log($level, $category, $title, $info);
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
    $FBAOrderID = get_post_meta($order_id, "FBA_OrderID", TRUE);
    $request->setSellerFulfillmentOrderId($FBAOrderID);
    invokeGetFulfillmentOrder($service, $request, $order_id);
}

function invokeGetFulfillmentOrder(FBAOutboundServiceMWS_Interface $service, $request, $order_id)
{
    $order = new WC_Order($order_id);
    try {
        $response = $service->getFulfillmentOrder($request);
        if ($response->isSetGetFulfillmentOrderResult()) {
            $getFulfillmentOrderResult = $response->getGetFulfillmentOrderResult();
            if ($getFulfillmentOrderResult->isSetFulfillmentOrder()) {
                $fulfillmentOrder = $getFulfillmentOrderResult->getFulfillmentOrder();
                if ($fulfillmentOrder->isSetFulfillmentOrderStatus()) {
                    $OrderStatus = $fulfillmentOrder->getFulfillmentOrderStatus();
                    switch ($OrderStatus) {
                        case 'RECEIVED':
                            update_post_meta($order_id, "FBA_Status", "Received");
                            break;
                        case 'INVALID':
                            $note = "Amazon FBA Status - Invalid. No further action will be taken.";
                            $order->add_order_note($note, 0);
                            update_post_meta($order_id, "FBA_Status", "Invalid");
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
                            update_post_meta($order_id, "FBA_Status", "Cancelled");
                            break;
                        case 'COMPLETE':
                            $percentfulfilled = get_post_meta($order_id, "PercentFulfilledByAmazon", true);
                            if ($percentfulfilled == '100') {
                                $order->update_status('completed');
                                $note = "Amazon FBA Status - Complete. Entire order fulfilled successfully.";
                                $order->add_order_note($note, 0);
                            } else {
                                $note = "Amazon FBA Status - Complete WooCommerce Order status not changed to complete as not all of the order was sent to Amazon FBA.";
                                $order->add_order_note($note, 0);
                            }
                            update_post_meta($order_id, "FBA_Status", "Completed");
                            update_post_meta($order_id, "FBA_Tracking_ID", "Waiting");
                            break;
                        case 'COMPLETEPARTIALLED':
                            $note = "Amazon FBA Status - Partially Complete. Some items were cancelled or unfulfillable";
                            $order->add_order_note($note, 0);
                            update_post_meta($order_id, "FBA_Status", "Partially Complete");
                            update_post_meta($order_id, "FBA_Tracking_ID", "Waiting");
                            break;
                        case 'UNFULFILLABLE':
                            $note = "Amazon FBA Status - Unfulfillable. No further action will be taken.";
                            $order->add_order_note($note, 0);
                            update_post_meta($order_id, "FBA_Status", "Unfulfillable");
                            break;
                    }
                }
            }
            if ($getFulfillmentOrderResult->isSetFulfillmentOrderItem()) {
                $fulfillmentOrderItem = $getFulfillmentOrderResult->getFulfillmentOrderItem();
                $member1List          = $fulfillmentOrderItem->getmember();
                $note                 = "";
                foreach ($member1List as $member1) {
                    if ($member1->isSetUnfulfillableQuantity()) {
                        $UnfulfillableCount = $member1->getUnfulfillableQuantity();
                        $SKU                = $member1->getSellerSKU();
                        $note .= $UnfulfillableCount . " X " . $SKU . ".  ";
                    }
                }
                if ($note != '') {
                    $newnote = "Unfulfillable By AmazonFBA: " . $note;
                    $order->add_order_note($newnote, 0);
                }
            }
            if ($getFulfillmentOrderResult->isSetFulfillmentShipment()) {
                echo ("                FulfillmentShipment\n");
                $fulfillmentShipment = $getFulfillmentOrderResult->getFulfillmentShipment();
                $member2List         = $fulfillmentShipment->getmember();
                foreach ($member2List as $member2) {
                    if ($member2->isSetFulfillmentShipmentStatus()) {
                        $ShipmentStatus = $member2->getFulfillmentShipmentStatus();
                        update_post_meta($order_id, "FBA_Shipment_Status", $ShipmentStatus);
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
                            }
                            if ($member4->isSetTrackingNumber()) {
                                $TrackingIDNumber = $member4->getTrackingNumber();
                                update_post_meta($order_id, "FBA_Tracking_ID", $TrackingIDNumber);
                                // Update tracking number in plugin WooCommerce Shipment Tracking
                                update_post_meta($order_id, '_tracking_number', $TrackingIDNumber);
                            }
                        }
                    }
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
    }
}
////////////////////////////////////////////////////////////
/////////////////Get Amazon Fulfilment Tracking Information
////////////////////////////////////////////////////////////
function GetAmzFBAOrderTracking($order_id)
{
    if (CheckForMissingConfig() != '') {
        $returnmessage = 'Failed getting order tracking information from Amazon FBA. Required configuration missing in settings.';
        $note          = "Failed getting order tracking information from Amazon FBA. Required configuration missing in settings.";
        $order->add_order_note($note, 0);
        //Add to Log
        $level    = "Bad";
        $category = "Order";
        $title    = "Get Info Error - Order ID:" . $order_id;
        $info     = "Failed getting order information from Amazon FBA. Required configuration missing in settings..";
        AmzFBA_Woo_Log($level, $category, $title, $info);
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
    }
}

?>
