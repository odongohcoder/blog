<?php
$meta = [];
$meta["META_KEYWORDS"] = "Blog";
$meta["META_DESCRIPTION"] = "Open Source Blog";
$meta["META_URL"] = $base;
$meta["META_TITLE"] = isset($result[0]['title']) ? $result[0]['title'] : $admintitle;
$meta["AUTHOR"] = isset($result[0]['name']) ? $result[0]['name'] : $_SESSION['name'];
$meta["DATE"] = isset($result[0]['date']) ? date("Y-m-d", strtotime($result[0]['date'])) : date("Y-m-d");
?>
