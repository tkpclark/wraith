<?php 
		include("check.php"); 
		include("style.php");
		include("calendar/cld.php");
		include("area_code.php");
		
	
		
		
		
	
		echo "<font size=4><caption>运营数据查询>></caption></font><br>";
		
		
		
		//form
		echo "<form name=statistic action= statistic.php method=post>";
		
		//table
		echo "<table border='1' cellspacing='0' cellpadding='1' width='80%' class='tabs'>";
		
		//first line of table
		echo "<th colspan='7'>查询条件</th>";
		
		//second line of table
		echo "<tr width='30' align='left'>";
		$start_date = isset($_POST['d1'])?$_POST['d1']:date("Y-m-d");;
		echo "<td>MO开始时间&nbsp;<input id='date1' name='d1' type='text' value=$start_date></td>";
	
	
		/***********sp*************/
		//$sp = (isset($_POST['sp'])&&($_POST['sp']!='%'))?$_POST['sp']:"全部";
		if(isset($_POST['sp']) && $_POST['sp']!='%')
		{
					$sql = "select spname from mtrs_sp where id='".$_POST['sp']."'";
					$result=mysql_query($sql) or die (mysql_error());
					$row=mysql_fetch_row($result);
					$sp=$row[0];
					$sp_value=$_POST['sp'];
		}
		else
		{
					$sp = "全部";
					$sp_value='%';
		}
		echo "<td>SP&nbsp;&nbsp;";
		echo "<select name=sp>";
		$sql="select id, spname from mtrs_sp where status=1";
		echo "<option value='$sp_value'>$sp</option>";
		echo "<option value='%'>全部</option>";
		$result=mysql_query($sql) or die (mysql_error());
		while($row=mysql_fetch_row($result))
		{
			echo "<option value=$row[0]>$row[1]</option>";
		}
		echo "</select>";
		echo "</td>";
		
		/***********cp*************/
		if(isset($_POST['cp']) && $_POST['cp']!='%')
		{
					$sql = "select cpname from mtrs_cp where id='".$_POST['cp']."'";
					$result=mysql_query($sql) or die (mysql_error());
					$row=mysql_fetch_row($result);
					$cp=$row[0];
					$cp_value=$_POST['cp'];
		}
		else
		{
					$cp = "全部";
					$cp_value='%';
		}
		echo "<td>CP&nbsp;&nbsp;";
		echo "<select name=cp>";
		$sql="select id, cpname from mtrs_cp where status=1";
		echo "<option value='$cp_value'>$cp</option>";
		echo "<option value='%'>全部</option>";
		$result=mysql_query($sql) or die (mysql_error());
	 	while($row=mysql_fetch_row($result))
		{
			echo "<option value=$row[0]>$row[1]</option>";
		}
		echo "</select>";
		echo "</td>";
		
		
		/***********phone*************/
		$phone=isset($_POST['phone'])?$_POST['phone']:"";
		echo "<td>手机号<input id=phone name=phone type=text value='$phone' /></td>";
		
		
		
		echo "<td></td>";
		echo "</tr>";
	
		//third line of table
		echo "<tr width='30' align='left'>";
		$end_date = isset($_POST['d2'])?$_POST['d2']:date("Y-m-d");;
		echo "<td>MO结束时间&nbsp;<input id='date2' name='d2' type='text' value=$end_date></td>";
		
		
		
		/***********channel*************/
		if(isset($_POST['channel']) && $_POST['channel']!='%')
		{
					$sql = "select spnumber, mocmd from mtrs_channel where id='".$_POST['channel']."'";
					$result=mysql_query($sql) or die (mysql_error());
					$row=mysql_fetch_row($result);
					$channel="$row[0]+$row[1]";
					$channel_value=$_POST['channel'];
		}
		else
		{
					$channel = "全部";
					$channel_value='%';
		}
		echo "<td>通道&nbsp;&nbsp;";
		echo "<select name=channel>";
		echo "<option value='$channel_value'>$channel</option>";
		echo "<option value='%'>全部</option>";
		$sql="select id, spnumber, mocmd from mtrs_channel where status=1";
		$result=mysql_query($sql) or die (mysql_error());
		while($row=mysql_fetch_row($result))
		{
			echo "<option value=$row[0]>$row[1]+$row[2]</option>";
		}
		echo "</select>";
		echo "</td>";
		
		
		
	
	
		/***********cmd*************/
		if(isset($_POST['cmd']) && $_POST['cmd']!='%')
		{
					$sql = "select spnumber, mocmd from mtrs_cmd where id='".$_POST['cmd']."'";
					$result=mysql_query($sql) or die (mysql_error());
					$row=mysql_fetch_row($result);
					$cmd="$row[0]+$row[1]";
					$cmd_value=$_POST['cmd'];
		}
		else
		{
					$cmd = "全部";
					$cmd_value='%';
		}
		echo "<td>指令";
		echo "<select name=cmd>";
		echo "<option value='$cmd_value'>$cmd</option>";
		echo "<option value='%'>全部</option>";
		$sql="select id, spnumber, mocmd from mtrs_cmd where status=1";
		$result=mysql_query($sql) or die (mysql_error());
		while($row=mysql_fetch_row($result))
		{
			echo "<option value=$row[0]>$row[1]+$row[2]</option>";
		}
		echo "</select>";
		echo "</td>";
		
		
		/***********zone*************/
		if(isset($_POST['zone']) && $_POST['zone']!='%')
		{
					$zone_value=$_POST['zone'];
					$zone=$area_code[$zone_value];
					
		}
		else
		{
					$zone = "全部";
					$zone_value='%';
		}
		
		echo "<td>地区&nbsp;&nbsp;&nbsp;";
		echo "<select name=zone>";
		echo "<option value='$zone_value'>$zone</option>";
		echo "<option value='%'>全部</option>";
		while($key = key($area_code))
		{
			echo "<option value='$key'>$area_code[$key]</option>";
			next($area_code);
		}
		echo "</select>";
		echo "</td>";
		
	
		
		echo "<td></td>";
		echo "</tr>";
		
		
		//分组信息
		echo "<tr>";
		echo "<td colspan='4'>";
		
		//日期分组
		$date_group=isset($_POST['date_group'])?"checked":"";
		echo "<input type='checkbox' name='date_group' $date_group>";
		echo "<select name='date_type'>";
		$date_type=$_POST['date_type'];
		if($date_type=='hour')
		{
			echo "<option value='hour'>小时分组</option>";
			echo "<option value='day'>日期分组</option>";
		}
		else
		{
			echo "<option value='day'>日期分组</option>";
			echo "<option value='hour'>小时分组</option>";
		}
		echo "</select>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//sp分组
		$sp_group=isset($_POST['sp_group'])?"checked":"";
		echo "<input type='checkbox' name='sp_group' $sp_group>sp分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//通道分组
		$channel_group=isset($_POST['channel_group'])?"checked":"";
		echo "<input type='checkbox' name='channel_group' $channel_group>通道分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//cp分组
		$cp_group=isset($_POST['cp_group'])?"checked":"";
		echo "<input type='checkbox' name='cp_group' $cp_group>cp分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//指令分组
		$cmd_group=isset($_POST['cmd_group'])?"checked":"";
		echo "<input type='checkbox' name='cmd_group' $cmd_group>指令分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//省份分组
		$zone_group=isset($_POST['zone_group'])?"checked":"";
		echo "<input type='checkbox' name='zone_group' $zone_group>省份分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		echo "</td>";
		
		echo "<td><input type=submit name='submit' value='提交'></td>";
		echo "</tr>";
		echo "</table>";
	
		echo "</form>";
		
		
		///=====================query===========================///
		/*
		echo $cp."<br>";
		echo $cmd."<br>";
		echo $sp."<br>";
		echo $channel."<br>";
		echo $phone."<br>";
		echo $zone."<br>";
		*/
		
		
		//table
		echo "<table border='1' cellspacing='0' cellpadding='1' width='80%' class='tabs'>";
		echo "<tr>
  				<th>ID</th>
  				<th>日期</th>
  				<th>sp</th>
  				<th>通道道</th>
  				<th>cp</th>
  				<th>指令</th>
  				<th>省份</th>
  				<th>MO</th>
  				<th>MO(成功)</th>
  				<th>百分比</th>
  				<th>转发MO</th>
  				<th>转发MO(成功)</th>
  				<th>百分比</th>			
  				<th>金额</th>
  				</tr>";	
  				
  	//compose sql
  	$group_flag=0;
  	
		$sql="select ";
		if($date_group=="checked")
		{
			$group_flag=1;
			if($date_type=='day')
				$sql.="DATE_FORMAT(stat_date,'%Y-%m-%d'), ";
			else
				$sql.="DATE_FORMAT(stat_date,'%Y-%m-%d %H'), ";
		}
		else
		{
			$sql.="'全部', ";
		}
		
		if($sp_group=="checked")
		{
			$group_flag=1;
			$sql.="spname, ";
		}
		else
		{
			$sql.="'全部', ";
		}
		
		if($channel_group=="checked")
		{
			$group_flag=1;
			$sql.="chn_spnumber, chn_mocmd, ";
		}
		else
		{
			$sql.="'全部', '', ";
		}
		
		if($cp_group=="checked")
		{
			$group_flag=1;
			$sql.="cpname, ";
		}
		else
		{
			$sql.="'全部', ";
		}
		
		if($cmd_group=="checked")
		{
			$group_flag=1;
			$sql.="cmd_spnumber, cmd_mocmd, ";
		}
		else
		{
			$sql.="'全部', '',";
		}
		
		if($zone_group=="checked")
		{
			$group_flag=1;
			$sql.="zone, ";
		}
		else
		{
			$sql.="'全部', ";
		}
		
		
		$sql.="sum(msg_total),sum(msg_success),sum(msg_forward_total), sum(msg_forward_success),sum(msg_total_fee) from mtrs_stat where 1 ";
		if($start_date)
			$sql.="and stat_date >= '$start_date 00:00:00' ";
		if($end_date)
			$sql.="and stat_date <= '$end_date 23:59:59' ";
		if($sp_value!='%')
			$sql.="and spID = $sp_value ";
		if($channel_value!='%')
			$sql.="and channelID = $channel_value ";
		if($cp_value!='%')
			$sql.="and cpID = $cp_value ";
		if($cmd_value!='%')
			$sql.="and cmdID = $cmd_value ";
		if($zone_value!='%')
			$sql.="and zone = '$zone_value' ";
			
		//group by
		if($group_flag==1)
		{
				$sql.="group by ";
			
				if($date_group=="checked")
					$sql.="stat_date,";
					
				if($sp_group=="checked")
					$sql.="spID,";
					
				if($channel_group=="checked")
					$sql.="channelID,";
				
				if($cp_group=="checked")
					$sql.="cpID,";
					
				if($cmd_group=="checked")
					$sql.="cmdID,";
					
				if($zone_group=="checked")
					$sql.="zone,";
					
				//drop the last comma
				
				$sql=substr($sql,0,strlen($sql)-1);
		}
			

		
		
		
		
		$result=mysql_query($sql) or die (mysql_error());	
			
		//echo $sql."&nbsp;";
		//echo "result:".mysql_num_rows($result)."<br>";
		$i=0;
		
		$sum_mo=0;
		$sum_mo_suc=0;
		$sum_msg_forward=0;
		$sum_msg_forward_suc=0;
		$sum_msg_total_fee=0;
		
		while($row=mysql_fetch_row($result))
		{
				$i++;
				echo "<tr align='center'>";
				echo "<td>$i</td>";
				echo "<td>$row[0]</td>";
				echo "<td>$row[1]</td>";
				echo "<td>$row[2]&nbsp;$row[3]</td>";
				echo "<td>$row[4]</td>";
				echo "<td>$row[5]&nbsp;$row[6]</td>";
				if($row[7]=='全部')
				{
						echo "<td>$row[7]</td>";
				}
				else
				{
						$area_key=$row[7];
						echo "<td>$area_code[$area_key]</td>";
						//echo "<td>$row[7]</td>";
				}
					
				echo "<td>$row[8]</td>";
				echo "<td>$row[9]</td>";
				
				//percentage
				$percent=$row[8]>0?number_format(100*$row[9]/$row[8],2)."%":"";
				echo "<td>".$percent."</td>";
				
				echo "<td>$row[10]</td>";
				echo "<td>$row[11]</td>";
				
				//percentage
				$percent=$row[10]>0?number_format(100*$row[11]/$row[10],2)."%":"";
				echo "<td>".$percent."</td>";
				
				//msg_total_fee
				echo "<td>".$row[12]."</td>";
				
				echo "</tr>";
				
				
				$sum_mo+=$row[8];
				$sum_mo_suc+=$row[9];
				$sum_msg_forward+=$row[10];
				$sum_msg_forward_suc+=$row[11];
				$sum_msg_total_fee+=$row[12];
				
				
		}
		mysql_free_result($result);
   
   
	   //合计
	   echo "<tr align='center'>";
	   echo "<td>合计</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td>";
	   echo "<td>$sum_mo</td>";
	   echo "<td>$sum_mo_suc</td>";
	   $percent=$sum_mo>0?number_format(100*$sum_mo_suc/$sum_mo,2)."%":"0.00%";
			echo "<td>".$percent."</td>";
	   echo "<td>$sum_msg_forward</td>";
	   echo "<td>$sum_msg_forward_suc</td>";
	   $percent=$sum_msg_forward>0?number_format(100*$sum_msg_forward_suc/$sum_msg_forward,2)."%":"0.00%";
			echo "<td>".$percent."</td>";
			echo "<td>".$sum_msg_total_fee."</td>";
	   echo "</tr>";
		
		echo "";
		echo "</table>";
		

?>


