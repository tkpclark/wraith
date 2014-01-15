<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="easyui/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="easyui/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="easyui/demo/demo.css">
        <script type="text/javascript" src="easyui/jquery.min.js"></script>
        <script type="text/javascript" src="easyui/jquery.easyui.min.js"></script>
        <script>
        
		
        $(document).ready(function(){
            pageSize=20;
        	function compose_url(query_type,pageNumber){
                var url="";
                url += "stat_rent_query.php?";
                url += "&query_type="+query_type;
                url += "&phone_number="+$("#phone_number").val();
                url += "&products="+$("#products").val();
                url += "&date1="+$('#date1').datebox('getValue');
                url += "&date2="+$('#date2').datebox('getValue');
                url += "&pageSize="+pageSize;
                url += "&pageNumber="+pageNumber;

                return url;
                
            }
            
        	var date= new Date();
    		$('#date1').datebox('setValue', yesterday(date));
    		$('#date2').datebox('setValue', yesterday(date));
    		
			$("#query").click(function(){
				$.getJSON(compose_url('result_info',0), function(result){
					//alert(result.count);
					$("#result_info").text('总条数：'+result.count+' '+'总金额：'+result.sum);
					$('#result_records').panel('refresh',compose_url('result_page',1));
				});
        	})
        })
        
        </script>
</head>
<body>



<?php 
	include("check.php"); 
	include("style.php");
?>

	
	
	<table width='100%'><tr>
 	<td>开始时间&nbsp;<input id="date1" type="text" class="easyui-datebox" data-options="formatter:myformatter" required="required" value=""></input></td>
 	<td>结束时间&nbsp;<input id='date2' type="text" class="easyui-datebox" data-options="formatter:myformatter" required="required" value=""></input></td>
 	<script type="text/javascript">
		function myformatter(date){
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate();
			return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
		}

		function yesterday(date){
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate()-1;
			return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
		}
		
		
	</script>
 	<td>手机号码<input id=phone_number name=phone_number type=text value='' /></td>
 	<td>计费代码&nbsp;&nbsp;
 	<select id=products>
<?php

 	echo "<option value=".$_COOKIE['products'].">全部</option>";
 	$sql="select id,message from wraith_products where id in (".$_COOKIE['products'].")";
 	$result=exsql($sql);
 	while($row=mysqli_fetch_row($result))
 	{
 		echo "<option value=$row[0]>$row[1]</option>";
 	}
 	
 ?>	
 	</select></td>
 	<td><button id=query type=button>查询</button></td>
 	</tr></table>
	
 	
	
	<table width='100%'>
	<tr>
	 <td width=70%><div id="pagination" class="easyui-pagination" data-options="" style="border:1px solid #ddd;"></div></td>
	 <td id='result_info'></td>  
	</tr>
	</table>
	<div id='result_records' class='easyui-panel' style='height:200px'></div>'
	
</body>
</html>




