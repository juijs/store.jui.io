<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

	<div class="editor-tool">
		<div class="property-category select"></div>		
		<span class="editor-navbar">
			<?php if ($isMy) { ?>
			<div class="group" style="display:none">
				<a class="button"><i class="icon-upload"></i> Upload File</a> 
				<input type="file" accept=".js" id="component_load" />
			</div>
			<?php } ?>
			<div class="theme-list"></div>
		</span>
		<span  class="splitter-toggle right" data-splitter="previewSplitter" data-splitter-toggle="1"  title="Toggle Preview" ><?php echo get_svg_image('right') ?></i></span>  
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

<form id="chart_form" action="<?php echo V2_PLUGIN_URL ?>/theme/generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
	<input type="hidden" name="component_code" value="" />
	<input type="hidden" name="sample_code" value="" />
	<input type="hidden" name="name" value="" />
	<input type="hidden" name="type" value="theme" />
</form>

<form id="theme_form" action="<?php echo V2_PLUGIN_URL ?>/theme/theme.check.php" method="post" target="theme_frame" enctype="multipart/form-data" style="display:none">
	<input type="hidden" name="theme_name" value="" />
	<input type="hidden" name="component_code" value="" />
</form>
