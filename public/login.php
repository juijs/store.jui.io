<?php 

if ($_SESSION['login']) {
?>
	<a class='form-btn form-btn-dpurple form-btn-small' href="/mylist.php">My List</a>

	<?php if ($_SESSION['avatar']) { ?><img src='<?php echo $_SESSION['avatar'] ?>' width="30" height="30" align="absmiddle" /><?php } ?>
	<?php echo $_SESSION['username'] ?> 
	
	<a href="/logout.php">Logout</a>
<?php } else { ?>
<img src="images/icn_lock.png">
<a href="/login_form.php">Login</a>
<?php } ?>