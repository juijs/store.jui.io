<div style="text-align:center;margin-top:50px;margin-bottom:120px;">
<?php if ($prevPage > 0) { ?>
	<a href="?page=<?php echo $prevPage ?>&sort=<?php echo $_GET['sort'] ?>&mode=<?php echo $_GET['mode'] ?>" class="button button-link form-btn-off prev-button"><i class="icon icon-chevron-left"></i> Prev Page </a>
<?php } ?>
<?php if ($nextPage > 0) { ?>
	<a href="?page=<?php echo $nextPage ?>&sort=<?php echo $_GET['sort'] ?>&mode=<?php echo $_GET['mode'] ?>" class="button button-link form-btn-on next-button">Next Page <i class="icon icon-chevron-right"></i></a>
<?php } ?>
</div>
