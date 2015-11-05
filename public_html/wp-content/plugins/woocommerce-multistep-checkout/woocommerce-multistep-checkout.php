<?php
/**
 * Plugin Name: WooCommerce MultiStep Checkout
 * Description: WooCommerce Multi-Step-Checkout enable multi-step-checkout functionality on WooCommerce checkout page.
 * Version: 1.05
 * Author: Mubashir Iqbal
 * Author URI: http://www.mubashir09.com
 * Text Domain: woocommerce-multistep-checkout
 * Domain Path: /languages/
 */
if (!defined('ABSPATH'))
    die();

function dependentplugin_activate() {

    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        // deactivate dependent plugin
        deactivate_plugins(plugin_basename(__FILE__));

        exit('<strong>WooCommerce Multistep Checkout</strong> requires <a target="_blank" href="http://wordpress.org/plugins/woocommerce/">WooCommerce</a> Plugin to be installed first.');
    }
}

register_activation_hook(__FILE__, 'dependentplugin_activate');


load_plugin_textdomain('woocommerce-multistep-checkout', false, dirname(plugin_basename(__FILE__)) . '/languages/');

add_filter('woocommerce_locate_template', 'wcmultichecout_woocommerce_locate_template', 1, 3);

function wcmultichecout_woocommerce_locate_template($template, $template_name, $plugin_path) {

    $plugin_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/';
    if (file_exists($plugin_path . $template_name)) {
        $template = $plugin_path . $template_name;
        return $template;
    }

    return $template;
}

function enque_woocommerce_multistep_checkout_scripts() {
    $wizard_type = get_option('wmc_wizard_type');
    wp_register_script('jquery-steps', plugins_url('/js/jquery.steps.js', __FILE__), array('jquery'));
    wp_register_script('jquery-validate', plugins_url('/js/jquery.validate.js', __FILE__), array('jquery'));
    if ($wizard_type == '' || $wizard_type == 'classic') {
        wp_register_style('jquery-steps', plugins_url('/css/jquery.steps-classic.css', __FILE__));
    } else {
        wp_register_style('jquery-steps', plugins_url('/css/jquery.steps-modern.css', __FILE__));
    }
    wp_register_style('jquery-steps-main', plugins_url('/css/main.css', __FILE__));
    wp_register_style('jquery-steps-normalize', plugins_url('/css/normalize.css', __FILE__));

    /*     * *Only add on WooCommerce checkout page * */
    if (is_checkout() || defined('ICL_LANGUAGE_CODE')) {
        wp_enqueue_script('jquery-steps');
        wp_enqueue_script('jquery-validate');
        wp_enqueue_style('jquery-steps');
        wp_enqueue_style('jquery-steps-main');
        wp_enqueue_style('jquery-steps-normalize');
    }
}

if(!$_GET['amazon_payments_advanced']){
add_action('wp_enqueue_scripts', 'enque_woocommerce_multistep_checkout_scripts');
}


/* * **********Plugin Options Page ** */
add_action('admin_menu', 'woocommercemultichekout_menu_page');

function woocommercemultichekout_menu_page() {
    add_menu_page('WooCommerce MultiStep Checkout', 'WooCommerce MultiStepCheckout', 'manage_options', 'wcmultichekout', 'wcmultichekout_options', 'dashicons-cart', '66');
}

/* * * Add Color Picker * */
add_action('admin_enqueue_scripts', 'wp_enqueue_color_picker');

function wp_enqueue_color_picker() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker-script', plugins_url('script.js', __FILE__), array('wp-color-picker'), false, true);
}

