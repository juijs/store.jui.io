<header>
	<div  class="nav-wrap">
		<span class="logo-link">
				<a class="logo" href="/v2/"><img src="images/storeJUI-logo.svg" width="52px" height="52px" align="absmiddle"></a>
		</span>

		<span class="gallery">store</span>

		<?php if ($share_view_enable ) {  ?>
		<span class="share-button <?php echo ($page_id = 'view') ? 'on' : '' ?>">
			<?php if ($_SESSION['login']) { ?>
				<a class="button button-regular" onclick="show_component_modal()">Share</a>
			<?php } else { ?>
				<a class="button button-regular" onclick="show_login_modal()">Share</a>
			<?php } ?>
		</span>
		<?php } ?>
		<span class="share-button scrolled-share-button">
			<?php if ($_SESSION['login']) { ?>
				<a class="button button-regular" onclick="show_component_modal()">Share</a>
			<?php } else { ?>
				<a class="button button-regular" onclick="show_login_modal()">Share</a>
			<?php } ?>
		</span>

		<span class="login">
			<?php include_once __DIR__."/login.php" ?>
		</span>
	</div>
</header>
