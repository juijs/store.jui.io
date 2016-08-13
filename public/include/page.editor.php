					<div class="editor-panel-full  editor-panel-border  view-component" style="background:#ffffff">

						<div class="editor-tool" style="font-size:13px;">
							<ul class="tab top" id="module_convert" style="margin:0px;float:left;padding-left: 19px;">
								<li class="active"><a href="#" value="html">HTML</a></li>
								<li><a href="#" value="js">Javascript</a></li>
								<li><a href="#" value="css">CSS</a></li>
								<li style="display:none" class='result-btn'><a href="#" value="result">Result</a></li>
							</ul>
							<span class="editor-navbar" style="float:right">
								<a class='btn run-btn' onclick="coderun()" style="position:absolute;right:10px;">Run <i class="icon-play"></i></a>
								<?php if ($isMy) { ?>
								<!--
								<div class='' style="display:inline-block">
									<a class="btn"><i class="icon-upload"></i> Upload File</a> 
									<input type="file" accept=".js" id="component_load" style="right: 30px;" />
								</div>-->
								<?php } ?>
							</span>
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

				<form id="chart_form" action="generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
					<input type="hidden" name="component_code" value="" />
					<input type="hidden" name="sample_code" value="" />
					<input type="hidden" name="html_code" value="" />
					<input type="hidden" name="css_code" value="" />
					<input type="hidden" name="name" value="" />
					<input type="hidden" name="type" value="page" />
					<input type="hidden" name="resources" value="" />
					<input type="hidden" name="preprocessor" value="" />
				</form>
