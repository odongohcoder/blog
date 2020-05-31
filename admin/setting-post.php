<?php
include_once 'start.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  $setting = isset($_POST['setting']) ? $_POST['setting'] : '';

  $sql = "UPDATE `settings` SET `bool`='0'";
  if($stmt = $pdo->prepare($sql)){
    $stmt->execute();
    if(!empty($setting)){
      foreach($setting as $key => $row){
        $sql = "UPDATE `settings` SET `bool`='1' WHERE `id`=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $row, PDO::PARAM_STR);
        $stmt->execute();
      }
    }
    if(empty($setting) || $row === end($setting)){
      header('location:index.php');
    } else {
      die('Something went wrong');
    }
  }
}
?>
