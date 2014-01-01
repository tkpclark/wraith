<script Language="JavaScript">
function ask(id)
{
	var str=
		answer=confirm("确定要删除id="+id+"的记录吗？");
	if(answer=="1")
		return true;
	else 
		return false;
}
</script>

<?php 
	include("check.php"); 
	include("style.php");
	echo "<body>";
	
	$pn=isset($_POST['pn'])?$_POST['pn']:isset($_GET['pn'])?$_GET['pn']:"";  //inquiry this month
 	$pg_num=isset($_GET['pg_num'])?$_GET['pg_num']:1;
 	
 	$page_title="黑名单";
	$page_name="blklist.php";
	$pg_lmt=20;
	$tbl="wraith_blklist";
	$start_item=($pg_num-1)*$pg_lmt;
	
	echo "<div><font size=4 color=red>$page_title</font></div>";
	 	echo "<form name=pn_inq action= $page_name method=post\">";
	 	echo "手机号码：<input type=\"text\" name=\"pn\">";
	 	echo "<input type=submit name=submit value=查询>";
	 	echo "</form>";
	 	
	 	
	 	
		$sql="select count(*) from $tbl
				where phone_number like '%$pn%'";

	//echo $sql;
	$result=mysqli_query($mysqli,$sql);
	$row=mysqli_fetch_row($result);
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
				where phone_number like '%$pn%'
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
  				<th>手机号</th>
				<th>删除</th>

  				
  			</tr>";
  while($row=mysqli_fetch_row($result))
  {
  		echo"<tr><td>$row[0]</td><td>$row[1]</td>";
  		echo "<td align=center  onclick=\"return ask($row[0]);\"><font size=2><a href=\"blklist_del.php?id=$row[0]\" >删除</a>&nbsp;</td>";
  		echo "</tr>";
  }
   mysqli_free_result($result);
   
   echo "</table>";
  
   echo "<br>";
   echo "<form name=load_config method=post action=".$_SERVER['PHP_SELF']."?load=1 >";
   echo "<input type=submit name='submit' value='重新加载'  style='width:250px;height:500px;'>";
   if(isset($_GET['load']))
   {
   	$cmd = dirname(__FILE__)."/../controller/start_controller.sh";
   	//$cmd='python /home/sms/MsgTunnel/src/msgforward/config_maker.py';
   	//echo "cmd: ".$cmd."<br>";
   	exec($cmd, $output, $cmd_result);
   	if($cmd_result != 0)
   		echo "加载失败!<br>";
   				else
   					print_r($output[0]);
   					//var_dump($output);
   }
   echo "</body>";
   
?>
