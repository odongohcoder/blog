<?php
defined('_BASE') ?: header('location: ../../index.php');
// Get comments data
require_once 'core/queries/db.comments.php';
?>

<?php if(!empty($comments)):?>
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
<?php endif;?>

<!-- START COMMENT FORM-->
<?php if (isset($_SESSION['email']) && _BASE):?>

<form action="core/posts/post.comment.php" method="POST" enctype="multipart/form-data">
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
