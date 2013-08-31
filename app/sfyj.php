<?php
/*
$text = '1234';
 
$cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
$iv_size = mcrypt_enc_get_iv_size($cipher);
 
$key = 't5d0c#OAn%MAhLWD';
$iv =  'L+\~f4,Ir)b$=pkf';
 
echo "<strong>IV:</strong> " . bin2hex($iv) . '<br />';
echo "<strong>Key:</strong> " . bin2hex($key) . '<br />';
echo '<strong>Before encryption:</strong> ' . $text . '<br />';
 
// Encrypt
if (mcrypt_generic_init($cipher, $key, $iv) != -1)
{
    $encrypted = mcrypt_generic($cipher, $text);
    mcrypt_generic_deinit($cipher);
 
    echo '<strong>After encryption:</strong> ' . bin2hex($encrypted) . '<br />';
}
 
// Decrypt
if (mcrypt_generic_init($cipher, $key, $iv) != -1)
{
    $decrypted = mdecrypt_generic($cipher, $encrypted);
    mcrypt_generic_deinit($cipher);
 
    echo '<strong>After decryption:</strong> ' . $decrypted . '<br />';
}
*/

function addpadding($string, $blocksize = 16){
	$len = strlen($string);
	$pad = $blocksize - ($len % $blocksize);
	$string .= str_repeat(chr($pad), $pad);
	return $string;
}
function fnEncrypt($Value, $SecretKey, $iv){
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $SecretKey, addpadding($Value), MCRYPT_MODE_CBC, $iv)));
}
//解碼
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
$KEY = 't5d0c#OAn%MAhLWD';
$IV =  'L+\~f4,Ir)b$=pkf';
$Value = '1234';
//壓碼使用範例:
$en = fnEncrypt($Value,$KEY,$IV);
//echo $en;

//解碼使用範例:
$de = fnDecrypt($en,$KEY,$IV);
//echo $de;
?>
