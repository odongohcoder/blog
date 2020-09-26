<?php

// Function: upload image
function Delete_Image($fileTmpName,$fileName){
  global $maxDim;
  foreach ($maxDim as $key => $val){
    if ($key == 'large') {
      // unlink('..' . _UPLOADDIRECTORY . $image);
      return '..' . _UPLOADDIRECTORY . $image;
    } else {
      // unlink('..' . _UPLOADDIRECTORY . $key . '/' . $image);
      return '..' . _UPLOADDIRECTORY . $key . '/' . $image;
    }
  }
}

?>
