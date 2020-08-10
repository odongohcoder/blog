<?php

// Function: read database
function Read_DB($pdo,$sql,$param){
  $stmt = $pdo->prepare($sql);
  if (isset($param)) {
    foreach ($param as $keyparam => $valparam) {
      $stmt->bindParam($keyparam, $valparam[0],$valparam[1]);
    }
  }
  $stmt->execute();
  $result = $stmt->fetchAll();
  return $result;
}

// Function: write database
function Write_DB($pdo,$sql,$param){
  $stmt = $pdo->prepare($sql);
  if (isset($param)) {
    foreach ($param as $keyparam => $valparam) {
      $stmt->bindParam($keyparam, $valparam[0],$valparam[1]);
    }
  }
  $stmt->execute();
}

// Function set template variable
function Template_Vars($author,$result,$type,$name){
  if ($author && ($type != 'file')) {
    $author_result = '<input type="';
    $author_result .= $type;
    $author_result .= '" ';
    $author_result .= 'name="';
    $author_result .= $name;
    $author_result .= '" ';
    $author_result .= 'value="';
    $author_result .= $result;
    $author_result .= '">';
    return $author_result;
  } elseif ($type != 'file') {
    return $result;
  }
}

// Function: specify file
function Specify_File($file){
  $fileExtensionsImages = ['jpeg','jpg','png','gif'];
  $fileExtensionsAudio = ['mp3','m4a','ogg'];
  $fileParameters = ['dl=0','dl=1'];
  $tmp = explode('.', $file);
  $fileExtension = strtolower(end($tmp));
  if(in_array($fileExtension,$fileExtensionsAudio)){
    return 'audio';
  } elseif(in_array($fileExtension,$fileExtensionsImages)){
    return 'img';
  }
}

// Function: upload image
function Upload_Image($fileTmpName,$fileName,$maxDim){
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

?>
