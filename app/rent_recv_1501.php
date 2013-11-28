<?php


require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('rent_record');


if(!isset($_REQUEST['data']))
{
	echo "arguments error!";
	exit;
} 

/*  mt 加密前参数以@#分隔 依次为手机号@#指令@#长号码@#linkid@下行内容 */
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
