<?php
include_once '../core/constants/directory.php';
include_once 'start.php';

  // Delete Files
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $deletedImages = $_POST['images'];

    if(empty($deletedImages)){
      $deletedImages_err = 'Please choose image';
    }

    if(empty($deletedImages_err)){
      foreach ($deletedImages as $value){
        $stmt = $pdo->prepare("SELECT `paragraph`.`paragraph` FROM `paragraph` WHERE `paragraph`.`id` = :id");
        $stmt->bindParam(':id', $value, PDO::PARAM_STR);
        $stmt->execute();
        $image = $stmt->fetchColumn();
        foreach ($maxDim as $key => $val){
          if ($key == 'large') {
            unlink('..' . _UPLOADDIRECTORY . $image);
          } else {
            unlink('..' . _UPLOADDIRECTORY . $key . '/' . $image);
          }
        }
        $stmt = $pdo->prepare('DELETE FROM `paragraph` WHERE `paragraph`.`id` = :id');
        $stmt->bindParam(':id', $value, PDO::PARAM_STR);
        $stmt->execute();
      }

      if($value === end($deletedImages)){
        $admintitle = "Deleted successful";
      } else {
        die('Something went wrong');
      }
    }
  }

  $sql = "SELECT `paragraph`.`paragraph`, `paragraph`.`id`, `paragraph`.`postid` FROM `paragraph` WHERE `paragraph`.`userid` = :userid AND `paragraph`.`item` = 'img'";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':userid', $_SESSION['id'], PDO::PARAM_STR);
  $stmt->execute();
  $images = $stmt->fetchAll();
?>

<!doctype html>
<html lang="nl">
<?php include '../template/' . $template . '/head.php'; ?>
<body>
  <input id="dark-mode" name="dark-mode" class="dark-mode-checkbox visually-hidden" type="checkbox">
  <!-- Start wrapper -->
  <div class="wrapper theme-container">
    <?php
    include '../template/' . $template . '/header.php';
    ?>

    <div class="container">
      <?php if ($images):?>
        <div class="inner">
          <h1><?php echo $admintitle; ?></h1>

          <?php if ($deletedImages_err):?>
            <span class="invalid-feedback"><?php echo $deletedImages_err; ?></span>
          <?php endif; ?>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
          <table style="width:100%">

            <?php foreach($images as $image):?>
              <tr>
                <td><input type="checkbox" name="images[]" value="<?php echo $image['id']; ?>"></td>
              <td>
                <img src="<?php print '..' . _UPLOADDIRECTORY . 'thumb/' . $image['paragraph']; ?>">
              </td>
              <td><?php print $image['paragraph'] ?></td>
              <td>
                <?php if($image['postid']): ?>
                  <a href="../index.php?article=<?php print $image['postid'] ?>">Article</a>
                <?php endif; ?>
              </td>
              </tr>
            <?php endforeach; ?>

          </table>
          <input type="submit" value="Delete">
          </form>

        </div>
      <?php else: ?>
        <?php
        include '../template/' . $template . '/error.php';
        ?>
      <?php endif; ?>
    </div>

  </div>
  <!-- End wrapper -->

    <?php
    include '../template/' . $template . '/footer.php';
    ?>

    <?php
    unset($stmt);
    unset($pdo);
    ?>
  </body>
  </html>
