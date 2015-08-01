<div style="float:right;padding-left:10px;">
	<div style="position:relative;">
		<a class="share-button" onclick="toggleSns(this)" style="cursor:pointer" title="share"><i class="icon-report-link">&nbsp;</i></a>
		<div class="share-container" style="z-index:99;line-height:10px;width:142px;padding:10px;position:absolute;display:none;margin-top:-10px;background:white;right:0px;border:1px solid #ddd;">
		<div class="share-buttons">
			  <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url ?>&t=<?php echo $share_text ?>" title="Share on Facebook" target="_blank"><img src="images/sns/Facebook.svg"></a>
			  <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url ?>&text=<?php echo $share_text ?>" target="_blank" title="Tweet"><img src="images/sns/Twitter.svg"></a>
			  <a href="https://plus.google.com/share?url=<?php echo $share_url ?>" target="_blank" title="Share on Google+"><img src="images/sns/Google+.svg"></a>
			  <a href="http://www.tumblr.com/share?v=3&u=<?php echo $share_url ?>&t=<?php echo $share_text ?>&s=" target="_blank" title="Post to Tumblr"><img src="images/sns/Tumblr.svg"></a>
		</div>
        <div class="share-link" style='margin-top:15px'>
            <label>Share Link<label>
            <div style="margin-top:5px"><input type='text' class='input'  style='width:142px' value='<?php echo urldecode($share_url) ?>' /> </div>
        </div>
        <div class="embed-link" style='margin-top:12px'>
            <label style="margin:5px">Embed Link<label>
            <div style='margin-top:5px;'><input type='text' class='input' style='width:142px' value='<iframe src="<?php echo $embed_url ?>" width="100%" height="400px"></iframe>'/> </div>
        </div>
        </div>
	</div>
</div>
