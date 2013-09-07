<?php

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');

$address['c1']

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('pay_m_sender');

function update_report($linkid)
{
	$sql = sprintf("select report from `wraith_mt` where linkid = '%s' limit 1 ",$linkid );
	$res = mysqli_query($mysqli, $sql);
	if(mysqli_num_rows($res))
	{
		$row = $res->fetch_array(MYSQLI_ASSOC);
		if($row[0]=='4')
			$report = 2;
		else 
			$report = 0;
		$sql_update = sprintf("update wraith_pay_m_record set report = '%s' where linkid = '%s'",$report,$linkid );
		mysqli_query($mysqli, $sql);
	
		
		if($report==2)
			return true;
		else
			return false;
	}
	
	return false;
}
function send_to_coop()
{
	
}
function scan_record()
{

	$sql = sprintf("select * from `wraith_pay_m_record` where in_time > NOW() - interval 1 day and is_trans=0 and report =2");
	$logging->info($sql);
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
	
	while($row = $res->fetch_array(MYSQLI_ASSOC))
	{
		// Get Parameter for next SP
		echo $row['Phone'];
		if(update_report($row['linkid'])==true)
		{
			send_to_coop();
		}
		
		 
	}
}




while(1)
{
	
	scan_record();
	sleep(1);
	
}
