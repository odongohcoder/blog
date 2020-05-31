<?php
$sql = "
SELECT * FROM `settings`
INNER JOIN `users` ON `users`.`id` = `comment`.`userid`
WHERE `comment`.`postid` = :postid
ORDER BY `date` DESC
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':postid', $_GET["article"], PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll();

$comment_err = '';
?>

<!-- START COMMENT FORM-->
<?php if (isset($_SESSION['email']) && $base):?>

<form action="admin/settings-post.php" method="POST" enctype="multipart/form-data">
  <div class="outer">
    <div class="inner"><p><strong>Settings</strong></p></div>
      <div id="Settings">
        <?php foreach($comments as $row):?>

        <?php endforeach;?>
      </div>

      <div class="form-row">
        <div class="col">
          <button type="submit" value="Update">Update</button>
        </div>
      </div>
    </div>
  </div>
</form>

<?php endif; ?>
