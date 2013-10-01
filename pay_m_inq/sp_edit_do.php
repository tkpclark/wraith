<?php
	include("check.php"); 
	include("style.php");
	
$spID = isset($_REQUEST['spID'])?$_REQUEST['spID']:"";
$spname = isset($_REQUEST['spname'])?$_REQUEST['spname']:"";
$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";

if($spID=="")
	$sql = "insert into mtrs_sp (spname,status) values('$spname','$status')";
else
	$sql="update mtrs_sp set spname='$spname',status='$status' where ID=$spID";
echo $sql;


mysql_query($sql) or die (mysql_error());

Header("Location:sp_list.php");
