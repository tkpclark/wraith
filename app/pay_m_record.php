<?php

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('pay_m_record');



$record = json_decode($_REQUEST['record']);
//$logging->info($record);
//====================
$sql = sprintf("insert into wraith_pay_m_record(in_time,Phone,fee,linkid,message,sp_number,area)
			 values(NOW(),'%s','%s','%s','%s','%s','%s')",
		$record->{'phone_number'},
		$record->{'amount'},
		$record->{'linkid'},
		$record->{'message'},
		$record->{'sp_number'},
		$record->{'area'}
	);
$logging->info($sql);
mysqli_query($mysqli, "set names utf8");
$res = mysqli_query($mysqli, $sql);
if (!$res) {
	$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
}

//====================
$sql = sprintf("insert into wraith_mt(gwid,sp_number,phone_number,linkid,amount,product_id,product_code,message,in_time,area)
			values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',NOW() ,'%s')",
		$record->{'gwid'},
		$record->{'sp_number'},
		$record->{'phone_number'},
		$record->{'linkid'},
		$record->{'amount'},
		$record->{'product_id'},
		$record->{'product_code'},
		//$record->{'message'}
		$record->{'default_msg'},
		$record->{'area'}
);
$logging->info($sql);
mysqli_query($mysqli, "set names utf8");
$res = mysqli_query($mysqli, $sql);
if (!$res) {
	echo "Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error;
}