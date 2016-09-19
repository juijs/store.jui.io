<header>
	<div  class="nav-wrap">
		<span class="logo-link">
				<a class="logo" href="/v2/"><img src="images/storeJUI-logo.svg" width="52px" height="52px" align="absmiddle"></a>
		</span>

		<span class="gallery">store</span>

		<span class="share-button <?php echo ($page_id = 'view') ? 'on' : '' ?>">
			<a class="button button-regular" onclick="show_component_modal()"><?php //echo $type_image ?> <span style="float:left;"><?php echo $type_name ?></span> <span class="arrow" style="float:right"><?php echo get_svg_image('arrow1') ?></span></a>
		</span>

		<?php if ($page_id != "login_form") { ?>
		<span class="login">
			<?php include_once __DIR__."/login.php" ?>
		</span>
		<?php } ?>
	</div>
</header>
