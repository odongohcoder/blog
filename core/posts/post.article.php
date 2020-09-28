<?php
// Init session
session_start();
// Include db config
require_once '../constants/directory.php';
// Include db config
require_once '../../creds/db.php';
// Include image sizes
include_once '../../array/sizes.php';
// Include functions
require_once '../functions/function.read_db.php';
require_once '../functions/function.write_db.php';
require_once '../functions/function.specify_file.php';
require_once '../functions/function.upload_image.php';

$datum = date("Y-m-d H:i:s");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // Sanitize POST
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

  $title =  isset($_POST['title']) ? trim($_POST['title']) : '';
  $subtitle =  isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
  $subject =  isset($_POST['subject']) ? trim($_POST['subject']) : '';
  $deletedParagraph = isset($_POST['delete_paragraph']) ? $_POST['delete_paragraph'] : '';

  // Validate title
  if(empty($title)){
    $title_err = 'Please enter title';
  }
  // Validate subtitle
  if(empty($subtitle)){
    $subtitle_err = 'Please enter subtitle';
  }

  // Create 2 dimensional arrays of files
  if (isset($_FILES['longcopy']['name'])) {
    $longcopy_files = $_FILES['longcopy']['name'];
    foreach ($longcopy_files as $key => $val){
      if (isset($_POST['file_name'][$key])){
        (!empty($val)) ?: $val = $_POST['file_name'][$key];
      }
      $longcopy_files[$key] = [];
      (empty($val)) ?: array_push($longcopy_files[$key], 'img', $val);
    }
  }
  // Create 2 dimensional arrays of textarea
  if (!empty($_POST['longcopy'])) {
    $longcopy_text = $_POST['longcopy'];
    foreach ($longcopy_text as $key => $val){
      $longcopy_text[$key] = [];
      array_push($longcopy_text[$key], 'txt', $val);
    }
  }
  // Combine 2 dimensional arrays and sort in order of appearance
  $longcopy = array_filter($longcopy_text);
  (!isset($longcopy_files)) ?: $longcopy += array_filter($longcopy_files);
  ksort($longcopy);

  // Make sure errors are empty
  if(empty($title_err) && empty($subtitle_err) && empty($longcopy_err)){

    // Upload image
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
            Upload_Image($fileTmpName,$fileName);
          }
        }
      }
    }

    if ($_GET["article"] != 'new') {

      // -- START UPDATE -- //
      // Remove selected paragraphs from post
      if(!empty($deletedParagraph)){
        foreach ($deletedParagraph as $key => $val){
          $sql = $sql = 'UPDATE `paragraph` SET `postid` = :postid WHERE `id` = :id AND `userid` = :userid';
          $param = [':postid'=>[NULL],':id'=>[$val],':userid'=>[$_SESSION['id']]];
          Write_DB($pdo,$sql,$param);
        }
      }
      // Delete longcopy from paragraph
      $sql = 'DELETE FROM `paragraph` WHERE `postid` = :postid';
      $param = [':postid'=>[$_GET["article"]]];
      Write_DB($pdo,$sql,$param);
      // Write longcopy in order of appearance
      if(!empty($longcopy)){
        foreach ($longcopy as $item => $row){
          $sql = 'INSERT INTO `paragraph` (`userid`, `paragraph`, `postid`, `item`) VALUES (:userid, :paragraph, :postid, :item)';
          $param = [':userid'=>[$_SESSION['id']],':paragraph'=>[$row[1]],':postid'=>[$_GET["article"]],':item'=>[$row[0]]];
          Write_DB($pdo,$sql,$param);
        }
      }
      // Update title, subtitle and subject
      $sql = 'UPDATE `post` SET `title`=:title, `subtitle`=:subtitle, `subject`=:subject, `date`=:datum WHERE `id` = :id AND `userid` = :userid';
      $param = [':title'=>[$title],':subtitle'=>[$subtitle],':subject'=>[$subject[0]],':datum'=>[$datum],':id'=>[$_GET["article"]],':userid'=>[$_SESSION['id']]];
      Write_DB($pdo,$sql,$param);
      // -- END UPDATE -- //

    } else {

      // -- START INSERT -- //
      $sql = 'INSERT INTO `post` (`userid`, `title`, `subtitle`, `subject`, `date`) VALUES (:userid, :title, :subtitle, :subject, :datum)';
      $param = [':userid'=>[$_SESSION['id']],':title'=>[$title],':subtitle'=>[$subtitle],':subject'=>[$subject[0]],':datum'=>[$datum]];
      Write_DB($pdo,$sql,$param);

      $lastInsertId = $pdo->lastInsertId();

      if(!empty($longcopy)){
        foreach ($longcopy as $item => $row){
          $sql = 'INSERT INTO `paragraph` (`userid`, `paragraph`, `postid`, `item`) VALUES (:userid, :paragraph, :postid, :item)';
          $param = [':userid'=>[$_SESSION['id']],':paragraph'=>[$row[1]],':postid'=>[$lastInsertId],':item'=>[$row[0]]];
          Write_DB($pdo,$sql,$param);
        }
      }
      // -- END INSERT -- //

    }

    if(empty($title_err)){
      // header('location: ../../index.php?article='.(($_GET["article"] != 'new') ? $_GET["article"] : $lastInsertId));

    } else {
      die('Something went wrong');
    }
  }
}
?>
