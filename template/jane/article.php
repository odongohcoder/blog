<?php
defined('_BASE') ?: header('location: ../../index.php');
?>

		<div class="article-blog">
			<article>

				<div class="outer">
					<div class="inner">
            <?php $article->subject(); ?>
					</div>
					<div class="inner">
            <h1><?php $article->title(); ?></h1>
            <h2><?php $article->subtitle(); ?></h2>
					</div>
					<div class="inner">
						<div class="author">
							<div class="users-image" style="background-image:url('img/users/<?php echo $article->authorImage(); ?>')"></div>
							<div class="users-name"><strong><?php echo $article->authorName(); ?></strong> on <?php echo date("d.m.y", strtotime($article->date()));?></div>
						</div>
					</div>
				</div>

				<?php foreach($paragraph as $key => $row):?>
					<?php if ($row['item'] == 'img'):?>
						<div class="header-image">
              <?php $article->setImage($row['paragraph'],$key); ?>
						</div>
					<?php elseif($row['item'] == 'txt'):?>
						<div class="outer">
							<div class="inner">
                <p><?php $article->setText($row['paragraph'],'longcopy['.$key.']'); ?></p>
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
              <?php $article->setText($row['caption'],'longcopy['.$key.']'); ?>
            </small>
          <?php endif; ?>
				<?php endforeach;?>
			</article>
		</div>
