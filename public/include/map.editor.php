<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

	<div class="editor-tool" style="font-size:13px;">
			<ul class="tab top" id="module_convert" style="margin:0px;float:left;padding-left: 19px;">
				<li><a href="#"  value="component"><i class="icon-home"></i> <?php echo $type_text['map'] ?></a></li>
				<li><a href="#" value="html">HTML</a></li>
				<li class="active"><a href="#" value="js">Javascript</a></li>
			</ul>

		<span class="editor-navbar" style="float:right;display:inline-block;width:170px;">
			<?php if ($isMy) { ?>
			<a class="btn"><i class="icon-upload"></i> Upload File</a> 
			<input type="file" accept=".svg" id="component_load" style="right: 30px;" />
			<?php } ?>
				<a class='btn' onclick="coderun()" style="position:absolute;right:10px;">Run <i class="icon-play"></i></a>
		</span>
	</div>

	<div id="tab_contents_component" class="tab-contents editor-codemirror" style="display:none">
		<textarea id="component_code"></textarea>
	</div>
		<div id="tab_contents_js" class="tab-contents editor-codemirror">
			<textarea id="sample_code">/* <strong>@path</strong> will be replaced with the actual file path. */

jui.ready([ "chart.builder" ], function(builder) {
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
		<form id="chart_form" action="generate.map.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
				<input type="hidden" name="component_code" value="" />
				<input type="hidden" name="sample_code" value="" />
				<input type="hidden" name="html_code" value="" />
				<input type="hidden" name="name" value="" />
				<input type="hidden" name="type" value="map" />
		</form>