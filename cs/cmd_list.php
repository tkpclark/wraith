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
<head>
<script type="text/javascript" src="jquery.js"></script>
<script>
$(document).ready(function(){
		/*
		   jQuery.extend({

setTable:function () {
	//alert("it's my func!");
	$.get("cmd_list.php",function(result){
	$("body").replaceWith(result);
	});
	}
	});
		//$.setTable();
		 */
		$("see_deduction").click(function(){
			var cmd=$(this);
			var cmd_id=$(this).parent().parent("tr").children("td:eq(0)").text();
			//var cmd_id=$(this).text();
			//alert(cmd_id);
			$.get("cmd_display_deduction.php?cmd_id="+cmd_id,function(result){
				$(cmd).parent().children("display_deduction").replaceWith(result);
				});
			});

		$("see_deduction").click();


		$("add_deduction").click(function(){

				var add_deduction=$(this);
				//alert($(this).parent().parent("tr:eq(0)").children("td:eq(0)").text());
				//alert($(tr_old).children("td:eq(1)").text());
				//alert($(this).attr("value"));

				var cmd_id=$(add_deduction).parent().parent("tr:eq(0)").children("td:eq(0)").text();


				$.get("cmd_edit_deduction.php?id="+cmd_id,function(result){
					$(add_deduction).before(result);

					$("#deduction_submit").click(function(){
						//alert($("#deduction_value").val()+" "+$("#area_code").find("option:selected").text());
						var add_deduction_url="cmd_add_deduction.php?cmd_id="+cmd_id+"&area_code="+$("#area_code").find("option:selected").val()+"&deduction_value="+$("#deduction_value").val();
						//alert(add_deduction_url);
						$.get(add_deduction_url,function(result){
							//alert(result);
							$("edit_deduction").remove();

							$.get("cmd_display_deduction.php?cmd_id="+cmd_id,function(result){
								$(add_deduction).parent().children("display_deduction").replaceWith(result);


								});



							});
						});
				});
		});



});
</script>
</head>
<body>

<?php
echo "<body>";
echo "<font size=4><caption>指令列表>></caption></font>
<br><br><br>
<font size=1>

<table border='1' cellspacing='0' cellpadding='1' width='90%' class='tabs'>

<tr>
<th>序号</th>
<th>cp名称</th>
<th>指令</th>
<th>收费</th>
<th>通道</th>
<th>信息类型</th>
<th>扣量</th>
<th>url</th>
<th>状态</th>
<th>编辑</th>
<th>删除</th>
</tr>";
$buf= "SELECT t1.ID, t2.cpname, t1.spnumber, t1.mocmd, t3.spnumber, t3.mocmd,t3.msgtype, t1.status, t1.url, t1.fee
FROM mtrs_cmd t1, mtrs_cp t2, mtrs_channel t3 
where t1.cpID=t2.ID and t1.channelID=t3.ID;";
//echo $buf;
$result=mysql_query($buf) or die (mysql_error());
echo "<div align=left><font size=2>共<font color=red>".mysql_num_rows($result)."</font>条记录";
while($row=mysql_fetch_row($result))
{
	echo"<tr>";
	//seq
	echo "<td align=center><font size=2>$row[0]</td>";

	//cp name
	echo "<td align=center><font size=2>$row[1]</td>";

	//cmd(spnumber+mocmd)precise
	echo "<td align=center><font size=2>$row[2]+$row[3]</td>";

	//fee
	echo "<td align=center><font size=2>$row[9]</td>";
	
	//channel
	echo "<td align=center><font size=2>$row[4]+$row[5]</td>";
	

	//msgtype
	if($row[6]==1)
		echo "<td align=center><font size=2>短信</td>";
	else if($row[6]==2)
		echo "<td align=center><font size=2>彩信</td>";
	else
		echo "<td align=center><font size=2>数据异常</td>";

	//deduction

	echo "<td align=center>";
	//display deduction
	echo "<display_deduction></display_deduction>";

	////////////
	echo "<add_deduction><a href='#'>添加扣量</a></add_deduction>";
	echo "<see_deduction style='visibility:hidden'><a href='#'>查看扣量</a></see_deduction>";
	//	echo "&nbsp;";
	//	echo "<add_deduction_commit value='$row[0]'><a href='#'>提交</a></add_deduction_commit>";
	echo "</td>";

	//url
	echo "<td align=center><font size=2>$row[8]</td>";

	//状态
	if($row[7]==1)
		echo "<td align=center><font size=2>正常</td>";
	else if($row[7]==2)
		echo "<td align=center><font size=2>关闭</td>";
	else
		echo "<td align=center><font size=2>数据异常</td>";

	//modify
	//	echo "<td align=center><font size=2>编辑</td>";


	echo "<td align=center><font size=2><a href=\"cmd_edit.php?cmd_id=$row[0]\" >编辑</a>&nbsp;</td>";
	//delete
	echo "<td align=center onclick=\"return ask($row[0]);\" ><font size=2><a href=\"cmd_del.php?cmdid=$row[0]\" >删除</a>&nbsp;</td>";
	echo"</tr>";
}
mysql_free_result($result);
echo "</table>";
echo "</font>";

?>


</body>
