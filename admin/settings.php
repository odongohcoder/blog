<?php
include_once '../core/constants/directory.php';
include_once 'start.php';

if (!isset($_SESSION['admin']) && !defined('_BASE')){
  header('location: ../admin/');
}

$sql = "
SELECT * FROM `settings`
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$settings = $stmt->fetchAll();

$setting = [];
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
  <div class="inner">

    <h1><?php echo $admintitle; ?></h1>
    <form action="../core/posts/post.setting.php" method="POST" enctype="multipart/form-data">
      <table>
        <?php foreach($settings as $row):?>
          <tr>
            <td>
              <label>
                <input type="checkbox" name="setting[]" value="<?php echo $row['id']; ?>" <?php ($row['bool'] != 1) ?: print 'checked'; ?>>
              </label>
            </td>
            <td><?php echo $row['name']; ?></td>
          </tr>
        <?php endforeach;?>
      </table>

      <div class="form-row">
        <div class="col">
          <button type="submit" value="Update">Update</button>
        </div>
      </div>
    </form>
  </div>
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
