<?php
require_once('conn.php');
require_once('session.php');
function checkRight($id){
	if(isset($_SESSION['id'])){
		if($_SESSION['id']==$id){
			return true;
		}
		if($_SESSION['isAdmin']==1){
			return true;
		}
		return false;
	}else{
		return false;
	}
}

function isIdExist($id){	
	$sql="select id from `student_student` where `id`=(?)";           //我修改了
	global $conn;
	$stmt=$conn->prepare($sql);
	$stmt->bind_param('s',$id);
	$stmt->execute();
//	echo print_r($stmt);
//	$stmt->bind_result($id);
	if($stmt->fetch()){
		return true;
	}else{
		return false;
	}
}
function add($id,$pwd,$name,$gender,$photo,$grade){
	$pwd=md5($pwd);
	$sql="insert into student_manage (id,password) values (?,?)";
	global $conn;
	$stmt=$conn->prepare($sql);
	$stmt->bind_param('ss',$id,$pwd);
	$stmt->execute();
	// var_dump($stmt->execute());

	$sql="insert into student_student (id,name,gender,photopath,grade) values (?,?,?,?,?)";
	$stmt=$conn->prepare($sql);
	$stmt->bind_param('ssisi',$id,$name,$gender,$photo,$grade);
	$stmt->execute();
	// var_dump($stmt->execute());
	if($stmt->affected_rows==1){
		exit(callback(0,'Register Success!'));
		// exit(callbackSuccess());
	}
	if($stmt->affected_rows==-1){
		exit(callback(-1,'id already exists!'));
	}
}
function del($id){
	if(!isIdExist($id)){
		exit(callback(-1,'Delete Error','id not exists!'));
	}
	global $conn;

	// //判断删除的是不是管理员
	// $sql="select isAdmin from student_manage where id=(?)";
	// $stmt=$conn->prepare($sql);
	// $stmt->bind_param('s',$id);
	// $stmt->execute();
	// // $stmt->bind_result($_isAdmin);
	// var_dump($stmt->bind_result($_isAdmin));
	// var_dump($_isAdmin);
	// if ($_isAdmin==1) {
	// 	exit(callback(-1,'Delete Error','you cannot delete Admin!'));
	// }
	// $stmt->free_result();

	$sql="delete from student_manage where id=(?)";
	$stmt=$conn->prepare($sql);
	$stmt->bind_param('s',$id);
	$stmt->execute();
	$stmt->free_result();

	$sql="delete from student_student where id=(?)";
	$stmt=$conn->prepare($sql);
	$stmt->bind_param('s',$id);
	$stmt->execute();
	$stmt->close();
	if($stmt->affected_rows==1){
		exit(callback(0,'Delete Success!'));
	}
	if($stmt->affected_rows==0){
		exit(callback(-1,'Delete Error!','id not exists!'));
	}
}
function mod($id,$name,$gender,$photo,$grade){
	// var_dump($id.$name.$gender.$photo.$grade);
	if(!isIdExist($id)){
		exit(callback(-1,'Delete Error','id not exists!'));
	}
	global $conn;
	$sql="select name,gender,photopath,grade from student_student where id=(?)";
	$stmt=$conn->prepare($sql);
	$stmt->bind_param('s',$id);
	$stmt->execute();
	$stmt->bind_result($_name,$_gender,$_photo,$_grade);
	$name=empty($name) ? $_name : $name;
	$gender=empty($gender) ? $_gender : $gender;
	$photo=empty($photo) ? $_photo : $photo;
	$grade=empty($grade) ? $_grade : $grade;
	$stmt->free_result();
	$stmt->close();	
	$sql="UPDATE student_student SET name=?,gender=?,photopath=?,grade=? WHERE id=?";
	$stmt1=$conn->prepare($sql);
	if ($stmt1 === false) {
		//trigger_error($conn->error, E_USER_ERROR);
		print_r($conn);
	}
	$stmt1->bind_param('sisss',$name,$gender,$photo,$grade,$id);
	$stmt1->execute();	
	// var_dump($stmt1);
	if($stmt1->affected_rows==1){
		exit(callback(0,'Update Success!'));
	}
	if($stmt1->affected_rows==0){
		exit(callback(-1,'Update Error!','Unknown Error'));
	}	
}	
function modpwd1($id,$old,$new){
	if(!isIdExist($id)){
		exit(callback(-1,'Modpwd Error','id not exists!'));
	}
	global $conn;
	//echo "id:".$id;
	$sql="select id from `student_manage` where `id`=(?) and `password`=(?)";
	$stmt=$conn->prepare($sql);
	$stmt->bind_param('ss',$id,md5($old));
	$stmt->execute();
	if($stmt->fetch()){
		$stmt->free_result();
		$stmt->close();
		$sql="UPDATE student_manage SET password=? WHERE id=?";
		$stmt=$conn->prepare($sql);
		if ($stmt === false) {
			//trigger_error($conn->error, E_USER_ERROR);
			print_r($conn);
		}
		$stmt->bind_param('ss',md5($new),$id);
		$stmt->execute();	
		if($stmt->affected_rows==1){
			exit(callback(0,'Modify Password Success!'));
		}
		if($stmt->affected_rows==0){
			exit(callback(-1,'Modify Password Error!','Same with the previous password.'));
		}	
	}else{
		exit(callback(-1,'Modpwd Error','Old password wrong!'));
	}
}
function modpwd2($id,$new){
	if(!isIdExist($id)){
		exit(callback(-1,'Modpwd Error','id not exists!'));
	}
	global $conn;
	$sql="UPDATE student_manage SET password=? WHERE id=?";
	$stmt1=$conn->prepare($sql);
	if ($stmt1 === false) {
		//trigger_error($conn->error, E_USER_ERROR);
		print_r($conn);
	}
	$stmt1->bind_param('ss',md5($new),$id);
	$stmt1->execute();	
	if($stmt1->affected_rows==1){
		exit(callback(0,'Modify Password Success!'));
	}
	if($stmt1->affected_rows==0){
		exit(callback(-1,'Modify Password Error!','Unknown Error'));
	}	
}
function upload(){
	//require_once('include.php');

	//print_r($_FILES["upfile"]); 
	if(is_uploaded_file($_FILES['photo']['tmp_name'])){ 
	$upfile=$_FILES["photo"]; 
	$name=$upfile["name"];//上传文件的文件名 
	$type=$upfile["type"];//上传文件的类型 
	$size=$upfile["size"];//上传文件的大小 
	$tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径 
	$ext=".jpeg";
	switch ($type){ 
		case 'image/pjpeg':
			$okType=true; 
			$ext=".jpg";
			break; 
		case 'image/jpeg':
			$okType=true; 
			$ext=".jpg";
			break; 
		case 'image/gif':
			$okType=true; 
			$ext=".gif";
			break; 
		case 'image/png':
			$okType=true; 
			$ext=".png";
			break; 
		default :
			$oktype=false;
			$ext=".webp";
		} 

	if($okType){ 
	/** 
	* 0:文件上传成功<br/> 
	* 1：超过了文件大小，在php.ini文件中设置<br/> 
	* 2：超过了文件的大小MAX_FILE_SIZE选项指定的值<br/> 
	* 3：文件只有部分被上传<br/> 
	* 4：没有文件被上传<br/> 
	* 5：上传文件大小为0 
	*/ 
		$error=$upfile["error"];//上传后系统返回的值 
	//echo "上传文件名称是：".$name."<br/>"; 
	//echo "上传文件类型是：".$type."<br/>"; 
	//echo "上传文件大小是：".$size."<br/>"; 
	//echo "上传后系统返回的值是：".$error."<br/>"; 
	//echo "上传文件的临时存放路径是：".$tmp_name."<br/>";
	//echo "开始移动上传文件<br/>"; 
	//把上传的临时文件移动到up目录下面 
	$random=date(DATE_ATOM)+rand(1,999);
	$name=md5($random).$ext;
	move_uploaded_file($tmp_name,'photo/'.$name); 
	$destination="photo/".$name; 
	//echo "上传信息：<br/>"; 
	if($error==0){ 
		//echo "文件上传成功啦！"; 
		//echo "<br>图片预览:<br>"; 
		//echo "<img src=".$destination.">"; 
		return $destination;
		//echo " alt=\"图片预览:\r文件名:".$destination."\r上传时间:\">"; 
	}elseif ($error==1){ 
		exit(callback(-1,'upload error','more','超过了文件大小，在php.ini文件中设置')); 
	}elseif ($error==2){ 
		exit(callback(-1,'upload error','more','超过了文件的大小MAX_FILE_SIZE选项指定的值')); 
	}elseif ($error==3){ 
		exit(callback(-1,'upload error','more','文件只有部分被上传')); 
	}elseif ($error==4){ 
		exit(callback(-1,'upload error','more','没有文件被上传')); 
	}else{ 
		exit(callback(-1,'upload error','more','上传文件大小为0')); 
	} 
}else{ 
	exit(callback(-1,'upload error','more','请上传jpg,gif,png等格式的图片！')); 
} 
} 
}
?>
