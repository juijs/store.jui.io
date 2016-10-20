<div class="tool-button">
	<span class="content-btn">
		<a href="?sort=update_time" class="button button-link <?php echo $_GET['sort'] == 'update_time' ? 'active' : '' ?>">DATE</a>
		<a href="?sort=good" class="button button-link <?php echo $_GET['sort'] == 'good' ? 'active' : '' ?>">SCORE</a>

		<?php if ($_SESSION['login']) {?>
		<a href="dashboard.php" class="button button-link dashboard-button <?php echo $page_id == 'dashboard' ? 'active' : '' ?>"><?php echo get_svg_image('me') ?></a>

		<?php } else { ?>
		<a href="#" 	onclick='show_login_modal()' class="button button-link dashboard-button <?php echo $page_id == 'dashboard' ? 'active' : '' ?>"><?php echo get_svg_image('me') ?></a>

		<?php } ?>
	</span>
</div>

