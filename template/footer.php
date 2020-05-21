</div>

		<footer>
			<?php if(isset($_SESSION['email'])): ?>
			<nav class="menu" id="admin">
				<?php
					$pages = $adminmenu;
					include 'menu.php';
				?>
			</nav>
		<?php endif; ?>
		</footer>

		<script>
	  //if('serviceWorker' in navigator) {
	    //navigator.serviceWorker
	    //.register('service-worker.js')
	    //.then(function() { console.log("Service Worker Registered"); });
	  //}
	  </script>
		<script src="https://cdn.jsdelivr.net/npm/macy@2"></script>
		<script>
		var macy = Macy({
			container: '#grid',
			trueOrder: false,
			waitForImages: false,
			columns: 3,
			breakAt: {
				768: 2,
        480: 1
			}
		});
	  </script>
		<script>
		document.addEventListener('DOMContentLoaded', function () {
			const checkbox = document.querySelector('.dark-mode-checkbox');
			checkbox.checked = localStorage.getItem('darkMode') === 'true';
			checkbox.addEventListener('change', function (event) {
				localStorage.setItem('darkMode', event.currentTarget.checked);
			});
		});
		</script>
		
	  <?php
	  unset($stmt);
	  unset($pdo);
	  ?>
	  </body>
	</html>
