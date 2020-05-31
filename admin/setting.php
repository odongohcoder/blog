<?php
$sql = "
SELECT * FROM `settings`
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$settings = $stmt->fetchAll();

$setting = [];
?>

<!-- START COMMENT FORM-->
<?php if (isset($_SESSION['email']) && $base):?>
<div class="inner">
  <form action="setting-post.php" method="POST" enctype="multipart/form-data">
    <p><strong>Settings</strong></p>
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
    </div>
  </form>
</div>

<?php endif; ?>
