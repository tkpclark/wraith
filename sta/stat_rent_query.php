<?php
	require_once 'mysql.php';
	
	$tbl="wraith_rent_record";
	
	
	/***********condition***********/
	$sql_condition="where 1  ";
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
		$sql.=$sql_condition." and report='ok'";
		//echo $sql;
		$result=mysqli_query($mysqli,$sql);
		$row=mysqli_fetch_assoc($result);

		echo json_encode($row);
		exit;
		
	}
	if($query_type=='result_page')
	{
		echo "<table width=100%>";
		echo "<tr>
				<th>ID</th>
				<th>手机号码</th>
				<th>时间</th>
				<th>指令</th>
				<th>省份</th>
				<th>地区</th>
				<th>金额</th>
				<th>状态</th>
				</tr>";
		
		$sql="select id,phone_number,in_date,message,province,area,amount,report from $tbl ";
		$sql.=$sql_condition;
		$sql.=" order by id desc";
		$sql.=" limit ".$_REQUEST['pageSize']*($_REQUEST['pageNumber']-1).",".$_REQUEST['pageSize'];
		//echo $sql;
		mysqli_query($mysqli,"set names utf8");
		if($result=mysqli_query($mysqli,$sql))
		{
			while($row=mysqli_fetch_assoc($result))
			{
				echo "<tr align='center'>";
				echo "<td>".$row['id']."</td>";
				echo "<td>".$row['phone_number']."</td>";
				echo "<td>".$row['in_date']."</td>";
				echo "<td>".$row['message']."</td>";
				echo "<td>".$row['province']."</td>";
				echo "<td>".$row['area']."</td>";
				echo "<td>".$row['amount']."</td>";
				echo "<td>".$row['report']."</td>";
				echo "</tr>";
			}
			mysqli_free_result($result);
		}
		echo "</table>";
		
		
	}
