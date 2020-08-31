<?php

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

?>
