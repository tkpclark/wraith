<?php 
	include("check.php"); 
	include("style.php");
	include("fun.php");
	echo "<body>";
	echo "<form action=# method=POST>";
	
	echo "开始日期&nbsp;";
	echo "<input type=text name=start_date value=2010-09-01 size=20>";
	echo "&nbsp;&nbsp;";
	echo "结束日期&nbsp;";
	echo "<input type=text name=end_date value=2010-10-01 size=20>";
	echo "&nbsp;&nbsp;";
	
	if($_COOKIE['type']==1)
	{
		echo "上行指令&nbsp;";
		echo "<input type=text name=mo size=20>";
	}
	else if($_COOKIE['type']==5)
	{
				$sql="select mo from user 
				where username like '".$_COOKIE['username']."'";
				$result=mysql_query($sql) or die (mysql_error());
		  	$row=mysql_fetch_row($result);
		  	mysql_free_result($result);
		  	$mo=$row[0];
		  	//$mo_str="'".str_replace("|","' or '",$mo)."'";
		  	
	}
	echo "<input type=submit value=统计><input type=reset value=重置>";
	echo"</form>";
	echo "</body>";	
	
	if($_POST['start_date']&&$_POST['end_date'])
	{
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
			echo "开始日期[$start_date]结束日期[$end_date]";
			if($start_date > $end_date )
			{
				 echo "数据日期有误!开始日期不得大于结束日期";
				 exit;
			}
			
			if($_COOKIE['type']==1)
			{
					$mo=$_POST['mo']?$_POST['mo']:'%';
					if($mo=='%')
						echo "上行指令[全部指令]";
					else
						echo "上行指令[$mo]";
					
			}
			else if($_COOKIE['type']==5)
			{
						$sql="select mo from user 
						where username like '".$_COOKIE['username']."'";
						$result=mysql_query($sql) or die (mysql_error());
				  	$row=mysql_fetch_row($result);
				  	mysql_free_result($result);
				  	$mo=$row[0];
			}
			else;
			echo "<br>";	
			echo "<table width=400>";
	 		echo "<tr bgcolor=#645375>
	  				<th>日期</th>
	  				<th>上行指令</th>
	  				<th>订购数量</th>
	  				
	  			</tr>";
			$stat_start_date=$start_date;
			$stat_end_date=datepp($stat_start_date);
			//echo $stat_start_date."<br>".$stat_end_date;
		  while(1)
		  {
		  		if($stat_start_date > $end_date)
		  			break;
			  	$mo_e=explode("|",$mo);
		  		for($i=0;$i < sizeof($mo_e);$i++)
		  		{	
				  	$sql="select count(*) from mo where  mo.linkid is not null and mo.linkid !='' and mo.time between '$stat_start_date' and '$stat_end_date' and mo.message like '$mo_e[$i]'";
				  	//echo $sql."<br>";
				  	$result=mysql_query($sql) or die (mysql_error());
				  	$row=mysql_fetch_row($result);
				  	mysql_free_result($result);
			  		echo"<tr>";
			  		echo"<td>$stat_start_date</td>";
			  		echo"<td>$mo_e[$i]</td>";
			  		echo"<td>$row[0]</td>";
			  		echo"</tr>";
				  	$total+=$row[0];
				  }
			  	
			  	$stat_start_date=datepp($stat_start_date);
			  	$stat_end_date=datepp($stat_start_date);
			  	
		  }
		  echo "<tr><td>总计</td><td></td><td>$total</td>";
	   echo "</table>";
	   echo "<br>";
	 }
?>
