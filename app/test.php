<?php
	require_once('/home/log4php/php/Logger.php');

	Logger::configure('log4php_config.xml');
	$logging = Logger::getLogger('testProduct');

	//$logging->info('just for test.');
	//$logging->info($_SERVER['REQUEST_URI']);
	$logging->info($_GET['record']);
	$record = json_decode($_GET['record']);
	$logging->info("sp_number: ".$record->{'sp_number'})
	
?>
