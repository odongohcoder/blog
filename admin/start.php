<?php
// Init session
session_start();
// Validate login
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header('location: login.php');
  exit;
}
// Include db config
require_once '../creds/db.php';
// Include paths
include_once '../template/directory.php';
// Include meta vars
include_once '../array/meta.php';
// Include menu items
include_once '../array/links.php';
// Include image sizes
include_once '../array/sizes.php';

foreach ($adminmenu as $row){
  if ($dir . '/' . $file == $row['link']) {
    $admintitle = $row['name'];
  }
}
($admintitle) ?: $admintitle = 'Admin' ;
?>
