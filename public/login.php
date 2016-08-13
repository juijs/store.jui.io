<?php 

if ($_SESSION['login']) {
?>
	<span class='login-menu-big'>

		<?php if ($_SESSION['avatar']) { ?><img src='<?php echo $_SESSION['avatar'] ?>' width="30" height="30" align="absmiddle" /><?php } ?>
		<a href="/mylist.php"><?php echo $_SESSION['username'] ?></a>
		<span class="separator">|</span>
		<a href="/logout.php">Logout</a>		
	</span>
	<span class='login-menu-small'>
		<span onclick="dd_1.show()" style="cursor:pointer;font-size:22px;color:#abadb1;"><i class='icon-menu'></i></span>
		<div id="login-menu-small" class="dropdown large" style="top:50px;right:20px;">
			<ul style="width: 150px;">
				<li >

					<?php if ($_SESSION['avatar']) { ?><img src='<?php echo $_SESSION['avatar'] ?>' width="30" height="30" align="absmiddle" /><?php } ?>
					<?php echo $_SESSION['username'] ?> 
				</li>
				<li class="divider"></li>
				<li value="mylist"><i class='icon-dashboardlist'></i> My List</li>
				<li class="divider"></li>
				<li value="logout"><i class='icon-info'></i> Logout</li>
			</ul>
		</div>

		<script type="text/javascript">
			jui.ready([ "ui.dropdown" ], function(dropdown) {
				dd_1 = dropdown("#login-menu-small", {
					event: {
						change: function(data) {
							if (data.value == 'mylist') {
								location.href = "/mylist.php";
							} else if (data.value == 'logout') {
								location.href = "/logout.php";
							}
						}
					}
				});
			});
		</script>
	</span>


<?php } else { ?>
	<img src="images/icn_lock.png">
	<a href="/login_form.php">Login</a>
<?php } ?>
