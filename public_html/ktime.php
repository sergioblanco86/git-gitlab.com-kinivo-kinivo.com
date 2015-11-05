<?php

	date_default_timezone_set('America/Los_Angeles');
	$day  = date('N', time());
	$hour = date('H', time());

	$day  = $day*1;
	$hour = $hour*1;

	if ( ($day < 6)&&( $hour > 8 )&&( $hour < 17 ) ){
		echo "open";
	} else {
		echo "closed";
	}

?>
