<?php $page_id = "login_form" ?>
<?php include_once "header.php" ?>

<style type="text/css">
.btn-sns {
	width: 260px;
	margin-bottom: 12px;
	font-size: 16px;
	padding-left: 50px !important;
}

.btn-sns > i {
	position: absolute;
	font-size: 24px;
	left: 18px;
	top: 7px;
}
</style>

<div style="padding-top:30px"></div>

<div id="content-container">
	<div style="padding-top: 70px;margin:0 auto;">
		<div><a href="javascript:void(login('facebook'))" class="btn large plat btn-sns btn-facebook"><i class="icon-facebook"></i> Connect with Facebook</a></div>
		<div><a href="javascript:void(login('twitter'))" class="btn large rect btn-sns btn-twitter"><i class="icon-twitter"></i> Connect with Twitter</a> </div>
		<div><a href="javascript:void(login('github'))" class="btn large rect btn-sns btn-github"><i class="icon-github"></i> Connect with Github</a> </div>
	</div>
</div>

</body>
</html>