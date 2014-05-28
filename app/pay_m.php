<?php
	/*
	 * 此模块负责处理所有类似盛峰远景公司的网游充值业务
	 * 
	 */ 

	require_once('/home/log4php/php/Logger.php');
	require_once('mysql.php');
	require_once('aes.php');
	
	Logger::configure('log4php_config.xml');
	$logging = Logger::getLogger('pay1');
	
	$pieces = explode("_",$_SERVER['REQUEST_URI']);
	$coop_id = $pieces[1];
	echo $coop_id;
	
	
	$KEY = 't5d0c#OAn%MAhLWD';
	$IV =  'L+\~f4,Ir)b$=pkf';
	


	$prim = $_REQUEST['Prim'];
	$PayChannelCode = $_REQUEST['PayChannelCode'];
	
	$arguments = explode("~",fnDecrypt($prim,$KEY,$IV));
	
	$sql = sprintf("insert1 into wraith_pay_m_request(in_time,OrderNo,Phone,fee,CKTime,PayChannelCode,coop_id)
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

	
	
?>