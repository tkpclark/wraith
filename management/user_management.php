<?php 
	include("check.php"); 
	include("style.php");
	
    if($_POST['username'])
    {
    	  $username=$_POST['username'];
		    $sql="select username from user where username='$username'";
		    $result=mysql_query($sql) or die (mysql_error());
		    if(mysql_num_rows($result))
		    {
		       echo"<font color=red>该帐号已存在!</font><br><br>";
		    }
				else
				{
				    $pwd=$_POST['password'];
				    $type=$_POST['type'];
				    if($type==1)
				    	$mo='%';
				    else
				    	$mo=$_POST['mo'];
				    $md5_pwd=md5($pwd);
				    
				    $sql="INSERT INTO user (username,password,type,mo)
				    				VALUES ('$username', '$md5_pwd', '$type','$mo')";
				    //echo $sql;
				    mysql_query($sql) or die (mysql_error());
			  }
    }

		if(isset($_GET['username']))
		{
			$sql="delete from user where username='".$_GET['username']."'";
			//echo $sql;
			mysql_query($sql) or die (mysql_error());
		}
	echo "<body>";
		/////
	echo "<form action=user_management.php method=POST>";
	
	
	///////////
	echo "姓&nbsp;&nbsp;&nbsp;&nbsp;名";
	echo "<input type=text name=username size=20><br>";
	echo "密&nbsp;&nbsp;&nbsp;&nbsp;码";
	echo "<input type=text name=password size=20><br>";
//	echo "重复输入";
//	echo "<input type=text name=password2 size=20><br>";
	
	echo "账户类型";
	echo "<select name=type>";
	echo "<option value=1>管理员</option>";
	echo "<option value=5>受限用户</option>";
	echo "</select><br>";
	
	echo "允许查看";
	echo "<input type=text name=mo size=20>";
	echo "<font color=red>管理员用户不填写此项</font><br>";
	
	///////
	echo "<input type=submit value=提交><input type=reset value=重置>";
	
	//////
	echo"</form>";
	echo"<br>";
	
	$sql="set names gbk";
	mysql_query($sql) or die (mysql_error());
	$sql="select * from user";
  $result=mysql_query($sql) or die (mysql_error());
  ////////
  //echo "result:$got_num<br>";
  echo "<table>";
  echo "<tr bgcolor=#645375>
  				<th>用户名</th>
  				<th>类型</th>
  				<th>允许查看</th>
  				<th>操作</th>
  			</tr>";
  while($row=mysql_fetch_row($result))
  {
  		echo"<tr>
  					<td>$row[0]</td>
  					<td>$row[2]</td>
  					<td>$row[3]</td>
  					<td><a href='user_management.php?username=$row[0]'>删除</a></td>
  				</tr>";
  }
   mysql_free_result($result);
   echo "</table>";
   echo "<br>";
	
	
	
	echo "</body>";	
?>