<?php
// subfolder on top of $_SERVER['SERVER_NAME']
$subfolder = "/";
// protocol http or https
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
// base url of blog
$base = $protocol . '://' . $_SERVER['SERVER_NAME'] . $subfolder;
// admin folder and file
$dir = str_replace($subfolder,'',dirname($_SERVER['PHP_SELF']));
// current file
$file = basename($_SERVER['SCRIPT_FILENAME']);
// root server
$currentDir = $_SERVER['DOCUMENT_ROOT'] . $subfolder;
// main upload folder images
$uploadDirectory = "/img/article/";
?>
