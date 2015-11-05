<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$Amz_SKU_Table     = $wpdb->prefix . "posts";
$Woo_SKU_Table     = $wpdb->prefix . "postmeta";

$queryMatched      = "
SELECT post_title, meta_value FROM $Amz_SKU_Table A LEFT JOIN $Woo_SKU_Table W
      ON BINARY A.post_title = BINARY W.meta_value
WHERE W.meta_key='_sku'
AND A.post_type='amazonfbasku'
      ;";
$queryUnmatchedWoo = "
SELECT meta_value FROM $Amz_SKU_Table A RIGHT JOIN $Woo_SKU_Table W
      ON BINARY A.post_title = BINARY W.meta_value
WHERE W.meta_key='_sku'
AND A.post_title IS NULL
      ;";

$queryUnmatchedAmz = "
SELECT post_title FROM $Amz_SKU_Table A LEFT JOIN $Woo_SKU_Table W
      ON BINARY A.post_title = BINARY W.meta_value
WHERE W.meta_key IS NULL
AND A.post_type='amazonfbasku'
      ;";
$queryALL	= "
SELECT * FROM $Amz_SKU_Table A LEFT JOIN $Woo_SKU_Table W
      ON A.post_title = W.meta_value
WHERE A.post_type='amazonfbasku'
UNION
SELECT * FROM $Amz_SKU_Table A RIGHT JOIN $Woo_SKU_Table W
      ON A.post_title = W.meta_value
WHERE W.meta_key='_sku'
;";
$products          = $wpdb->get_results($queryMatched);
?>
<div class="wrap">
<h2>Matched Products</h2>
<p>Below is a table of your all your SKUs. The upper table shows the successful matches between your AmazonFBA Account and your WooCommerce store. The bottom two tables show the unmatched products. Unmatched products won't be included in order or inventory synchronisation. In order to correct this we advise amend the SKU held in WooCommerce to match the corresponding SKU in Amazon FBA account.</p>
<p><b>Note:</b>Matching SKUs is case sensitive.</p>
<table class="wp-list-table widefat fixed" cellspacing="0">
<thead><tr><th>Amazon SKU</th><th>Woo SKU</th></tr></thead>
<tfoot><tr><th>Amazon SKU</th><th>Woo SKU</th></tr></tfoot>
<tbody>
<?php
$i = 1;
foreach ($products as $product) {
    echo '<tr ';
    if ($i & 1) {
        echo 'class="alternate"';
    }
    echo '>';
    echo '<td>' . $product->post_title . '</td>';
    echo '<td>' . $product->meta_value . '</td>';
    echo '</tr>';
    $i++;
}
?>
</tbody></table>
<p></p>
<div>
<div style="max-width:49%;display:inline-block;vertical-align:top;">
<table class="wp-list-table widefat fixed" cellspacing="0">
<thead><tr><th>Unmatched SKUs from Amazon</th></tr></thead>
<tfoot><tr><th>Unmatched SKUs from Amazon</th></tr></tfoot>
<tbody>
<?php
$products = $wpdb->get_results($queryUnmatchedAmz);
$i        = 1;
foreach ($products as $product) {
    echo '<tr ';
    if ($i & 1) {
        echo 'class="alternate"';
    }
    echo '>';
    echo '<td>' . $product->post_title . '</td>';
    echo '</tr>';
    $i++;
}
?>
</tbody></table>
</div>
<div style="max-width:49%;display:inline-block;vertical-align:top;">
<table class="wp-list-table widefat fixed" cellspacing="0">
<thead><tr><th>Unmatched SKUs from WooCommerce</th></tr></thead>
<tfoot><tr><th>Unmatched SKUs from WooCommerce</th></tr></tfoot>
<tbody>
<?php
$products = $wpdb->get_results($queryUnmatchedWoo);
$i        = 1;
foreach ($products as $product) {
    echo '<tr ';
    if ($i & 1) {
        echo 'class="alternate"';
    }
    echo '>';
    echo '<td>' . $product->meta_value . '</td>';
    echo '</tr>';
    $i++;
}
?>
</tbody></table>
</div>
</div>
</div> <!-- Close Wrap -->
