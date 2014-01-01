<?php
	include("check.php"); 
	include("style.php");

$channelID = isset($_REQUEST['channelID'])?$_REQUEST['channelID']:"";
$spID = isset($_REQUEST['spID'])?$_REQUEST['spID']:"";
$spnumber = isset($_REQUEST['spnumber'])?$_REQUEST['spnumber']:"";
$mocmd = isset($_REQUEST['mocmd'])?$_REQUEST['mocmd']:"";
$msgtype = isset($_REQUEST['msgtype'])?$_REQUEST['msgtype']:"";
$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";
$fee = isset($_REQUEST['fee'])?$_REQUEST['fee']:"";

if($channelID=="")
	$sql = "insert into mtrs_channel (spnumber,mocmd,msgtype,status,spID,fee) values('$spnumber','$mocmd','$msgtype','$status','$spID','$fee')";
else
	$sql="update mtrs_channel set spnumber='$spnumber',mocmd='$mocmd',msgtype='$msgtype',spID='$spID',status='$status',fee='$fee' where ID=$channelID";
echo $sql;


mysql_query($sql) or die (mysql_error());

Header("Location:channel_list.php");
