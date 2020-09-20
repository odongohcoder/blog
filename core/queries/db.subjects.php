<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}

$sql = "SELECT * FROM `subject`";
$subjects = Read_DB($pdo,$sql,null);
?>
