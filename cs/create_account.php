<?php 
	include("check.php"); 
	include("style.php");
?>
<script language="Javascript">
function checkpwd()
{
	if(document.form1.name.value=="")
	{
		alert("请输入用户名！");
		document.form1.name.focus();
		return false;
	}
	if(document.form1.pwd1.value==""||document.form1.pwd2.value=="")
	{
		alert("请输入密码！");
		document.form1.pwd1.focus();
		return false;
	}
	if(document.form1.pwd1.value!=document.form1.pwd2.value)
	{
		alert("两次输入的密码不同!");
		return false;
	}
	if(document.form1.position.value)
	{
		alert("请选择帐号类型！");
		document.form1.position.focus();
		return false;
	}
	if((document.form1.position.value=="worker")&&(document.form1.member_name.value==""))
	{
		alert("请选择大小工姓名");
		return false;
	}
	
}

</script>
<font size=4 color=red>创建新帐号：</font>
<br><br><br><br><br><br>
<center>

<table border="1" cellspacing="0" cellpadding="1" width="410" >
<form action=ps_create_accounts.php name=form1 method=post onsubmit="return checkpwd();">
<tr height="30"><td align=center><font size=2 color=red>用户名：&nbsp;&nbsp;<input type=text name=name></td></tr>
<tr height="30"><td align=center><font size=2 color=red>密码：&nbsp;&nbsp;&nbsp;&nbsp;<input type=password name=pwd1></td></tr>
<tr height="30"><td align=center><font size=2 color=red>重复输入：<input type=password name=pwd2></td></tr>
<tr height="30"><td align=center><font size=2 color=red>请选择帐号的类型：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr height="30"><td align=center><input type=radio name=position value=admin><font size=2 color=red>管理员&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr height="30"><td align=center><input type=radio name=position value=counter><font size=2 color=red>前台&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr height="30"><td align=center><input type=radio name=position value=manage><font size=2 color=red>后台管理&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr height="30"><td align=center><input type=radio name=position value=finance><font size=2 color=red>财务&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr height="30"><td align=center><input type=radio name=position value=worker><font size=2 color=red>大小工&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr height="30"><td align=center> 
<select name="member_name">
<option value=""><font size=2 color=red>请选择使用员工的姓名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
<?php
   
   $sql="select name from member";
   $result=mysql_query($sql);
   while($row=mysql_fetch_row($result))
   {      
	echo"<option value=$row[0]>$row[0]</option>";
   }	
?>		  
</select>
</td>
<tr><td align=center><input type=submit value=确定></td></tr>
</table>

</center>
