<?php
	//echo "...<br>";
	require_once('AES_official.php');

	$data = urldecode('PLeU3Bu7S9WoEkJQXoUsx4BHNRIoovHk3oRJq1uemFAFOtpm8i7yRE0H4OAHr3ZZAU%2bJJk3fMf%2bxfGd4BeCXmg%3d%3d');
	//echo $data;
	
	$myAES =  new AES();
	echo $myAES->decrypt($data);


	//$Prim = 'PLeU3Bu7S9WoEkJQXoUsx4BHNRIoovHk3oRJq1uemFAFOtpm8i7yRE0H4OAHr3ZZAU%2bJJk3fMf%2bxfGd4BeCXmg%3d%3d';

	
	echo "quiting...";
?>
