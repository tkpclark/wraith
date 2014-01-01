<?php
	include("check.php"); 
	include("style.php");
	
	if(!isset($_GET['id']))
	{
		exit;
	}
	$id=$_GET['id'];
	
	$sql="delete from wraith_blklist where ID=$id";
	echo $sql;
	exsql($sql);
	
	Header("Location:blklist.php");
	
?>