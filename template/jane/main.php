<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}
$sql = "SELECT `subject` FROM `subject`";
$subject = Read_DB($pdo,$sql,null);

$post = new Post();
?>

<!-- START CONTAINER -->
<div class="container">

	<?php if (isset($_GET["article"]) && isset($result[0]['title'])):?>

    <?php if ($author): ?>
      <form action="admin/article-post.php" method="POST" enctype="multipart/form-data">
    <?php endif; ?>

		<div class="article-blog">
			<article>
				<div class="outer">
					<div class="inner">
						<a href="index.php?subject=<?php echo $result[0][5];?>" class="subject"><?php echo $result[0]['subject'];?></a>
					</div>
					<div class="inner">
            <h1><?php echo $post->set_text($result[0]['title'],'title'); ?></h1>
            <h2><?php echo $post->set_text($result[0]['subtitle'],'subtitle'); ?></h2>
					</div>
					<div class="inner">
						<div class="author">
							<div class="users-image" style="background-image:url('img/users/<?php print $result[0]['users_image']; ?>')"></div>
							<div class="users-name"><strong><?php echo $result[0]['name'];?></strong> on <?php echo date("d.m.y", strtotime($result[0]['date']));?></div>
						</div>
					</div>
				</div>

				<?php
				$sql = "SELECT * FROM `paragraph` WHERE `paragraph`.`postid` = :article_id";
        $param = [':article_id'=>[$article_id,PDO::PARAM_INT]];
        $paragraph = Read_DB($pdo,$sql,$param);
				?>

				<?php foreach($paragraph as $key => $row):?>
					<?php if ($row['item'] == 'img'):?>
						<div class="header-image">
              <?php echo $post->set_image($key,$row['paragraph']); ?>
						</div>
					<?php elseif($row['item'] == 'txt'):?>
						<div class="outer">
							<div class="inner">
                <p><?php echo $post->set_text($row['paragraph'],'longcopy['.$key.']'); ?></p>
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
              <?php echo $post->set_text($row['caption'],'longcopy['.$key.']'); ?>
            </small>
          <?php endif; ?>
				<?php endforeach;?>
			</article>
		</div>

    <?php if ($author): ?>
    <div class="form-row">
      <div class="col">
        <button type="submit" value="Add post">Update post</button>
      </div>
    </div>
  </form>
  <?php endif; ?>

		<?php include 'admin/comment.php';?>

    <!-- START STARTPAGE -->
	<?php elseif(!empty($result)):?>
		<div id="grid">
	<?php foreach($result as $i => $row):?>
		<div class="item-blog">
			<div class="inner">
				<a href="<?php echo _BASE;?>index.php?article=<?php echo $row[0];?>">
					<div class="clmn leftcolumn">

						<?php
						$sql = "SELECT `paragraph`.`paragraph` FROM `paragraph` WHERE (`paragraph`.`postid` = :article_id AND `paragraph`.`item` = 'img')";
            $param = [':article_id'=>[$row[0],PDO::PARAM_INT]];
            $image = Read_DB($pdo,$sql,$param);
						?>

						<?php if($image):?>
							<?php list($width, $height) = getimagesize("img/article/small/".$image[0]["paragraph"]);
							if($width >= $height):
								$orientation = "landscape";
							else:
								$orientation = "portrait";
							endif;
							?>
							<img src="<?php echo _BASE;?>img/article/small/<?php echo $image[0]["paragraph"]; ?>">
						<?php endif; ?>
					</div>
			<div class="clmn rightcolumn <?php if(!$image): print 'no-image'; else: print $orientation; endif; ?>">
				<article>
					<div class="outer">
					<div class="inner">
						<div class="subject"><?php echo $row['subject'];?></div>
					</div>
					<div class="inner">
						<h1><?php echo $row['title'];?></h1>
						<h2><?php echo $row['subtitle'];?></h2>
					</div>
					<div class="inner">
						<div class="author">
							<div class="users-image" style="background-image:url('<?php echo _BASE;?>img/users/<?php print $row['users_image']; ?>')"></div>
							<div class="users-name"><strong><?php echo $row['name'];?></strong> on <?php echo date("d.m.y", strtotime($row['date']));?></div>
						</div>
					</div>
					</div>
				</article>
			</div>
			</a>
			</div>
		</div>
	<?php endforeach;?>
	</div>
  <!-- END STARTPAGE -->

<?php else:?>

<?php
include 'error.php';
?>

<?php endif;?>
</div>
<!-- END CONTAINER -->
