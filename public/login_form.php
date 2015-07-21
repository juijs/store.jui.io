
<?php $page_id = "login_form" ?>
<?php include_once "header.php" ?>

<style type="text/css">

.btn-sns {
	width : 300px;
    max-width:90%;
	margin-bottom:10px;
}


.btn-facebook {
	
}
</style>

<div style="padding-top:20px"></div>
<div id="content-container">

	<div style="padding:20px;padding-top:50px;">
		<div><a href="javascript:void(login('facebook'))" class="btn btn-large btn-rect btn-sns btn-facebook"> | Connect with Facebook</a></div>
		<div><a href="javascript:void(login('twitter'))" class="btn btn-large btn-rect btn-sns btn-twitter"> | Connect with Twitter</a> </div>
		<div><a href="javascript:void(login('github'))" class="btn btn-large btn-rect btn-sns btn-github"> | Connect with Github</a> </div>
	</div>

</div>

<?php include_once "footer.php" ?>
