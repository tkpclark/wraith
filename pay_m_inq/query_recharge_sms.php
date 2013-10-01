<?php 
	include("check.php"); 
	include("style.php");
	include("calendar/cld.php");
	echo "<body>";
	
	//0:等待处理 1:可做为订单充值的记录   2:用户不在规定省份 3：下行扣费失败 
	$sms_status[0] = '等待报告';
	$sms_status[1] = '充值成功';
	$sms_status[2] = '省份错误';
	$sms_status[3] = '扣费失败';
	
	$order_status[0] = '等待充值';
	$order_status[1] = '充值完毕，等待发送';
	$order_status[2] = '已完成';
	$order_status[4] = '被覆盖';
	
	$pn=isset($_REQUEST['pn'])?$_REQUEST['pn']:"";  //inquiry this month
	$start_date=isset($_REQUEST['d1'])?$_REQUEST['d1']:""; 
	$end_date=isset($_REQUEST['d2'])?$_REQUEST['d2']:"";
 	$pg_num=isset($_GET['pg_num'])?$_GET['pg_num']:1;
 	
 	$page_title="短信查询";
	$page_name="query_recharge_sms.php";
	$pg_lmt=1000;
	$tbl="wraith_pay_m_record";
	$start_item=($pg_num-1)*$pg_lmt;
	
	echo "<div><font size=4 color=red>$page_title</font></div>";
	
	
 	echo "<form name=pn_inq action= $page_name method=post\">";
 	echo "<td>&nbsp;开始时间&nbsp;<input id='date1' name='d1' type='text' </td>";
 	echo "<td>&nbsp;结束时间&nbsp;<input id='date2' name='d2' type='text' ></td>";
 	echo "&nbsp;手机号码：<input type=\"text\" name=\"pn\">";
 	echo "<input type=submit name=submit value=查询>";
 	echo "</form>";
 	
 	/////
	$sql_where = "where Phone like '%$pn%'";
	if($start_date)
		$sql_where.=" and in_time > '$start_date'";
	if($end_date)
		$sql_where.=" and in_time < '$end_date'";
	
	$sql="select count(*) from $tbl	".$sql_where;

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

	 	$sql="select * from $tbl ".$sql_where." order by ID desc limit $start_item,$pg_lmt ;";

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
  				<th>短信内容</th>
  				<th>资费</th>
  				<th>上行时间</th>
  				<th>下行时间</th>
  				<th>短信状态</th>
  				<th>订单状态</th>
  			</tr>";
  while ($row = $result->fetch_row())
  {
  		$status = $row[5];
  		echo"<tr>
  					<td>$row[0]</td>
  					<td>$row[9]</td>
  					<td>$row[1]</td>
  					<td>$row[4]</td>
  					<td>$row[7]</td>
  					<td>$row[3]</td>
  					<td>$row[8]</td>
  					<td>$sms_status[$status]</td>";

  		if(strlen($row[9])>3)//orderNo exists
  		{
	  		//get order status
	  		$tsql="select status from wraith_pay_m_request where OrderNo='$row[9]'";
	  		$tresult=exsql($tsql) or die (mysql_error());
	  		$trow = $tresult->fetch_row();
	  		mysqli_free_result($tresult);
	  		$ostatus=$trow[0];
	  		echo "<td>$order_status[$ostatus]</td></tr>";
  		}
  		else
  		{
  			echo "<td></td></tr>";
  		}
  		//echo "<td>$ostatus</td></tr>";
  }
   mysqli_free_result($result);
   
   echo "</table>";
   echo "<br>";
   echo "</body>";
   
?>
