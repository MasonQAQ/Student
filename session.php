<?php
if(!isset($_SESSION)){
    session_start();
}else{
    echo 'Session started.';
}
