<?php 
		include("check.php"); 
		include("style.php");
		include("calendar/cld.php");
		
	
		
		
		
	
		echo "<font size=4><caption>运营数据查询>></caption></font><br>";
		
		
		
		//form
		echo "<form name=statistic action=statistic.php method=post>";
		
		//table
		echo "<table>";
		
		//first line of table
		//echo "<th colspan='7'>查询条件</th>";
		
		//second line of table
		echo "<tr>";
		
		
		$start_date = isset($_POST['d1'])?$_POST['d1']:date("Y-m-d");;
		echo "<td>MO开始时间&nbsp;<input id='date1' name='d1' type='text' value=$start_date></td>";
		$end_date = isset($_POST['d2'])?$_POST['d2']:date("Y-m-d");;
		echo "<td>MO结束时间&nbsp;<input id='date2' name='d2' type='text' value=$end_date></td>";
		echo "</tr></table>";
	
		echo "<table><tr><td>";
		/*********** GWID *************/
		$gwid=isset($_POST['gwid'])?$_POST['gwid']:"All";
		echo "GWID&nbsp;&nbsp;";
		echo "<select name=gwid>";
		$sql="select id from wraith_gw where status=1";
		echo "<option value='$gwid'>$gwid</option>";
		echo "<option value='All'>All</option>";
		$result=exsql($sql);
		while($row=mysqli_fetch_row($result))
		{
			echo "<option value=$row[0]>$row[0]</option>";
		}
		echo "</select>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		/*********** product_id *************/
		$product_id=isset($_POST['product_id'])?$_POST['product_id']:"All";
		echo "计费代码&nbsp;&nbsp;";
		echo "<select name=product_id>";
		$sql="select distinct(product_id) from wraith_products where status=1";
		echo "<option value='$product_id'>$product_id</option>";
		echo "<option value='All'>All</option>";
		$result=exsql($sql);
		while($row=mysqli_fetch_row($result))
		{
		echo "<option value=$row[0]>$row[0]</option>";
		}
		echo "</select>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		/***********sp_number*************/
		$sp_number=isset($_POST['sp_number'])?$_POST['sp_number']:"All";
		echo "SP号码<input id=sp_number name=sp_number type=text value='$sp_number' />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		

		/***********area*************/
		$area=isset($_POST['area'])?$_POST['area']:"All";
		echo "地区&nbsp;&nbsp;&nbsp;<input id=area name=area type=text value='$area' />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		/*
		if(isset($_POST['area']) && $_POST['area']!='All')
		{
					$area_value=$_POST['area'];
					$area=$area_value;
					
		}
		else
		{
					$area = "All";
					$area_value='All';
		}
		
		echo "地区&nbsp;&nbsp;&nbsp;";
		
		
		echo "<select name=area>";
		echo "<option value='$area_value'>$area</option>";
		echo "<option value='All'>All</option>";
		while($key = key($area_code))
		{
			echo "<option value='$key'>$area_code[$key]</option>";
			next($area_code);
		}
		echo "</select>";
		*/
		
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
	
		
		echo "</td></tr></table>";
		/*******************group*******************************/
		
		echo "<table><tr><td>";
		//分组信息
		
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
		
		//gwid分组
		$gwid_group=isset($_POST['gwid_group'])?"checked":"";
		echo "<input type='checkbox' name='gwid_group' $gwid_group>网关分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//省份分组
		$area_group=isset($_POST['area_group'])?"checked":"";
		echo "<input type='checkbox' name='area_group' $area_group>地区分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//产品分组
		$product_id_group=isset($_POST['product_id_group'])?"checked":"";
		echo "<input type='checkbox' name='product_id_group' $product_id_group>产品分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//长号码分组
		$sp_number_group=isset($_POST['sp_number_group'])?"checked":"";
		echo "<input type='checkbox' name='sp_number_group' $sp_number_group>长号码分组";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		
		
		echo "</td></tr></table>";
		
		
		
		
		echo "<input type=submit name='submit' value='提交'></td>";
		echo "</form>";
		
		
		///=====================query===========================///
		/*
		echo $cp."<br>";
		echo $cmd."<br>";
		echo $sp."<br>";
		echo $channel."<br>";
		echo $phone."<br>";
		echo $area."<br>";
		*/
		
		
		//table
		echo "<table>";
		echo "<tr>
  				<th>ID</th>
  				<th>日期</th>
				<th>网关</th>
				<th>产品</th>
  				<th>长号码</th>
  				<th>省份</th>
  				<th>总数</th>
  				<th>成功总数</th>
  				<th>百分比</th>		
  				<th>总金额</th>
  				</tr>";	
  				
  	//compose sql
  	$group_flag=0;
  	
		$sql="select ";
		if($date_group=="checked")
		{
			$group_flag=1;
			if($date_type=='day')
				$sql.="DATE_FORMAT(stat_time,'%Y-%m-%d') as ddd, ";
			else
				$sql.="DATE_FORMAT(stat_time,'%Y-%m-%d %H') as ddd, ";
		}
		else
		{
			$sql.="'All', ";
		}
		/////////
		if($gwid_group=="checked")
		{
			$group_flag=1;
			$sql.="gwid, ";
		}
		else
		{
			$sql.="'All', ";
		}
		////////
		if($product_id_group=="checked")
		{
			$group_flag=1;
			$sql.="product_id, ";
		}
		else
		{
			$sql.="'All', ";
		}
		
		
		/////////
		if($sp_number_group=="checked")
		{
			$group_flag=1;
			$sql.="sp_number, ";
		}
		else
		{
			$sql.="'All', ";
		}
		
		////////	
		if($area_group=="checked")
		{
			$group_flag=1;
			$sql.="province, ";
		}
		else
		{
			$sql.="'All', ";
		}
		
		
		$sql.="sum(num),sum(success_num),sum(all_amount) from wraith_statistic where 1 ";
		if($start_date)
			$sql.="and stat_time >= DATE_FORMAT('$start_date 00:00:00','%Y-%m-%d:%H')";
		if($end_date)
			$sql.="and stat_time <= DATE_FORMAT('$end_date 23:59:59','%Y-%m-%d:%H')";
		if($sp_number!='All')
			$sql.="and sp_number = '$sp_number' ";
		if($gwid!='All')
			$sql.="and gwid = '$gwid' ";
		if($product_id!='All')
			$sql.="and product_id = '$product_id' ";
		if($area!='All')
			$sql.="and province = '$area' ";
			
		//group by
		if($group_flag==1)
		{
				$sql.="group by ";
			
				if($date_group=="checked")
					$sql.="ddd,";
					
				if($product_id_group=="checked")
					$sql.="product_id,";
					
				if($sp_number_group=="checked")
					$sql.="sp_number,";
				
				if($gwid_group=="checked")
					$sql.="gwid,";
									
				if($area_group=="checked")
					$sql.="province,";
					
				//drop the last comma
				
				$sql=substr($sql,0,strlen($sql)-1);
		}
			

		
		
		
		exsql("set names utf8");
		$result=exsql($sql);	
			
		//echo $sql."&nbsp;";
		//echo "result:".mysqli_num_rows($result)."<br>";
		$i=0;
		
		$sum=0;//总条数
		$sum_success=0;//总成功条数
		$sum_all_amount=0;//总金额
		while($row=mysqli_fetch_row($result))
		{
				$i++;
				echo "<tr align='center'>";
				echo "<td>$i</td>";
				echo "<td>$row[0]</td>";
				echo "<td>$row[1]</td>";
				echo "<td>$row[2]</td>";
				echo "<td>$row[3]</td>";
				/*
				if($row[4]=='All')
				{
						echo "<td>$row[4]</td>";
				}
				else
				{
						$area_key=$row[4];
						echo "<td>$area_code[$area_key]</td>";
						//echo "<td>$row[4]</td>";
				}
				*/
				echo "<td>$row[4]</td>";
				echo "<td>$row[5]</td>";
				echo "<td>$row[6]</td>";
				
				//percentage
				$percent=$row[5]>0?number_format(100*$row[6]/$row[5],2)."%":"";
				echo "<td>".$percent."</td>";
				echo "<td>".($row[7]/100)."</td>";
				
				
				echo "</tr>";
				
				
				$sum+=$row[5];
				$sum_success+=$row[6];
				$sum_all_amount+=$row[7];
				
				
		}
		mysqli_free_result($result);
   
   
	   //合计
	   echo "<tr align='center'>";
	   echo "<td>合计</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td>";
	   echo "<td>$sum</td>";
	   echo "<td>$sum_success</td>";
	   $percent=$sum>0?number_format(100*$sum_success/$sum,2)."%":"0.00%";
		echo "<td>".$percent."</td>";
		echo "<td>".($sum_all_amount/100)."</td>";
	   echo "</tr>";
		
		echo "";
		echo "</table>";
		

?>


