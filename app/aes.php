<?php
function addpadding($string, $blocksize = 16){
	$len = strlen($string);
	$pad = $blocksize - ($len % $blocksize);
	$string .= str_repeat(chr($pad), $pad);
	return $string;
}
function fnEncrypt($Value, $SecretKey, $iv){
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $SecretKey, addpadding($Value), MCRYPT_MODE_CBC, $iv)));
}

function strippadding($string){
	$slast = ord(substr($string, -1));
	$slastc = chr($slast);
	$pcheck = substr($string, -$slast);
	if(preg_match("/$slastc{".$slast."}/", $string)){
		$string = substr($string, 0, strlen($string)-$slast);
		return $string;
	} else {
		return false;
	}
}
function fnDecrypt($Value, $SecretKey, $iv){
	$str = strippadding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $SecretKey, base64_decode($Value), MCRYPT_MODE_CBC, $iv));
	return $str;
}

//自定您的KEY , IV
/*
$KEY = 't5d0c#OAn%MAhLWD';
$IV =  'L+\~f4,Ir)b$=pkf';
$Value = '1234';
//壓碼使用範例:
$en = fnEncrypt($Value,$KEY,$IV);
//echo $en;

//解碼使用範例:
$de = fnDecrypt($en,$KEY,$IV);
//echo $de;
*/
?>
