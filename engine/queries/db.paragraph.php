<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}

$sql = "SELECT * FROM `paragraph` WHERE `paragraph`.`postid` = :article_id";
$param = [':article_id'=>[$article_id,PDO::PARAM_INT]];
$paragraph = Read_DB($pdo,$sql,$param);
?>
