<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
* Add Box on Order Page
*/
add_action( 'add_meta_boxes', 'add_AmzFBA_order_meta_boxes' );

function add_AmzFBA_order_meta_boxes()
{
    add_meta_box(
        'woocommerce-order-AmzFBA',
        __( 'Amazon FBA' ),
        'order_meta_box_AmazonFBA',
        'shop_order',
        'side',
        'default'
    );
}
function order_meta_box_AmazonFBA()
{
    $id = $_GET['post'];
    $FBAOrderId = get_post_meta($id, 'FBA_OrderId', true);
    $FBAOrderStatus = get_post_meta($id, 'FBA_Status', "FALSE");
    ?>
    <ul class="order_actions">
          <?php
              if ($FBAOrderStatus == "Cancelling") {
              ?>
              <li class="wide">
                Amazon has been asked to cancel the shipment <br><br>Wait 5 mins and refresh this page to see if cancellation went through.
              </li>
              <?php
              }
              else {
              if ((($FBAOrderStatus == "Cancelled") || ($FBAOrderStatus == "Invalid")) && (!empty($FBAOrderId) && isset($FBAOrderId))) {
              ?>
              <li class="wide">
                FBA (fulfillment) has been cancelled.<br><i>Please clear/delete FBA_OrderId Meta to resubmit</i>
              </li>
              <?php
              }
              else {
              if(empty($FBAOrderId) || !isset($FBAOrderId)){
              ?>
              <li class="wide">
                Order not sent to Amazon.
                <button type="button" id="FulfilAmzOrder" class="button button-primary save_order" name="Fulfil" value="<?php echo $id; ?>">Fulfil</button>
                <div id="FulAmzOrder_Result" style="display:hidden;"></div>
              </li>
              <?php
            }else{
              if (($FBAOrderStatus != "COMPLETE") && ($FBAOrderStatus != "Partially Complete")) {
              ?>
              <li class="wide">
                Order sent to Amazon<br>
                <i>Ref:<?php echo $FBAOrderId;?></i>
                <button type="button" id="CancelOrderBtnId" class="button button-primary cancel_order" name="CancelFBA" value="<?php echo $id; ?>">Cancel FBA</button>
                <div id="CancelFBA_Result" style="display:hidden;"></div>
            <br>
              </li>
              <?php
              } else {
              ?>
              <li class="wide">
                Amazon has shipped the order. Tracking info is below in order meta data.<br>
              </li>
              <?php
            }
            }
            }
            }
          ?>
    </ul>
    <?php
    AmzFBA_fulfilorder_js();
}

// <i>Please clear/delete FBA_OrderId Meta to resubmit</i>
function AmzFBA_fulfilorder_js(){
  ?>
  <script type="text/javascript" >
	jQuery(document).ready(function($) {
          $( "#FulfilAmzOrder").click(function() {
              $( "#FulfilAmzOrder").prop('disabled', true);
              $( "#FulfilAmzOrder").removeClass( "button-primary" );
              $( "#FulfilAmzOrder").addClass( "button-primary-disabled" );
              var orderid =	$( "#FulfilAmzOrder").val();
              var data = {
                'action': 'AmzFBA_fulfil_order',
                'orderid': orderid
              };
              $.post(ajaxurl, data, function(response) {
                $( "#FulAmzOrder_Result" ).html( response );
                $( "#FulAmzOrder_Result" ).slideDown();
              });
          });
          $( "#CancelOrderBtnId").click(function(){
              $( "#CancelOrderBtnId").prop('disabled', true);
              $( "#CancelOrderBtnId").removeClass( "button-primary" );
              $( "#CancelOrderBtnId").addClass( "button-primary-disabled" );
              var orderid =	$( "#CancelOrderBtnId").val();
              var data = {
                'action': 'AmzFBA_cancel_order',
                'orderid': orderid
              };
              $.post(ajaxurl, data, function(response) {
                $( "#CancelFBA_Result" ).html( response );
                $( "#CancelFBA_Result" ).slideDown();
              });
          });
	});
	</script>
  <?php
}
add_action( 'wp_ajax_AmzFBA_fulfil_order', 'AmzFBA_fulfil_order_ajax' );
add_action( 'wp_ajax_AmzFBA_cancel_order', 'AmzFBA_cancel_order_ajax' );

function AmzFBA_fulfil_order_ajax() {
	global $wpdb; // this is how you get access to the database
  if(isset($_POST['orderid']) && !empty($_POST['orderid'])){
    $id = $_POST['orderid'];
    CreateAmzFBAOrder($id);
    echo '<div class="updated"><p>Amazon FBA Fulfilment order created.</p></div>';
  }else{
    echo '<div class="error"><p>An error occured.</p></div>';
  }
	die(); // this is required to terminate immediately and return a proper response
}

function AmzFBA_cancel_order_ajax() {
	global $wpdb; // this is how you get access to the database
  if(isset($_POST['orderid']) && !empty($_POST['orderid'])){
    $id = $_POST['orderid'];
    CancelAmzFBAOrder($id);
    echo '<div class="updated"><p>Amazon has been asked to cancel the shipment <br><br>Wait 5 mins and refresh this page to see if cancellation went through.</p></div>';
  }else{
    echo '<div class="error"><p>An error occured.</p></div>';
  }
	die(); // this is required to terminate immediately and return a proper response
}
