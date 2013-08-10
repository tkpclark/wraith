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
?>
<body>
<font size=4><caption>CP列表>></caption></font>
<br><br><br>
<font size=1>

<!--<table border=1 cellspacing="0">-->
<table border="1" cellspacing="0" cellpadding="1" width="50%" class="tabs">

<tr><th>序号</th><th>CP名称</th><th>状态</th><th>编辑</th><th>删除</th></tr>
<?php

  $buf= "select * from mtrs_cp";
  $result=mysql_query($buf) or die (mysql_error());
  echo "<div align=left><font size=2>共<font color=red>".mysql_num_rows($result)."</font>条记录";
  while($row=mysql_fetch_row($result))
  {
    echo"<tr>";
		//seq
	  echo "<td align=center><font size=2>$row[0]</td>";
	  
	  //cp name
	  echo "<td align=center><font size=2>$row[1]</td>";
	  
	  //status
	  if($row[2]==1)
			echo "<td align=center><font size=2>正常</td>";
		else if($row[2]==2)
			echo "<td align=center><font size=2>关闭</td>";
		else
			echo "<td align=center><font size=2>数据异常</td>";
			
		//modify
	//	echo "<td align=center><font size=2>编辑</td>";
		echo "<td align=center><font size=2><a href=\"cp_edit.php?cp_id=$row[0]\" >编辑</a>&nbsp;</td>";
		//delete
		echo "<td align=center  onclick=\"return ask($row[0]);\"><font size=2><a href=\"cp_del.php?cpid=$row[0]\" >删除</a>&nbsp;</td>";
    echo"</tr>";
  }
?>
</table>

</font>
</body>
