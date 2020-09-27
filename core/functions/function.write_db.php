<?php

// Function: write database
function Write_DB($pdo,$sql,$param){
  $stmt = $pdo->prepare($sql);
  if (isset($param)) {
    foreach ($param as $keyparam => $valparam) {
      $pdoconstant = PDO::PARAM_STR;
      $stmt->bindParam($keyparam, $valparam[0],!isset($valparam[1])?$pdoconstant:$valparam[1]);
    }
  }
  $stmt->execute();
}

?>
