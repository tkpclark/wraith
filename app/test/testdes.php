<?php
require_once('../des.php');
$key='12345678';
$str='abcdefg';
$data=do_mencrypt($str,$key);
echo $data."\n";
$data=do_nencrypt($str,$key);
echo $data."\n";
$Des = new Des();
$encode = $Des->encrypt($str,$key,true);
//$decode = $Des->decrypt($encode,$key,true);
echo $encode."\n";


$Des_Crypt = new Des_Crypt($key);
$encode = $Des_Crypt->encrypt($str,$key,true);
echo $encode."\n";
