<?php
include_once 'start.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  $postid = trim($_POST['postid']);
  $comment =  trim($_POST['comment']);
  if(empty($comment)){
    $comment_err = 'Please enter comment';
  }
  if(empty($comment_err)){
    $sql = 'INSERT INTO `comment` (`userid`, `comment`, `postid`, `date`) VALUES (:userid, :comment, :postid, :date)';
    if($stmt = $pdo->prepare($sql)){
      $stmt->bindParam(':userid', $_SESSION['id'], PDO::PARAM_INT);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':postid', $postid, PDO::PARAM_STR);
      $stmt->bindParam(':date', $datum, PDO::PARAM_STR);
      if($stmt->execute()){
        header('location: ../index.php?article='.$postid);
      } else {
        die('Something went wrong');
      }
    }
  }
}
?>
