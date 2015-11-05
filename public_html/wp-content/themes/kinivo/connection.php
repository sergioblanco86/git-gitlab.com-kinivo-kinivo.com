<?php
	$dbhost = 'localhost';
	$dbuser = 'kinivoco_liveusr';
	$dbpass = 'Cm$g-Zb,T4oc';
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
	$dbname = 'kinivoco_live';
	mysql_select_db($dbname);
?>	