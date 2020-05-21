<ul>
	<?php foreach($pages as $key=>$val):?>
		<?php if(isset($_SESSION['email']) || $key == "admin"): ?>
		<li <?php if( $val['link'] == $dir ):?>class="active"<?php endif;?>>
			<a href="<?php echo $base . '/'; print $val['link']; ?>">
				<?php if(!empty($_SESSION['users_image']) && $key == "admin"): ?>
					<div class="users-image" style="background-image:url(<?php print $base . '/img/users/' . $_SESSION['users_image']; ?>)"></div>
				<?php else: ?>
					<?php echo file_get_contents($base . '/img/icon/' . $val['icon']); ?>
				<?php endif; ?>
			</a>
		</li>
		<?php endif; ?>
	<?php endforeach;?>
</ul>
