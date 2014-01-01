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
<font size=4><caption>通道列表>></caption></font>
<br><br><br>
<font size=1>

<!--<table border=1 cellspacing="0">-->
<table border="1" cellspacing="0" cellpadding="1" width="50%" class="tabs">

<tr>
	<th>序号</th>
	<th>spnumber</th>
	<th>mo指令</th>
	<th>单条计费</th>
	<th>信息类型</th>
	<th>所属SP</th>	
	<th>状态</th>
	<th>编辑</th>
	<th>删除</th>
</tr>
<?php

  $buf= "select * from mtrs_channel";
  $result=mysql_query($buf) or die (mysql_error());
  echo "<div align=left><font size=2>共<font color=red>".mysql_num_rows($result)."</font>条记录";
  while($row=mysql_fetch_row($result))
  {
    echo"<tr>";
		//seq
	  echo "<td align=center><font size=2>$row[0]</td>";
	  
	  //sp number
	  echo "<td align=center><font size=2>$row[1]</td>";
	  
	  //mocmd
	  echo "<td align=center><font size=2>$row[2]</td>";
	  
	  //fee
	  echo "<td align=center><font size=2>$row[6]</td>";
	  
	  //msgtype
	  if($row[3]==1)
			echo "<td align=center><font size=2>短信</td>";
		else if($row[3]==2)
			echo "<td align=center><font size=2>彩信</td>";
		else
			echo "<td align=center><font size=2>数据异常</td>";
	  
	  //belong which sp
	  //get spname
		$sql="select spname from mtrs_sp where ID=$row[5]";
		$result_spname=mysql_query($sql) or die (mysql_error());
	  $row_spname=mysql_fetch_row($result_spname);
	  $spname=$row_spname[0];
	  echo "<td align=center><font size=2>$spname</td>";
	  
	  //status
	  if($row[4]==1)
			echo "<td align=center><font size=2>正常</td>";
		else if($row[4]==2)
			echo "<td align=center><font size=2>关闭</td>";
		else
			echo "<td align=center><font size=2>数据异常</td>";
			
		//modify
	//	echo "<td align=center><font size=2>编辑</td>";
		echo "<td align=center ><font size=2><a href=\"channel_edit.php?channel_id=$row[0] \" >编辑</a>&nbsp;</td>";
		//delete
		echo "<td align=center onclick=\"return ask($row[0]);\"><font size=2><a href=\"channel_del.php?channelid=$row[0]\" >删除</a>&nbsp;</td>";
    echo"</tr>";
  }
?>
</table>

</font>
</body>
