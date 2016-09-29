<div class="editor-panel-full view-result" id="result">
	<div class="editor-tool">
		<span class="splitter-toggle" data-splitter="previewSplitter" title="Toggle Editor" style='margin-left:5px' ><?php echo get_svg_image('left') ?></i></span>
		<span class="title"> Preview </span>

		<a class="button button-link  export-btn feature-button" title="Export to PDF"><i class='icon-report2'></i><?php if (!$is_viewer) {?> Export to PDF<?php } ?></a>
	</div>
	<div id="tab_contents_1" class="tab-contents editor-info">
		 <iframe name="chart_frame" frameborder="0" border="0" width="100%" height="99%"></iframe>
		 <iframe name="export_frame" frameborder="0" border="0" width="100%" height="99%" style="display:none"></iframe>
	</div>
</div>
