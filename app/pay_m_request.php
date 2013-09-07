<?php
	

	require_once('/home/log4php/php/Logger.php');
	require_once('mysql.php');
	require_once('AES_official.php');
	
	Logger::configure('log4php_config.xml');
	$logging = Logger::getLogger('pay_m_request');
	
	$pieces = explode("_",$_SERVER['REQUEST_URI']);
	$coop_id = $pieces[2];
	//echo $coop_id;
	
	/*
	echo $_REQUEST['Prim'];
	echo $_REQUEST['PayChannelCode'];
	exit;
	*/
	
	
	if(!isset($_REQUEST['Prim']) || !isset($_REQUEST['PayChannelCode']) )
	{
		echo "401~参数错误!~";
		exit;
	}

	$PayChannelCode = $_REQUEST['PayChannelCode'];
	
	$myAES =  new AES();
	$prim = $myAES->decrypt(urldecode($_REQUEST['Prim']));
	//$prim_test = "kpPovuQMHkeQlf7u2AkAfbQW1YbpOWA6YMuOulGjVVgGAGOPsGz6B94HKhWD0ajFQdBhweb11ySGqzBceRtaoA%3d%3d";
	//$prim = $myAES->decrypt(urldecode($prim_test));
	$logging->info("prim:".$prim);
	
	
	
	$arguments = explode("~",$prim);
	
	$sql = sprintf("insert into wraith_pay_m_request(in_time,OrderNo,Phone,fee,CKTime,PayChannelCode,coop_id)
			 values(NOW(),'%s','%s','%s','%s','%s','%s')",
			 $arguments[0],
			 $arguments[1],
			 $arguments[2],
			 $arguments[3],
			 $PayChannelCode,
			 $coop_id
			 );
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
	echo "000~成功~";

	
	
?>