
<script type="text/javascript">
$(function() {

	var ExternalItemTemplate = '<span title="drag me for ordering" class="handle" draggable="true"><i class="icon-dashboardlist"></i></span><input type="text" placeholder="//myhost.com/my.js" class="input" /><a class="btn small"><i class="icon-exit"></i></a>';

	var sampleCode = window.sampleCode = CodeMirror.fromTextArea($("#sample_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  }
	});

	var htmlCode = window.htmlCode = CodeMirror.fromTextArea($("#html_code")[0], {
	  mode:  "htmlmixed",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  }
	});

	var cssCode = window.cssCode = CodeMirror.fromTextArea($("#css_code")[0], {
	  mode:  "css",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  }
	});

	var editor_list = ['js', 'html', 'css'];
	var code_editor_list = [sampleCode, htmlCode, cssCode];

	window.splitter_move_done = function () {
		for(var i = 0, len = code_editor_list.length; i < len; i++) {
			code_editor_list[i].refresh();
		}
	}


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

				if ($('.editor-splitter').css('display') == 'none') {
					if (value == 'result') {
						$('.editor-right').show();
						coderun();
					} else {
						$('.editor-right').hide();

					}
				}
	});

	window.code_name_list = {
		"markdown": "Markdown",
		"javascript": "Javascript",
		"css": "CSS",
		"stylus": "stylus",
		"jade": "Jade",
		"scss": "SCSS",
		"sass": "SASS",
		"coffeescript": "Coffescript",
		"html": "HTML",
		"haml": "Haml",
		"typescript": "Typescript"
	};

	window.coderun = function coderun () {

		removeError();

		window.coderun.sampleCodeText = sampleCode.getValue();
		window.coderun.htmlCodeText = htmlCode.getValue();
		window.coderun.cssCodeText = cssCode.getValue();

        $("#chart_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#chart_form [name=html_code]").val(window.coderun.htmlCodeText);
        $("#chart_form [name=css_code]").val(window.coderun.cssCodeText);
        $("#chart_form [name=name]").val($("#name").val());
        $("#chart_form [name=resources]").val(getResourceList());
        $("#chart_form [name=preprocessor]").val(getPreProcessorList());

        $("#chart_form").submit();

	}

	<?php include_once "error.view.php" ?>

	window.getResourceList = function getResoureList() {
		return $(".external-item input[type=text]").map(function() {
			return $(this).val();
		}).get().join(",");
	}

	window.setResourceList = function setReousrceList(resourceList) {
		resourceList = resourceList || "";
		var arr = resourceList.split(",");

		var $list = $(".external-list");
		$list.empty();
		for(var i = 0; i < arr.length; i++) {
			var $item = $("<div class='external-item' />");
			$item.append(ExternalItemTemplate);

			$item.find("input").val(arr[i]);

			$list.append($item);
		}

		$list.sortable({ placeholderClass: 'border-on' 	});
	}

	window.getPreProcessorList = function getPreProcessList() {

		return $(".p-item select").map(function() {
			return $(this).val();
		}).get().join(",");
	} 

	window.setPreProcessorList = function setPreProcessList(preprocessorList) {
		var arr = (preprocessorList || "").split(",");
			
		$(".p-item select").each(function(i) {
			if (arr[i])
			{
				$(this).val(arr[i]);
			}

		});

		updatePreProcessorList();
	}

	window.updatePreProcessorList = function updatePreProcessList() {
		var arr = getPreProcessorList().split(",");
		// update ui title 
		var html = arr[0];
		var javascript = arr[1];
		var css = arr[2];

		$("#module_convert [value=js]").html(code_name_list[javascript]);
		$("#module_convert [value=html]").html(code_name_list[html]);
		$("#module_convert [value=css]").html(code_name_list[css]);

		var htmlMode = html;
		var javascriptMode = javascript;
		var cssMode = css;

		if (htmlMode == 'html') {
			htmlMode = 'htmlmixed'
		}

		if (cssMode == 'less') {
			cssMode = 'css'
		}

		if (cssMode == 'scss') {
			cssMode = 'sass'
		}

		// set syntax highlighting
	    sampleCode.setOption("mode", javascriptMode);
	    htmlCode.setOption("mode", htmlMode);
		cssCode.setOption("mode", cssMode);

	}

	window.savecode = function savecode() {

		$(".blockUI").show();

		var data = {
			type : 'page',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val(),
			sample_code : sampleCode.getValue(),
			html_code : htmlCode.getValue(),
			css_code : cssCode.getValue(),
			sample : $("#sample").val(),
			resources : getResourceList(),
			preprocessor : getPreProcessorList()
		}

		$.post("/save.php", data, function(res) {
			$(".blockUI").hide();

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
				sampleCode.setValue(data.sample_code || "");
				htmlCode.setValue(data.html_code || "");
				cssCode.setValue(data.css_code || "");
				setResourceList(data.resources);
				setPreProcessorList(data.preprocessor);
				coderun();
			});
		} else {
			updatePreProcessorList();
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

	$.getJSON("/scandir.framework.php", function(data) {

		var currentGroup = "";
		var arr = [];
		var $currentOpt = null;

		arr.push($("<option value=''>No Lib(Pure JS)</option>"));
		for(var i = 0, len = data.length; i < len; i++) {
			var file = data[i];
			var group = file.name.split(".")[0];

			if (group != currentGroup) {
				$currentOpt = $("<optgroup />").attr('label', group);

				arr.push($currentOpt);

				currentGroup = group;
			}

			if ($currentOpt) {

				var name = file.info.name || file.name;

				var $opt = $("<option />").val(file.name).html(name);

				$currentOpt.append($opt);
			}

		}

		$(".framework-list").html(arr);
	});

	window.resetExternalItem = function() {
		$(".external-item input").each(function() {

			var value = $.trim($(this).val());

			if (value == '') {
				$(this).parent().remove();
			}
		});

	}

	$(".framework-list").change(function(e) {
		var name = $(this).val();

		if ($.trim(name) == '') {
			return;
		}

		resetExternalItem();

		var count = 0; 
		$(".external-item input").each(function() {

			var value = $.trim($(this).val());

			if (value == name) {
				count++;
			}
		});

		if (count > 0) {
			return;
		}

		var $item = $("<div class='external-item' />");

		$item.append(ExternalItemTemplate);

		$item.find(".input").val(name);
		var $list = $(".external-list");
		$list.append($item);
		
		$list.sortable({ placeholderClass: 'border-on' 	});

	});

	var resourceTab = jui.create('ui.tab', ".import-toolbar", {
		target: ".import-content",
	});

	$(".add-form-btn").on('click', function() {
		var $item = $("<div class='external-item' />");

		$item.append(ExternalItemTemplate);

		var $list = $(".external-list");
		$list.append($item);
		
		$list.sortable({ placeholderClass: 'border-on' 	});
	});

	$(".external-list").on("click", '.external-item .btn', function(e) {
		$(e.currentTarget).parent().remove();

		if ($(".external-list").children().length == 0) {
			$(".add-form-btn").click();
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
			<li ><a href="#external-resources">External Resources</a></li>
			<li><a href="#preprocessor">Preprocessor</a></li>
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
			<div id="preprocessor">
				<div class='p-list'>
					<div class="p-item">
						<label>HTML</label>
						<select class='input'>
							<option value="html">HTML</option>
							<option value="jade">Jade</option>
							<option value="markdown" selected>Markdown</option>
							<option value="haml" disabled>Haml</option>
						</select>
					</div>
					<div class="p-item">
						<label>JavaScript</label>
						<select class='input'>
							<option value="javascript">JavaScript</option>
							<option value="coffeescript" disabled>CoffeeScript</option>
							<option value="typescript" disabled>TypeScript</option>
						</select>
					</div>
					<div class="p-item">
						<label>CSS</label>
						<select class='input'>
							<option value="css">CSS</option>
							<option value="less">LESS</option>
							<option value="scss">SCSS</option>
							<option value="stylus">Stylus</option>
						</select>
					</div>
				</div>
			</div>
			<div id="external-resources">
				<div class="external-list">
					<div class='external-item'>
						<span title="drag me for ordering" class=
'handle'><i class='icon-dashboardlist'></i></span><input type="text" placeholder="//myhost.com/my.js or my.css" class="input" /><a class='btn small'><i class='icon-exit'></i></a>
					</div>
				</div>
				<div class='external-toolbar'>
					<div style="float:left">
						<a class='btn add-form-btn'><i class='icon-plus' ></i> Add Resource</a>					
					</div>
					<div style="float:right;">
					Quick Reference : 

						<select class='input framework-list'>
						</select>
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
