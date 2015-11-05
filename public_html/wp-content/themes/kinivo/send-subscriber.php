<?php  
include('connection.php');
$type = $_POST['type'];

if($type == 'subscriber'){
	$email = $_POST['email'];
	$date = date('Y-m-d');
	if($email != ''){
		$sqlresult = mysql_query('insert into subscribers values(NULL,"'.$email.'","'.$date.'")');
		echo $sqlresult;
	}else{
		echo '3';
	}
}

if($type == 'contact'){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	if($name != '' && $email != '' && $message != ''){

		$email2 = 'sergio.blanco@ideaware.co';

		$subject2 = "Kinivo.com - Contact Form - ".$subject;

		$message2 = "---------------------------------- \n";
		$message2.= "      CONTACT FORM       \n";
		$message2.= "---------------------------------- \n";
		$message2.= "NAME:    ".$name."\n";
		$message2.= "EMAIL:    ".$email."\n";
		$message2.= "SUBJECT:    ".$subject."\n";
		$message2.= "DATE:    ".date("d/m/Y")."\n";
		$message2.= "TIME:     ".date("h:i:s a")."\n";
		$message2.= "IP:       ".$_SERVER['REMOTE_ADDR']."\n\n";
		$message2.= "---------------------------------- \n";
		$message2.= "Message:    ".$message."\n";
		$message2.= "---------------------------------- \n";
		$message2.= "From kinivo.com \n";

		$headers = "From: support@kinivo.com \r\n";
		$headers .= "Reply-To: no-reply@kinivo.com\r\n";
		$headers .= "Return-Path: no-reply@kinivo.com\r\n";

		if (mail($email2, $subject2, $message2, $headers,"-f support@kinivo.com")) {
			echo '1';
		} else {
			echo 'ERROR';
		}
		
	}else{
		echo '3';
	}
}

?>