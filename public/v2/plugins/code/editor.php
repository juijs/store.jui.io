<div class="editor-panel-full  editor-panel-border type-editor-container  view-component">

		<div class="file-container">
			<div class="file-toolbar">
				<h1>FILES</h1>
				<?php if ($isMy && !$is_viewer) { ?>
				<div class="buttons">
					<a class="add-directory-btn" title="add folder"  ><i class="icon-add-dir"></i> FOLDER</a>
					&nbsp;&nbsp;&nbsp;
					<a class="add-file-btn" title="add file"  ><i class='icon-report2'></i> FILE</a>
				</div>
				<?php } ?>
			</div>
			<div class="file-content">
			</div>
	
		</div>
		<div class="code-container">
			<div class="code-toolbar">
				<h1><span  class="splitter-toggle" data-splitter="fileSplitter" title="Toggle Files" ><?php echo get_svg_image('left') ?></span> <i class="icon-report2"></i> <a class="file-name" title="Please click if you want to see a file on new window">EDITOR</a><span class="file-type-tools"></span></h1>
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
