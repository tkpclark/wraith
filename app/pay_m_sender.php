<?php

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');
require_once('AES_official.php');
require_once('pay_m_lib.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('pay_m_sender');



function send_to_coop_do($request)
{
	global $logging;
	global $mysqli;
	
	$Prim = sprintf("1~%s~%s~%s~%s~",$request['OrderNo'],$request['fee'],$request['PayChannelCode'],$request['Phone']);
	
	$myAES =  new AES();
	$url='http://eft.5151pay.com/GetInfo.aspx';
	$post_data = "Prim=".$myAES->encrypt($Prim)."&PayChannelCode=".$request['PayChannelCode'];
	$logging->info("url:".$url." post:". $post_data);
	//begin to send
	$ch = curl_init () ;
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	$resp = curl_exec ( $ch ) ;
	curl_close($ch);
	
	if($resp == '')
	{
		$logging->info("not get resp , send later!");
		return;
	}
	$logging->info("resp:".$resp);
	
	
	////update db
	mysqli_query($mysqli, "set names utf8");
	$sql = sprintf("update wraith_pay_m_request set status='2', response2='%s',send_time=NOW() where id = '%s'",$resp,$request['id']);
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
	return $resp;
	
}


function scan_request()
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select * from `wraith_pay_m_request` where status='1' and in_time > NOW()-interval 1 day");
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}

	while($request = $res->fetch_array(MYSQLI_ASSOC))
	{
		$logging->info("dealing request id:".$request['id']);
		send_to_coop_do($request);		
	}
}


//================main================

while(1)
{

	scan_request();
	sleep(1);

}
