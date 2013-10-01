<?php
  //   header("Content-type:text/html;charset=GB2312");
     include("mysql.php");
      
      $username='admin';
      $password=md5('1234');
			$sql="insert into mtrs_users (username,password,type) values ('$username','$password',1)";
			echo $sql;
			exsql($sql);
			
?>