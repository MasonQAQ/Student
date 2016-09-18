<?php
$host     = 'localhost'; //数据库服务器
$user     = 'root'; //数据库用户名
$password = 'yy201314'; //数据库密码
$database = 'student'; //数据库名
$conn = new mysqli($host, $user, $password,$database);
/* check connection */
if (mysqli_connect_errno()) {
//    printf("Connect failed: %s\n", mysqli_connect_error());
//    exit('数据库连接失败！或者指定数据库不存在');
	exit();
}
//print_r($conn);
mysqli_query($conn,"set names utf8");

