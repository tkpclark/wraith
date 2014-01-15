<?php
	require_once 'mysql.php';
	
	$tbl="wraith_rent_record";
	
	
	/***********condition***********/
	$sql_condition="where 1 ";
	if(isset($_REQUEST["query_type"]))
	{
		$query_type=$_REQUEST["query_type"];
	}
	if(isset($_REQUEST["phone_number"])&&!empty($_REQUEST["phone_number"]))
	{
		$sql_condition.="and phone_number='".$_REQUEST["phone_number"]."' ";
	}
	if(isset($_REQUEST["products"]))
	{
		$sql_condition.="and product_seq in(".$_REQUEST["products"].") ";
	}
	if(isset($_REQUEST["date1"]))
	{
		$sql_condition.="and in_date>='".$_REQUEST["date1"]."' ";
	}
	if(isset($_REQUEST["date2"]))
	{
		$sql_condition.="and in_date<='".$_REQUEST["date2"]."' ";
	}
	

	
	
	/**********result_info***************/
	if($query_type=='result_info')
	{
		$sql="select count(amount) as count,ifnull(ceil(sum(amount)/100),0) as sum from $tbl  ";
		$sql.=$sql_condition;
		//echo $sql;
		$result=mysqli_query($mysqli,$sql);
		$row=mysqli_fetch_assoc($result);

		echo json_encode($row);
		exit;
		
	}
