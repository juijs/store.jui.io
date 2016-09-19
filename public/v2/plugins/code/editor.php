<div class="editor-panel-full  editor-panel-border type-editor-container  view-component">

		<div class="file-container">
			<div class="file-toolbar">
				<h1>FILES</h1>
				<?php if ($isMy && !$is_viewer) { ?>
				<a class="add-btn" title="add file or directory"  ><?php echo get_svg_image('plus') ?></a>
				<?php } ?>
			</div>
			<div class="file-content">
			</div>
	
		</div>
		<div class="code-container">
			<div class="code-toolbar">
				<h1><span  class="splitter-toggle" data-splitter="fileSplitter" title="Toggle Files" ><?php echo get_svg_image('left') ?></span>  EDITOR <span class="file-name" style="font-size:12px;color:#a9a9a9;"></span></h1>
				<?php if ($isMy && !$is_viewer) { ?> 
				<a class="show-history revision toolbar-button" title="Show file history"><?php echo get_svg_image('commit') ?></a>
				<?php } ?>
				<span  class="splitter-toggle right" data-splitter="previewSplitter" data-splitter-toggle="1"  title="Toggle Preview" ><?php echo get_svg_image('right') ?></i></span>  
			</div>

			<div class="code-content">
					<div class="editor image-editor" style="display:none"></div>
					<div class="editor text-editor"><textarea id="base_code"></textarea></div>
					<div class="editor history-viewer" style="display:none"></div>
			</div>
		</div>
</div>
