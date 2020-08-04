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

//Check filelink function
function Check_File($key,$val){
  $tmp = explode('?', $val);
  $fileParameter = strtolower(end($tmp));
  $tmp = explode('.', strtok($val, "?"));
  $fileExtension = strtolower(end($tmp));
  if(in_array($fileExtension,$fileExtensionsAudio)){
    if(in_array($fileParameter,$fileParameters)){
      array_push($longcopy_text[$key], 'audio', strtok($val, "?") . '?dl=1');
    } else {
      array_push($longcopy_text[$key], 'audio', $val);
    }
  } elseif(in_array($fileExtension,$fileExtensionsImages)){
    array_push($longcopy_text[$key], 'img', $val);
  }
}
?>
