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
	if(isset($_GET['cp_id']))
	{
		$cp_id=$_GET['cp_id'];
				
		$sql = "select cpname,status from mtrs_cp where id=$cp_id";
		//echo $sql;
		$result=mysql_query($sql) or die (mysql_error());
		$row=mysql_fetch_row($result);
		$cp_name=$row[0];
		$cp_status=$row[1];
		
	}
	else
	{
		$cp_name="";
		$cp_status="1";
	}
?>

<font size=4><caption>cp信息编辑>></caption></font>
<br><br><br>

<body>
<form name=cp_edit_form method=post action=cp_edit_do.php<?php if(isset($cp_id)) echo "?cpID=$cp_id"; ?> onsubmit='return check()'>

<table border='1' cellspacing='0' cellpadding='1' width='25%' class='tabs'>
<?php
if(isset($cp_id))
{
	echo "<tr><th>ID</th><th align='center'>$cp_id</th></tr>";
}
?>
<tr>
	<th>cp名称&nbsp;&nbsp;</th>
	<th align='center'>
		<input type='text' name='cpname' value='<?php echo $cp_name?>' size='30'/>
	</th>
</tr>

<tr>	
	<th> 状态 </th>
	<th align='center'>
		正常<input type=radio name=status value=1 <?php if($cp_status==1) echo "checked=\"checked\""?>/> 
		关闭<input type=radio name=status value=2 <?php if($cp_status==2) echo "checked=\"checked\""?>/> 
	</th>
</tr>
	
</table>
 <br>

 <input type=submit name='submit' value='确定'>
</form>
