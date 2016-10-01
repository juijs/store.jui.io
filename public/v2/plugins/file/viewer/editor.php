<div class="editor-panel-full  editor-panel-border type-editor-container  view-component" style="background:#ffffff">

		<div class="file-container">
			<div class="file-toolbar">
				<h1>FILES</h1>
				<a class="add-btn" title="add file or directory"  ><?php echo get_svg_image('plus') ?></a>
			</div>
			<div class="file-content">
			</div>
	
		</div>
		<div class="code-container">
			<div class="code-toolbar">
				<h1><span  class="splitter-toggle" data-splitter="fileSplitter" title="Toggle Files" ><i class='icon-chevron-left'> </i></span>  EDITOR</h1>
				<span  class="splitter-toggle right" data-splitter="previewSplitter" data-splitter-toggle="1"  title="Toggle Preview" ><i class='icon-chevron-right'> </i></span>  
			</div>

			<div class="code-content">
					<div class="editor image-editor" style="display:none">
					</div>
					<div class="editor text-editor">
							<textarea id="base_code"></textarea>
					</div>

			</div>
		</div>
</div>
