<?php

$url='';

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $url = "https://"; 
}   
else {
    $url = "http://";
}
if(isset($_SERVER['HTTP_HOST']) && isset($_SERVER['PHP_SELF'])){
    $url.= $_SERVER['HTTP_HOST'];
    $currentPath = $_SERVER['PHP_SELF']; 
    $pathInfo = pathinfo($currentPath);
    $url.= $pathInfo['dirname'];
}   
?>