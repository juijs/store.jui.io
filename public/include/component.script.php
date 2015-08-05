
<script type="text/javascript">
$(function() {
	var componentCode = window.componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
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

	jui.create("ui.button", "#js_html_convert", { 
		type : "radio",
		event : {
			change : function(data) {
				$("#js_html_convert a").removeClass('focus');
				$("#js_html_convert a[value=" + data.value + "]").addClass('focus');
				if (data.value == 'js') {
					$("#tab_contents_js").show();
					$("#tab_contents_html").hide();

					sampleCode.refresh();

				} else if (data.value == 'html') {
					$("#tab_contents_js").hide();
					$("#tab_contents_html").show();

					htmlCode.refresh();
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

        $("#chart_form [name=component_code]").val(window.coderun.componentCodeText);
        $("#chart_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#chart_form [name=html_code]").val(window.coderun.htmlCodeText);
        $("#chart_form [name=name]").val($("#name").val());

        $("#chart_form").submit();

	}

	<?php include_once "error.view.php" ?>

	window.forkcode = function savecode() {

		var data = {
            type : 'component',
			id : '<?php echo $_GET['id'] ?>'
		}
	}
	window.savecode = function savecode() {

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

		$(this).toggleClass('active');

		$(".view-component").toggleClass('has-submenu');
		componentCode.refresh();

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
});
</script>


<?php include __DIR__."/script.php" ?>