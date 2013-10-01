<?php
	include("check.php"); 
	include("style.php");
	
	if(!isset($_GET['cpid']))
	{
		exit;
	}
	$cpid=$_GET['cpid'];
	
	$sql="delete from mtrs_cp where ID=$cpid";
	echo $sql;
	mysql_query($sql) or die (mysql_error());
	
	Header("Location:cp_list.php");
	
?>