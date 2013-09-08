<?php

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');
require_once('AES_official.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('pay_m_sender');

function update_report($linkid)
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select report from `wraith_mt` where linkid = '%s' limit 1 ",$linkid );
	$res = mysqli_query($mysqli, $sql);
	if(mysqli_num_rows($res))
	{
		$row = $res->fetch_array(MYSQLI_ASSOC);
		if($row['report']=='4')
			$report = 2;
		else 
			$report = 0;
		$sql_update = sprintf("update wraith_pay_m_record set report = '%s' where linkid = '%s'",$report,$linkid );
		$logging->info($sql_update);
		mysqli_query($mysqli, $sql_update);
	
		
		if($report==2)
			return true;
		else
			return false;
	}
	
	return false;
}
function send_to_coop_do($Prim,$PayChannelCode)
{
	global $logging;
	global $mysqli;
	$myAES =  new AES();
	$url='http://eft.5151pay.com/GetInfo.aspx';
	$post_data = "Prim=".$myAES->decrypt($Prim)."&PayChannelCode=".$PayChannelCode;
	$logging->info("url:".$url." post:". $post_data);
	//begin to send
	$ch = curl_init () ;
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$result = curl_exec ( $ch ) ;
	curl_close($ch);
	$logging->info("result:".$result);
	return $result;
	
}
function send_to_coop($row_record)
{
	global $logging;
	global $mysqli;
	//get orderno and  from request table
	$sql = sprintf("select OrderNo, PayChannelCode from wraith_pay_m_request where  Phone = '%s' order by id limit 1", $row_record['Phone']);
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
	if(mysqli_num_rows($res)>0)
	{
		$row = $res->fetch_array(MYSQLI_ASSOC);
		
		$Prim = sprintf("1~%s~%s~%s~%s~",$row['OrderNo'],$row_record['fee']/100,$row['PayChannelCode'],$row_record['Phone']);
		$logging->info("prim:".$Prim);
		
		$result = send_to_coop_do($Prim,$row['PayChannelCode']);
		
		
		//update resp to the db
		mysqli_query($mysqli, "set names utf8");
		$sql = sprintf("update wraith_pay_m_record set resp='%s' where id = '%s'",$result,$row_record['id']);
		$logging->info($sql);
		$res = mysqli_query($mysqli, $sql);
		if (!$res) {
			$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		}
		$is_trans=1;
		
	}
	else //no this OrderNo
	{
		$is_trans=2;
	}
	
	
	$sql = sprintf("update wraith_pay_m_record set is_trans='%s',out_time=NOW() where id = '%s'",$is_trans,$row_record['id']);
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
}
function scan_record()
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select * from `wraith_pay_m_record` where in_time > NOW() - interval 1 day and is_trans=0 and report is NULL");
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
	
	while($row = $res->fetch_array(MYSQLI_ASSOC))
	{
		//echo $row['Phone'];
		if(update_report($row['linkid'])==true)
		{
			send_to_coop($row);
		}
		
		 
	}
}




while(1)
{
	
	scan_record();
	sleep(1);
	exit;
	
}
