<?php

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('rent_record');



$record = json_decode($_REQUEST['record']);
//$logging->info($record);
//====================

$status=strpos($record->{'allow_province'},$record->{'province'})===false?2:0;

$sql = sprintf("insert into wraith_rent_record(in_time,Phone,fee,linkid,message,sp_number,province,area,deal_flag)
			 values(NOW(),'%s','%s','%s','%s','%s','%s','%s','%s')",
		$record->{'phone_number'},
		$record->{'amount'},
		$record->{'linkid'},
		$record->{'message'},
		$record->{'sp_number'},
		$record->{'province'},
		$record->{'area'},
		$status
	);
$logging->info($sql);
mysqli_query($mysqli, "set names utf8");
$res = mysqli_query($mysqli, $sql);
if (!$res) {
	$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
}

//====================

if($status==0)//给用户回信息（如果不在规定省份内，则不下发）
{
	$sql = sprintf("insert into wraith_mt(gwid,sp_number,phone_number,linkid,amount,product_id,product_code,message,in_time,province,area)
				values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',NOW() ,'%s','%s')",
			$record->{'gwid'},
			$record->{'sp_number'},
			$record->{'phone_number'},
			$record->{'linkid'},
			$record->{'amount'},
			$record->{'product_id'},
			$record->{'product_code'},
			//$record->{'message'}
			$record->{'default_msg'},
			$record->{'province'},
			$record->{'area'}
	);
	$logging->info($sql);
	mysqli_query($mysqli, "set names utf8");
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
		echo "Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error;
	}
}