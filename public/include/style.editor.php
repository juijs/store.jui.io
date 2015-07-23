<div class="editor-panel  editor-panel-border  view-component" style="background:#ffffff">

	<div class="editor-tool" style="font-size:13px;">
		<a class="label" data-view="component"><span class="simbol simbol-style">S</span>tyle</a>
		<span style="padding-left:20px">Load <input type="file" accept=".less" id="component_load" style="width:200px;"/></span>


		<select id="component_list" style="float:right;margin-left:2px;" class="input">
			<option value="">Select component</option>
			<option value="">--------------</option>
		</select>
		
	</div>
	<div class="editor-tool2" style="font-size:13px;border-top:0px;">
		<a class="label" data-view="sample">Sample Code</a>
	
		<div style="float:right">
			<label><input type="checkbox" id="autoRun" /> Auto </label>
			<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>
			<form id="result_form" action="generate.ui.php" method="post" target="result_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="sample_type" value="" />
				<input type="hidden" name="name" value="" />
			</form>
			<form id="style_form" action="theme.check.php" method="post" target="style_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
			</form>
		</div>
	</div>

	<div id="tab_contents_2" class="tab-contents editor-codemirror editor-bottom-full" style="background:#ffffff">
		<textarea id="component_code"></textarea>
	</div>
	<div id="tab_contents_1" class="tab-contents editor-codemirror editor-bottom-full">
		<table class="table table-simple" id="table_style">
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