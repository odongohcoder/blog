<?php
// Init session
session_start();
// Include paths
require_once 'core/constants/directory.php';
// Include db config
require_once 'creds/db.php';
// Include functions
require_once 'core/functions/function.read_db.php';
require_once 'core/functions/function.write_db.php';
require_once 'core/functions/function.specify_file.php';
require_once 'core/functions/function.upload_image.php';
// Include classes
require_once 'core/classes/class.article.php';
// Include template
require_once 'core/queries/db.template.php';
// Check if blog is private
require_once 'core/queries/db.private.php';
if((!isset($_SESSION['email']) || empty($_SESSION['email'])) && $private[0]['bool'] == '1'){
  header('location: admin/');
  exit;
}

// Include menu items
include_once 'array/links.php';

// Include post result
require_once 'core/queries/db.result.php';
// Get subjects data
require_once 'core/queries/db.subjects.php';
// Get paragraph data
!isset($_GET["article"]) ?: require_once 'core/queries/db.paragraph.php';

$author = isset($_SESSION['id']) && $result ? (($result[0]['id'] == $_SESSION['id']) || (isset($_GET["article"]) && $_GET["article"] == 'new') ? true : false) : false;

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
        <?php if ($author): ?>
          <form action="core/posts/post.article_update.php?article=<?php echo $_GET["article"]; ?>" method="POST" enctype="multipart/form-data">
        <?php endif; ?>
        <?php include 'template/' . $template . '/article.php'; ?>
        <?php if ($author): ?>
            <button type="submit" value="Add post">Update post</button>
          </form>
        <?php endif; ?>
        <?php include 'template/' . $template . '/comment.php';?>
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
