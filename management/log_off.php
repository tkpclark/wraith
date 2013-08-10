<?php
    include("check.php");
    $username=$_COOKIE['username'];
    $action=0;//action ==0 表示注销,default is 0

    $sql="insert into wraith_login_history (username,action,time) values('$username','$action',NOW())";
    exsql($sql);
    setcookie("username","");
    setcookie("password","");
    //echo "欢迎再次使用！ 正在退出......";
  //  sleep(1);
    
    header("Location:login.htm");
?>
