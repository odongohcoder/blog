<?php
$sql = "
SELECT `comment`.`comment`, `comment`.`date`, `users`.`id`, `users`.`name`, `users`.`users_image` FROM `comment`
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

<div class="container">
  <div class="comment-blog">
    <div class="outer">
      <div class="inner"><p><strong>Comments</strong></p></div>
      <?php foreach($comments as $row):?>
        <div class="inner <?php ($row['id'] == $result[0]['id']) ? print 'author' : print 'reader'; ?>">
          <div class="users-image" style="background-image:url('img/users/<?php print $row['users_image']; ?>')"></div>
          <div class="comment">
            <div class="users-name"><strong><?php echo $row['name'];?></strong> on <?php echo date("d.m.y", strtotime($row['date']));?></div>
            <p><?php echo $row['comment'];?></p>
          </div>
        </div>
      <?php endforeach;?>
    </div>
  </div>
</div>

<!-- START COMMENT FORM-->
<?php if (isset($_SESSION['email']) && $base):?>

<form action="admin/comment-post.php" method="POST" enctype="multipart/form-data">
  <div class="outer">
    <div class="inner">
      <div id="Comments">
        <input name="postid" type="hidden" value="<?php echo $_GET['article']; ?>">
        <div class="form-group">
          <textarea name="comment" class="<?php echo (!empty($comment_err)) ? 'is-invalid' : ''; ?>" required></textarea>
          <?php if ($comment_err):?>
          <span class="invalid-feedback"><?php echo $comment_err; ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="col">
          <button type="submit" value="Add post">Add comment</button>
        </div>
      </div>
    </div>
  </div>
</form>

<?php endif; ?>
