<?php
	include("check.php"); 
	include("style.php");
	
	if(!isset($_GET['cmdid']))
	{
		exit;
	}
	$cmdid=$_GET['cmdid'];
	
	$sql="delete from mtrs_cmd where ID=$cmdid";
	echo $sql;
	mysql_query($sql) or die (mysql_error());
	
	$sql="delete from mtrs_deduction where cmdID=$cmdid";
	echo $sql;
	mysql_query($sql) or die (mysql_error());
	
	
	Header("Location:cmd_list.php");
	
?>