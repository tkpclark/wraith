<?php
	include("check.php"); 
	include("style.php");
	
	echo "<body>";
	
	$pn=isset($_POST['pn'])?$_POST['pn']:isset($_GET['pn'])?$_GET['pn']:"";  //inquiry this month
 	$pg_num=isset($_GET['pg_num'])?$_GET['pg_num']:1;
 	
 	$page_title="待发短信查询";
	$page_name="query_mt.php";
	$pg_lmt=10;
	$tbl="mt";
	$start_item=($pg_num-1)*$pg_lmt;
	
	
	echo "<div><font size=4 color=red>$page_title</font></div>";
	 	echo "<form name=pn_inq action= $page_name method=post\">";
	 	echo "手机号码：<input type=\"text\" name=\"pn\">";
	 	echo "<input type=submit name=submit value=查询>";
	 	echo "</form>";
 	

  
	
	
	//echo "pg_num:".$pg_num."<br>";
	//echo "pn:".$pn."<br>";
	
	////////
	$sql="set names gbk";
  mysql_query($sql);
	
	$sql="select count(*) from $tbl
				where address like '%$pn%' and issent=0";
	//echo $sql;
	$result=mysql_query($sql) or die (mysql_error());
	$row=mysql_fetch_row($result);
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
				where address like '%$pn% and issent=0'
				limit $start_item,$pg_lmt ;";
  $result=mysql_query($sql) or die (mysql_error());
  $got_num=mysql_num_rows($result);
  ////////
  //echo "result:$got_num<br>";
   echo "<table>";
      echo "<tr bgcolor=#645375>
      				<th>ID</th>
      				<th>目地号码</th>
      				<th>源号码</th>
      				
      				<th>产品ID</th>
      				<th>linkid</th>
      				<th>资费</th>
      				<th>网关响应</th>
      				
      				<th>状态报告</th>
      				<th>短信内容</th>
      				<th>插入时间</th>
      				<th>发送</th>
      			</tr>";
      while($row=mysql_fetch_row($result))
      {
      		echo"<tr>
      					<td>$row[0]</td>
      					<td>$row[1]</td>
      					<td>$row[2]</td>
      					
      					<td>$row[4]</td>
      					<td>$row[5]</td>
      					<td>$row[6]</td>
      					
      					<td>$row[8]</td>
      					<td>$row[10]</td>
      					<td>$row[3]</td>
      					
      					<td>$row[9]</td>
      					<td>$row[12]</td>
      				</tr>";
      }
       mysql_free_result($result);
       echo "</table>";
   echo "<br>";
   echo "</body>";
   
?>
