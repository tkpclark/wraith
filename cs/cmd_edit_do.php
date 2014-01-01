<?php
	include("check.php"); 
	include("style.php");

$cmd_id = isset($_REQUEST['cmd_id'])?$_REQUEST['cmd_id']:"";
$cpID = isset($_REQUEST['cpid'])?$_REQUEST['cpid']:"";
$channelID = isset($_REQUEST['channelid'])?$_REQUEST['channelid']:"";
$spnumber = isset($_REQUEST['cmd_spnumber'])?$_REQUEST['cmd_spnumber']:"";
$mocmd = isset($_REQUEST['cmd_mocmd'])?$_REQUEST['cmd_mocmd']:"";
$deduction = isset($_REQUEST['deduction'])?round($_REQUEST['deduction']/100,2):"";
$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";
$url = isset($_REQUEST['url'])?$_REQUEST['url']:"";
$fee = isset($_REQUEST['fee'])?$_REQUEST['fee']:0;


if($cmd_id=="")
{
		$sql = "insert into mtrs_cmd (spnumber,mocmd,cpID,channelID,status,url) 
						values('$spnumber','$mocmd','$cpID','$channelID','$status', '$url')";
		echo $sql;
		mysql_query($sql) or die (mysql_error());
		
		//default deduction
		$sql = "insert into mtrs_deduction(cmdID,zone,deduction,fee) values('".mysql_insert_id()."','0','$deduction','$fee')";
		echo $sql;
		mysql_query($sql) or die (mysql_error());
}
else
{
		$sql = "update mtrs_cmd set cpID='$cpID',channelID='$channelID', spnumber='$spnumber', mocmd='$mocmd',status='$status',url='$url',fee=$fee
						where ID=$cmd_id";
		echo $sql;
		mysql_query($sql) or die (mysql_error());
		
		$sql = "update mtrs_deduction set deduction=$deduction where cmdID=$cmd_id and zone='0'";
		echo $sql;
		mysql_query($sql) or die (mysql_error());
}



Header("Location:cmd_list.php");
