<?php
	require_once("check.php"); 
	//include("style.php");
	

	if(!isset($_GET['cmd_id'])||!isset($_GET['area_code'])||!isset($_GET['deduction_value']))
		exit;
	$cmd_id=$_GET['cmd_id'];
	$area_code=$_GET['area_code'];
	$deduction_value=$_GET['deduction_value'];
	echo $cmd_id.$area_code.$deduction_value;
	$deduction_value=number_format($deduction_value/100,2);
	$sql = "insert into mtrs_deduction (cmdID, zone, deduction) values ('$cmd_id', '$area_code', '$deduction_value')";
	mysql_query($sql) or die (mysql_error());
?>

number_format(100*$row[11]/$row[10],2)