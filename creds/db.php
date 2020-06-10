<?php
($base)?: die('Something went wrong');

define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'blog');

try {
  $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8", DB_USERNAME, DB_PASSWORD);
} catch(PDOException $e) {
  die("ERROR: Could not connect.");
}
