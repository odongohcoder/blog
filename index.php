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
// Include classes
require_once 'engine/classes/class.article.php';
// Include template
require_once 'engine/queries/db.template.php';
// Check if blog is private
require_once 'engine/queries/db.private.php';
if((!isset($_SESSION['email']) || empty($_SESSION['email'])) && $private[0]['bool'] == '1'){
  header('location: admin/');
  exit;
}

// Include menu items
include_once 'array/links.php';

// Include post result
require_once 'engine/queries/db.result.php';
// Get subjects data
require_once 'engine/queries/db.subjects.php';
// Get paragraph data
!isset($_GET["article"]) ?: require_once 'engine/queries/db.paragraph.php';

$author = isset($_SESSION['id']) && $result ? (($result[0]['id'] == $_SESSION['id']) ? true : false) : false;

// Include meta vars
include_once 'array/meta.php';
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
