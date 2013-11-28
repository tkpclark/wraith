<?php
require_once('des.php');

function send_to_destination_1501($request)
{
	//mo 加密前参数依次为 手机号#指令#长号码#linkid
	//mr 加密前参数依次为 手机号#指令#长号码#linkid#状态报告
	
	global $logging;
	global $mysqli;

	if($request['deal_flag']==0)//send mo
	{
		$data = sprintf("%s#%s#%s#%s",$request['phone_number'],$request['message'],$request['sp_number'],$request['linkid']);
		$deal_flag = 1;
	}
	else if($request['deal_flag']==1 and strlen($request['report']) > 2)//send mr
	{
		$data = sprintf("%s#%s#%s#%s#%s",$request['phone_number'],$request['message'],$request['sp_number'],$request['linkid'],$request['report']);
		$deal_flag = 2;
	}
	else//wait for report
	{
		return;
	}
	
	//plaintext
	$logging->info("data plain text:".$data);
	
	///des
	$key='123';
	$data=do_mdecrypt($data,$key);
	//$logging->info("data des encrypted:".$data);
	
	//base64
	$data=base64_encode($data);
	$logging->info("data base64:".$data);
	
	
	$url="http://111.111.111/xxxx?data=".$data;
	$logging->info("url:".$url);
	
	
	$resp = file_get_contents($url);
	if(1)//success
	{

		update_deal_flag($request, $deal_flag);
	}
	else
	{
		$logging->info("ERROR:resp:".$resp);
	}


}