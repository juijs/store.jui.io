<div class="editor-panel  editor-panel-border  view-component" style="background:#ffffff">

	<div class="editor-tool" style="font-size:13px;">
		<a class='h2' data-view="component" style="display:inline-block"><?php echo $type_text['component'] ?></a>
		
		<span class="editor-navbar">
			<?php if ($isMy) { ?>
			<a class="btn"><i class="icon-upload"></i> Upload File</a> 
			<input type="file" accept=".js" id="component_load" style="right: 30px;" />
			<?php } ?>
		</span>
	</div>

	<div id="tab_contents_1" class="tab-contents editor-codemirror">
		<textarea id="component_code"></textarea>
	</div>
</div>
