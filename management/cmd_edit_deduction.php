<?php

		require_once("area_code.php");
	
		echo "<edit_deduction>";
		echo "<select id='area_code'>";
		echo "<option value='0'>默认</option>";
		while($key = key($area_code))
		{
			echo "<option value='$key'>$area_code[$key]</option>";
			next($area_code);
		}
		echo "</select>";

		echo "扣量<input id='deduction_value' type=text size='5'/>%";
		echo "<input id='deduction_submit' type=submit  value='提交'><br>";
		echo "</edit_deduction>";
?>