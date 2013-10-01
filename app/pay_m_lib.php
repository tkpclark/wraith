<?php
function is_phone_legal($record)
{
	global $logging;
	$logging->info($record['allow_province']);
	//$logging->info($record['Phone']);
	return 1;
}