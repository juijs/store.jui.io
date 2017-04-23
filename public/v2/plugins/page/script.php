<?php @include_once V2_PLUGIN."/page/settings.php" ?>

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


	sampleCode.on('change', function () {   coderun(); 	 });
	htmlCode.on('change', function () {   coderun(); 	 });
	cssCode.on('change', function () {   coderun(); 	 });


	var editor_list = ['js', 'html', 'css'];
	var code_editor_list = [sampleCode, htmlCode, cssCode];


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
		// html 
		"markdown": "Markdown",
		"html": "HTML",
		"haml": "Haml",
		"jade": "Jade",

		// script 
		"javascript": "Javascript",
		"coffeescript": "Coffescript",
		"typescript": "Typescript",

		// css 
		"css": "CSS",
		"stylus": "stylus",
		"less": "LESS",
		"scss": "SCSS"

	};
	window.slide_coderun_timer = null;
	window.coderun = function coderun () {

		if (slide_coderun_timer) {
			clearTimeout(slide_coderun_timer);
		}

		slide_coderun_timer = setTimeout(function () { 
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
		}, 1000);	
	}

	<?php include_once INC."/error.view.php" ?>

<?php if ($isMy && !$is_viewer) { ?>
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

		$.post("/v2/save.php", data, function(res) {
			$(".blockUI").hide();

			if (res.result)
			{
					if (location.href.indexOf('id=') > -1)
					{
		                coderun();
					} else {
	                    location.href = '?id=' + res.id; 	
					}
			} else {
				alert(res.message ? res.message : 'Failed to save');
			}
		});
	}

	window.deletecode = function deletecode () {
		if (confirm("Delete this component?\r\ngood count is also deleted.")) {
			$.post("/v2/delete.php", { id : '<?php echo $_GET['id'] ?>' }, function(res) {
				if (res.result) {
					location.href = '/v2/dashboard.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}
<?php } else { ?>
	window.savecode = function savecode() {

		coderun();
	}
<?php } ?>

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

				sampleCode.clearHistory();
				htmlCode.clearHistory();
				cssCode.clearHistory();

				setResourceList(data.resources);
				setPreProcessorList(data.preprocessor);
				coderun();
			});
		} else {
			updatePreProcessorList();
			coderun();
		}


	}

	loadContent();



	   window.previewSplitter = jui.create('ui.splitter', '.editor-area', {
		   initSize: '50%',
		   items : [ '.editor-left', '.editor-right' ]
	   });


});
</script>
