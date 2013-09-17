<?php

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');
require_once('AES_official.php');
require_once('pay_m_lib.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('pay_m_record_checker');


function get_report($linkid)
{
	//return 0:no report 1:success report 2:failed report
	global $logging;
	global $mysqli;
	$sql = sprintf("select report from `wraith_mt` where linkid = '%s' limit 1 ",$linkid );
	$res = mysqli_query($mysqli, $sql);
	if(mysqli_num_rows($res))
	{
		$row = $res->fetch_array(MYSQLI_ASSOC);
		if($row['report']=='4')
		{
			return 1;
		}
		elseif($row['report']=='')
		{
			return 0;
		}
		else
		{
			return 2;
		}
		/*
		$sql_update = sprintf("update wraith_pay_m_record set report = '%s' where linkid = '%s'",$report,$linkid );
		$logging->info($sql_update);
		mysqli_query($mysqli, $sql_update);
		*/

	}
	else
	{
		return 0;
	}
}

/*
function find_order($phone,&$order)
{
	global $logging;
	global $mysqli;
	//get orderno and  from request table
	$sql = sprintf("select OrderNo, PayChannelCode from wraith_pay_m_request where  Phone = '%s' order by id desc limit 1", $phone);
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
	if(mysqli_num_rows($res)>0)
	{
		$order = $res->fetch_array(MYSQLI_ASSOC);
		return true;
	}
	else //no this OrderNo
	{
		return false;
	}
	
	

	
}
*/
function update_record($id, $deal_flag)
{
	global $logging;
	global $mysqli;
	mysqli_query($mysqli, "set names utf8");
	$sql = sprintf("update wraith_pay_m_record set deal_flag='%s' ,deal_time=NOW() where id = '%s'",$deal_flag,$id);
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
}
function deal_record($record)
{
	//echo $row['Phone'];
	global $logging;
	if(is_phone_legal($record['Phone'])==0)
	{
		//$logging->info($record['Phone']." is out of provinces!");
		return 2;
	}
	/*
	if(is_reach_max_count($record['Phone']))
	{
		//$logging->info($record['Phone']." reach the max count!");
		return 5;
	}
	*/
	
	////
	$report = get_report($record['linkid']);
	if($report==0)
	{
		return 0;//report not get yet
	}
	elseif($report==1)
	{
		return 1;
	}
	elseif($report==2)
	{
		return 3;
	}
		
}
function scan_record()
{
	global $logging;
	global $mysqli;
	$sql = sprintf("select * from `wraith_pay_m_record` where deal_flag=0 and in_time > NOW()-interval 1 day");
	//$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
		exit;
	}
	
	while($record = $res->fetch_array(MYSQLI_ASSOC))
	{
		$logging->info("dealing record id:".$record['id']);
		$deal_flag = deal_record($record);
		$logging->info("deal result: ".$deal_flag);
		//update db
		if($deal_flag)
			update_record($record['id'],$deal_flag);
		 
	}
}


//================main================

while(1)
{
	
	scan_record();
	sleep(1);
	
}
