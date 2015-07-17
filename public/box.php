<?php
$id = (string)$data['_id'];
$link = "view.php?id=".($id);
?>
<div class="summary-box"><div class="summary-normal">
		<div class="name">
			<span><img src="<?php echo $data['avatar'] ?>" width="30" height="30" class='avatar' align='absmiddle'/>&nbsp;<?php echo $data['username'] ?></span>
			<span class="good" style="float:right;overflow:auto;display:inline-block;">
				<a href="javascript:void(good('<?php echo $id?>'))"><img src="images/good.png" /></a> 
				<span id="good_count_<?php echo $id?>"><?php echo $data['good'] ? $data['good'] : 0 ?></span>
			</span>
		</div>
		<div class="imagesfield">
			<a href="<?php echo $link ?>"><img class="chart-image" src="<?php echo $data['sample'] ? $data['sample'] : 'images/chart-sample.jpg' ?>"></a>
		</div>
		<div class="summary-info">
			<div class="title"><?php echo $data['title'] ? $data['title'] : '&nbsp;' ?></div>
			<div class="content"><?php echo $data['description'] ? str_replace("\r\n", "", $data['description']) : '&nbsp;' ?></div>
		</div>
	</div>
</div>
