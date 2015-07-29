<div class="editor-panel view-data" style="background:#ffffff">
	<div class="editor-tool" style="font-size:13px;">
		<a class="h2" style="display:inline-block" data-view="data">Data Input</a>
		<div style='float:right;cursor:pointer;display:none;' class='close'><i class='icon-close' style='font-size:20px'></i></div>
		<span style="float:right;margin-right:10px;font-size:15px;">
			Type : 
			<select id="select_data_type" class="input">
				<option value="json">json</option>
				<option value="text">text</option>
			</select>
			<a class='btn' onclick="inputData()">Push</a>
		</span>
	</div>

<div id="tab_contents_2" class="tab-contents editor-codemirror">
<textarea id="sample_code">{
	"text" : "Hello, Data Server"
}</textarea>
	</div>
</div>
