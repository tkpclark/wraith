<script Language="JavaScript">
function ask(id)
{
	var str=
		answer=confirm("确定要删除id="+id+"的记录吗？");
	if(answer=="1")
		return true;
	else 
		return false;
}
</script>
<?php 
	include("check.php"); 
	include("style.php");
	
	
	
	
	//add or modify
	if(isset($_POST['product_code'])&&isset($_POST['product_id'])&&isset($_POST['mo'])&&isset($_POST['mt'])&&isset($_POST['fee']))
	{
		$product_id=$_POST['product_id'];
		$product_code=$_POST['product_code'];
		$app=$_POST['app'];
		$sp_number=$_POST['sp_number'];
		$mo=$_POST['mo'];
		$mt=$_POST['mt'];
		$fee=$_POST['fee'];
		$allow_province=$_POST['allow_province'];
		$forbidden_area=$_POST['forbidden_area'];
		$gwid=$_POST['gwid'];
		
		if($_GET['type']=="insert")
		{
			$sql="set names utf8";
			exsql($sql);
			
			exsql("set names utf8");
			$sql="insert into wraith_products(product_id,product_code,url,sp_number,message,amount,gwid,default_msg,allow_province,forbidden_area)
						values('$product_id','$product_code','$app','$sp_number','$mo','$fee','$gwid','$mt','$allow_province','$forbidden_area')";
			//echo $sql;
			exsql($sql);
		}
		else if($_GET['type']=="update")
		{
			$sql="set names utf8";
			mysqli_query($mysqli,$sql) or die (mysqli_error());
			
			$id=$_GET['id'];
			$sql="update wraith_products set product_id='$product_id',product_code='$product_code',sp_number='$sp_number',url='$app',message='$mo',default_msg='$mt',amount='$fee',gwid='$gwid',allow_province='$allow_province',forbidden_area='$forbidden_area' where id=$id";
			//echo $sql;
			exsql($sql);
		}
	}
	
	
	if(isset($_GET['cmd']))
	{
		if($_GET['cmd']==1) //delete
		{
				$sql="delete from wraith_products where ID=".$_GET['id'];
				//echo $sql;
				mysqli_query($mysqli,$sql) or die (mysqli_error());
		}
		else if($_GET['cmd']==2 || $_GET['cmd']==3)//modify
		{
			
			if($_GET['cmd']==2)
			{
				$id=$_GET['id'];
				exsql("set names utf8");
				$sql="select * from wraith_products where ID=$id";
				//echo $sql;
				$result=mysqli_query($mysqli,$sql) or die (mysqli_error());
				$row=mysqli_fetch_row($result);
				echo "<form action='product_management.php?type=update&id=$id' method=POST>";
			}
			else
			{
				$row=[];
				echo "<form action=product_management.php?type=insert method=POST>";
			}
				
				
				

				///////////
				echo "<table>";
				echo "<tr>";
				echo "<td>产品ID&nbsp;</td>";
				echo "<td><input type=text name=product_id value=".(isset($row[1])&&$row[1]?$row[1]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>业务代码&nbsp;</td>";
				echo "<td><input type=text name=product_code value=".(isset($row[2])&&$row[2]?$row[2]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>业务逻辑&nbsp;</td>";
				echo "<td><input type=text name=app value=".(isset($row[3])&&$row[3]?$row[3]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>SP号码&nbsp;</td>";
				echo "<td><input type=text name=sp_number value=".(isset($row[4])&&$row[4]?$row[4]:'""')."  size=120></td>";
				echo "<tr>";
				echo "<td>上行指令&nbsp;</td>";
				echo "<td><input type=text name=mo value=".(isset($row[5])&&$row[5]?$row[5]:'""')."  size=120></td>";
				echo "<tr>";
				echo "<td>默认回复&nbsp;</td>";
				echo "<td><input type=text name=mt value=".(isset($row[8])&&$row[8]?$row[8]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>允许访问的省份&nbsp;</td>";
				echo "<td><input type=text name=allow_province value='".(isset($row[10])&&$row[10]?$row[10]:'北京 天津 上海 重庆 河北 山西 辽宁 吉林 黑龙江 江苏 浙江 安徽 福建 江西 山东 河南 湖北 湖南 广东 海南 四川 贵州 云南 陕西 甘肃 青海 台湾 广西 内蒙 西藏 宁夏 新疆 香港 澳门 未知')."' size=170></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>禁止的地区&nbsp;</td>";
				echo "<td><input type=text name=forbidden_area value='".(isset($row[11])&&$row[11]?$row[11]:'')."' size=170></td>";
				echo "<tr>";
				echo "<td>资费(分)&nbsp;</td>";
				echo "<td><input type=text name=fee value=".(isset($row[6])&&$row[6]?$row[6]:'""')." size=120></td>";
				echo "<tr>";
				echo "<td>网关ID&nbsp;</td>";
				echo "<td><input type=text name=gwid value=".(isset($row[7])&&$row[7]?$row[7]:'""')." size=120></td>";
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
	$sql="select * from wraith_products";
  $result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
  $got_num=mysqli_num_rows($result);
  ////////
  //echo "result:$got_num<br>";
  echo "<a href='product_management.php?cmd=3'>添加新产品</a>&nbsp;&nbsp;&nbsp;&nbsp;";
  
  
  
  echo "<form name=load_config method=post action=".$_SERVER['PHP_SELF']."?load=1 >";
  echo "<input type=submit name='submit' value='重新加载'  style='width:250px;height:500px;'>";
  
  
  if(isset($_GET['load']))
  {
  	$cmd = dirname(__FILE__)."/../controller/start_controller.sh";
  	//$cmd='python /home/sms/MsgTunnel/src/msgforward/config_maker.py';
  	//echo "cmd: ".$cmd."<br>";
  	exec($cmd, $output, $cmd_result);
  	if($cmd_result != 0)
  		echo "加载失败!<br>";
  	else
  		print_r($output[0]);
  		//var_dump($output);
  	}
  	
  	
	
  echo "<table>";
  echo "<tr bgcolor=#645375>
  				<th>ID</th>
  				<th>产品代码</th>
  				<th>业务代码</th>
  				<th>产品处理逻辑</th>
  				<th>SP号码</th>
  				<th>指令</th>
  				<th>资费</th>
  				
  				<th>允许省份</th>
  				<th>禁止地区</th>
  				<th>网关代码</th>
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
  					<td>$row[5]</td>
  					<td>$row[6]</td>
  					
  					<td>$row[10]</td>
  					<td>$row[11]</td>
  					<td>$row[7]</td>
  					<td onclick=\"return ask($row[0]);\" ><a href='product_management.php?cmd=1&id=$row[0]'>删除</a></td>
  					<td><a href='product_management.php?cmd=2&id=$row[0]'>修改</a></td>
  				</tr>";
  }
   mysqli_free_result($result);
   echo "</table>";
   echo "<br>";
   echo "</form>";
   echo "</body>";
	
