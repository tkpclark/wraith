﻿<?php
  include("mysql.php");
  $username=$_POST['name'];
  $password=$_POST['pass'];
  $md5_password=md5($password);
  $sql="select usertype,products from wraith_sta_users where username='$username' and password='$md5_password'";

  $result=mysqli_query($mysqli,$sql);
  if(mysqli_num_rows($result)==1)
  {
     setcookie("username",$username);
     setcookie("password",$md5_password);
     $row=mysqli_fetch_row($result);
     setcookie("usertype",$row[0]);
     setcookie("products",$row[1]);
     //record
     $sql="insert into wraith_login_history(username,action,time) values('$username','1',NOW())";
     if(!exsql($sql))
     {
         echo $sql;
     }
		 header("Location:home.html");
  }
  else
  {
  	header("Location:login.htm"); 	
  }
  
?>
