<?php $page_id = 'view'; ?>
<?php include_once "header.php" ?>

<style type="text/css">
.CodeMirror {
	height: 100%;
}
body {
    overflow:hidden;
}
</style>

<div class="editor-container">
	<div class="editor-content">
		<div class="editor-area">
			<div class="editor-code">
				<div class="editor-data" style="background:#ffffff">

					<div class="editor-tool" style="font-size:13px;">
						<a class="label">Component</a>

						<div style="float:right">
							<a href="#" class='component_link' target="_blank"><i class='icon-' ></i></a>
						</div>
					</div>

					<div id="tab_contents_1" class="tab-contents editor-info">
						<textarea id="component_code"></textarea>
					</div>
				</div>
				<div class="editor-component" style="background:#ffffff">
					<div class="editor-tool" style="font-size:13px;">
						<a class="label">Sample Code</a>
                        <div style="float:right">
                            <form id="chart_form" action="generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
                                <input type="hidden" name="component_code" value="" />
                                <input type="hidden" name="sample_code" value="" />
                                <input type="hidden" name="name" value="" />
                            </form>
						</div>
					</div>

					<div id="tab_contents_2" class="tab-contents editor-info">
						<textarea id="sample_code"></textarea>
					</div>
				</div>
			</div>
			<div class="editor-result" style="padding-left:10px;padding-right:10px;">
                <div class="editor-meta">
 
					<div class="editor-tool" style="font-size:13px;">
						<a class="label">Information</a>

					</div>

					<div id="tab_contents_1" class="tab-contents editor-info" style="overflow-y:auto">
					    <form style="padding:50px">
                            <div class="row" style="padding:5px;">
                                <div class="col col-2">Title </div>
                                <div class="col col-9"><input type="text" class="input" style="width:100%;" id="title" disabled /></div>
                            </div>
                            <div class="row" style="padding:5px">
                                <div class="col col-2">Name </div>
                                <div class="col col-9"><input type="text" class="input" style="width:100%;" id="name" disabled /></div>
                            </div>
                            <div class="row" style="padding:5px">
                                <div class="col col-2">Description </div>
                                <div class="col col-9">
                                    <textarea style="width:100%;height: 100px;" class="input" id="description" disabled></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
               
                </div>
				<div class="editor-result-frame" id="result">

					<div class="editor-tool" style="font-size:13px;">
						<a class="label">Result</a>
                        <div style="float:right">

                        </div>
					</div>

					<div id="tab_contents_1" class="tab-contents editor-info">
                         <iframe name="chart_frame" frameborder="0" border="0" width="100%" height="99%"></iframe>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function() {
	var componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
      readOnly : true 
	});

	var sampleCode = CodeMirror.fromTextArea($("#sample_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
      readOnly : true
	});


	window.coderun = function coderun () {
		window.coderun.componentCodeText = componentCode.getValue();
		window.coderun.sampleCodeText = sampleCode.getValue();

        $("#chart_form [name=component_code]").val(window.coderun.componentCodeText);
        $("#chart_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#chart_form [name=name]").val($("#name").val());

        $("#chart_form").submit();
	}


	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
				$("#title").val(data.title);
				$("#name").val(data.name);
				$("#description").val(data.description);
				componentCode.setValue(data.component_code);
				sampleCode.setValue(data.sample_code);

				if (data.name && data.access != 'private'){
					$(".component_link").show().attr('href', '/api/' + data.name + ".js");
				} else {
					$(".component_link").hide();
				}


				coderun();
			});

		}


	}

	loadContent();


});
</script>
<?php include_once "footer.php" ?>
