<div class="component-modal">
	<div class="block-background"></div>
	<a class="close"><?php echo get_svg_image('close') ?></a>
	<div class="content">
		<?php 
			$count = 0;
		foreach ($type_list as $type => $obj) { 
			//if ($count % 3 == 0) echo "<div class='item-row'>";	

			$link = $obj['link'];

			if (!$link) {
				$link = '/v2/editor.php?type='.$type;
			}
			?><a href="<?php echo $link ?>"  class="item type-svg-<?php echo $type ?>"><?php echo get_svg_image($obj['img']) ?><div class="name"><?php echo $obj['name'] ?></div><div class="line"></div></a><?php  
		
			//if ($count %3 == 2) echo "</div>"; 

			$count++;
		} ?>
	</div>
</div>

<div class="login-modal">
	<div class="block-background"></div>
	<a class="close"><?php echo get_svg_image('close') ?></a>


	<div class="content">

		<div class="log">
			<img src="/v2/images/storeJUI-logo.svg" width="70px" height="70px" align="absmiddle">
			<span class="gallery">store</span>
		</div>
		<div class="login-message">
			You can use a component with your sns account.
		</div>
		<div class="message">
			<div class='line'></div>
			<div class='line-message'>Connect with</div>
			<div class='line'></div>
		</div>
		<div>
			<div><a href="javascript:void(login('facebook'))" class="login-btn btn-facebook">Facebook</a></div>
			<div><a href="javascript:void(login('twitter'))" class="login-btn btn-twitter">Twitter</a> </div>
			<div><a href="javascript:void(login('github'))" class="login-btn btn-github">Github</a> </div>
		</div>
		<p>
			&copy; 2016 JUI. All Rights Reserved.
		</p>
	</div>


</div>

<?php
		$share_text = urlencode($description)." #store #jui #js" ;
		$share_url = urlencode($url_root."/v2/view.php?id=".$id);
		$embed_url = $url_root."/v2/viewer.php?id=".$id;
		$only_view_url = $url_root."/v2/embed.php?id=".$id;
		$thumbnail_url = $url_root."/v2/thumbnail.php?id=".$id;
?>

<div class="share-modal">
	<div class="block-background"></div>
	<a class="close"><?php echo get_svg_image('close') ?></a>
	<div class="content">

				<div class="share-container">
					<div class="share-buttons">
						  <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url ?>&t=<?php echo $share_text ?>" title="Share on Facebook" target="_blank"><?php echo get_svg_image('facebook') ?></a>
						  <a class="twitter" href="https://twitter.com/intent/tweet?url=<?php echo $share_url ?>&text=<?php echo $share_text ?>" target="_blank" title="Tweet"><?php echo get_svg_image('twitter') ?></a>
						  <a class="googleplus" href="https://plus.google.com/share?url=<?php echo $share_url ?>" target="_blank" title="Share on Google+"><?php echo get_svg_image('googleplus') ?></a>
						  <a  class="tumblr" href="http://www.tumblr.com/share?v=3&u=<?php echo $share_url ?>&t=<?php echo $share_text ?>&s=" target="_blank" title="Post to Tumblr"><?php echo get_svg_image('tumblr') ?></a>
						  <a  class="kakao" href="javascript:void(0)" onclick="shareToKakao('<?php echo $share_url ?>', '<?php echo $share_text ?>', '<?php echo $thumbnail_url?>')" title="Post to Kakao"><?php echo get_svg_image('kakao') ?></a>
					</div>
					<div class="share-link">
						<label>Share Link</label>
						<div style="margin-top:5px"><input type='text' value='<?php echo urldecode($share_url) ?>' readonly /> </div>
					</div>
					<div class="embed-link">
						<label>Preview Link</label>
						<div style='margin-top:5px;'><input type='text' value='<?php echo $only_view_url ?>' readonly/> </div>
					</div>
					<div class="embed-link">
						<label>Preview Frame</label>
						<div style='margin-top:5px;'><textarea readonly><iframe src="<?php echo $only_view_url ?>" style="border:1px solid #ececec;width:100%;height:400px;" allowfullscreen="true"></iframe></textarea> </div>
					</div>
					<div class="embed-link">
						<label>Code + Preview Link</label>
						<div style='margin-top:5px;'><input type='text' value='<?php echo $embed_url ?>' readonly/> </div>
					</div>
					<div class="embed-link">
						<label>Code + Preview Frame</label>
						<div style='margin-top:5px;'><textarea readonly><iframe src="<?php echo $embed_url ?>" style="border:1px solid #ececec;width:100%;height:400px;" allowfullscreen="true"></iframe></textarea> </div>
					</div>

				</div>


	</div>
</div>


<div class="loading-modal">
	<div class="block-background"></div>

	<div class="content">

		<div class="loading-message"></div>
		<div class="loading-image">
			<?php echo get_svg_image('loveit-2') ?>
		</div>
	</div>
</div>

