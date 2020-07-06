<!-- START CONTAINER -->
<div class="container">

	<?php if (isset($_GET["article"]) && isset($result[0]['title'])):?>

		<div class="article-blog">
			<article>
				<div class="outer">
					<div class="inner">
						<a href="index.php?subject=<?php echo $result[0][5];?>" class="subject"><?php echo $result[0]['subject'];?></a>
					</div>
					<div class="inner">
						<h1><?php print $result[0]['title'];?></h1>
						<h2><?php print $result[0]['subtitle'];?></h2>
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
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
				$stmt->execute();
				$paragraph = $stmt->fetchAll();
				?>

				<?php foreach($paragraph as $row):?>
					<?php if ($row['item'] == 'img'):?>
						<div class="header-image">
							<img src="img/article/<?php print $row['paragraph']; ?>">
							<?php ($row['caption']) ? print '<small>' . $row['caption'] . '</small>': ''; ?>
						</div>
					<?php elseif($row['item'] == 'txt'):?>
						<div class="outer">
							<div class="inner">
								<p <?php (!$author) ?: print 'contenteditable';?>><?php print $row['paragraph']; ?></p>
								<?php ($row['caption']) ? print '<small>' . $row['caption'] . '</small>': ''; ?>
							</div>
						</div>
					<?php elseif($row['item'] == 'audio'):?>
						<div class="outer">
							<div class="inner">
								<audio controls>
									<source src="<?php print $row['paragraph']; ?>" type="audio/mpeg">
									Your browser does not support the audio tag.
								</audio>
								<small>
									<?php ($row['caption']) ? print $row['caption'] : print $row['paragraph']; ?>
								</small>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach;?>
			</article>
		</div>

		<?php include 'admin/comment.php';?>

	<?php elseif($total > 0):?>
		<div id="grid">
	<?php foreach($result as $i => $row):?>
		<div class="item-blog">
			<div class="inner">
				<a href="<?php echo _BASE;?>index.php?article=<?php echo $row[0];?>">
					<div class="clmn leftcolumn">

						<?php
						$sql = "SELECT `paragraph`.`paragraph` FROM `paragraph` WHERE (`paragraph`.`postid` = :article_id AND `paragraph`.`item` = 'img')";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':article_id', $row[0], PDO::PARAM_INT);
						$stmt->execute();
						$image = $stmt->fetchAll();
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

<?php else:?>

<?php
include 'error.php';
?>

<?php endif;?>
</div>
<!-- END CONTAINER -->
