<?php

//Read database function
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

//Check file function
function Check_File($file){
  $tmp = explode('.', $file);
  $fileExtension = strtolower(end($tmp));
  if(in_array($fileExtension,$fileExtensionsAudio)){
    return 'audio';
  } elseif(in_array($fileExtension,$fileExtensionsImages)){
    return 'img';
  }
}

?>
