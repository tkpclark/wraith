<?php 
	include("check.php"); 
	include("style.php");
	echo "<body>";
	
	//0:等待收集充值短信 1：已收集完毕等待发送 2：已发送  4：被覆盖的订单
	$order_status[0] = '等待充值';
	$order_status[1] = '充值完毕';
	$order_status[2] = '已完成';
	$order_status[4] = '被覆盖';
	
	$pn=isset($_REQUEST['pn'])?$_REQUEST['pn']:"";  //inquiry this month
 	$pg_num=isset($_GET['pg_num'])?$_GET['pg_num']:1;
 	
 	$page_title="订单查询";
	$page_name="query_order.php";
	$pg_lmt=1000;
	$tbl="wraith_pay_m_request";
	$start_item=($pg_num-1)*$pg_lmt;
	
	echo "<div><font size=4 color=red>$page_title</font></div>";
	 	echo "<form name=pn_inq action= $page_name method=post\">";
	 	echo "手机号码：<input type=\"text\" name=\"pn\">";
	 	echo "<input type=submit name=submit value=查询>";
	 	echo "</form>";
 	

		$sql="select count(*) from $tbl
				where Phone like '%$pn%'";

	//echo $sql;
	$result=mysqli_query($mysqli,$sql);
	$row = mysqli_fetch_row($result);
	$total_num=$row[0];
	$total_pg_num=ceil($total_num/$pg_lmt);
	echo "您查询的条件为[*$pn*],共有[$total_num]条记录&nbsp;&nbsp;&nbsp;";
	if($total_num==0)
		exit;
		
	//////////////
	 $pre_pg_num=$pg_num-1;
   $next_pg_num=$pg_num+1;
   if($pg_num > 1)
   {
   		echo "<a href=\"$page_name?pn=$pn&pg_num=1\">首页</a>&nbsp;";
   		echo "<a href=\"$page_name?pn=$pn&pg_num=$pre_pg_num\">上一页</a>&nbsp;";
   		
   }
   else
   {
   		echo "首页&nbsp上一页&nbsp;";
   }
   if($pg_num == $total_pg_num)
   {
   		echo "下一页&nbsp;尾页&nbsp;";
   }
   else
   {
   	 echo "<a href=\"$page_name?pn=$pn&pg_num=$next_pg_num\">下一页</a>&nbsp;";
   	  echo "<a href=\"$page_name?pn=$pn&pg_num=$total_pg_num\">尾页</a>&nbsp;";
   }
   
   echo "[$pg_num/$total_pg_num]";
   
   
   //////////////

	 	$sql="select * from $tbl
				where Phone like '%$pn%'
				order by ID desc
				limit $start_item,$pg_lmt ;";

	 //echo $sql;
	 exsql("set names utf8");
  $result=exsql($sql) or die (mysql_error());
  $got_num=mysqli_num_rows($result);
  ////////
  //echo "result:$got_num<br>";
  echo "<table>";
  echo "<tr>
  				<th>ID</th>
  				<th>订单号</th>
  				<th>手机号</th>
  				<th>资费</th>
  				<th>上行时间</th>
  				<th>下行时间</th>
  				<th>订单状态</th>
  			</tr>";
  while ($row = $result->fetch_row())
  {
  		$status = $row[9];
  		echo"<tr>
  					<td>$row[0]</td>
  					<td>$row[2]</td>
  					<td>$row[3]</td>
  					<td>$row[4]</td>
  					<td>$row[1]</td>
  					<td>$row[10]</td>
  					<td>$order_status[$status]</td>
			</tr>";  				

  		
  }
   mysqli_free_result($result);
   
   echo "</table>";
   echo "<br>";
   echo "</body>";
   
?>
