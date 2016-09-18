<?php
/***********************************************************************************
Method:

POST

Param:

upfile(上传表单中的name="upfile")
callback=Callback(回调函数，非必须)

Ret:

Callback({'errorCode':'0','errorMsg':'Upload Success!','path':'/photo/10001.jpg'});
Callback({'errorCode':'-1','errorMsg':'Upload Error!','more':'Image Only!'});
*************************************************************************************/
require_once('include.php');
$res=upload();
if($res){
	exit(callback(0,'Upload Success!',$res));
}else{
	exit(callback(-1,'Upload Error!',$res));
}
?>
