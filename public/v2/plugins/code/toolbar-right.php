<?php if ($is_viewer) { ?>
<div>
	<a class="logo" href="/v2/editor.php?id=<?php echo $id ?>">Edit on <img src="images/storeJUI-logo.svg" width="30px" height="30px" align="absmiddle" style="margin-top:-10px"> store</a>
</div>
<?php } else { ?>
<?php if ($isMy) { ?>
<a class="button button-link" id="commit-btn"><i class='icon-edit' ></i> Commit</a>
<?php } ?>

<?php } ?>
