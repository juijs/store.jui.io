<?php if ($is_viewer) { ?>
<span><?php echo $type_list[$type]['name'] ?></span>
<?php } else {  ?>
	<?php if ($isMy) { ?>
		<a class="button button-link" id="template"><i class="icon-template"></i> Template</a>
	<?php } ?>
<?php } ?>
