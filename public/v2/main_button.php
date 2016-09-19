<div class="main-button">
make your components 

<?php if ($_SESSION['login']) { ?>
	<a class="button button-large button-lowercase" onclick="show_component_modal()">share</a>
<?php } else { ?>
	<a class="button button-large button-lowercase" onclick="show_login_modal()">share</a>
<?php } ?>
 on JUI store.
</div>
