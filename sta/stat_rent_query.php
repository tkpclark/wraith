<?php

	/*******************显示结果*******************/
	echo "111";
	exit;
	$pg_lmt=20;
	$pg_seq=isset($_REQUEST['pq_seq'])?$_REQUEST['pq_seq']:1;
	$start_item=($pg_seq-1)*$pg_lmt;
	$sql_condition=" where in_date >= DATE_FORMAT('$start_date 00:00:00','%Y-%m-%d:%H') and in_date <= DATE_FORMAT('$end_date 23:59:59','%Y-%m-%d:%H')";
	
	$sql_limit = "limit $start_item,$pg_lmt ";
	$sql_count="select count(*) from $tbl ";
	$sql_sum="select sum(amount) from $tbl ";
	$sql_order="order by id desc ";
	
	$sql_main="select id,phone_number,in_date,product_id,province,area,amount from $tbl ";
	
	$product_id=$_REQUEST['product_id'];
	if(!empty($product_id))
		$sql_condition.="and product_id = '$product_id' ";
	
	$phone_number=$_REQUEST['phone_number'];
	if(!empty($phone_number))
		$sql_condition.="and phone_number = '$phone_number' ";
	
	
	
	
	/********get count of result*********/
	exsql("set names utf8");
	$sql=$sql_count.$sql_condition;
	echo "<br>$sql<br>";
	$result=mysqli_query($mysqli,$sql);
	$row=mysqli_fetch_row($result);
	$result_count=$row[0];
	$pg_count=ceil($result_count/$pg_lmt);
	echo "共有[$result_count]条记录&nbsp;&nbsp;&nbsp;";
	if($result_count==0)
		exit;
		
	//////////////
	
	$pg_seq_pre=$pg_seq-1;
	$pg_seq_next=$pg_seq+1;
	if($pg_seq > 1)
	{
		echo "<a href=\"$page_name?disp_result=1&phone_number=$phone_number&product_id=$product_id&pq_seq=1\">首页</a>&nbsp;";
		echo "<a href=\"$page_name?disp_result=1&phone_number=$phone_number&product_id=$product_id&pq_seq=$pg_seq_pre\">上一页</a>&nbsp;";
	}
	else
	{
		echo "首页&nbsp上一页&nbsp;";
	}
	if($pg_seq == $pg_count)
	{
		echo "下一页&nbsp;尾页&nbsp;";
	}
	else
	{
		echo "<a href=\"$page_name?disp_result=1&phone_number=$phone_number&product_id=$product_id&pq_seq=$pg_seq_next\">下一页</a>&nbsp;";
	 	echo "<a href=\"$page_name?disp_result=1&phone_number=$phone_number&product_id=$product_id&pq_seq=$pg_count\">尾页</a>&nbsp;";
	}
	
	echo "[$pg_seq/$pg_count]";
   //////////////
	

	 
	/**********get result************/
	echo "<table>";
	echo "<tr>
		<th>ID</th>
		<th>手机号码</th>
		<th>时间</th>
		<th>产品</th>
		<th>省份</th>
		<th>地区</th>
		<th>金额</th>
	</tr>";
	
	exsql("set names utf8");
	$sql=$sql_main.$sql_condition.$sql_order.$sql_limit;
	$result=exsql($sql);
	
	echo "<br>$sql<br>";
	//echo "result:".mysqli_num_rows($result)."<br>";
	$result=exsql($sql) or die (mysql_error());
	$record_count=0;
	$sum=0;//总金额
	while($row=mysqli_fetch_row($result))
	{
		echo "<tr align='center'>";
		echo "<td>$row[0]</td>";
		echo "<td>$row[1]</td>";
		echo "<td>$row[2]</td>";
		echo "<td>$row[3]</td>";
		echo "<td>$row[4]</td>";
		echo "<td>$row[5]</td>";
		echo "<td>$row[6]</td>";
		echo "</tr>";
	
		$record_count++;
		$sum+=$row[5];
	
	}
	mysqli_free_result($result);
	echo "</table>";
?>


