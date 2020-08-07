<?php
include_once 'start.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  // Sanitize POST
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

  $title =  isset($_POST['title']) ? trim($_POST['title']) : '';
  $subtitle =  isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
  $subject =  isset($_POST['subject']) ? trim($_POST['subject']) : '';

  // Create 2 dimensional arrays of files
  if (isset($_FILES['longcopy']['name'])) {
    $longcopy_files = $_FILES['longcopy']['name'];
    foreach ($longcopy_files as $key => $val){
      (!empty($val)) ?: $image_err = 'Please upload image';
      $longcopy_files[$key] = [];
      array_push($longcopy_files[$key], 'img', $val);
    }
  }
  // Create 2 dimensional arrays of textarea
  if (!empty($_POST['longcopy'])) {
    $longcopy_text = $_POST['longcopy'];
    foreach ($longcopy_text as $key => $val){
      (!empty($val)) ?: $longcopy_err = 'Please enter longcopy' ;
      $longcopy_text[$key] = [];
      array_push($longcopy_text[$key], 'txt', $val);
    }
  }
  // Combine 2 dimensional arrays and sort in order of appearance
  $longcopy = $longcopy_text + $longcopy_files;
  ksort($longcopy);
  print_r($longcopy);

  // Validate title
  if(empty($title)){
    $title_err = 'Please enter title';
  }
  // Validate subtitle
  if(empty($subtitle)){
    $subtitle_err = 'Please enter subtitle';
  }

  // Prepare file upload
if($_FILES){
  foreach ($_FILES['longcopy']['name'] as $i => $fileName){
    $fileSize = $_FILES['longcopy']['size'][$i];
    $fileTmpName  = $_FILES['longcopy']['tmp_name'][$i];
    $fileType = $_FILES['longcopy']['type'][$i];
    if($fileName){
      if(Specify_File($fileName) != 'img'){
        $image_err = 'Please upload a JPG, PNG or GIF file';
      } elseif($fileSize > 2000000){
        $image_err = 'Please upload a file less than or equal to 2MB';
      } elseif(empty($image_err) && empty($title_err) && empty($subtitle_err) && empty($longcopy_err)){
        Upload_Image($fileTmpName,$fileName,$maxDim);
      }
    }
  }
}

  // Make sure errors are empty
  if(empty($title_err) && empty($subtitle_err) && empty($image_err) && empty($longcopy_err)){

    if($row === end($longcopy) || empty($longcopy)){
      header('location: ../index.php?article='.$postid);
    } else {
      die('Something went wrong');
    }
  }
}
?>
