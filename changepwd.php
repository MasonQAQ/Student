<?php
header('Content-type: application/json');
/**************************************************************************************************
Method:

POST

Param:

id(存在此参数时，判断权限；无此参数时，修改自身的)
oldpwd(老密码，如果是管理员修改别人的，忽略此参数；否则，此参数必须)
newpwd(新密码，必须)
callback=Callback(回调函数，非必须)

Ret:

Callback({'errorCode':'0','errorMsg':'Change Password Success!'});
Callback({'errorCode':'-1','errorMsg':'Change Password Error!/Old Password Invalid/Permission denied'});
*******************************************************************************************************/
require_once("include.php");
if(!isset($_SESSION['id'])){
    header("Location: login.html");
    exit(callback(-1,'Update Error!','you have no permission'));
}
$id=$_POST['id'];
$oldpwd=$_POST['oldpwd'];
$newpwd=$_POST['newpwd'];
if(!isset($_POST['newpwd'])){
        exit(callback(-1,'Please specify a newpwd!'));
}
if(isset($_POST['id'])&&$_SESSION['isAdmin']==1){
	if(!checkRight($id)){
		exit(callback(-1,'update error','Permission Denied!'));
	}
	modpwd2($id,$newpwd);
}else{
	if(!isset($oldpwd)){
		exit(callback(-1,'Please specify the oldpwd!'));
	}
	modpwd1($_SESSION['id'],$oldpwd,$newpwd);
}