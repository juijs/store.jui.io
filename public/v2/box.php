<?php
$id = (string)$data['_id'];
$link = "view.php?id=".($id);

$type = $data['type'];
if (!$type) $type = 'component';

$first = $type_text[$type];


$avatar = $data['avatar'];
$isMy = true;
//var_dump($data, $_SESSION);
if (($data['login_type'] != $_SESSION['login_type'] || $data['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}

$share_text = urlencode($description)." #store #jui #js" ;
$share_url = urlencode(V2_URL."/view.php?id=".$id);
$embed_url = V2_URL."/embed.php?id=".$id;
$thumbnail_url = V2_URL."/thumbnail.php?id=".$id;
?>
<a class="summary-box " data-id="<?php echo $id ?>" href="/v2/view.php?id=<?php echo $id ?>" >
	<div class="summary-image" style="background-image:url(<?php echo $thumbnail_url ?>)"> 	</div>

	<div class="summary-description"><?php echo $data['description'] ? str_replace("\r\n", "", $data['description']) : '&nbsp;' ?>	</div>

	<div class="summary-normal">
		<div class="summary-info">
			<div class="content">
				<div class="info">
					<span class="type-name"><?php echo strtoupper($first) ?></span> 
					<span class="view-link">				
						<?php if ($data['access'] == 'public') { ?>
						<span class="good_btn" href="javascript:void(good('<?php echo $id?>'))"><?php //echo get_svg_image('loveit') ?></span><span id="good_count_<?php echo $id?>" class="good-icon"><span class="good" ><?php echo get_svg_image('loveit-2') ?></span> <?php echo $data['good'] ? $data['good'] : 0 ?></span>
						<?php } ?>
						
					</span>
				</div>
				<h3 class="title"> <?php echo strip_tags( $data['title'] ? $data['title'] : 'Untitled' ) ?> </h3>
			</div>
		</div>
	</div>
	<div class="summary-link ">
		<span class="user-link"><img src="<?php echo $avatar ?>" width="16" height="16" class='avatar' align='absmiddle'/>&nbsp;<?php echo $data['username'] ?></span>
	</div>
</a>
<?php if ($isMy) { ?> <div class='delete-btn' onclick='deletecode("<?php echo $id ?>")' style='vertical-align:middle;cursor:pointer;'><i class='icon-exit'></i></div><?php } ?>
