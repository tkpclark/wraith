<?php 
require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');
require_once('AES_official.php');
require_once('pay_m_lib.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('pay_m_request_checker');

function check_record($request)
{	
	global $logging;
	global $mysqli;
	
	
	$sql = sprintf("update `wraith_pay_m_record` set OrderNo='%s' where OrderNo is NULL and deal_flag= 1 and Phone='%s' and in_time > NOW()-interval 1 day",$request['OrderNo'],$request['Phone']);
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}
	
	
	$sql = sprintf("select sum(fee) as total_fee from `wraith_pay_m_record` where OrderNo='%s' and in_time > NOW()-interval 1 day",$request['OrderNo']);
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}
	$record = $res->fetch_array(MYSQLI_ASSOC);
	//$logging->info($record);
	if($record['total_fee'] >= 100*$request['fee'])// got all charging sms
	{
		$sql = sprintf("update `wraith_pay_m_request` set status=1 where OrderNo ='%s'",$request['OrderNo']);
		$logging->info($sql);
		$res = mysqli_query($mysqli, $sql);
		if (!$res) {
			$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
			exit;
		}
	}
	
}

function scan_request()
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select * from `wraith_pay_m_request` where status ='0' and in_time > NOW()-interval 1 day");
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}

	while($request = $res->fetch_array(MYSQLI_ASSOC))
	{
		//$logging->info("dealing request id:".$request['id']);

		check_record($request);		
	}
}


//================main================

while(1)
{

	scan_request();
	sleep(1);

}
?>