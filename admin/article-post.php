<?php
include_once 'start.php';

// Process form when post submit
if($_SERVER['REQUEST_METHOD'] === 'POST'){

  // Sanitize POST
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

  // Put post vars in regular vars
  $title =  isset($_POST['title']) ? trim($_POST['title']) : '';
  $subtitle =  isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
  // $subject =  isset($_POST['subject']) ? trim($_POST['subject']) : '';

  // Create 2 dimensional arrays of files
  if (isset($_FILES['longcopy']['name'])) {
    $longcopy_files = $_FILES['longcopy']['name'];
    foreach ($longcopy_files as $key => $val){
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
    // Validate image
    (!empty($fileName)) ?: $image_err = 'Please upload image' ;
    $fileSize = $_FILES['longcopy']['size'][$i];
    $fileTmpName  = $_FILES['longcopy']['tmp_name'][$i];
    $fileType = $_FILES['longcopy']['type'][$i];
    $tmp = explode('.', $fileName);
    $fileExtension = strtolower(end($tmp));
    // Validate image
    if($fileName){
      if(!in_array($fileExtension,$fileExtensionsImages)){
        $image_err = 'Please upload a JPG, PNG or GIF file';
      } elseif($fileSize > 2000000){
        $image_err = 'Please upload a file less than or equal to 2MB';
      } elseif(empty($image_err) && empty($title_err) && empty($subtitle_err) && empty($longcopy_err)){
        // Resize Image -- $maxDim defined in ../array/sizes.php included in start.php
        list($width, $height) = getimagesize($fileTmpName);
        $src = imagecreatefromstring(file_get_contents($fileTmpName));
        foreach ($maxDim as $keyDim => $valDim){
          $ratio = $width/$height;
          $new_width = ($width > $valDim) ? $valDim : $width;
          $new_height = ($width > $valDim) ? $valDim/$ratio : $height;
          $subfolder = ($keyDim == 'large') ? '' : $keyDim . '/';
          $uploadPath = _CURRENTDIR . _UPLOADDIRECTORY . $subfolder . basename($fileName);
          $dst = imagecreatetruecolor($new_width, $new_height);
          imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
          imagejpeg($dst, $uploadPath, 100);
          imagedestroy($dst);
        }
        imagedestroy($src);
      }
    }
  }
}

  // Make sure errors are empty
  if(empty($title_err) && empty($subtitle_err) && empty($image_err) && empty($longcopy_err)){

    // Prepare insert query
    $sql = 'INSERT INTO `post` (`userid`, `title`, `subtitle`, `subject`, `date`) VALUES (:userid, :title, :subtitle, :subject, :datum)';

    if($stmt = $pdo->prepare($sql)){
      // Bind params
      $stmt->bindParam(':userid', $_SESSION['id'], PDO::PARAM_INT);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':subtitle', $subtitle, PDO::PARAM_STR);
      $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
      $stmt->bindParam(':datum', $datum, PDO::PARAM_STR);
      $stmt->execute();
      $postid = $pdo->lastInsertId();

      if(!empty($longcopy)){
        foreach ($longcopy as $item => $row){
          $sql = 'INSERT INTO `paragraph` (`userid`, `paragraph`, `postid`, `item`) VALUES (:userid, :paragraph, :postid, :item)';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':userid', $_SESSION['id'], PDO::PARAM_STR);
          $stmt->bindParam(':paragraph', $row[1], PDO::PARAM_STR);
          $stmt->bindParam(':postid', $postid, PDO::PARAM_STR);
          $stmt->bindParam(':item', $row[0], PDO::PARAM_STR);
          $stmt->execute();
        }
      }

      if($row === end($longcopy) || empty($longcopy)){
        header('location: ../index.php?article='.$postid);
      } else {
        die('Something went wrong');
      }
    }
  }
}
?>
