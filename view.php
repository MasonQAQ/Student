<?php
header('Content-type: application/json');
require_once('include.php');
//var_dump($_SESSION);
//检测是否登录，若没登录则转向登录界面
if(!isset($_SESSION['id'])){
	header("Location: login.html");
    exit(callback(-1,'view Error!','you have no permission'));
}
include('conn.php');
$userid = $_SESSION['id'];
if($user_query = mysqli_query($conn,"select * from student_student")){
	$array = array();
	$userscount = 0;
	while($row = mysqli_fetch_array($user_query)){
		$temp['id'] = $row['id'];
		$temp['name'] = $row['name'];
		$temp['gender'] = $row['gender'];
		$temp['photo'] = $row['photopath'];
		$temp['grade'] = $row['grade'];
		array_push($array,$temp);
		$userscount++;
	}
	$result['errorCode'] = 0;
	$result['errorMsg'] = 'Success!';
	$result['isAdmin'] = $_SESSION['isAdmin'];
	$result['userscount'] = $userscount;
	$result['array'] = $array;
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
};

?>
