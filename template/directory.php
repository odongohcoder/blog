<?php
// subfolder on top of $_SERVER['SERVER_NAME']
$subfolder = "/blog";
// protocol http or https
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
// base url of blog
$base = $protocol . '://' . $_SERVER['SERVER_NAME'] . $subfolder;
// current folder
$dir = ltrim(dirname($_SERVER['PHP_SELF']), $subfolder);
// current file
$file = basename($_SERVER['SCRIPT_FILENAME']);
// root server
$currentDir = $_SERVER['DOCUMENT_ROOT'] . $subfolder;
// main upload folder images
$uploadDirectory = "/img/article/";
?>
