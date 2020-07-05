<?php
include_once 'start.php';

if (!isset($_SESSION['admin']) && _BASE){
  header('location: ../admin/');
}

$sql = "
SELECT * FROM `settings`
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$settings = $stmt->fetchAll();

$setting = [];

// Include head
include '../template/' . $template . '/header.php';
?>

<div class="container">
  <div class="inner">

    <h1><?php echo $admintitle; ?></h1>
    <form action="setting-post.php" method="POST" enctype="multipart/form-data">
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

<?php
include '../template/' . $template . '/footer.php';
?>
