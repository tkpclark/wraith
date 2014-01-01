<?php 
	include("check.php"); 
	include("style.php");
	include("fun.php");
	echo "<body>";
	echo "<form action=# method=POST>";
	
	echo "开始日期&nbsp;";
	echo "<input type=text name=start_date value=2010-08-01 size=20>";
	echo "&nbsp;&nbsp;";
	echo "结束日期&nbsp;";
	echo "<input type=text name=end_date value=2010-08-31 size=20>";
	echo "<input type=submit value=统计><input type=reset value=重置>";
	echo"</form>";
	echo "</body>";	
	
	if($_POST['start_date']&&$_POST['end_date'])
	{
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];

			$mo=$_POST['mo']?$_POST['mo']:'%';
			
			echo "开始日期[$start_date]结束日期[$end_date]";
			if($mo=='%')
				echo "上行指令[ALL]";
			else
				echo "上行指令[$mo]";
			echo "<br>";	
			if($start_date > $end_date )
			{
				 echo "数据日期有误!开始日期不得大于结束日期";
				 exit;
			}
			
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
		  		if($stat_start_date >= $end_date)
		  			break;
			  	$sql="select count(*) from mo where message like '$mo' and time between '$stat_start_date' and '$stat_end_date'";
			  	//echo $sql."<br>";
			  	$result=mysql_query($sql) or die (mysql_error());
			  	$row=mysql_fetch_row($result);
			  	mysql_free_result($result);
		  		echo"<tr>
		  					<td>$stat_start_date</td>
		  				";
		  		if($mo=='%')
		  					echo "<td>ALL</td>";
		  		else
		  					echo"<td>$mo</td>";
		  		echo "<td>$row[0]</td>
		  				</tr>";
			  	$total+=$row[0];
			  	
			  	$stat_start_date=datepp($stat_start_date);
			  	$stat_end_date=datepp($stat_start_date);
			  	
		  }
		  echo "<tr><td>总计</td><td></td><td>$total</td>";
	   echo "</table>";
	   echo "<br>";
	 }

?>