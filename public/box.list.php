<?php
$id = (string)$data['_id'];
$link = "view.php?id=".($id);

$type = $data['type'];
if (!$type) $type = 'component';

$first = $type_text[$type];


$avatar = $data['avatar'];

$isMy = true;

?>
<div class="summary-box box-list" data-id="<?php echo $id ?>"><div class="summary-normal">
		<div class="summary-info">
			<div style="float:right;margin-top:10px;">
<?php
					$share_text = urlencode($description)." #store #jui #js" ;
					$share_url = urlencode("http://".$_SERVER['HTTP_HOST']."/view.php?id=".$id);
					$embed_url = "http://".$_SERVER['HTTP_HOST']."/embed.php?id=".$id;
					$thumbnail_url = "http://".$_SERVER['HTTP_HOST']."/thumbnail.php?id=".$id;
					include "sns.button.php" 
			?>


			<?php if ($data['access'] == 'public') { ?>
			<span class="good" style="overflow:auto;display:inline-block;">
				<a href="javascript:void(good('<?php echo $id?>'))"><i class="icon-like" style="color: #999; font-size: 16px;"></i></a> 
				<span id="good_count_<?php echo $id?>"><?php echo $data['good'] ? $data['good'] : 0 ?></span>
			</span>
			<?php } ?>
			</div>
			<div class="title"><span class="simbol simbol-<?php echo $type ?>"><?php echo $first ?></span> <a href="<?php echo $link ?>"><?php echo $data['title'] ? $data['title'] : 'Untitled' ?></a></div>
			<div class="content">

			<?php if ($isMy) { ?><a href="#" onclick="deletecode('<?php echo $id ?>')" class='box-delete-btn'><i class='icon-trashcan'></i> Delete</a><?php } ?>
				<?php echo $data['description'] ? str_replace("\r\n", "", $data['description']) : '&nbsp;' ?>

			</div>
		</div>
	</div>
</div>
