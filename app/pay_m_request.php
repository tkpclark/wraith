<?php
	

require_once('/home/log4php/php/Logger.php');
require_once('mysql.php');
require_once('AES_official.php');

Logger::configure('log4php_config.xml');
$logging = Logger::getLogger('pay_m_request');

$p1 = explode("_",$_SERVER['REQUEST_URI']);
$p2 = explode("?",$p1[2]);
$coop_id = $p2[0];
//echo $coop_id;

/*
echo $_REQUEST['Prim'];
echo $_REQUEST['PayChannelCode'];
exit;
*/
function is_phone_legal($phone)
{
	return 1;
}

if(!isset($_REQUEST['Prim']) || !isset($_REQUEST['PayChannelCode']) )
{
	echo "401~参数错误!~";
	exit;
}

$PayChannelCode = $_REQUEST['PayChannelCode'];



$myAES =  new AES();
$prim = $myAES->decrypt($_REQUEST['Prim']);

$logging->info("prim:".$prim);
$arguments = explode("~",$prim);

if(is_phone_legal($arguments[1])==0)
{
	$response = "606~不接受订单~";
}
else
{
	$response = "000~成功~";
}
echo $response;



//to db
//cover the request of same phone_number

$sql = sprintf("update wraith_pay_m_request set status=4 where status=0 and phone='%s'",$arguments[1]);
$logging->info($sql);
$res = mysqli_query($mysqli, $sql);
if (!$res) {
	$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
}


$sql = sprintf("insert into wraith_pay_m_request(in_time,OrderNo,Phone,fee,CKTime,PayChannelCode,coop_id,response1)
		 values(NOW(),'%s','%s','%s','%s','%s','%s','%s')",
		 $arguments[0],
		 $arguments[1],
		 $arguments[2],
		 $arguments[3],
		 $PayChannelCode,
		 $coop_id,
		 $response
		 );
$logging->info($sql);
mysqli_query($mysqli, "set names utf8");
$res = mysqli_query($mysqli, $sql);
if (!$res) {
	$logging->info("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
}

	

	
	
?>