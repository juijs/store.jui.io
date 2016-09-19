<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

	<div class="editor-tool">
		<ul class="tab top" id="module_convert" style="margin:0px;float:left;padding-left: 19px;">
			<li class="active"><a href="#" value="html">HTML</a></li>
			<li><a href="#" value="js">Javascript</a></li>
			<li><a href="#" value="css">CSS</a></li>
			<li style="display:none" class='result-btn'><a href="#" value="result">Result</a></li>
		</ul>
		<span  class="splitter-toggle right" data-splitter="previewSplitter" data-splitter-toggle="1"  title="Toggle Preview" ><?php echo get_svg_image('right') ?></span>  
	</div>

	<div id="tab_contents_html" class="tab-contents editor-codemirror" >
		<textarea id="html_code"># Hello!</textarea>
	</div>

	<div id="tab_contents_js" class="tab-contents editor-codemirror" style="display:none">
		<textarea id="sample_code"></textarea>
	</div>

	<div id="tab_contents_css" class="tab-contents editor-codemirror" style="display:none">
		<textarea id="css_code"></textarea>
	</div>
</div>

<form id="chart_form" action="<?php echo V2_PLUGIN_URL ?>/<?php echo $type ?>/generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
<input type="hidden" name="component_code" value="" />
<input type="hidden" name="sample_code" value="" />
<input type="hidden" name="html_code" value="" />
<input type="hidden" name="css_code" value="" />
<input type="hidden" name="name" value="" />
<input type="hidden" name="type" value="<?php echo $type ?>" />
<input type="hidden" name="resources" value="" />
<input type="hidden" name="preprocessor" value="" />
</form>
