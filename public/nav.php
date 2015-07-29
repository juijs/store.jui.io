<header class="nav-wrap">
	<?php if ($page_id != "login_form") { ?>
	<span class="gallery">Gallery</span>

		<?php  if ($page_id == 'mylist') { ?>
		<span class="nav-loadchart" style="text-align:center;">
			<a href="component.php" class='btn create-button'>
				<div class='button-image'><i class='icon-check'></i> <?php echo $type_text['component'] ?></div> 
			</a>
			<a href="theme.php" class='btn create-button'>
				<div class='button-image'><i class='icon-textcolor'></i> <?php echo $type_text['theme'] ?></div>
			</a>
			<a href="style.php" class='btn create-button'>
				<div class='button-image'><i class='icon-edit'></i> <?php echo $type_text['style'] ?></div>
			</a>
			<a href="map.php" class='btn create-button'>
				<div class='button-image'><i class='icon-image'></i> <?php echo $type_text['map'] ?></div>
			</a>
		</span>

		<?php } else if ($page_id != 'manager') { ?>
		<span class="nav-loadchart">Sharing a component.
			<a id="btn-chart-upload" class="form-btn form-btn-dpurple form-btn-small" href="<?php echo !$_SESSION['login'] ? "/login_form.php?url=".urlencode($_SERVER['PHP_SELF']) : "/mylist.php" ?>">Go!</a>
		</span>
		<?php } else { ?>

		<span class="nav-loadchart">Sharing a component.
			<a id="btn-chart-upload" class="form-btn form-btn-dpurple form-btn-small" href="<?php echo !$_SESSION['login'] ? "/login_form.php?url=".urlencode($_SERVER['PHP_SELF']) : "/mylist.php" ?>">Go!</a>
		</span>
		<?php } ?>

	<span class="logo-link">
			<a class="logo" href="/"><img style="margin-top:3px" src="images/logo-jui.png"></a>
	</span>
	<span class="login">
		<?php include_once __DIR__."/login.php" ?>
	</span>
	<?php } else {?>
	<span class="gallery">Login</span>

	<span class="nav-loadchart">
		You can use a component with your sns account.
	</span>

	<span class="logo-link">
			<a class="logo" href="/"><img src="images/logo-jui.png"></a>
	</span>
	<?php } ?>

</header>
