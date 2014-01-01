<?php 
include('style.php');


?>
	
<font size=4><caption>重新加载>></caption></font>
<br><br><br>
<body>


<form name=load_config method=post action=load_config.php?load=1 >
<input type=submit name='submit' value='重新加载'  style="width:250px;height:500px;">
</form>
</body>


<?php

if(isset($_GET['load']))
{
	$cmd = "/home/app/wraith/src/controller/start_controller.sh";
	//$cmd='python /home/sms/MsgTunnel/src/msgforward/config_maker.py';
	//echo "cmd: ".$cmd."<br>";
	exec($cmd, $output, $result);
	if($result != 0)
		echo "加载失败!<br>";
	else
		echo $output[0];
	//var_dump($output);
}
?>