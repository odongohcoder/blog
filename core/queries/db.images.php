<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}

$sql = "SELECT `paragraph`.`paragraph`, `paragraph`.`id`, `paragraph`.`postid` FROM `paragraph` WHERE `paragraph`.`userid` = :userid AND `paragraph`.`item` = 'img'";
$param = [':userid'=>[$_SESSION['id']];
$images = Read_DB($pdo,$sql,$param);

?>
