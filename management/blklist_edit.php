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


<font size=4><caption>黑名单添加>></caption></font>
<br><br><br>

<body>
<form name=blklist_edit_form action=blklist_edit_do.php method=post >

手机号码：<input type=text name=phone_number size=30>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=submit value=添加>


</form>
