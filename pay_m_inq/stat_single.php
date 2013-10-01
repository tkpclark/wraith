<?php 
	include("check.php"); 
	include("style.php");
	include("fun.php");
	echo "<body>";
	echo "<form action=# method=POST>";
	
	echo "开始日期&nbsp;";
	echo "<input type=text name=start_date value=2011-03-01 size=20>";
	echo "&nbsp;&nbsp;";
	echo "结束日期&nbsp;";
	echo "<input type=text name=end_date value=2011-04-01 size=20>";
	echo "&nbsp;&nbsp;";
	
	if($_COOKIE['type']==1)
	{
		echo "上行指令&nbsp;";
		echo "<input type=text name=mo size=20>";
	}

	echo"<input type=checkbox name=prov />";
	echo "分省统计&nbsp;";

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
			
			
			
			$sql="set names gbk";
  		mysql_query($sql);
			
			if($_POST['prov']=="on")//分省统计
			{
				if($_COOKIE['type']==1)//管理员
				{
					if($_POST['mo']!='')
						$mo_str=$_POST['mo'];
					else
						$mo_str='%';
						/*
					$sql="select a.fprovince,count(*) from mt b
								join mo c on c.linkid=b.linkID and c.time=b.insert_time
								Left Join CODE_SEGMENT a 
								on substr(b.address,1,7)=a.fprefix 
								where b.insert_time > '$start_date' and b.insert_time<'$end_date '
								and b.report=4 group by a.fprovince 
								and c.message like '$mo_str'";
							*/
				}
				else if($_COOKIE['type']==5)//受限用户
				{
						$sql="select mo from user 
						where username like '".$_COOKIE['username']."'";
						$result=mysql_query($sql) or die (mysql_error());
				  	$row=mysql_fetch_row($result);
				  	mysql_free_result($result);
				  	$mo=$row[0];
				  	$mo_str=str_replace("|","' or '",$mo);
				}
				$condition="insert_time between '$start_date' and '$end_date '
								and report=4 
								and message like '$mo_str' ";
				$sql="select prov,count(*) from mt where $condition group by prov";
				//echo $sql."<br>";
				$result=mysql_query($sql) or die (mysql_error());
			  echo "<br>";	
				echo "<table width=400>";
	 			echo "<tr bgcolor=#645375>
	  				<th>省份</th>
	  				<th>数量</th>
	  				</str>";
	  		while($row=mysql_fetch_row($result))
	  		{
	  			if($_COOKIE['type']==1)//管理员
	  			{
	  				$prov_num=$row[1];
	  			}
	  			else if($_COOKIE['type']==5)//受限用户
	  			{
			  		$prov_num=ceil(0.88*$row[1]);
			  	}
			  	echo"<tr>
			  					<td>$row[0]</td>
			  					<td>$prov_num</td>
			  				</tr>";
  			}
  			 mysql_free_result($result);
  			 
  			 $sql="select count(*) from mt where $condition";
  			 $result=mysql_query($sql) or die (mysql_error());
  			 $row=mysql_fetch_row($result);
  			 echo"<tr>
			  					<td>总计</td>
			  					<td>$row[0]</td>
			  			</tr>";
  			 
  			 echo "</table>";
   			 echo "<br>";
			}
			else//按天统计
			{
					if($_COOKIE['type']==5)
					{
								$sql="select mo from user 
								where username like '".$_COOKIE['username']."'";
								$result=mysql_query($sql) or die (mysql_error());
						  	$row=mysql_fetch_row($result);
						  	mysql_free_result($result);
						  	$mo=$row[0];
						  	//$mo_str="'".str_replace("|","' or '",$mo)."'";
						  	
					}
					else if($_COOKIE['type']==1)
					{
						if($_POST['mo']!='')
						$mo=$_POST['mo'];
					else
						$mo='%';
					}
					echo "<br>";	
					echo "<table width=400>";
			 		echo "<tr bgcolor=#645375>
			  				<th>日期</th>
			  				<th>上行指令</th>
			  				<th>点播数量</th>
			  				
			  			</tr>";
					$stat_start_date=$start_date;
					$stat_end_date=datepp($stat_start_date);
					//echo $stat_start_date."<br>".$stat_end_date;
					
				  while(1)
				  {
				  		if($stat_start_date > $end_date)
				  			break;
				  		//echo "mo:".$mo."<br>";
//				  		if(!isset($mo))
//				  		$mo_e='%';
//				  		else
				  			$mo_e=explode("|",$mo);
				  		for($i=0;$i < sizeof($mo_e);$i++)
				  		{	
						  	$sql="select count(*) from mt where insert_time >= '$stat_start_date' and insert_time < '$stat_end_date'
						  	 			and report='4' 
						  	 			and mo like '$mo_e[$i]%'";
						  	//echo $sql."<br>";
						  	$result=mysql_query($sql) or die (mysql_error());
						  	$row=mysql_fetch_row($result);
						  	mysql_free_result($result);
					  		echo"<tr>";
					  		echo"<td>$stat_start_date</td>";
					  		echo"<td>$mo_e[$i]</td>";
					  		if($_COOKIE['type']==1)
					  			$num=$row[0];
					  		else if($_COOKIE['type']==5)
					  			$num=ceil(0.88*$row[0]);
					  		echo"<td>$num</td>";
					  		echo"</tr>";
					  		
						  	$total+=$num;
						  }
					  	
					  	$stat_start_date=datepp($stat_start_date);
					  	$stat_end_date=datepp($stat_start_date);
					  	
				  }
				  echo "<tr><td>总计</td><td></td><td>$total</td>";
			   echo "</table>";
			   echo "<br>";
			}
	 }
?>
