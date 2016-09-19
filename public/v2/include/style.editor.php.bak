<div class="editor-panel editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

	<div class="editor-tool" style="font-size:13px;">
		<a class='h2' style="display:inline-block"  data-view="component"><?php echo $type_text['style'] ?></a>
		<select id="key-list" class='input' onchange="location.href='#' + this.value;"></select>
		<span class="editor-navbar">
			<?php if ($isMy) { ?>
			<div class="group" id="js_html_convert">
				<a class="btn"><i class="icon-upload"></i> Upload File</a> 
				<input type="file" accept=".less" id="component_load" />
			</div>
			<a class="btn" onclick="select_theme(this)">Select Theme</a>
			<select id="sample_list" class="input">
				<option value="">Select Sample</option>
			</select>
			<?php } ?>		
		</span>
	</div>
	<div id="tab_contents_2" class="tab-contents editor-codemirror" style="background:#ffffff">
		<textarea id="component_code"></textarea>
			<form id="result_form" action="generate.ui.php" method="post" target="result_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="sample_type" value="" />
				<input type="hidden" name="name" value="" />
				<input type="hidden" name="theme_name" value="" />
			</form>
			<form id="style_form" action="theme.check.php" method="post" target="style_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
			</form>
	</div>
	<div id="tab_contents_1" class="tab-contents editor-codemirror" style='overflow-y:auto'>
		<div class='property'>
		
		</div>
	</div>

	<div class="tab-contents editor-codemirror" style="display:none">
		<textarea id="sample_code"></textarea>
	</div>
</div>
