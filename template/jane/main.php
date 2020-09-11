<?php
defined('_BASE') ?: header('location: ../../index.php');
?>

<!-- START MAINPAGE -->
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
<!-- END MAINPAGE -->
