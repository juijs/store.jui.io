<div class="editor-panel-full  editor-panel-border type-editor-container  view-component">

		<div class="file-container">
			<div class="file-toolbar">
				<h1>FILES</h1>
			</div>
			<div class="file-content">
			</div>
	
		</div>
		<div class="code-container">
			<div class="code-toolbar">
				<h1> <i class="icon-report2"></i> <a class="file-name" title="Please click if you want to see a file on new window">EDITOR</a><span class="file-type-tools"></span></h1>
                <div class='file-common-tools'>
				<?php if ($isMy) { ?> 
				<a class="show-history revision toolbar-button" title="Show file history"><?php echo get_svg_image('commit') ?></a>
				<a class="save-btn revision toolbar-button" title="Save a file"><i class='icon-clip'></i> SAVE</a>
				<?php } ?>
                </div>
			</div>

			<div class="code-content">
					<div class="editor image-editor" style="display:none"></div>
					<div class="editor text-editor"><textarea id="base_code"></textarea></div>
					<div class="editor history-viewer" style="display:none"></div>
			</div>
		</div>
</div>
