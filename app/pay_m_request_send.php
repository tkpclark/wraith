<?php
/*
// 1. 初始化
$ch = curl_init();
$p1 = "kpPovuQMHkeQlf7u2AkAfbQW1YbpOWA6YMuOulGjVVgGAGOPsGz6B94HKhWD0ajFQdBhweb11ySGqzBceRtaoA%3d%3d";
$Prim = urldecode($p1);

// 2. 设置选项，包括URL
$url = "http://202.85.209.109/pay_m_7453?Prim=".$Prim."&PayChannelCode=SJDXSPDX";

$data = array('foo'=>'bar',
		'baz'=>'boom',
		'cow'=>'milk',
		'php'=>'hypertext processor'
);
$url = "http://202.85.209.109/pay_m_7453";

curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "PayChannelCode=SJDXSPDX");
// 3. 执行并获取HTML文档内容
$output = curl_exec($ch);
echo $output;
// 4. 释放curl句柄
curl_close($ch);

*/



$p1 = "kpPovuQMHkeQlf7u2AkAfbQW1YbpOWA6YMuOulGjVVgGAGOPsGz6B94HKhWD0ajFQdBhweb11ySGqzBceRtaoA%3d%3d";
$Prim = urldecode($p1);
$url = 'http://202.85.209.109/pay_m_7453';
$post_data = "Prim=".$Prim."&PayChannelCode=SJDXSPDX";
$ch = curl_init () ;
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$result = curl_exec ( $ch ) ;
curl_close($ch);
echo $result;


/*
function do_post_request($url, $data, $optional_headers = null)
{
	$params = array('http' => array(
			'method' => 'POST',
			'content' => $data
	));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response;
}

$post_data = "Prim=3&PayChannelCode=4";
$url = 'http://202.85.209.109/pay_m_7453';
echo do_post_request($url, $post_data);

*/
?>