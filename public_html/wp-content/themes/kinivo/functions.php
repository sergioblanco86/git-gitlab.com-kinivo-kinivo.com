<?php
//Theme JS
function theme_js(){
	wp_enqueue_scripts('jquery');
}

function ajax_login_init(){

    wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/ajax-login-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');

    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __('<span class="success">Sending user info, please wait...</span>')
    ));

    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}

function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('<span class="error">Wrong username or password.</span>')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('<span class="success">Login successful, redirecting...</span>')));
    }

    die();
}


/**/
 add_action( 'woocommerce_after_checkout_form', 'mi_contenido_checkout', 10, 1);
function mi_contenido_checkout( $checkout ) {
?>

    <script type="text/javascript">
    jQuery(document).ready(function( $ ) {
        $('#place_order').on('click',function (argument) {
            var email = "";

<?php

    if (!is_user_logged_in()) {
?>
        email = $('#billing_email').val();
<?php
    }else{
        global $current_user;
        get_currentuserinfo();        
?>
        email = <?php echo $current_user->user_email; ?>
<?php
    } 
?>
    

            var subcMail = $("#subscrbe-user").is(':checked');
            console.log(subcMail);
            if (subcMail == true) {
                $.ajax({
                    url: "<?php bloginfo('template_url'); ?>/send-subscriber.php",
                    type: 'POST',
                    data: {'email': $.trim(email) , 'type': 'subscriber'},
                })
                .done(function(data) {
                    console.log("success "+data);
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
                
            }
        });
    });
    </script>
<?php

}


// function adian_load_persistent_cart(){

//     global $current_user;

//     if( ! $current_user )
//     return false;

//     $saved_cart = get_user_meta( $current_user->ID, '_woocommerce_persistent_cart', true );

//     if ( $saved_cart ){
//         if ( empty( WC()->session->cart ) || ! is_array( WC()->session->cart ) || sizeof( WC()->session->cart ) == 0 ){
//             WC()->session->set('cart', $saved_cart['cart'] );   
//         }
//     }

// }

// add_action( 'init', 'adian_load_persistent_cart', 10, 1 );


// Display Price For Variable Product With Same Variations Prices
add_filter('woocommerce_available_variation', function ($value, $object = null, $variation = null) {
    if ($value['price_html'] == '') {
        $value['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
    }
    return $value;
}, 10, 3);


function wp_editor_fontsize_filter( $buttons ) {
        array_shift( $buttons );
        array_unshift( $buttons, 'fontsizeselect');
        array_unshift( $buttons, 'formatselect');
        return $buttons;
}    
add_filter('mce_buttons_2', 'wp_editor_fontsize_filter');

/*Update notifications*/
add_action('after_setup_theme','remove_core_updates');
function remove_core_updates()
{
 if(! current_user_can('update_core')){return;}
 add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
 add_filter('pre_option_update_core','__return_null');
 add_filter('pre_site_transient_update_core','__return_null');
}

remove_action('load-update-core.php','wp_update_plugins');
add_filter('pre_site_transient_update_plugins','__return_null');

/*END Update notifications*/

add_action( 'init', 'theme_js' );
show_admin_bar(false);
add_theme_support( 'woocommerce' );
add_filter( 'woocommerce_enqueue_styles', '__return_false' );
add_db_table_editor('title=Subscribers&table=subscribers');

add_action('dbte_row_deleted', 'my_dbte_row_deleted', 10, 2);

    function my_dbte_row_deleted($currentTable, $idRemoved){
      echo "IDEAWARE TEST : id=".$idRemoved;
    }


/*Reg User*/
function vb_register_user_scripts() {
  // Enqueue script
  wp_register_script('vb_reg_script', get_template_directory_uri() . '/js/ajax-registration.js', array('jquery'), null, false);
  wp_enqueue_script('vb_reg_script');
 
  wp_localize_script( 'vb_reg_script', 'vb_reg_vars', array(
        'vb_ajax_url' => admin_url( 'admin-ajax.php' ),
      )
  );
}
add_action('wp_enqueue_scripts', 'vb_register_user_scripts', 100);



/**
 * New User registration
 *
 */
function vb_reg_new_user() {
 
  // Verify nonce
  

  // Post values
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $email    = $_POST['mail'];
    $name     = $_POST['name'];
    $nick     = $_POST['nick'];
    
    /**
     * IMPORTANT: You should make server side validation here!
     *
     */

    $userdata = array(
        'user_login' => $username,
        'user_pass'  => $password,
        'user_email' => $email,
        'first_name' => $name,
        'nickname'   => $nick,
        'role'       => 'customer'
    );

    $user_id = wp_insert_user( $userdata ) ;

    //On success
    if( !is_wp_error($user_id) ) {
        $info = array();
        $info['user_login'] = $email;
        $info['user_password'] = $password;
        $info['remember'] = true;
        wp_signon( $info, false );
        echo '1';
    } else {
        echo $user_id->get_error_message();
    } 
  die();
    
}
 
add_action('wp_ajax_register_user', 'vb_reg_new_user');
add_action('wp_ajax_nopriv_register_user', 'vb_reg_new_user');

function newUserName()
{

}

add_filter('Wc_social_login_account_unlinked','newUserName');