

<font size=4><caption>黑名单添加>></caption></font>
<br><br><br>

<table>
<tr>
<td>单个添加</td>
<td>
<form name=blklist_edit_form action=blklist_edit_do.php method=post >
手机号码：<input type=text name=phone_number size=30>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=submit value=添加>
</form>
</td>
</tr>

<tr>
<td>批量添加</td>
<td>
<form ENCTYPE="multipart/form-data" ACTION="blklist_edit.php" METHOD="POST">
<input type="hidden" name="MAX_FILE_SIZE"  value="10000000">
请选取文件:  
<input NAME="userfile" TYPE="file">
<input TYPE="submit" VALUE="Send File">
</form>
</td>
</tr>

</table>
<?php

include("check.php");
include("style.php");


if(!isset($_FILES['userfile']))
	exit;
$first_file = $_FILES['userfile'];  //获取文件1的信息

$upload_dir = '/tmp/upload'; //保存上传文件的目录

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
	
	echo "失败！ 原因：";
    switch($first_file['error']) {

    
	case 1:
		echo "文件大小超出了服务器的空间大小";
		break;

	case 2:
		echo "要上传的文件大小超出浏览器限制";
		break;
		 
	case 3:
		echo "文件仅部分被上传";
		break;
		 
	case 4:
		echo "没有找到要上传的文件";
		break;
		 
	case 5:
		echo "服务器临时文件夹丢失";
		break;
		 
	case 6:
		echo "文件写入到临时文件夹出错";
		break;
}
    exit;
}

$file_handle = fopen($upload_dir.$file_name, "r");
$count=0;
while (!feof($file_handle)) {
	$line = trim(fgets($file_handle));
	if($line=='')
		continue;
	if(1)
	{
		
		//echo "格式正确!<br>";
		$sql=sprintf("insert into wraith_blklist(phone_number) values('%s')",$line);
		//echo $sql;
		exsql($sql);
		$count++;
	}
	else
	{
		$a=strlen($line);
		echo $a;
		echo "<font color='red'>[".$line."] 格式错误!忽略</font><br>";
	}
		
	
}
fclose($file_handle);
echo "<br><br>成功导入".$count."个号码!";
?>
