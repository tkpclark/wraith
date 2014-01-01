<?php 
	include("check.php"); 
	include("style.php");
	
	if(isset($_POST['product'])&&isset($_POST['message'])&&isset($_POST['time']))
	{
		$product=$_POST['product'];
		$message=$_POST['message'];
		$time=$_POST['time'];
		
		$sql="set names gbk";
		mysql_query($sql) or die (mysql_error());
		$sql="insert into sub_sending(productID,message,sendingTime)values('$product','$message','$time')";
		mysql_query($sql) or die (mysql_error());
	}
	if(isset($_GET['id']))
	{
		$sql="delete from sub_sending where ID=".$_GET['id'];
		//echo $sql;
		mysql_query($sql) or die (mysql_error());
	}
	
	
	
	echo "<body>";
	
	
	/////
	echo "<form action=sub_sending.php method=POST>";
	
	
	///////////
	echo "产品&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<select name=product>";
	$sql="select productID from product";
	$result=mysql_query($sql) or die (mysql_error());
	while($row=mysql_fetch_row($result))
	{
	  echo "<option>".$row[0]."</option>";
	}
	mysql_free_result($result);
	echo "</select>";
	echo "<br>";
	
	
	
	///////////
	echo "下发内容&nbsp;";
	echo "<input type=text name=message size=120><br>";
	
	
	
	////////////
	echo "下发时间&nbsp;";
	$sql="select NOW()";
	$result=mysql_query($sql) or die (mysql_error());
	$row=mysql_fetch_row($result);
	echo "<input type=text name=time value='$row[0]'>";
	//echo "<input type=checkbox name=sentnow>立即下发";
	echo "<br>";
	
	
	///////
	echo "<input type=submit value=提交><input type=reset value=重置>";
	
	
	//////
	echo"</form>";
	
	
	$sql="set names gbk";
	mysql_query($sql) or die (mysql_error());
	$sql="select * from sub_sending order by ID desc;";
  $result=mysql_query($sql) or die (mysql_error());
  $got_num=mysql_num_rows($result);
  ////////
  //echo "result:$got_num<br>";
  echo "<table>";
  echo "<tr bgcolor=#645375>
  				<th>ID</th>
  				<th>产品</th>
  				<th>下发内容</th>
  				<th>下发时间</th>
  				<th>是否已下发</th>
  				<th>操作</th>
  				
  			</tr>";
  while($row=mysql_fetch_row($result))
  {
  		echo"<tr>
  					<td>$row[0]</td>
  					<td>$row[1]</td>
  					<td>$row[2]</td>
  					<td>$row[3]</td>
  				";
  		if($row[4]==2)
  		{
  					echo "<td>已下发</td>";
  					echo "<td>删除</td>";
  		}
  		if($row[4]==1)
  		{
  					echo "<td>正在下发</td>";
  					echo "<td>删除</td>";
  		}
  		if($row[4]==0)
  		{
  					echo "<td>未下发</td>";		
  					echo "<td><a href='sub_sending.php?id=$row[0]'>删除</a></td>";
  		}
  		
  		echo"</tr>";
  }
   mysql_free_result($result);
   echo "</table>";
   echo "<br>";
	
	
	
	echo "</body>";	
?>