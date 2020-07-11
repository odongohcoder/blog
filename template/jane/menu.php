<?php
defined('_BASE') or die('Something went wrong');
?>

<ul>
	<?php foreach($pages as $key=>$val):?>
		<?php if(isset($_SESSION['email']) || $key == "admin"): ?>
		<li <?php if( $val['link'] == _DIR ):?>class="active"<?php endif;?>>
			<a href="<?php print _BASE . $val['link']; ?>">
				<?php if(!empty($_SESSION['users_image']) && $key == "admin"): ?>
					<div class="users-image" style="background-image:url(<?php print _BASE . 'img/users/' . $_SESSION['users_image']; ?>)"></div>
				<?php else: ?>
					<?php echo file_get_contents(_BASE . 'img/icon/' . $val['icon']); ?>
				<?php endif; ?>
			</a>
		</li>
		<?php endif; ?>
	<?php endforeach;?>
</ul>
