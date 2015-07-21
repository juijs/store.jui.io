<div class="editor-panel-pull editor-panel-border view-component" style="">

	<div class="editor-tool" style="font-size:13px;">
		<a class="label" data-view="component"><span class="simbol simbol-theme">T</span>heme</a>
		<span style="padding-left:20px">Load <input type="file" accept=".js" id="component_load" style="width:200px;"/></span>


		<select id="component_list" style="float:right;margin-left:2px;" class="input">
			<option value="">Select component</option>
			<option value="">--------------</option>
		</select>
		
	</div>
	<div class="editor-tool2" style="font-size:13px;border-top:0px;">
		<a class="label" data-view="sample">Sample Code</a>
		<span>
			<select id="sample_list" class="input">
				<option value="">Select Sample</option>
			</select>
		</span>
		<div style="float:right">
			<label><input type="checkbox" id="autoRun" /> Auto </label>
			<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>
			<form id="chart_form" action="generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="name" value="" />
			</form>
			<form id="theme_form" action="theme.check.php" method="post" target="theme_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
			</form>
		</div>
	</div>

	<div id="tab_contents_2" class="tab-contents editor-codemirror editor-bottom-full" style="background:#ffffff">
		<textarea id="component_code"></textarea>
	</div>
	<div id="tab_contents_1" class="tab-contents editor-codemirror editor-bottom-full">
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
	<div class="editor-statusbar">
		
		<label style="display:none"><input type="radio" name="source" value="table" checked onclick="toggleDesign(this.value)"/> Table </label>
		<label style="display:none"><input type="radio" name="source" value="code" onclick="toggleDesign(this.value)"/> Code </label>

		<span style="float:right">
			License : 
			<select class="input" id="license">
				<option value="None" selected>None</option>
				<option value="Apache License 2.0">Apache License 2.0</option>
				<option value="GNU General Public License v2.0">GNU General Public License v2.0</option>
				<option value="MIT License">MIT License</option>
			</select>
		</span>
	</div>
</div>