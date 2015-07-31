<div class="editor-panel editor-panel-border view-sample" style="background:#ffffff">
	<div class="editor-tool" style="font-size:13px;">
		<a class="h2" style="display:inline-block" data-view="sample">Sample Code</a>
		<div  class="editor-navbar">
			<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>
			<form id="chart_form" action="generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="name" value="" />
			</form>
		</div>
	</div>

					<div id="tab_contents_2" class="tab-contents editor-codemirror">
						<textarea id="sample_code">var data = [
   { x : 'sample1', y : 200 } , 
   { x : 'sample2', y : 300 },
   { x : 'sample4', y : 2100 } ,
   { x : 'sample3', y : 5000 }
];

var chart = jui.create("chart.builder", '#result', {
	axis : {
		x : { 
          type : 'block', domain : 'x'
		},
		y : {
          type : 'range', domain : 'y'
		},
		data : data 
	}
});

</textarea>
	</div>
</div>
