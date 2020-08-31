<?php

// Function: upload image
function Upload_Image($fileTmpName,$fileName){
  global $maxDim;
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
