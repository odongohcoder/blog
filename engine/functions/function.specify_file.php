<?php

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

?>
