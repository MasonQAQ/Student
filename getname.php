<?php
require_once('include.php');
if(!isset($_SESSION['name'])){
        exit(callback1(-1,'Please Login!'));
}
else{
	exit(callback1(0,$_SESSION['name']));
}
?>