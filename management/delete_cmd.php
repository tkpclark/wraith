<?php
include("check.php"); 
include("style.php");
if(!isset($_REQUEST['id']))
	die("no argument id");
$sql = "delete from mtrs_cmd where id=".$_REQUEST['id'];
echo $sql;
mysql_query($sql) or die (mysql_error());
Header("Location:cmd_list.php");