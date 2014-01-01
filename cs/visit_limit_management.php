<?php 
	include("check.php"); 
	include("style.php");
	
	//add or modify
	if(isset($_POST['province'])&&isset($_POST['product_id'])&&isset($_POST['daily_count'])&&isset($_POST['monthly_count']))
	{
		$product_id=$_POST['product_id'];
		$province=$_POST['province'];
		$daily_count=$_POST['daily_count'];
		$monthly_count=$_POST['monthly_count'];
		
		if($_GET['type']=="insert")
		{
			$sql="set names utf8";
			exsql($sql);
			
			exsql("set names utf8");
			$sql="insert into wraith_visit_limit(product_id,province,daily_count,monthly_count)
						values('$product_id','$province','$daily_count','$monthly_count')";
			//echo $sql;
			exsql($sql);
		}
		else if($_GET['type']=="update")
		{
			$sql="set names utf8";
			mysqli_query($mysqli,$sql) or die (mysqli_error());
			
			$id=$_GET['id'];
			$sql="update wraith_visit_limit set product_id='$product_id',province='$province',daily_count='$daily_count',monthly_count='$monthly_count' where id=$id";
			//echo $sql;
			exsql($sql);
		}
	}
	
	
	if(isset($_GET['cmd']))
	{
		if($_GET['cmd']==1) //delete
		{
				$sql="delete from wraith_visit_limit where ID=".$_GET['id'];
				//echo $sql;
				mysqli_query($mysqli,$sql) or die (mysqli_error());
		}
		else if($_GET['cmd']==2 || $_GET['cmd']==3)//modify
		{
			
			if($_GET['cmd']==2)
			{
				$id=$_GET['id'];
				exsql("set names utf8");
				$sql="select * from wraith_visit_limit where ID=$id";
				//echo $sql;
				$result=mysqli_query($mysqli,$sql) or die (mysqli_error());
				$row=mysqli_fetch_row($result);
				echo "<form action='visit_limit_management.php?type=update&id=$id' method=POST>";
			}
			else
			{
				$row=[];
				echo "<form action=visit_limit_management.php?type=insert method=POST>";
			}
				
				
				

				///////////
				echo "<table>";
				echo "<tr>";
				echo "<td>产品ID&nbsp;</td>";
				echo "<td><input type=text name=product_id value=".(isset($row[1])&&$row[1]?$row[1]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>省份&nbsp;</td>";
				echo "<td><input type=text name=province value=".(isset($row[2])&&$row[2]?$row[2]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>日访问上限&nbsp;</td>";
				echo "<td><input type=text name=daily_count value=".(isset($row[3])&&$row[3]?$row[3]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>月访问上限&nbsp;</td>";
				echo "<td><input type=text name=monthly_count value=".(isset($row[4])&&$row[4]?$row[4]:'""')."  size=120></td>";
				echo "</table>";
				///////
				echo "<br><input type=submit value=提交><input type=reset value=重置>";
				
				//////
				echo"</form>";

				exit;
		}
	
		
	}
	
	
	
	
	//////////////list

	echo "<body>";
	

	$sql="set names utf8";
	mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
	$sql="select * from wraith_visit_limit";
  $result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
  $got_num=mysqli_num_rows($result);
  ////////
  //echo "result:$got_num<br>";
  echo "<a href='visit_limit_management.php?cmd=3'>添加新规则</a>";
	
  echo "<table>";
  echo "<tr bgcolor=#645375>
  				<th>ID</th>
  				<th>产品ID</th>
  				<th>省份</th>
  				<th>日访问上限</th>
  				<th>月访问上限</th>
  				<th>删除</th>
  				<th>修改</th>
  				
  			</tr>";
  while($row=mysqli_fetch_row($result))
  {
  		echo"<tr>
  					<td>$row[0]</td>
  					<td>$row[1]</td>
  					<td>$row[2]</td>
  					<td>$row[3]</td>
  					<td>$row[4]</td>
  					<td><a href='visit_limit_management.php?cmd=1&id=$row[0]'>删除</a></td>
  					<td><a href='visit_limit_management.php?cmd=2&id=$row[0]'>修改</a></td>
  				</tr>";
  }
   mysqli_free_result($result);
   echo "</table>";
   echo "<br>";
	
	
	echo "</body>";	
?>


<form name=load_config method=post action=visit_limit_management.php?load=1 >
<input type=submit name='submit' value='重新加载'  style="width:250px;height:500px;">
</form>
</body>


<?php

if(isset($_GET['load']))
{
	$cmd = dirname(__FILE__)."/../controller/start_controller.sh";
	//$cmd='python /home/sms/MsgTunnel/src/msgforward/config_maker.py';
	//echo "cmd: ".$cmd."<br>";
	exec($cmd, $output, $result);
	if($result != 0)
		echo "加载失败!<br>";
	else
		print_r($output[0]);
	//var_dump($output);
}