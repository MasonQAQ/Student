<?php
/******************************************************************
Method:

POST

Param:

callback=Callback(回调函数，非必须)

Ret:

Callback({'errorCode':'0','errorMsg':'Logout Success!'});
Callback({'errorCode':'-1','errorMsg':'Unknown Error!'});
*******************************************************************/
require_once('include.php');
 
// 如果要清理的更彻底，那么同时删除会话 cookie
// 注意：这样不但销毁了会话中的数据，还同时销毁了会话本身
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
 
if(isset($_SESSION['id'])){
	$_SESSION = array();
	session_unset();
	session_destroy();
	exit(callback(0,'Logout Success'));
}else{
	$_SESSION = array();
	session_unset();
	session_destroy();
	exit(callback(0,'Not login'));
}
?>
