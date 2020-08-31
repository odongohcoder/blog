<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}
$sql = "SELECT * FROM `subject`";
$subjects = Read_DB($pdo,$sql,null);

$sql = "SELECT * FROM `paragraph` WHERE `paragraph`.`postid` = :article_id";
$param = [':article_id'=>[$article_id,PDO::PARAM_INT]];
$paragraph = Read_DB($pdo,$sql,$param);

$post = new Post();
?>

    <?php if ($author): ?>
      <form action="engine/posts/post.article.php" method="POST" enctype="multipart/form-data">
    <?php endif; ?>

		<div class="article-blog">
			<article>

				<div class="outer">
					<div class="inner">
            <?php $post->set_select($subjects,$result[0][5]); ?>
					</div>
					<div class="inner">
            <h1><?php $post->set_text($result[0]['title'],'title'); ?></h1>
            <h2><?php $post->set_text($result[0]['subtitle'],'subtitle'); ?></h2>
					</div>
					<div class="inner">
						<div class="author">
							<div class="users-image" style="background-image:url('img/users/<?php print $result[0]['users_image']; ?>')"></div>
							<div class="users-name"><strong><?php echo $result[0]['name'];?></strong> on <?php echo date("d.m.y", strtotime($result[0]['date']));?></div>
						</div>
					</div>
				</div>

				<?php foreach($paragraph as $key => $row):?>
					<?php if ($row['item'] == 'img'):?>
						<div class="header-image">
              <?php $post->set_image($key,$row['paragraph']); ?>
						</div>
					<?php elseif($row['item'] == 'txt'):?>
						<div class="outer">
							<div class="inner">
                <p><?php $post->set_text($row['paragraph'],'longcopy['.$key.']'); ?></p>
							</div>
						</div>
					<?php elseif($row['item'] == 'audio'):?>
						<div class="outer">
							<div class="inner">
								<audio controls>
									<source src="<?php print $row['paragraph']; ?>" type="audio/mpeg">
									Your browser does not support the audio tag.
								</audio>
							</div>
						</div>
					<?php endif; ?>
          <?php if ($row['caption']): ?>
            <small>
              <?php $post->set_text($row['caption'],'longcopy['.$key.']'); ?>
            </small>
          <?php endif; ?>
				<?php endforeach;?>
			</article>
		</div>

    <?php if ($author): ?>
      <button type="submit" value="Add post">Update post</button>
    </form>
  <?php endif; ?>

		<?php include 'template/' . $template . '/comment.php';?>
