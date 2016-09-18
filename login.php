<?php
header('Content-type: application/json');
require_once('include.php');
//session_unset();
if(!isset($_POST['id'])){
	exit();
        //exit(callback(-1,'Login Error!','id not null'));
}
if(!isset($_POST['pwd'])){
	exit();
       // exit(callback(-1,'Login Error!','pwd not null'));
}
$id = htmlspecialchars($_POST['id']);
$pwd = MD5($_POST['pwd']);
//$pwd = $_GET['pwd'];
// 创建连接
include('conn.php'); 
$aaa = "select * from student_manage,student_student where student_manage.id='$id' and student_manage.password='$pwd' and student_manage.id=student_student.id limit 1";
if($check_query = mysqli_query($conn,$aaa)){
	// var_dump($check_query);
	if($result = mysqli_fetch_assoc($check_query) ) {
		//var_dump($result);
		$_SESSION['id']=$id;
		$_SESSION['pwd']=$result['password'];
		$_SESSION['name']=$result['name'];
		$_SESSION['isAdmin']=$result['isAdmin'];
		$result2['errorCode'] = 0;
		$result2['errorMsg'] = 'Login Success!';
		//exit(json_encode($result2));
	} else  {
		$result2['errorCode'] = -1;
		$result2['errorMsg'] = 'Login Error!';
		$result2['more'] = 'id not exists or password is not correct!';
		//exit(json_encode($result2,JSON_UNESCAPED_UNICODE));
		}
//	var_dump($_SESSION);
	exit(json_encode($result2,JSON_UNESCAPED_UNICODE));
}
mysqli_close($conn);
?>
