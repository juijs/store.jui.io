<div class="editor-panel-pull editor-panel-border view-component">

	<div class="editor-tool" style="font-size:13px;border-bottom:0px;">
		<a class='h2' style="display:inline-block"  data-view="component"><?php echo $type_text['theme'] ?></a>
		
		<span class="editor-navbar">
			<div class="group">
				<a class="btn"><i class="icon-upload"></i> Upload File</a> 
				<a class="btn" onclick="select_theme(this)">Select Theme</a>
			</div>
			<input type="file" accept=".js" id="component_load" />
		</span>
	</div>
	<div id="tab_contents_2" class="tab-contents editor-codemirror" style="background:#ffffff">
		<textarea id="component_code"></textarea>
			<form id="chart_form" action="generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="name" value="" />
			</form>
			<form id="theme_form" action="theme.check.php" method="post" target="theme_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
			</form>
	</div>
	<div id="tab_contents_1" class="tab-contents editor-codemirror">
		<table class="table table-simple table-headline" id="table_theme">
			<thead>
				<tr>
					<th width="40%">key</th>
					<th width="60%">value</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>

	<div class="tab-contents editor-codemirror" style="display:none">
		<textarea id="sample_code"></textarea>
	</div>
</div>
