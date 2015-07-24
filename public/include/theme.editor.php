<div class="editor-panel-pull editor-panel-border view-component" style="">

	<div class="editor-tool" style="font-size:13px;">
		<a class="h2" style="display:inline-block"  data-view="component"><?php echo $type_text['theme'] ?></a>
		<span style="padding-left:20px">Load <input type="file" accept=".js" id="component_load" style="width:200px;"/></span>

		
		<span style="float:right">
			<a class="btn " onclick="select_theme(this)">Select Theme</a>

		</span>

		<div style='float:right;cursor:pointer;display:none;' class='close'><i class='icon-close' style='font-size:20px'></i></div>
		
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
		<table class="table table-simple" id="table_theme">
		<thead>
			<tr>
				<th width="40%">key</th>
				<th width="60%">value</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
		</table>
	</div>

	<div class="tab-contents editor-codemirror" style="display:none">
		<textarea id="sample_code"></textarea>
	</div>
</div>
