<?php 

if ($_SESSION['login']) {
?>
	<span class='login-menu-big'>

		<?php if ($_SESSION['avatar']) { ?><img src='<?php echo $_SESSION['avatar'] ?>' width="30" height="30" align="absmiddle" class="avatar" /><?php } ?>
		<a href="/v2/dashboard.php"><?php echo $_SESSION['username'] ?></a> <span class="splitter">&nbsp;</span> <a href="/v2/logout.php" class="logout">LOGOUT</a>
	</span>
<?php } else { ?>
	<a class="button button-common" onclick="show_login_modal()" style="font-size:12px;letter-spacing:1px;">SIGN IN</a>
<?php } ?>