function add_jquery_steps_options() {
    if (is_checkout() || defined('ICL_LANGUAGE_CODE')):
        $wizard_type = get_option('wmc_wizard_type');
        ?>

        <script>
            jQuery(document).ready(function (){
            jQuery("form.checkout .validate-required .input-text").attr("required", "required");
                    jQuery("form.checkout .validate-email .input-text").addClass("email");
                    jQuery("#wizard").steps({
            headerTag: "h1",
                    bodyTag: "section",
                    transitionEffect: "<?php echo get_option('wmc_animation') ? get_option('wmc_animation') : 'fade' ?>",
                    stepsOrientation: "<?php echo get_option('wmc_orientation') ? get_option('wmc_orientation') : 'horizontal' ?>",
                    enableAllSteps: <?php echo get_option('wmc_enable_all_steps') ? get_option('wmc_enable_all_steps') : 'false' ?>,
                    enablePagination: <?php echo get_option('wmc_enable_pagination') ? get_option('wmc_enable_pagination') : 'true' ?>,
        <?php if (get_option('wmc_remove_numbers') == 'true'): ?>
                titleTemplate: '#title#',
        <?php endif; ?>
            labels:{
            next: '<?php echo get_option('wmc_btn_next') ? __(get_option('wmc_btn_next'), 'woocommerce-multistep-checkout') : __('Next', 'woocommerce-multistep-checkout'); ?>',
                    previous: '<?php echo get_option('wmc_btn_prev') ? __(get_option('wmc_btn_prev'), 'woocommerce-multistep-checkout') : __('Previous', 'woocommerce-multistep-checkout') ?>',
                    finish: '<?php echo get_option('wmc_btn_finish') ? __(get_option('wmc_btn_finish'), 'woocommerce-multistep-checkout') : __('Place Order', 'woocommerce-multistep-checkout'); ?>'
            },
                    onStepChanging: function (event, currentIndex, newIndex)
                    {
                    jQuery("#wizard").validate().settings.ignore = ":disabled,:hidden";
                            return jQuery("form.checkout").valid();
                    }


            });
                    jQuery(".actions > ul li:last a").addClass("finish-btn");
                    jQuery(".finish-btn").click(function(){
            jQuery("#place_order").trigger("click");
            });
                    jQuery("ul.payment_methods li").click(function(){
            jQuery("ul.payment_methods li .payment_box").hide();
                    jQuery(this).find(".payment_box").show();
            });
            });</script>
        <?php if ($wizard_type == 'classic' || $wizard_type == '') { //if this is a classic wizard type  ?>
            <style>

                .wizard > .steps .current a, .wizard > .steps .current a:hover{
                    background: <?php echo get_option('wmc_tabs_color') ?>;
                    color: <?php echo get_option('wmc_font_color') ?>;
                }

                .wizard > .steps .disabled a{
                    background: <?php echo get_option('wmc_inactive_tabs_color') ?>;
                }

                .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active{
                    background: <?php echo get_option('wmc_buttons_bg_color') ?>;
                    color: <?php echo get_option('wmc_buttons_font_color') ?>;
                }
                .wizard > .steps .done a{
                    background: <?php echo get_option('wmc_completed_tabs_color') ?>;
                }
                .wizard > .content{
                    background: <?php echo get_option('wmc_wrapper_bg') ?>;
                }

                .woocommerce form .form-row label, .woocommerce-page form .form-row label, .woocommerce-checkout .shop_table, .woocommerce table.shop_table tfoot th,
                .woocommerce table.shop_table th, .woocommerce-page table.shop_table th, #ship-to-different-address
                {
                    color: <?php echo get_option('wmc_form_labels_color') ?>;
                }

            </style>

        <?php } else { //if modern wizard
            ?>
            <style>
            <?php if (get_option('wmc_tabs_color')): ?>
                    .wizard > .steps li.current a:before{
                        border-bottom: 30px solid <?php echo get_option('wmc_tabs_color') ?>;
                        border-top: 30px solid <?php echo get_option('wmc_tabs_color') ?>
                    }
                    .wizard > .steps li.current a:after{
                        border-left: 20px solid <?php echo get_option('wmc_tabs_color') ?>
                    }                 
                    .wizard > .steps li.current a{
                        background-color: <?php echo get_option('wmc_tabs_color') ?>
                    }
            <?php endif; ?>
                                                                    
            <?php if (get_option('wmc_buttons_bg_color')): ?>
                    .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active{
                        background: <?php echo get_option('wmc_buttons_bg_color') ?>;
                    }
            <?php endif; ?>
                                            
            <?php if (get_option('wmc_buttons_font_color')): ?>
                    .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active{
                        color: <?php echo get_option('wmc_buttons_font_color') ?>;
                    }
            <?php endif; ?>
                                            
                                                                
            <?php if (get_option('wmc_inactive_tabs_color')): ?>
                    .wizard > .actions .disabled a{
                        background: <?php echo get_option('wmc_inactive_tabs_color') ?>
                    }

                    .wizard > .steps a:before {
                        border-bottom: 30px solid <?php echo get_option('wmc_inactive_tabs_color') ?>;
                        border-top: 30px solid <?php echo get_option('wmc_inactive_tabs_color') ?>;
                    }

                    .wizard > .steps a:after{
                        border-left: 20px solid <?php echo get_option('wmc_inactive_tabs_color') ?>;
                    }

                    .wizard > .steps a{
                        background-color: <?php echo get_option('wmc_inactive_tabs_color') ?>;
                    }
            <?php endif; ?>
                                                                
            <?php if (get_option('wmc_wrapper_bg')): ?>    
                    .wizard > .content{
                        background: <?php echo get_option('wmc_wrapper_bg') ?>;
                    }
            <?php endif; ?>
                                                    
            <?php if (get_option('wmc_font_color')): ?> 
                    .wizard > .steps li.current a{
                        color: <?php echo get_option('wmc_font_color') ?>
                    }
            <?php endif; ?>
                                                    
            <?php if (get_option('wmc_buttons_bg_color')): ?> 
                    .wizard > .actions a{
                        background-color: <?php echo get_option('wmc_buttons_bg_color') ?>
                    }
                                                                
            <?php endif; ?>
                                                    
            <?php if (get_option('wmc_completed_tabs_color')): ?> 
                                                    
                    .wizard > .steps li.done a:before {
                        border-bottom: 30px solid <?php echo get_option('wmc_completed_tabs_color') ?>;
                        border-top: 30px solid <?php echo get_option('wmc_completed_tabs_color') ?>;
                    }
                    
                    .wizard > .steps li.done a:after{
                        border-left: 20px solid <?php echo get_option('wmc_completed_tabs_color') ?>;
                    }
                    
                    .wizard > .steps li.done a{
                         background-color: <?php echo get_option('wmc_completed_tabs_color') ?>;
                    }
            <?php endif; ?>
                    
            <?php if (get_option('wmc_form_labels_color')): ?> 
                    .woocommerce form .form-row label, .woocommerce-page form .form-row label, .woocommerce-checkout .shop_table, .woocommerce table.shop_table tfoot th,
                    .woocommerce table.shop_table th, .woocommerce-page table.shop_table th, #ship-to-different-address
                    {
                        color: <?php echo get_option('wmc_form_labels_color') ?>;
                    }
            <?php endif; ?>
            </style>
            <?php
        }
    endif;
}

