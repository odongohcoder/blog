<?php
// Init session
session_start();
// Validate login
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header('location: login.php');
  exit;
}
// Include paths
include_once '../array/directory.php';
// Include db config
require_once '../creds/db.php';
// Include db config
require_once '../admin/functions.php';
// Include settings
require_once '../array/template.php';
// Include menu items
include_once '../array/links.php';
// Include image sizes
include_once '../array/sizes.php';

// Init vars
$admintitle = 'Admin';
foreach ($adminmenu as $row){
  if (_DIR . '/' . _FILE == $row['link']) {
    $admintitle = $row['name'];
  }
}
$datum = date("Y-m-d H:i:s");
$null = null;

$fileName = $postid = '';
$longcopy = $longcopy_err = '';
$image = $image_err = '';
$url = $url_err = '';
$comment = $comment_err = '';
$allposts = $allposts_err = '';
$subject = $subject_err = '';
$deletedImages = $deletedImages_err = '';
$title = $title_err = '';
$subtitle = $subtitle_err = '';
$name = $name_err = '';
$email = $email_err = '';

// Init arrays
$longcopy = $longcopy_files = $longcopy_text = [];

// Include meta vars
include_once '../array/meta.php';
?>
