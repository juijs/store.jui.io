<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

	<div class="editor-tool">
		<div class="property-category select"></div>		
		<span class="editor-navbar">
			<div class="theme-list"></div>
		</span>
		<span  class="splitter-toggle right" data-splitter="previewSplitter" data-splitter-toggle="1"  title="Toggle Preview" ><?php echo get_svg_image('right') ?></span>  
	</div>

	<div id="tab_contents_2" class="tab-contents editor-codemirror" style="display:none;">
		<textarea id="component_code"></textarea>
	</div>
	<div id="tab_contents_1" class="theme-property tab-contents editor-codemirror" style="overflow:auto">

	</div>

	<div class="tab-contents editor-codemirror" style="display:none">
		<textarea id="sample_code"></textarea>
	</div>

</div>

<form id="chart_form" action="<?php echo V2_PLUGIN_URL ?>/style/generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
	<input type="hidden" name="component_code" value="" />
	<input type="hidden" name="theme_name" value="" />
	<input type="hidden" name="sample_type" value="" />
	<input type="hidden" name="type" value="style" />
</form>
