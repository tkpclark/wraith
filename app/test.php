<?php
	require_once('/home/log4php/php/Logger.php');
	require_once('mysql.php');

	Logger::configure('log4php_config.xml');
	$logging = Logger::getLogger('testProduct');

	//$logging->info('just for test.');
	//$logging->info($_SERVER['REQUEST_URI']);
	$logging->info($_GET['record']);
	$record = json_decode($_GET['record']);
	$logging->info("sp_number: ".$record->{'sp_number'});
	//$logging->info("sp_number: ".$record['sp_number']);
	var_dump($record);
	//$sql = "select * from wraith_mo";
	echo $record->{'sp_number'};
	
	
	if(substr($record->{'message'},0,1)=='1')
		$message = '双鹏展业欢迎您！这里是游戏玩家业务，回复5+任意内容体验指尖互动，回复a+任意内容体验双鹏咨询！';
	if(substr($record->{'message'},0,1)=='2')
		$message = '双鹏展业欢迎您！这里是游戏乐园业务，回复5+任意内容体验指尖互动，回复a+任意内容体验双鹏咨询！';
	if(substr($record->{'message'},0,1)=='3')
		$message = '双鹏展业欢迎您！这里是娱乐无限业务，回复5+任意内容体验指尖互动，回复a+任意内容体验双鹏咨询！';
	if(substr($record->{'message'},0,1)=='4')
		$message = '双鹏展业欢迎您！这里是游戏天地业务，回复5+任意内容体验指尖互动，回复a+任意内容体验双鹏咨询！';
	if(substr($record->{'message'},0,1)=='5')
		$message = '双鹏展业欢迎您！这里是指尖互动业务，回复5+任意内容体验指尖互动，回复a+任意内容体验双鹏咨询！';
	if(strtolower(substr($record->{'message'},0,1))=='a')
		$message = '双鹏展业欢迎您！这里是双鹏资讯业务，回复5+任意内容体验指尖互动，回复a+任意内容体验双鹏咨询！';
	
	
	
	
	$sql = sprintf("insert into wraith_mt(gwid,sp_number,phone_number,linkid,amount,product_id,product_code,message,in_time) 
			values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',NOW() )",
		$record->{'gwid'},
		$record->{'sp_number'},
		$record->{'phone_number'},
		$record->{'linkid'},
		$record->{'amount'},
		$record->{'product_id'},
		$record->{'product_code'},
		//$record->{'message'}
		$message
		);
	echo $sql;
	$logging->info($sql);
	mysqli_query($mysqli, "set names utf8");
	$res = mysqli_query($mysqli, $sql);
	if (!$res) {
	    echo "Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	/*
	if ($row = $res->fetch_assoc()) {
	    echo $row['sp_number'];
	}
	*/
	
?>
