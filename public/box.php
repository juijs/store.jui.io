<?php
$id = (string)$data['_id'];
$link = "view.php?id=".($id);

$type = $data['type'];
if (!$type) $type = 'component';

$first = $type_text[$type];

?>
<div class="summary-box" data-id="<?php echo $id ?>"><div class="summary-normal">
		<div class="name">
			<span><img src="<?php echo $data['avatar'] ?>" width="30" height="30" class='avatar' align='absmiddle'/>&nbsp;<?php echo $data['username'] ?></span>

			<?php
					$share_text = urlencode($description)." #store #jui #js" ;
					$share_url = urlencode("http://".$_SERVER['HTTP_HOST']."/view.php?id=".$id);
					$embed_url = "http://".$_SERVER['HTTP_HOST']."/embed.php?id=".$id;
					$thumbnail_url = "http://".$_SERVER['HTTP_HOST']."/thumbnail.php?id=".$id;
					include "sns.button.php" 
			?>


			<?php if ($data['access'] == 'public') { ?>
			<span class="good" style="float:right;overflow:auto;display:inline-block;">
				<a href="javascript:void(good('<?php echo $id?>'))"><i class="icon-like" style="color: #999; font-size: 16px;"></i></a> 
				<span id="good_count_<?php echo $id?>"><?php echo $data['good'] ? $data['good'] : 0 ?></span>
			</span>
			<?php } ?>
		</div>

		<?php if (/*!$data['sample'] ||*/ true) { // only iframe view ?>
		<div class="imagesfield" >
			<?php
				$embed_url = "embed.php?id=".$id."&only=result";

										// generate static file 
				$root = getcwd();
				$static_file = "/static/".$id."/embed.html";

				if (file_exists($root.$static_file)) {
					$embed_url = $static_file;
				}
			?>		
			<iframe src="<?php echo $embed_url ?>" class='iframe-wrap' id="result"></iframe>
	
			<a href="<?php echo $link ?>" class='box-a'>&nbsp;</a>
</div>

		<?php } else { ?>
		<div class="imagesfield">
			<a href="<?php echo $link ?>"><img class="chart-image" src="<?php echo $data['sample'] ? $data['sample'] : 'images/chart-sample.jpg' ?>"></a>
		</div>
		<?php } ?>
		<div class="summary-info">
			<div class="title"><span class="simbol simbol-<?php echo $type ?>"><?php echo $first ?></span> <?php echo $data['title'] ? $data['title'] : '&nbsp;' ?></div>
			<div class="content"><?php echo $data['description'] ? str_replace("\r\n", "", $data['description']) : '&nbsp;' ?></div>
		</div>
	</div>
</div>
