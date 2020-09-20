<?php
defined('_BASE') ?: header('location: ../../index.php');
$article = new Article();
print_r($result[0]);
$articleTest = new ArticleTest($result[0]);
?>

		<div class="article-blog">
			<article>

				<div class="outer">
					<div class="inner">
            <?php $article->set_select($subjects,$result[0][5]); ?>
					</div>
					<div class="inner">
            <h1><?php $article->set_text($result[0]['title'],'title'); ?></h1>
            <h2><?php $article->set_text($result[0]['subtitle'],'subtitle'); ?></h2>
					</div>
					<div class="inner">
						<div class="author">
							<div class="users-image" style="background-image:url('img/users/<?php echo $articleTest->author_image; ?>')"></div>
							<div class="users-name"><strong><?php echo $articleTest->author_name; ?></strong> on <?php echo date("d.m.y", strtotime($article->user($result[0]['date'])));?></div>
						</div>
					</div>
				</div>

				<?php foreach($paragraph as $key => $row):?>
					<?php if ($row['item'] == 'img'):?>
						<div class="header-image">
              <?php $article->set_image($key,$row['paragraph']); ?>
						</div>
					<?php elseif($row['item'] == 'txt'):?>
						<div class="outer">
							<div class="inner">
                <p><?php $article->set_text($row['paragraph'],'longcopy['.$key.']'); ?></p>
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
              <?php $article->set_text($row['caption'],'longcopy['.$key.']'); ?>
            </small>
          <?php endif; ?>
				<?php endforeach;?>
			</article>
		</div>
