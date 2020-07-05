<?php
// Template
$sql = "SELECT `url` FROM `template` WHERE `bool` = '1'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$template = $stmt->fetchColumn();
?>
