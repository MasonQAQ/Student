<?php
//error_reporting(E_ALL & ~E_NOTICE);
$callback='';
$leftBracket='';
$rightBracket='';
$end='';
if(isset($_GET['callback'])){
	$callback=$_GET['callback'];
	$callback=htmlspecialchars($callback);
	$leftBracket='(';
	$rightBracket=')';
	$end=';';
}
function callback($errorCode,$errorMsg,$more='',$moreKey='more'){
		$res['errorCode']=$errorCode;
		$res['errorMsg']=$errorMsg;
		return json_encode($res,JSON_UNESCAPED_UNICODE);
		//return "{\"errorCode\":\"$errorCode\",\"errorMsg\":\"$errorMsg\"}";
}

function callback1($errorCode,$errorMsg,$more='',$moreKey='more'){
	global $callback,$leftBracket,$rightBracket,$end;
	if($more){
		return $callback.$leftBracket."{\"errorCode\":\"$errorCode\",\"errorMsg\":\"$errorMsg\",\"$moreKey\":\"$more\"}".$rightBracket.$end;
	}else{
		return $callback.$leftBracket."{\"errorCode\":\"$errorCode\",\"errorMsg\":\"$errorMsg\"}".$rightBracket.$end;
	}
}
// function callbackFail(){
// 	$res['errorCode']='-1';
// 	$res['errorMsg']="fail";
// 	return json_encode($res,JSON_UNESCAPED_UNICODE);


// function callbackSuccess(){
// 	$res['errorCode']='0';
// 	$res['errorMsg']="success";
// 	return json_encode($res,JSON_UNESCAPED_UNICODE);
// }