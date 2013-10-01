<script Language="JavaScript">
function check()
{
	if(document.cp_channel_edit_form.deduction.value=="")
  {
     	   alert("请填写扣量比例!");
     	   document.cp_channel_edit_form.deduction.focus();
     	   return false;
	}
	
	if(document.cp_channel_edit_form.deduction.value > 100 || document.cp_channel_edit_form.deduction.value < 0)
	{
     	   alert("请填写1-100之间的数字!");
     	   document.cp_channel_edit_form.deduction.focus();
     	   return false;
	}
}
</script>
<?php 
	include("check.php"); 
	include("style.php");
	if(isset($_GET['channel_id']))
	{
		$channel_id=$_GET['channel_id'];
				
		$sql = "select spnumber,mocmd,msgtype,status,spID,fee from mtrs_channel where id=$channel_id";
		//echo $sql;
		$result=mysql_query($sql) or die (mysql_error());
		$row=mysql_fetch_row($result);
		$spnumber=$row[0];
		$mocmd=$row[1];
		$msgtype=$row[2];
		$status=$row[3];
		$spID=$row[4];
		$fee=$row[5];
		
		//get spname
		$sql="select spname from mtrs_sp where ID=$spID";
		$result=mysql_query($sql) or die (mysql_error());
	  $row=mysql_fetch_row($result);
	  $spname=$row[0];
		
	}
	else
	{
		$spnumber="";
		$mocmd="";
		$msgtype="1";
		$status="1";
		$spID="";
		$spname="";
		$fee="";
	}
?>
<font size=4><caption>新增通道>></caption></font>
<br><br><br>
<body>
<form name=cp_channel_edit_form method=post action=channel_edit_do.php<?php if(isset($channel_id)) echo "?channelID=$channel_id"; ?> onsubmit="return check()">

<table>
<?php
if(isset($channel_id))
{
	echo "<tr><th>ID</th><th align='center'>$channel_id</th></tr>";
}
?>
<tr>
	<th>SP名称&nbsp;&nbsp;</th>
	<th align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<select name=spID>
		<?php
			echo "<option value='$spID'>$spname</option>";
			$sql="select id, spname from mtrs_sp where status=1";
			$result=mysql_query($sql) or die (mysql_error());
	  	while($row=mysql_fetch_row($result))
	  	{
	  		echo "<option value=$row[0]>$row[1]</option>";
	  	}
		?>
		</select>
	</th>
</tr>

<tr>
	<th>目的号码</th>
	<th align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" name="spnumber" value="<?php echo $spnumber ?>" size="20"/>
	</th>
</tr>

<tr>
	<th>MO指令</th>
	<th align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" name="mocmd"  value="<?php echo $mocmd?>" size="15"/>
	</th>
</tr>

<tr>
	<th>单条计费</th>
	<th align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" name="fee"  value="<?php echo $fee?>" size="10"/>
	</th>
</tr>

<tr>	
	<th> 消息类型 </th>
	<th align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		短信<input type=radio name=msgtype value=1 <?php if($msgtype==1) echo "checked=\"checked\""?>/> 
		彩信<input type=radio name=msgtype value=2 <?php if($msgtype==2) echo "checked=\"checked\""?>/> 
	</th>
</tr>

<tr>	
	<th> 状态 </th>
	<th align="left">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		正常<input type=radio name=status value=1 <?php if($status==1) echo "checked=\"checked\""?>/> 
		关闭<input type=radio name=status value=2 <?php if($status==2) echo "checked=\"checked\""?>/> 
	</th>
</tr>
	
</table>
 <br>

 <input type=submit name="submit" value="确定">
</form>
</form>