if(!$_GET['amazon_payments_advanced']){
add_action('wp_head', 'add_jquery_steps_options');
}

function wcmultichekout_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>


    <?php
    //must check that the user has the required capability 
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }


    //Submit form
    if (isset($_POST['send_form']) && $_POST['send_form'] == 'Y') {

        $do_not_save = array('send_form', 'submit', 'wmc_restore_default');
        foreach ($_POST as $option_name => $option_value) {
            if (in_array($option_name, $do_not_save))
                continue;

            // Save the posted value in the database
            update_option($option_name, $option_value);
        }

        // If restore to default
        if (isset($_POST['wmc_restore_default']) && $_POST['wmc_restore_default']) {
            foreach ($_POST as $option_name => $option_value) {
                if (in_array($option_name, $do_not_save))
                    continue;
                delete_option($option_name);
            }
        }
        ?>
        <div class="updated"><p><strong><?php _e('settings saved.', 'woocommerce-multistep-checkout'); ?></strong></p></div>
        <?php
    }
    ?>
    <div class="wrapper">
        <div id="icon-edit" class="icon32"></div><h2><?php _e('WooCommerce MultiStep-Checkout', 'woocommerce-multistep-checkout') ?></h2>
        <form name="wccheckout_options" method="post" action="">
            <input type="hidden" name="send_form" value="Y">
            <table class="form-table">

                <tr>
                    <td><?php _e('Wizard Type', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_wizard_type">
                            <option value="classic" <?php selected(get_option('wmc_wizard_type'), 'classic', true); ?>><?php _e('Classic', 'woocommerce-multistep-checkout') ?></option>
                            <option value="modern" <?php selected(get_option('wmc_wizard_type'), 'modern', true); ?>><?php _e('Modern', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Select the type of Wizard', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td width="200"><?php _e('Tabs Color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_tabs_color" id="tabs_color" type="text" value="<?php echo get_option('wmc_tabs_color') ?>" class="regular-text" /><br /><span class="description"><?php _e('Select background color for active tabs', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Tabs Color for inactive tab', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_inactive_tabs_color" id="inactive_tabs_color" type="text" value="<?php echo get_option('wmc_inactive_tabs_color') ?>" class="regular-text" /><br /><span class="description"><?php _e('Select background color for inactive tabs', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Completed tabs color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_completed_tabs_color" id="completed_tabs_color" type="text" value="<?php echo get_option('wmc_completed_tabs_color') ?>" class="regular-text" /><br /><span class="description"><?php _e('Select background color for completed tabs', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Active Tabs Font Color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_font_color" id="font_color" type="text" value="<?php echo get_option('wmc_font_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Select Tabs Font Color', '') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Buttons Color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_buttons_bg_color" id="buttons_bg_color" type="text" value="<?php echo get_option('wmc_buttons_bg_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Next/Previous button color', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Buttons Font color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_buttons_font_color" id="buttons_font_color" type="text" value="<?php echo get_option('wmc_buttons_font_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Next/Previous button font color', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Wrapper Background', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_wrapper_bg" id="wrapper_bg" type="text" value="<?php echo get_option('wmc_wrapper_bg') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('', 'woocommerce-multistep-checkout') ?>Set wrapper background color</span></td>
                </tr>

                <tr>
                    <td><?php _e('Checkout form Labels', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_form_labels_color" id="form_labels_color" type="text" value="<?php echo get_option('wmc_form_labels_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Set Form Labels color', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>


                <tr>
                    <td><?php _e('Animation', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_animation">
                            <option value="fade" <?php selected(get_option('wmc_animation'), 'fade', true); ?>><?php _e('Fade', 'woocommerce-multistep-checkout') ?></option>
                            <option value="slide" <?php selected(get_option('wmc_animation'), 'slide', true); ?>><?php _e('Slide', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Select the type of animation', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <?php
                $wizard_type = get_option('wmc_wizard_type');
                if ($wizard_type == '' || $wizard_type == 'classic'):
                    ?>
                    <tr>
                        <td><?php _e('Orientation', 'woocommerce-multistep-checkout') ?></td>
                        <td><select name="wmc_orientation">
                                <option value="horizontal" <?php selected(get_option('wmc_orientation'), 'horizontal', true); ?>><?php _e('Horizontal', 'woocommerce-multistep-checkout') ?></option>
                                <option value="vertical" <?php selected(get_option('wmc_orientation'), 'vertical', true); ?>><?php _e('Vertical', 'woocommerce-multistep-checkout') ?></option>
                            </select>
                            <span class="description"><?php _e('Select Tabs Orientation', 'woocommerce-multistep-checkout') ?></span></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td><?php _e('Enable Pagination', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_enable_pagination">
                            <option value="true" <?php selected(get_option('wmc_enable_pagination'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>
                            <option value="false" <?php selected(get_option('wmc_enable_pagination'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Enable/Disable Pagination', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Next Button', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_btn_next" value="<?php echo get_option('wmc_btn_next') ? get_option('wmc_btn_next') : "Next" ?>" />
                        <span class="description"><?php _e('Enter text for Next button', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Previous Button', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_btn_prev" value="<?php echo get_option('wmc_btn_prev') ? get_option('wmc_btn_prev') : "Previous" ?>" />
                        <span class="description"><?php _e('Enter text for Previous button', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Place Order Button', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_btn_finish" value="<?php echo get_option('wmc_btn_finish') ? get_option('wmc_btn_finish') : "Place Order" ?>" />
                        <span class="description"><?php _e('Enter text for Place Order Button', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Remove Numbers', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_remove_numbers">
                            <option value="false" <?php selected(get_option('wmc_remove_numbers'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                            <option value="true" <?php selected(get_option('wmc_remove_numbers'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Remove Numbers From Steps', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Activate All Steps', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <select name="wmc_enable_all_steps">
                            <option value="false" <?php selected(get_option('wmc_enable_all_steps'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                            <option value="true" <?php selected(get_option('wmc_enable_all_steps'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('If enabled All steps will be activated initially', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td colspan="2"><h3 style="margin: 0;padding: 0"><?php _e('Tabs Labels', 'woocommerce-multistep-checkout') ?></h3></td>
                </tr>
                <tr>
                    <td><?php _e('Billing', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_billing_label" value="<?php echo get_option('wmc_billing_label') ? get_option('wmc_billing_label') : "Billing" ?>" />
                        <span class="description"><?php _e('Enter text for Billing label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Shipping', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_shipping_label" value="<?php echo get_option('wmc_shipping_label') ? get_option('wmc_shipping_label') : "Shipping" ?>" />
                        <span class="description"><?php _e('Enter text for Shipping label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Order Information', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_orderinfo_label" value="<?php echo get_option('wmc_orderinfo_label') ? get_option('wmc_orderinfo_label') : "Order Information" ?>" />
                        <span class="description"><?php _e('Enter text for Order Information label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Payment Info', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_paymentinfo_label" value="<?php echo get_option('wmc_paymentinfo_label') ? get_option('wmc_paymentinfo_label') : "Payment Info" ?>" />
                        <span class="description"><?php _e('Enter text for Payment Info label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Restore Plugin Defaults', 'woocommerce-multistep-checkout') ?></td>
                    <td><input type="checkbox" name="wmc_restore_default" value="yes" /></td>
                </tr>

            </table>


            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
            </p>

        </form>

        <script type="text/javascript">

                    jQuery(document).ready(function() {
            jQuery('#tabs_color, #font_color, #inactive_tabs_color, #completed_tabs_color, #buttons_bg_color, #buttons_font_color, #wrapper_bg, #form_labels_color').wpColorPicker();
            })
        </script>
    </div>        

    <?php
}
