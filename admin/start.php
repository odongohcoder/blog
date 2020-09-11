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
// Include image sizes
include_once '../array/sizes.php';
// Include functions
require_once '../engine/functions/function.read_db.php';
require_once '../engine/functions/function.write_db.php';
require_once '../engine/functions/function.specify_file.php';
require_once '../engine/functions/function.upload_image.php';
// Include classes
require_once '../engine/classes/class.article.php';
// Include queries
require_once '../engine/queries/db.template.php';
// Include menu items
include_once '../array/links.php';

// Init vars
$admintitle = 'Admin';
foreach ($adminmenu as $val){
  if (_DIR . '/' . _FILE == $val['link']) {
    $admintitle = $val['name'];
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
