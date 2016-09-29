<a class="button button-regular broadcast-btn feature-button"><i class='icon-was'></i> Broadcast</a>
<a class="button button-regular fullscreen-btn feature-button"><i class='icon-was'></i> Full Screen</a>
<a class="button button-regular button-common export-btn feature-button"><i class='icon-download'></i> Export to PDF</a>
<iframe style="display:none" name="export_frame"></iframe>
<script type="text/javascript">
$(function () {
	$(".export-btn").on('click', function () {

		$("iframe[name=export_frame]").attr('src', '<?php echo $embed_url ?>&print-pdf');
		
		setTimeout(function () {
			$("iframe[name=export_frame]")[0].contentWindow.print();
		}, 500);
	});	
});
</script>
