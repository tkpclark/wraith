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
	if(isset($_GET['sp_id']))
	{
		$sp_id=$_GET['sp_id'];
				
		$sql = "select spname,status from mtrs_sp where id=$sp_id";
		//echo $sql;
		$result=mysql_query($sql) or die (mysql_error());
		$row=mysql_fetch_row($result);
		$sp_name=$row[0];
		$sp_status=$row[1];
		
	}
	else
	{
		$sp_name="";
		$sp_status="1";
	}
?>

<font size=4><caption>sp信息编辑>></caption></font>
<br><br><br>

<body>
<form name=sp_edit_form method=post action=sp_edit_do.php<?php if(isset($sp_id)) echo "?spID=$sp_id"; ?> onsubmit='return check()'>

<table border='1' cellspacing='0' cellpadding='1' width='25%' class='tabs'>
<?php
if(isset($sp_id))
{
	echo "<tr><th>ID</th><th align='center'>$sp_id</th></tr>";
}
?>
<tr>
	<th>sp名称&nbsp;&nbsp;</th>
	<th align='center'>
		<input type='text' name='spname' value='<?php echo $sp_name?>' size='30'/>
	</th>
</tr>

<tr>	
	<th> 状态 </th>
	<th align='center'>
		正常<input type=radio name=status value=1 <?php if($sp_status==1) echo "checked=\"checked\""?>/> 
		关闭<input type=radio name=status value=2 <?php if($sp_status==2) echo "checked=\"checked\""?>/> 
	</th>
</tr>
	
</table>
 <br>

 <input type=submit name='submit' value='确定'>
</form>
