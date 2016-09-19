<?php @include_once V2_PLUGIN."/map/settings.php" ?>

<script type="text/javascript">
$(function() {

	var ExternalItemTemplate = '<span title="drag me for ordering" class="handle" draggable="true"><i class="icon-dashboardlist"></i></span><input type="text" placeholder="//myhost.com/my.js" class="input" /><a class="btn small"><i class="icon-exit"></i></a>';

	var componentCode = window.componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "xml",
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

				if ($('.editor-splitter').css('display') == 'none') {
					if (value == 'result') {
						$('.editor-right').show();
						coderun();
					} else {
						$('.editor-right').hide();

					}
				}
	});

	window.coderun_timer = null;
	window.coderun = function coderun () {

		if (coderun_timer) {
			clearTimeout(coderun_timer);
		}

		coderun_timer = setTimeout(function () { 

			removeError();

			window.coderun.componentCodeText = componentCode.getValue();
			window.coderun.sampleCodeText = sampleCode.getValue();
			window.coderun.htmlCodeText = htmlCode.getValue();

			$("#chart_form [name=component_code]").val(window.coderun.componentCodeText);
			$("#chart_form [name=sample_code]").val(window.coderun.sampleCodeText);
			$("#chart_form [name=html_code]").val(window.coderun.htmlCodeText);
			$("#chart_form [name=name]").val($("#name").val());

			$("#chart_form").submit();
		}, 1000);	
	}

	<?php include_once INC."/error.view.php" ?>


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
			html_code : htmlCode.getValue()
		}

		$.post("/v2/save.php", data, function(res) {
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
			$.post("/v2/delete.php", { id : '<?php echo $_GET['id'] ?>' }, function(res) {
				if (res.result) {
					location.href = '/v2/dashboard.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}

	window.setSampleImage = function setSampleImage(img) {
		$("#sample").val(img)
	}


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
		} else {
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
