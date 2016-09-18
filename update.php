<?php
header('Content-type: application/json');
/*******************************************************************************************
Method:

POST

Param:

action (add,delete,modify三选一，表示添加，删除，修改。必须)
id(存在此参数时，判断权限；无此参数时，修改自身的)
name(名字，非必须，为空表示不修改)
gender(姓名，非必须，为空表示不修改)
photo(照片路径，非必须，为空表示不修改)
grade(年级，非必须，为空表示不修改)
callback=Callback(回调函数，非必须)

Ret:

Callback({'errorCode':'0','errorMsg':'Modify Success!'});
Callback({'errorCode':'-1','errorMsg':'Modify Error!','more':'Permission denied/no such id'});
**********************************************************************************************/
require_once('include.php');
//检测是否登录，若没登录则转向登录界面
if(!isset($_SESSION['id'])){
    header("Location: login.html");
    exit(callback(-1,'Update Error!','you have no permission'));
}
if(!isset($_POST['action'])){
        exit(callback(-1,'Please specify an action!'));
}
$action=$_POST['action'];
if(isset($_POST['id'])){
	if ($_SESSION['isAdmin']==0) {
		if(!checkRight($id)){
			exit(callback(-1,'update error','Permission Denied!'));
		}
	}
	switch($action){
		case 'add':
			$id=$_POST['id'];
			$name=$_POST['name'];
			$gender=$_POST['gender'];
			if($gender) $gender=1;
			//$photo=$_POST['photo'];
			$photo=upload();
			$grade=$_POST['grade'];
			if(!isset($name)){exit(callback(-1,'Add Error','name not null'));}
			if(!isset($gender)){exit(callback(-1,'Add Error','gender not null'));}
			if(!isset($photo)){exit(callback(-1,'Add Error','photo not null'));}
			if(!isset($grade)){exit(callback(-1,'Add Error','grade not null'));}
			if ($_SESSION['isAdmin']==0) {
				exit(callback(-1,'update error','Permission Denied!'));
			}
			add($id,$id,$name,$gender,$photo,$grade);//default password is the $i
			break;
		case 'delete':
			$id=$_POST['id'];
			if ($_SESSION['isAdmin']==0) {
					exit(callback(-1,'update error','Permission Denied!'));
			}
			// var_dump($id);
			del($id);
			break;
		case 'modify':
			$id=$_POST['id'];
			$name=$_POST['name'];
			$gender=$_POST['gender'];
			if($gender) $gender=1;
			//$photo=$_POST['photo'];
			$photo=upload();
			// var_dump($photo);
			$grade=$_POST['grade'];
			mod($id,$name,$gender,$photo,$grade);
			break;
		default:
			exit(callback(-1,'action error','action must be add/delete/modify'));
	}
}else{
	switch($action){
		case 'add':
			exit(callback(-1,'permission error','not allow to add'));
			break;
		case 'delete':
			exit(callback(-1,'permission error','not allow to delete yourself'));
			break;
		case 'modify':
			$id=$_SESSION['id'];
			$name=$_POST['name'];
			$gender=$_POST['gender'];
			if($gender) $gender=1;
			//$photo=$_POST['photo'];
			$photo=upload();
			$grade=$_POST['grade'];
			mod($id,$name,$gender,$photo,$grade);
			break;
		default :
			exit(callback(-1,'action error','action must be add/delete/modify'));
	}
}
?>
