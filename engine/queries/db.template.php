<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}

$sql = "SELECT `url` FROM `template` WHERE `bool` = '1'";
$result = Read_DB($pdo,$sql,null);
$template = $result['0']['url'];
?>
