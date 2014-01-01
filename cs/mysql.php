<?php
  //   header("Content-type:text/html;charset=GB2312");
     $ip="202.85.209.109";
     $user="wraith";
     $pwd="tengyewudi2012@)!@";
     $db="wraith";
      
      
      $mysqli = new mysqli($ip, $user, $pwd, $db);
			if($mysqli->connect_error)
			{
				die('Connect Error (' . $mysqli->connect_errno . ') '
						. $mysqli->connect_error);
			}
      function exsql($sql)
			{
				global $mysqli;
				$result = mysqli_query($mysqli,$sql);
				if(!$result)
				 {
				 		echo "mysql error!\n";
				    echo $sql;
				    die('Connect Error (' . $mysqli->connect_errno . ') '
						. $mysqli->connect_error);
				 }
				 return $result;
			}
			
		//	$sql="set names gbk";
  		//exsql($sql);
?>
