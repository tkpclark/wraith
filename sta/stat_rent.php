<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <title>Pagination Links - jQuery EasyUI Demo</title>
        <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="../../themes/icon.css">
        <link rel="stylesheet" type="text/css" href="../demo.css">
        <script type="text/javascript" src="../../jquery.min.js"></script>
        <script type="text/javascript" src="../../jquery.easyui.min.js"></script>
</head>
<body>



<?php 
	include("check.php"); 
	include("style.php");

 	$page_title="数据查询";
	$page_name="stat_rent.php";
	$tbl="wraith_rent_record";
	
	
	echo "<table><tr>";
 	$start_date = date("Y-m-d");
 	echo "<td>开始时间&nbsp;<input id='date1' name='d1' type='text' value=$start_date></td>";
 	$end_date = date("Y-m-d");
 	echo "<td>结束时间&nbsp;<input id='date2' name='d2' type='text' value=$end_date></td>";
 	
 	/*****phone_number*****/
 	echo "<td>手机号码<input id=phone_number name=phone_number type=text value='' /></td>";
 	
 	/*****product_id*****/
 	echo "<td>计费代码&nbsp;&nbsp;";
 	echo "<select name=product_id>";
 	echo "<option value=''>全部</option>";
 	$sql="select distinct(product_id) from wraith_products where status=1";
 	$result=exsql($sql);
 	while($row=mysqli_fetch_row($result))
 	{
 		echo "<option value=$row[0]>$row[0]</option>";
 	}
 	echo "</select>";
 	
 	
 	//echo "<input name='disp_result' type='hidden' value='1'>";
 	echo "<button>查询</button>";
 	echo "</tr></table>";

 	
	
	echo "<div class='easyui-pagination' style='border:1px solid #ccc;'
        	data-options='
            total: 2000,
            pageSize: 10,
            onSelectPage: function(pageNumber, pageSize){
                $('#content').panel('refresh', 'http://202.85.209.109/test/t1.php?page='+pageNumber);
            }'>";
            
	echo "<div id='content' class='easyui-panel' style='height:200px'
            data-options=\"href:'http://202.85.209.109/test/t1.php?page=1'\">
            </div>'";
?>
	
</body>
</html>




