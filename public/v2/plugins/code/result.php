<div class="editor-panel-full view-result" id="result">
	<div class="preview-toolbar">
		<h1><span class="splitter-toggle" data-splitter="previewSplitter" title="Toggle Editor" ><?php echo get_svg_image('left') ?></i></span> PREVIEW</h1>
		<div class="preview-buttons">
			<a class="button button-link  notebook-btn feature-button" title="Notebook Mode"><i class='icon-was'></i>Notebook</a>
			<a class="button button-link  fullscreen-btn feature-button" title="FullScreen Mode"><i class='icon-was'></i><?php if (!$is_viewer) {?> Full Screen<?php } ?></a>
		</div>
	</div>
	<div id="tab_contents_1" class="tab-contents editor-info">
		 <iframe id="preview-frame" name="chart_frame" frameborder="0" border="0" width="100%" height="100%" allowfullscreen="true"></iframe>
	</div>
</div>
