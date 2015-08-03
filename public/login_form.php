
<?php $page_id = "login_form" ?>
<?php include_once "header.php" ?>

<style type="text/css">

.btn-sns {
	width : 300px;
	height: 45px;
    max-width:90%;
	margin-bottom:12px;
}

.btn-sns img {
	margin-top:-5px;
}

</style>

<div style="padding-top:30px"></div>
<div id="content-container">

	<div style="padding:20px;padding-top:70px;">
		<div><a href="javascript:void(login('facebook'))" class="btn large plat btn-sns btn-facebook"><img src="/images/sns/Facebook.svg" width="20" height="20" align="absmiddle"> Connect with Facebook</a></div>
		<div><a href="javascript:void(login('twitter'))" class="btn large rect btn-sns btn-twitter"> <img src="/images/sns/Twitter.svg"  width="20" height="20" align="absmiddle"> Connect with Twitter</a> </div>
		<div><a href="javascript:void(login('github'))" class="btn large rect btn-sns btn-github"> <img src="/images/sns/Github.svg"  width="20" height="20" align="absmiddle"> Connect with Github</a> </div>
	</div>

</div>

</body>
</html>