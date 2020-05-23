<?php
include_once 'start.php';

// Process form when post submit
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // Sanitize POST
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  // Put post vars in regular vars
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
      // Attempt to execute
      if($stmt->execute()){
        $send = "Upload succesful";
        header('location: ../index.php?article='.$postid);
      } else {
        $send = "Something went wrong";
        die('Something went wrong');
      }
    }
    unset($stmt);
  }
  // Close connection
  unset($pdo);
}
?>
