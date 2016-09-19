
<script type="text/javascript">
$(function() {
	var componentCode = window.componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "xml",
	  lineNumbers : true ,
	  extraKeys: {"Ctrl-Space": "autocomplete"}
	});

	var sampleCode = window.sampleCode = CodeMirror.fromTextArea($("#sample_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
	  extraKeys: {"Ctrl-Space": "autocomplete"}
	});


	var htmlCode = window.htmlCode = CodeMirror.fromTextArea($("#html_code")[0], {
	  mode:  "htmlmixed",
	  lineNumbers : true,
	  extraKeys: {"Ctrl-Space": "autocomplete"}
	});

	var editor_list = ['component', 'js', 'html'];
	var code_editor_list = [componentCode, sampleCode, htmlCode];

	
	$("#module_convert").on("click", "a", function () {

				var  value = $(this).attr('value');
				var $parent = $(this).parent();
				var $container = $parent.parent();
				$container.find("li.active").removeClass("active");
				$parent.addClass('active');

				for (var i = 0, len = editor_list.length; i < len ; i++ )
				{
					var editor = editor_list[i];
					$("#tab_contents_" + editor).toggle(editor == value);
					code_editor_list[i].refresh();
				}
	});



	$("#component_load").change(function(e) {
	
		if (e.target.files[0]) {
			var blob = e.target.files[0];
			var reader = new FileReader();

			reader.onloadend = function(evt) {
				componentCode.setValue(evt.target.result); 
			}

			reader.readAsText(blob, "utf-8");
		}

	});



	window.coderun = function coderun () {
		removeError();

		window.coderun.componentCodeText = componentCode.getValue();
		window.coderun.sampleCodeText = sampleCode.getValue();
		window.coderun.htmlCodeText = htmlCode.getValue();

        $("#chart_form [name=component_code]").val(window.coderun.componentCodeText);
        $("#chart_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#chart_form [name=html_code]").val(window.coderun.htmlCodeText);
        $("#chart_form [name=name]").val($("#name").val());

        $("#chart_form").submit();
	}

	<?php include_once "error.view.php" ?>

	window.forkcode = function savecode() {

		var data = {
            type : 'map',
			id : '<?php echo $_GET['id'] ?>'
		}
	}
	window.savecode = function savecode() {

		var data = {
			type : 'map',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val(),
			component_code : componentCode.getValue(),
			sample_code : sampleCode.getValue(),
			html_code : htmlCode.getValue(),
			sample : $("#sample").val()
		}

		if (data.name == '')
		{
			alert("Input a ID String (ex : my.module.name)");
			return;
		}


		$.post("/save.php", data, function(res) {
			
			if (res.result)
			{
				location.href = '?id=' + res.id; 	
			} else {
				alert(res.message ? res.message : 'Failed to save');
			}
		});
	}

	window.deletecode = function deletecode () {
		if (confirm("Delete this component?\r\ngood count is also deleted.")) {
			$.post("/delete.php", { id : '<?php echo $_GET['id'] ?>' }, function(res) {
				if (res.result) {
					location.href = '/mylist.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}

	window.setSampleImage = function setSampleImage(img) {
		$("#sample").val(img)
	}


	var timer = null;
	$("#autoRun").click(function() {
		if (this.checked ) {
			timer = setInterval(coderun, 5000);
		} else {
			clearInterval(timer);
		}
	});

	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
                $("[value=" + data.access + "]").attr('checked', true);
				$("#title").val(data.title);
				$("#name").val(data.name);
				$("#description").val(data.description);
				$("#license").val(data.license || "None");
				componentCode.setValue(data.component_code || "");
				sampleCode.setValue(data.sample_code || "");
				htmlCode.setValue(data.html_code || "");

				coderun();
			});
		}


	}

	loadContent();

	var fileListWin = window.fileListWin =  jui.create("ui.window", "#file-list", {
		width : 600,
		height : 500,
		modal : true,
		event : {
			apply : function() {
				updatePreProcessorList();
				this.hide();
			}
		}
	});

	$("#library").click(function() {

		fileListWin.show();

	});
});
</script>

<div id="file-list" class='window <?php echo $isMy ? 'my' : '' ?>' style='display:none'>
    <div class="head">
        <div class="left"><i class='icon-gear'></i> Setting</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
	<div class="body" style="padding:10px;">
		<div style="position:relative;width:100%;height:100%">
		<ul class="tab import-toolbar">
			<li class='active'><a href="#information">Information</a></li>
		</ul>
		<div id="tab_contents_1" class='import-content'>
			<div id="information">
				<div>
					<div style="padding:10px">
						<?php if ($isMy) { ?>
						<div class="row" style="padding:5px">
							<div class="col col-2">Access </div>
							<div class="col col-10">
								<label><input type="radio" name="access" value="public" checked onclick="viewAccessMessage()" <?php echo $data['access'] == 'public' ? 'checked' : '' ?>/> Public</label>
								<label><input type="radio" name="access" value="private" onclick="viewAccessMessage()" <?php echo $data['access'] == 'private' ? 'checked' : '' ?>/> Private </label>
								<label><input type="radio" name="access" value="share" onclick="viewAccessMessage()" <?php echo $data['access'] == 'share' ? 'checked' : '' ?>/> Share </label>
								<span id="access_message" style="font-size:11px;padding:5px;"></span>
							</div>
						</div>
						<?php } ?>
						<div class="row" style="padding:5px">
							<div class="col col-2">Name <i class="icon-help" title="Set the file name"></i></div>
							<div class="col col-10"><input type="text" class="input" style="width:100%;" id="name" require="true" <?php if (!$isMy) { ?>disabled<?php } ?> /></div>
						</div>
						<div class="row" style="padding:5px;">
							<div class="col col-2">Title </div>
							<div class="col col-10"><input type="text" class="input" style="width:100%;" id="title"  <?php if (!$isMy) { ?>disabled<?php } ?>  /></div>
						</div>
						<div class="row" style="padding:5px">
							<div class="col col-2">Description </div>
							<div class="col col-10">
								<textarea style="width:100%;height: 100px;" class="input" id="description" <?php if (!$isMy) { ?>disabled<?php } ?> ></textarea>
							</div>
						</div>
						<?php include_once "include/license.php" ?>
						<input type="hidden" id="sample" name="sample" value="" />
						<input type="hidden" name="resources" value="" />
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
	<div class="foot" style="text-align:right;padding-right:10px;">
		<a href="#" class="btn">Close</a>
		<a href="#" class="btn focus" onclick="fileListWin.emit('apply')">Apply</a>
	</div>
</div>

<div class='blockUI'>
	<div class='message'>Saving...</div>
</div>

<?php include __DIR__."/script.php" ?>