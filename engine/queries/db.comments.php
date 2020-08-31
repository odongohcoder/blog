<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}

$sql = "
SELECT `comment`.`comment`, `comment`.`date`, `users`.`id`, `users`.`name`, `users`.`users_image` FROM `comment`
INNER JOIN `users` ON `users`.`id` = `comment`.`userid`
WHERE `comment`.`postid` = :postid
ORDER BY `date` DESC
";
$param = [':postid'=>[$_GET["article"],PDO::PARAM_INT]];
$comments = Read_DB($pdo,$sql,$param);
$comment_err = '';
?>
