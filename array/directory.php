<?php
define('_SUBFOLDER', '/');
// protocol http or https
define('_PROTOCOL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http');
// base url of blog
define('_BASE', _PROTOCOL . '://' . $_SERVER['SERVER_NAME'] . _SUBFOLDER);
// admin folder and file
define('_DIR', str_replace(_SUBFOLDER, '', dirname($_SERVER['PHP_SELF'])));
// current file
define('_FILE', basename($_SERVER['SCRIPT_FILENAME']));
// root server
define('_CURRENTDIR', $_SERVER['DOCUMENT_ROOT'] . _SUBFOLDER);
// main upload folder images
define('_UPLOADDIRECTORY', "/img/article/");
// parameter to variable
!isset($_GET["subject"]) ?: $subject_id = intval($_GET["subject"]);
!isset($_GET["article"]) ?: $article_id = intval($_GET["article"]);
