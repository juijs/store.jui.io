<div class="editor-panel view-result" id="result">

	<div class="editor-tool" style="font-size:13px;">
		<a class='h2' style="display:inline-block"  data-view="result">Result</a>
		<div style="float:right">
			<?php if ($_GET['id']) { ?>
			<a class='btn' href="/view.php?id=<?php echo $_GET['id'] ?>">View!</a>
			<?php } ?>
		</div>
	</div>

	<div id="tab_contents_1" class="tab-contents editor-info">
		 <iframe name="chart_frame" frameborder="0" border="0" width="100%" height="99%"></iframe>
	</div>
</div>
