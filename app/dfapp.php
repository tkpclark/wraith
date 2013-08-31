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
	
	
	if($record->{'default_msg'})
		$message=$record->{'default_msg'};
	
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
