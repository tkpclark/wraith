<?php 
	include("check.php"); 
	include("style.php");
	
	
	if(isset($_GET['id']))
	{
		$sql="delete from blklist where mobile=".$_GET['mobile'];
		//echo $sql."<br>";
		mysql_query($sql) or die (mysql_error());
	}
	if(isset($_POST['mobile']))
	{
		$mobile=$_POST['mobile'];
		$sql="set names gbk";
		mysql_query($sql) or die (mysql_error());
		$sql="insert into blklist(mobile) values('$mobile')";
		//echo $sql;
		mysql_query($sql) or die (mysql_error());
	}


	
	echo "<body>";
	
	
	/////
	echo "<form action=blklist_management.php method=POST>";
	
	
	///////////
	echo "号码&nbsp;";
	echo "<input type=text name=mobile size=20><br>";

	
	///////
	echo "<input type=submit value=提交><input type=reset value=重置>";
	
	//////
	echo"</form>";
	
	
	$sql="set names gbk";
	mysql_query($sql) or die (mysql_error());
	$sql="select mobile from blklist";
  $result=mysql_query($sql) or die (mysql_error());
  $got_num=mysql_num_rows($result);
  ////////
  //echo "result:$got_num<br>";
  echo "<table>";
  echo "<tr bgcolor=#645375>
  				<th>黑名单号码</th>
  				
  			</tr>";
  while($row=mysql_fetch_row($result))
  {
  		echo"<tr>
  					<td>$row[0]</td>
  					<td><a href='blklist_management.php?mobile=$row[0]&id=del'>删除</a></td>
  				</tr>";
  }
   mysql_free_result($result);
   echo "</table>";
   echo "<br>";
	
	
	
	echo "</body>";	
?>