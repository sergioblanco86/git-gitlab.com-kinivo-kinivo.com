<?php
require_once('../../../wp-load.php');

$new_user_email = $_POST['new_user_email'];
$new_user_name = $_POST['new_user_name'];
$new_user_password = $_POST['new_user_password'];

$fields = array(
		'user_login' 	=> $new_user_name,
		'user_email' 	=> $new_user_email,
		'user_pass'		=> $new_user_password,
		'role'			=> 'customer'
	);

if( !email_exists( $new_user_email) ) {
	if ( !username_exists( $new_user_name ) ){
		$user_id = wp_insert_user( $fields );
		if ($user_id) {
			wp_set_auth_cookie( $user_id, false, is_ssl() );
			echo json_encode(array("response" => "ok", "msj" => $user_id));
		}else{
			echo json_encode(array("response" => "error", "msj" => $user_id." - Something happened with your account ceration, please contact support team." ));
		}
	}else{
		echo json_encode(array("response" => "error", "msj" => "That Username already exists, pick another" ));
	}

}else{
	echo json_encode(array("response" => "error", "msj" => "That Email already exists, pick another" ));
}




?>