<?php
	
      include("mysql.php");
      function check_login()
      {
      		global $mysqli;
      	  if(isset($_COOKIE['username'])&&isset($_COOKIE['password']))
      	  {
      	  	  $username=$_COOKIE['username'];
      	  	  $password=$_COOKIE['password'];
      	  	  $sql="select username from wraith_users where username='$username' and password='$password'";
      	  	  //echo $sql;
                  $result=mysqli_query($mysqli,$sql);
                  if(mysqli_num_rows($result)==1)
                     return true;
                  else
                     return false;
      	  }
      	  return false;
      }
      if(!check_login()) 
       {
       	   header("Location:login.htm");
       }

?>
