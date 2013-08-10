<?php 
	include("check.php"); 
	include("style.php");
	
	//add or modify
	if(isset($_POST['chanpinID'])&&isset($_POST['productID'])&&isset($_POST['mo'])&&isset($_POST['mt'])&&isset($_POST['fee']))
	{
		$chanpinID=$_POST['chanpinID'];
		$productID=$_POST['productID'];
		$mo=strtoupper($_POST['mo']);
		$mt=$_POST['mt'];
		$fee=$_POST['fee'];
		
		if($_GET['type']=="insert")
		{
			$sql="set names gbk";
			mysql_query($sql) or die (mysql_error());
		
			$sql="insert into product(chanpinID,productID,mo,mt,fee)
						values('$chanpinID','$productID','$mo','$mt','$fee')";
			//echo $sql;
			mysql_query($sql) or die (mysql_error());
		}
		else if($_GET['type']=="update")
		{
			$sql="set names gbk";
			mysql_query($sql) or die (mysql_error());
			
			$id=$_GET['id'];
			$sql="update product set chanpinID='$chanpinID',productID='$productID',mo='$mo',mt='$mt',fee='$fee' where id=$id";
			//echo $sql;
			mysql_query($sql) or die (mysql_error());
		}
	}
	
	
	if(isset($_GET['cmd']))
	{
		if($_GET['cmd']==1) //delete
		{
				$sql="delete from product where ID=".$_GET['id'];
				//echo $sql;
				mysql_query($sql) or die (mysql_error());
		}
		else if($_GET['cmd']==2)//modify
		{
				$id=$_GET['id'];
				$sql="select * from product where ID=$id";
				//echo $sql;
				$result=mysql_query($sql) or die (mysql_error());
				$row=mysql_fetch_row($result);
				
				
				
				echo "<form action='product_management.php?type=update&id=$id' method=POST>";

				///////////
				echo "产品代码&nbsp;";
				echo "<input type=text name=chanpinID value=$row[1] size=20><br>";
				echo "业务代码&nbsp;";
				echo "<input type=text name=productID value=$row[2] size=20><br>";
				echo "上行指令&nbsp;";
				echo "<input type=text name=mo value=$row[3] size=20><br>";
				echo "下行内容&nbsp;";
				echo "<input type=text name=mt value=$row[4] size=120><br>";
				echo "资费(分)&nbsp;";
				echo "<input type=text name=fee value=$row[5] size=5><br>";
				
				///////
				echo "<input type=submit value=提交><input type=reset value=重置>";
				
				//////
				echo"</form>";
				mysql_free_result($result);
				exit;
		}
		else if($_GET['cmd']==3) //add
		{
			echo "<form action=product_management.php?type=insert method=POST>";
			
			
			///////////
			echo "产品代码&nbsp;";
			echo "<input type=text name=chanpinID size=20><br>";
			echo "业务代码&nbsp;";
			echo "<input type=text name=productID size=20><br>";
			echo "上行指令&nbsp;";
			echo "<input type=text name=mo size=20><br>";
			echo "下行内容&nbsp;";
			echo "<input type=text name=mt size=120><br>";
			echo "资费(分)&nbsp;";
			echo "<input type=text name=fee size=5><br>";
			
			///////
			echo "<input type=submit value=提交><input type=reset value=重置>";
			
			//////
			echo"</form>";
			exit;
		
		}
		
	}
	
	
	
	
	//////////////list

	echo "<body>";
	

	$sql="set names gbk";
	mysql_query($sql) or die (mysql_error());
	$sql="select * from product";
  $result=mysql_query($sql) or die (mysql_error());
  $got_num=mysql_num_rows($result);
  ////////
  //echo "result:$got_num<br>";
  echo "<a href='product_management.php?cmd=3'>添加新产品</a>";
	
  echo "<table>";
  echo "<tr bgcolor=#645375>
  				<th>产品代码</th>
  				<th>业务代码</th>
  				<th>上行</th>
  				<th>下发内容</th>
  				<th>长度</th>
  				<th>资费</th>
  				<th>删除</th>
  				<th>修改</th>
  				
  			</tr>";
  while($row=mysql_fetch_row($result))
  {
  		echo"<tr>
  					<td>$row[1]</td>
  					<td>$row[2]</td>
  					<td>$row[3]</td>
  					<td>$row[4]</td>
  					";
  		echo "<td>".strlen($row[4])."</td>";	
  			echo"<td>$row[5]</td>
  					<td><a href='product_management.php?cmd=1&id=$row[0]'>删除</a></td>
  					<td><a href='product_management.php?cmd=2&id=$row[0]'>修改</a></td>
  				</tr>";
  }
   mysql_free_result($result);
   echo "</table>";
   echo "<br>";
	
	
	echo "</body>";	
?>