<?php
// Init session
session_start();
// Include paths
require_once 'engine/constants/directory.php';
// Include db config
require_once 'creds/db.php';
// Include functions
require_once 'engine/functions/function.read_db.php';
require_once 'engine/functions/function.write_db.php';
require_once 'engine/functions/function.specify_file.php';
require_once 'engine/functions/function.upload_image.php';
// Include functions
require_once 'engine/classes/class.post.php';
// Include settings
require_once 'engine/queries/db.template.php';

// Check if blog is private
$sql = "SELECT `bool` FROM `settings` WHERE `name` = :private";
$param = [':private'=>['Private',PDO::PARAM_INT]];
$private = Read_DB($pdo,$sql,$param);

if((!isset($_SESSION['email']) || empty($_SESSION['email'])) && $private[0]['bool'] == '1'){
  header('location: admin/');
  exit;
}

// Include menu items
include_once 'array/links.php';

if (isset($_GET["article"])):
  $sql = "
  SELECT `post`.`id`, `post`.`date`, `post`.`userid`, `post`.`title`, `post`.`subtitle`, `subject`.`id`, `subject`.`subject`, `users`.`id`, `users`.`name`, `users`.`users_image` FROM `post`
  INNER JOIN `subject` ON `subject`.`id` = `post`.`subject`
  INNER JOIN `users` ON `users`.`id` = `post`.`userid`
  WHERE `post`.`id` = :article_id
  ";
  $param = [':article_id'=>[$article_id,PDO::PARAM_INT]];
  $result = Read_DB($pdo,$sql,$param);
else:
  $sql = "
  SELECT `post`.`id`, `post`.`date`, `post`.`userid`, `post`.`title`, `post`.`subtitle`, `subject`.`id`, `subject`.`subject`, `users`.`id`, `users`.`name`, `users`.`users_image` FROM `post`
  INNER JOIN `subject` ON `subject`.`id` = `post`.`subject`
  INNER JOIN `users` ON `users`.`id` = `post`.`userid`
  ";
  if (isset($_GET["subject"])):
    $sql .= " WHERE `subject`.`id` = :subject_id";
    $param = [':subject_id'=>[$subject_id,PDO::PARAM_INT]];
  else:
    $param = null;
  endif;
  $sql .= " ORDER BY `post`.`id` DESC";
  $result = Read_DB($pdo,$sql,$param);
endif;

$author = isset($_SESSION['id']) && $result ? (($result[0]['id'] == $_SESSION['id']) ? true : false) : false;

// Include meta vars
include_once 'array/meta.php';
// Home META_TITLE
isset($_GET["article"]) ?: $meta["META_TITLE"] = 'Sincerity';
?>

<!doctype html>
<html lang="nl">
<?php include 'template/' . $template . '/head.php'; ?>
<body>
  <input id="dark-mode" name="dark-mode" class="dark-mode-checkbox visually-hidden" type="checkbox">
  <!-- START WRAPPER -->
  <div class="wrapper theme-container">
    <?php
    include 'template/' . $template . '/header.php';
    ?>
    <!-- START CONTAINER -->
    <div class="container">
      <?php if (isset($_GET["article"])): ?>
        <?php include 'template/' . $template . '/article.php'; ?>
      <?php elseif(!empty($result)): ?>
        <?php include 'template/' . $template . '/main.php'; ?>
      <?php else: ?>
        <?php include 'template/' . $template . '/error.php'; ?>
      <?php endif; ?>
    </div>
    <!-- END CONTAINER -->
  </div>
  <!-- END WRAPPER -->
  <?php include 'template/' . $template . '/footer.php'; ?>
  <?php
  unset($stmt);
  unset($pdo);
  ?>
</body>
</html>
