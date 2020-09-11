<?php
defined('_BASE') ?: header('location: ../../index.php');
?>

			<header id="header">
					<?php if (isset($_GET["article"])):?>
						<a href="index.php" class="return">
							<?php echo file_get_contents(_BASE . '/img/icon/' . "feather/arrow-left.svg"); ?>
						</a>
					<?php endif;?>
					<div class="logo">
						<a id="logo" href="<?php echo _BASE;?>">
							Sincerity&reg;
						</a>
					</div>
					<label class="dark-mode-label switch" for="dark-mode">
						<?php echo file_get_contents(_BASE . '/img/icon/' . "feather/sun.svg"); ?><?php echo file_get_contents(_BASE . '/img/icon/' . "feather/moon.svg"); ?>
					</label>
					<nav class="menu" id="main">
						<?php include 'menu.php'; ?>
					</nav>
			</header>
