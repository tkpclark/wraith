<?php
	include("check.php"); 
	include("style.php");
	
$cpID = isset($_REQUEST['cpID'])?$_REQUEST['cpID']:"";
$cpname = isset($_REQUEST['cpname'])?$_REQUEST['cpname']:"";
$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";

if($cpID=="")
	$sql = "insert into mtrs_cp (cpname,status) values('$cpname','$status')";
else
	$sql="update mtrs_cp set cpname='$cpname',status='$status' where ID=$cpID";
echo $sql;


mysql_query($sql) or die (mysql_error());

Header("Location:cp_list.php");
