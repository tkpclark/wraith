

<form ENCTYPE="multipart/form-data" ACTION="a.php" METHOD="POST">
<input type="hidden" name="MAX_FILE_SIZE"  value="1000">
<div align="center"><center> 请选取文件:  
<input NAME="userfile" TYPE="file">
<input TYPE="submit" VALUE="Send File">
</center></div></form>
<?php

include("check.php");
include("style.php");


if(!isset($_FILES['userfile']))
	exit;
$first_file = $_FILES['userfile'];  //获取文件1的信息

$upload_dir = '/tmp/'; //保存上传文件的目录

//处理上传的文件1
if ($first_file['error'] == UPLOAD_ERR_OK){
    //上传文件1在服务器上的临时存放路径
    $temp_name = $first_file['tmp_name'];
    //上传文件1在客户端计算机上的真实名称
    $file_name = $first_file['name'];
    //移动临时文件夹中的文件1到存放上传文件的目录，并重命名为真实名称
    move_uploaded_file($temp_name, $upload_dir.$file_name);
    echo '上传成功!  开始导入...<br/><br/><br/>';
}else{
    echo '上传失败!<br/>';
    exit;
}

$file_handle = fopen($upload_dir.$file_name, "r");
while (!feof($file_handle)) {
	$line = trim(fgets($file_handle));
	echo $line." ";
	if($line=='')
		continue;
	if(is_numeric($line) && strlen($line)==11)
	{
		
		echo "格式正确!<br>";
		$sql=sprintf("insert into wraith_blklist(phone_number) values('%s')",$line);
		//echo $sql;
		exsql($sql);
	}
	else
	{
		echo "格式错误!<br>";
	}
		
	
}
fclose($file_handle);
?>