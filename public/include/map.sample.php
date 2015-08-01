<div class="editor-panel editor-panel-border view-sample" style="background:#ffffff">
	<div class="editor-tool" style="font-size:13px;">
		<a class='h2' style="display:inline-block" data-view="sample">
			Sample Code <small style="cursor: default;">'@path' will be replaced with the actual file path.</small>
		</a>
		<div class="editor-navbar">
			<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>
			<form id="chart_form" action="generate.map.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="name" value="" />
			</form>
		</div>
	</div>

<div id="tab_contents_2" class="tab-contents editor-codemirror">
	<textarea id="sample_code">var chart = jui.include("chart.builder");

chart("#result", {
    padding : 0,
    axis : [{
        map : {
            path : @path
        }
    }]
});
</textarea>
	</div>
</div>
