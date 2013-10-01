<head>
<script type="text/javascript" src="jquery.js"></script>
<script>
$(document).ready(function(){	
		//delete deduction
		$("deduction_del").click(function(){
			var cmd=$(this);
			var deduction_id=$(this).attr("value");
			//alert($(this).attr("value"));
			$.get("cmd_del_deduction.php?id="+deduction_id,function(result){
					var cmd_id=$(cmd).parent().parent().parent("tr").children("td:eq(0)").text();
					//cmd_id=$(cmd).text();
					//alert("delete"+cmd_id);
						$.get("cmd_display_deduction.php?cmd_id="+cmd_id,function(result){
							//alert($(cmd).parent().parent().children("display_deduction").text());
	    					$(cmd).parent().parent().children("display_deduction").replaceWith(result);
	    			});
			});	
		});
});
</script>
</head>
<?php

		require_once("area_code.php");
		require_once("check.php");
	
		if(!isset($_GET['cmd_id']))
		{
			echo "no argument cmd_id";
		}
		$cmd_id=$_GET['cmd_id'];
		echo "<display_deduction>";
		$sql="select zone,deduction,ID from mtrs_deduction where cmdID='$cmd_id'";
		$result=mysql_query($sql) or die (mysql_error());
		while($row=mysql_fetch_row($result))
		{
			if($row[0]==0)
				$zone="默认";
			else 
				$zone=$area_code[$row[0]];
				
			$per=100*$row[1];
				
			echo $zone."&nbsp;扣&nbsp;".$per."%&nbsp;";
			echo "<deduction_del value='$row[2]'><a href=#>删&nbsp;</a></deduction_del>";
			//echo "<deduction_modi><a href=#>改</a></deduction_modi>";
			echo "<br>";
		}
		echo "</display_deduction>";
?>