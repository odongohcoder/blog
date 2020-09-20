<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}

if (isset($_GET["article"])):
  $sql = "
  SELECT `post`.`id`, `post`.`date`, `post`.`userid`, `post`.`title`, `post`.`subtitle`, `subject`.`id`, `subject`.`subject`, `users`.`id`, `users`.`name`, `users`.`users_image` FROM `post`
  INNER JOIN `subject` ON `subject`.`id` = `post`.`subject`
  INNER JOIN `users` ON `users`.`id` = `post`.`userid`
  WHERE `post`.`id` = :article_id
  ";
  $param = [':article_id'=>[$article_id,PDO::PARAM_INT]];
  $result = Read_DB($pdo,$sql,$param);
else:
  $sql = "
  SELECT `post`.`id`, `post`.`date`, `post`.`userid`, `post`.`title`, `post`.`subtitle`, `subject`.`id`, `subject`.`subject`, `users`.`id`, `users`.`name`, `users`.`users_image` FROM `post`
  INNER JOIN `subject` ON `subject`.`id` = `post`.`subject`
  INNER JOIN `users` ON `users`.`id` = `post`.`userid`
  ";
  if (isset($_GET["subject"])):
    $sql .= " WHERE `subject`.`id` = :subject_id";
    $param = [':subject_id'=>[$subject_id,PDO::PARAM_INT]];
  else:
    $param = null;
  endif;
  $sql .= " ORDER BY `post`.`id` DESC";
  $result = Read_DB($pdo,$sql,$param);
endif;
?>
