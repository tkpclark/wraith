<?php

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');
require_once('AES_official.php');
require_once('pay_m_lib.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('rent_sender');



function send_to_destination($request)
{
	global $logging;
	global $mysqli;
	
	if($request['deal_flag']==0)//send mo
	{
		$header = 'http://202.85.209.109/recv_mo_sms';
		$url = sprintf("%s?spnumber=%s&message=%s&phone=%s&linkid=%s",$header,$request['sp_number'],$request['message'],$request['phone_number'],$request['linkid']);
		$finish_status = 1; 
	}
	else if($request['deal_flag']==1)//send mr
	{
		$header = 'http://202.85.209.109/recv_mr_sms';
		$url = sprintf("%s?msgstatus=%s&linkid=%s",$header,$request['report'],$request['linkid']);
		$finish_status = 2;
	}
	$logging->info("url:".$url);
	$resp = file_get_contents($url);
	if($resp != 'ok')
	{
		$logging->info("ERROR:resp:".$resp);
		return;
	}

	
	////update db
	mysqli_query($mysqli, "set names utf8");
	$sql = sprintf("update wraith_rent_record set deal_flag=%d,send_time=NOW() where id = '%s'",$finish_status, $request['id']);
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
}
function update_one_report($linkid,$report)
{
	global $logging;
	global $mysqli;
	$sql = sprintf("update wraith_rent_record set report='%s' where linkid='%s'", $report, $linkid);
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}
}
function check_report($linkid)
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select report from `wraith_mt` where linkid='%s' and report is not null",$linkid);
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}
	if(mysqli_num_rows($res))
	{
		$request = $res->fetch_array(MYSQLI_ASSOC);
		//$logging->info("report:".$request['report']);
		if($request['report']=='4' or $request['report']=='DELIVRD')
			$report='DELIVRD';
		else
			$report='FAILED';
		update_one_report($linkid,$report);
		
	}
}

function update_report()
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select * from `wraith_rent_record` where (report is null or report='') and in_date > NOW()-interval 1 day");
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}
	
	while($request = $res->fetch_array(MYSQLI_ASSOC))
	{
		//$logging->info("dealing request id:".$request['id']);
		check_report($request['linkid']);
	}
}

function send_record()
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select * from `wraith_rent_record` where deal_flag in (0,1) and length(report) > 2 and in_date > NOW()-interval 1 day");
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}

	while($request = $res->fetch_array(MYSQLI_ASSOC))
	{
		//$logging->info("dealing request id:".$request['id']);
		send_to_destination($request);		
	}
}


//================main================

while(1)
{
	update_report();
	send_record();
	sleep(1);

}
