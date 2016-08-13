<div style="text-align:center;padding-top:20px;padding-bottom:20px;">
<?php if ($prevPage > 0) { ?>
	<a href="?page=<?php echo $prevPage ?>&sort=<?php echo $_GET['sort'] ?>&mode=<?php echo $_GET['mode'] ?>" class="btn-simple form-btn-off"><i class="icon icon-chevron-left"></i> Prev Page </a>
<?php } ?>
<?php if ($nextPage > 0) { ?>
	<a href="?page=<?php echo $nextPage ?>&sort=<?php echo $_GET['sort'] ?>&mode=<?php echo $_GET['mode'] ?>" class="btn-simple form-btn-on">Next Page <i class="icon icon-chevron-right"></i></a>
<?php } ?>
</div>
