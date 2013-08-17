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
		$message = '感谢使用双鹏展业提供的游戏玩家业务，您的1元卡号为123423425，密码为34543。1元/条。客服电话：4006075336。';
	if(substr($record->{'message'},0,1)=='2')
		$message = '感谢使用双鹏展业提供的游戏乐园业务，您的2元卡号为342512342，密码为54334。2元/条。客服电话：4006075336。';
	if(substr($record->{'message'},0,1)=='3')
		$message = '感谢使用双鹏展业提供的娱乐无限业务，您的3元卡号为767712342，密码为76974。3元/条。客服电话：4006075336。';
	if(substr($record->{'message'},0,1)=='4')
		$message = '感谢使用双鹏展业提供的游戏天地业务，您的4元卡号为346723965，密码为54778。4元/条。客服电话：4006075336。';
	if(substr($record->{'message'},0,1)=='5')
		$message = '感谢使用双鹏展业提供的指尖互动业务，您的5元卡号为340034872，密码为87349。5元/条。客服电话：4006075336。';
	if(strtolower(substr($record->{'message'},0,1))=='a')
		$message = '感谢使用双鹏展业提供的双鹏资讯业务，人的学习和成长过程，是一个非智力因素起决定作用的过程。5元/条。客服电话：4006075336。';
	
	
	
	
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
