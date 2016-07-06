
<script type="text/javascript">
$(function() {

	var ExternalItemTemplate = '<span title="drag me for ordering" class="handle" draggable="true"><i class="icon-dashboardlist"></i></span><input type="text" placeholder="//myhost.com/my.js" class="input" /><a class="btn small"><i class="icon-exit"></i></a>';

	var componentCode = window.componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  }

	});

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

	jui.create("ui.button", "#js_html_convert", { 
		type : "radio",
		event : {
			change : function(data) {
				$("#js_html_convert a").removeClass('focus');
				$("#js_html_convert a[value=" + data.value + "]").addClass('focus');
				if (data.value == 'js') {
					$("#tab_contents_js").show();
					$("#tab_contents_html").hide();
					$("#tab_contents_css").hide();

					sampleCode.refresh();

				} else if (data.value == 'html') {
					$("#tab_contents_js").hide();
					$("#tab_contents_html").show();
					$("#tab_contents_css").hide();

					htmlCode.refresh();

				} else if (data.value == 'css') {
					$("#tab_contents_js").hide();
					$("#tab_contents_html").hide();
					$("#tab_contents_css").show();

					cssCode.refresh();
				}
			}
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
		window.coderun.cssCodeText = cssCode.getValue();

        $("#chart_form [name=component_code]").val(window.coderun.componentCodeText);
        $("#chart_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#chart_form [name=html_code]").val(window.coderun.htmlCodeText);
        $("#chart_form [name=css_code]").val(window.coderun.cssCodeText);
        $("#chart_form [name=name]").val($("#name").val());
        $("#chart_form [name=resources]").val(getResourceList());
        $("#chart_form [name=preprocessor]").val(getPreProcessorList());

        $("#chart_form").submit();

	}

	<?php include_once "error.view.php" ?>

	window.forkcode = function forkcode() {

		var data = {
            type : 'component',
			id : '<?php echo $_GET['id'] ?>'
		}
	}

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

		$("#js_html_convert [value=js]").html(javascript);
		$("#js_html_convert [value=html]").html(html);
		$("#js_html_convert [value=css]").html(css);

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

	}

	window.savecode = function savecode() {

		$(".blockUI").show();

		var data = {
			type : 'component',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val(),
			component_code : componentCode.getValue(),
			sample_code : sampleCode.getValue(),
			html_code : htmlCode.getValue(),
			css_code : cssCode.getValue(),
			sample : $("#sample").val(),
			resources : getResourceList(),
			preprocessor : getPreProcessorList()
		}

		if (data.name == '')
		{
			alert("Input a ID String (ex : my.module.name)");
			$(".blockUI").hide();
			changeLayout('all');
			$("#name").focus().select();
			return;
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
				componentCode.setValue(data.component_code || "");
				sampleCode.setValue(data.sample_code || "");
				htmlCode.setValue(data.html_code || "");
				cssCode.setValue(data.css_code || "");
				setResourceList(data.resources);
				setPreProcessorList(data.preprocessor);
				coderun();
			});
		}


	}

	loadContent();

	var fileListWin = jui.create("ui.window", "#file-list", {
		width : 600,
		height : 400,
		modal : true,
		event : {
			hide : function() {
				updatePreProcessorList();
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
				var $opt = $("<option />").val(file.name).html(file.name);

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
	
	$("#file-list").on('click', 'li.leaf', function() {

		if (!confirm("Do you want to change the module?")){
			return;
		}

		$("#file-list li.active").removeClass('active');
		$(this).addClass('active');

		$.ajax({
			url : "/jui/js/" + $(this).data('path'),
			dataType : 'text',
			success : function(data) {
				componentCode.setValue(data);
				componentCode.refresh();

				fileListWin.hide();
			}
		})
	});

	$("#file-list").on('click', 'li.fold i,li.open i', function() {
		var $parent = $(this).parent();
		if ($parent.hasClass('fold')) {
			$parent.addClass('open').removeClass('fold');
			$parent.find("> ul").show();
		} else {
			$parent.addClass('fold').removeClass('open');
			$parent.find("> ul").hide();
		}
	});

	$("#library").click(function() {

		fileListWin.show();

		function generateTree(data, className) {

			var arr = [];
			for(var i = 0, len = data.length; i< len; i++) {
				if (data[i].is_dir) {
					var $li = $("<li />").addClass('open');

					$li.append("<i></i>");
					$li.append("<div><i></i> "+data[i].name+"</div>");
					$li.append(generateTree(data[i].list));

					arr.push($li);
				} else {
					var $li = $("<li />").addClass('leaf');

					$li.append("<i></i>");
					$li.append("<div><i></i> "+data[i].name+"</div>");

					$li.data('path', data[i].path);

					arr.push($li);
				}
			}

			arr[arr.length-1].addClass('last');

			return $("<ul />").append(arr).addClass(className || "");
		}

		$.getJSON("/scandir.php", function(data) {

			var $ul = generateTree(data);

			var $root = $("<ul class='tree line-file'><li class='open root'><i></i> <div><i></i> JUI</div></li></ul>");
			$root.find("li").append($ul);

			$("#loaded-file-list").html($root);

		});

	});

	var layout =  "sample+result";
	if (window.localStorage)
	{
		layout = window.localStorage.getItem("component.layout");
	}
	changeLayout(layout);
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
			<li class='active'><a href="#external-resources">External Resources</a></li>
			<li><a href="#preprocessor">Preprocessor</a></li>
			<li>
				<a href="#jui-resources">Load JUI Resources</a>
			</li>
		</ul>
		<div id="tab_contents_1" class='import-content'>
			<div id="jui-resources">
				<div id="loaded-file-list" class='submenu-content'></div>
			</div>
			<div id="preprocessor">
				<div class='preprocessor-help'>Sample Code </div>
				<div class='p-list'>
					<div class="p-item">
						<label>HTML</label>
						<select class='input'>
							<option value="html">HTML</option>
							<option value="jade">Jade</option>
							<option value="markdown">Markdown</option>
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
				<div class='external-help'>It can import  external css and js files. </div>
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
					<div style="float:right;display:none;">
					Quick Reference : 

						<select class='input framework-list'>
						</select>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>

<div class='blockUI'>
	<div class='message'>Saving...</div>
</div>

<?php include __DIR__."/script.php" ?>
