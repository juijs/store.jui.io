<div class="editor-panel editor-panel-border view-sample" style="background:#ffffff">
	<div class="editor-tool" style="font-size:13px;">
		<a class='h2' style="display:inline-block" data-view="sample">
			Sample Code <small style="cursor: default;"><strong>@path</strong> will be replaced with the actual file path.</small>
		</a>
		<div class="editor-navbar">
			<div class='group' id="js_html_convert">
				<a class='btn active focus' value='js'>JavaScript</a>
				<a class='btn' value='html'>HTML</a>
			</div>
			<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>
			<form id="chart_form" action="generate.map.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="html_code" value="" />
				<input type="hidden" name="name" value="" />
				<input type="hidden" name="type" value="map" />
			</form>
		</div>
	</div>

<div id="tab_contents_js" class="tab-contents editor-codemirror">
	<textarea id="sample_code">jui.ready([ "chart.builder" ], function(builder) {
	var chart = builder("#result", {
		height : 400,
		padding : 0,
		axis : [{
			map : {
				path : @path
			}
		}]
	});
});
</textarea>
	</div>


	<div id="tab_contents_html" class="tab-contents editor-codemirror" style="display:none">
		<textarea id="html_code"><div id="result"></div></textarea>
	</div>

</div>
