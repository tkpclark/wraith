<?php

	 function datepp($date)
	 {
	 		date_default_timezone_set('Asia/Chongqing');
	 		$ee=explode("-",$date);
			return date("Y-m-d",mktime(0,0,0,$ee[1],$ee[2]+1,$ee[0]));
	 }
?>
