<?php
if (!defined('_BASE')){
  header('location: ../../index.php');
}
?>

<div class="inner">
	<?php if (isset($_GET["article"]) || isset($_GET["subject"])):?>
		<h1>Whoops!</h1>
		<p>This page does not exist.</p>
	<?php else:?>
		<?php if (isset($admintitle)):?>
			<h1><?php echo $admintitle; ?></h1>
		<?php endif;?>
		<p>No content yet…</p>
	<?php endif;?>
</div>
