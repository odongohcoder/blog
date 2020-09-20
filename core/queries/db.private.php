<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}

$sql = "SELECT `bool` FROM `settings` WHERE `name` = :private";
$param = [':private'=>['Private',PDO::PARAM_INT]];
$private = Read_DB($pdo,$sql,$param);
?>
