<?php
	include("check.php"); 
	include("style.php");
	
	if(!isset($_GET['channelid']))
	{
		exit;
	}
	$channelid=$_GET['channelid'];
	
	$sql="delete from mtrs_channel where ID=$channelid";
	echo $sql;
	mysql_query($sql) or die (mysql_error());
	
	Header("Location:channel_list.php");
	
?>