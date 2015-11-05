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
    ?>
    <ul class="order_actions">
          <?php
            if(empty($FBAOrderId) || !isset($FBAOrderId)){
              ?>
              <li class="wide">
                Order not sent to Amazon.
                <button type="button" id="FulfilAmzOrder" class="button button-primary save_order" name="Fulfil" value="<?php echo $id; ?>">Fulfil</button>
                <div id="FulAmzOrder_Result" style="display:hidden;"></div>
              </li>
              <?php
            }else{
              ?>
              <li class="wide">
                Order sent to Amazon<br>
                <i>Ref:<?php echo $FBAOrderId;?></i><br>
                <i>Please clear/delete FBA_OrderId Meta to resubmit</i>
              <?php
            }
          ?>
    </ul>
    <?php
    AmzFBA_fulfilorder_js();
}
function AmzFBA_fulfilorder_js(){
  ?>
  <script type="text/javascript" >
	jQuery(document).ready(function($) {
		$( "#FulfilAmzOrder").click(function(){
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
	});
	</script>
  <?php
}
add_action( 'wp_ajax_AmzFBA_fulfil_order', 'AmzFBA_fulfil_order_ajax' );

function AmzFBA_fulfil_order_ajax() {
	global $wpdb; // this is how you get access to the database
	$id = $_POST['orderid'];
  CreateAmzFBAOrder($id);
  if(isset($_POST['orderid']) && !empty($_POST['orderid'])){
    $id = $_POST['orderid'];
    CreateAmzFBAOrder($id);
    echo '<div class="updated"><p>Amazon FBA Fulfilment order created.</p></div>';
  }else{
    echo '<div class="error"><p>An error occured.</p></div>';
  }
	die(); // this is required to terminate immediately and return a proper response
}
