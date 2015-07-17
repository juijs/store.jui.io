<?php $page_id = 'theme'; 

?>

<?php include_once "header.php";


if (!$_SESSION['login']) {
	echo "<script>alert('Please login.');history.go(-1);</script>";
	exit;
}


// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$data = $components->findOne(array('_id' => new MongoId($_GET['id'])));

if ($_GET['id'] && ($data['login_type'] != $_SESSION['login_type'] || $data['userid'] != $_SESSION['userid']) ) {
	echo "<script>alert('This component can not edit.');history.go(-1);</script>";
	exit;
}



?>

<style type="text/css">
.CodeMirror {
	height: 100%;
}
body { overflow: hidden; }
</style>

<div class="editor-container view-all">
	<div class="editor-content">
		<div class="editor-area">
			<div class="editor-code">
				<div class="editor-data2 view-component" style="background:#ffffff">

					<div class="editor-tool" style="font-size:13px;">
						<a class="label" data-view="component">Component</a>
						<span style="padding-left:20px">Load <input type="file" accept=".js" id="component_load" style="width:200px;"/></span>


						<select id="component_list" style="float:right;margin-left:2px;" class="input">
							<option value="">Select component</option>
							<option value="">--------------</option>
						</select>
						
					</div>
					<div class="editor-tool2" style="font-size:13px;">
						<a class="label" data-view="sample">Sample Code</a>
						<span>
							<select id="sample_list" class="input">
								<option value="">Select Sample</option>
							</select>
						</span>
                        <div style="float:right">
							<label><input type="checkbox" id="autoRun" /> Auto </label>
							<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>
                            <form id="chart_form" action="generate.php" method="post" target="chart_frame" enctype="multipart/form-data" style="display:none">
                                <input type="hidden" name="component_code" value="" />
                                <input type="hidden" name="sample_code" value="" />
                                <input type="hidden" name="name" value="" />
                            </form>
                            <form id="theme_form" action="theme.check.php" method="post" target="theme_frame" enctype="multipart/form-data" style="display:none">
                                <input type="hidden" name="component_code" value="" />
                            </form>
						</div>
					</div>

					<div id="tab_contents_2" class="tab-contents editor-codemirror editor-bottom-full" style="background:#ffffff">
						<textarea id="component_code"></textarea>
					</div>
					<div id="tab_contents_1" class="tab-contents editor-codemirror editor-bottom-full">
						<table class="table table-simple" id="table_theme">
						<thead>
							<tr>
								<th width="40%">key</th>
								<th width="60%">value</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
						</table>
					</div>

					<div class="tab-contents editor-codemirror" style="display:none">
						<textarea id="sample_code"></textarea>
					</div>
					<div class="editor-statusbar">
						
						<label style="display:none"><input type="radio" name="source" value="table" checked onclick="toggleDesign(this.value)"/> Table </label>
						<label style="display:none"><input type="radio" name="source" value="code" onclick="toggleDesign(this.value)"/> Code </label>

						<span style="float:right">
							License : 
							<select class="input" id="license">
								<option value="None" selected>None</option>
								<option value="Apache License 2.0">Apache License 2.0</option>
								<option value="GNU General Public License v2.0">GNU General Public License v2.0</option>
								<option value="MIT License">MIT License</option>
							</select>
						</span>
					</div>
				</div>
			</div>
			<div class="editor-result" style="padding-left:10px;padding-right:10px;">
                <div class="editor-meta view-information">
 
					<div class="editor-tool" style="font-size:13px;">
						<a class="label" data-view="information">Information</a>

                        <div style="float:right">
                            <a class="btn btn-small" onclick="savecode()">Save</a>
                            <a class="btn btn-small" onclick="deletecode()">Delete</a>
                        </div>
					</div>

					<div id="tab_contents_1" class="tab-contents editor-info" style="overflow-y:auto">
					    <div class="form-information" style="padding:10px">
                            <div class="row" style="padding:5px">
                                <div class="col col-2">Access </div>
                                <div class="col col-9">
                                    <label><input type="radio" name="access" value="public" checked onclick="viewAccessMessage()" /> Public</label>
                                    <label><input type="radio" name="access" value="private" onclick="viewAccessMessage()"/> Private</label>
                                    <span id="access_message" style="font-size:11px"></span>
                                </div>
                            </div>
                            <div class="row" style="padding:5px;">
                                <div class="col col-2">Title </div>
                                <div class="col col-9"><input type="text" class="input" style="width:100%;" id="title"  /></div>
                            </div>
                            <div class="row" style="padding:5px">
                                <div class="col col-2">Name </div>
                                <div class="col col-9"><input type="text" class="input" style="width:100%;" id="name" /></div>
                            </div>
                            <div class="row" style="padding:5px">
                                <div class="col col-2">Description </div>
                                <div class="col col-9">
                                    <textarea style="width:100%;height: 100px;" class="input" id="description"></textarea>
                                </div>
                            </div>
							<input type="hidden" id="sample" name="sample" value="" />
                        </div>
                    </div>
               
                </div>
				<div class="editor-result-frame view-result" id="result">

					<div class="editor-tool" style="font-size:13px;">
						<a class="label" data-view="result">Result</a>
                        <div style="float:right">
                            Output : png, svg, pdf
                        </div>
					</div>

					<div id="tab_contents_1" class="tab-contents editor-info">
                         <iframe name="chart_frame" frameborder="0" border="0" width="100%" height="99%"></iframe>
                         <iframe name="theme_frame" frameborder="0" border="0" width="0" height="0"></iframe>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<script type="text/template" id="theme_template">jui.define("<!= name !>", [], function() {
	return <!= json !>;
});
</script>

<script type="text/javascript">
$(function() {
	var componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
	  readOnly : true 
	});

	var sampleCode = $("#sample_code");

	sampleCode.setValue = function(str) {
		sampleCode.val(str);
	}

	sampleCode.getValue = function() {
		return sampleCode.val();
	}

	window.toggleDesign = function toggleDesign(type) {
		if (type == 'table')
		{
			$("#tab_contents_1").show();
			$("#tab_contents_2").hide();
		} else {
			$("#tab_contents_1").hide();
			$("#tab_contents_2").show();

			sampleCode.refresh();
		}

	}

	toggleDesign('table');

	$("#component_load").change(function(e) {
	
		if (e.target.files[0]) {
			var blob = e.target.files[0];
			var reader = new FileReader();

			reader.onloadend = function(evt) {
				componentCode.setValue(evt.target.result); 

				getThemeObject();
			}

			reader.readAsText(blob, "utf-8");
		}

	});

	window.view_type_list = function view_type_list(themeName, isChange) {
		var arr = ['jennifer', 'dark', 'pastel', 'pattern', 'gradient'];
		var type = "chart.theme";
		$component_list.empty();
		$component_list.append("<option value=''>Select Theme</option>");
		for(var i = 0, len = arr.length; i < len; i++) {
			$component_list.append("<option value='" + type + "." + arr[i] + "'>" + type + "." + arr[i] + "</option>");
		}


		if (isChange)
		{
			$component_list.val("chart.theme.jennifer");
			$component_list.change();
		}

	}

	var $component_list = $("#component_list"); 


	$component_list.on('change', function(e) {
		var value = $(this).val();

		if (value.length > 0) {
            var path = value.replace(/\./g, '/');
			$.get("/bower_components/jui/js/" + path + ".js").success(function(code) {
				componentCode.setValue(code); 	

				getThemeObject();

				$("#name").val("chart.theme.mytheme");
			});
		}
	});

	window.coderun = function coderun () {
		window.coderun.componentCodeText = componentCode.getValue();
		window.coderun.sampleCodeText = sampleCode.getValue();

        $("#chart_form [name=component_code]").val(window.coderun.componentCodeText);
        $("#chart_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#chart_form [name=name]").val($("#name").val());

        $("#chart_form").submit();
	}

	window.getThemeObject = function getThemeObject () {
		window.coderun.componentCodeText = componentCode.getValue();

        $("#theme_form [name=component_code]").val(window.coderun.componentCodeText);

        $("#theme_form").submit();
	}

	var timer = null;
	function convertTheme() {
		clearTimeout(timer);

		timer = setTimeout(function() { 

			var theme = { colors : [] };

			$("#table_theme tbody .picker-value").each(function() {
				var key = $(this).attr('data-key');
				var value = $(this).val();
				
				if (key == 'colors')
				{
					theme.colors.push(value);
				} else {
					theme[key] = value; 
				}
			});

			var name = $("#name").val();
			var template = jui.include("util.base").template($("#theme_template").html());
			var str = JSON.stringify(theme, null, 4);

			str = str.replace(/\n/g, "\n    ");

			componentCode.setValue(template({ name : name, json : str }));
			componentCode.refresh();

			coderun();
			
		}, 10);
	}

	window.setThemeObject = function setThemeObject(obj) {
		if(jui.include("util.base").browser.msie) return;

		window.table_2 = jui.create("uix.table", "#table_theme", {
			fields: [ "key", "value" ],
            scroll : true,
			scrollHeight : $(window).height() - 250,
			resize: true,
			tpl: {
				row: "<tr><td style='font-size:15px;'><!= key !></td><td><!= value !></td></tr>"
			}
		});

		// 테이블 초기화
		table_2.reset();

		for(var key in obj) {
			if(key == "colors") {

				var list = [];
				for(var i = 0, len = obj[key].length; i < len; i++) {
					var value = obj[key][i];
					if (value.indexOf("linear") > -1 || value.indexOf("radial") > -1) {
						list[i] = "<input type='text' class='input gradient-picker picker-value' style='width:200px;background:" + obj[key][i] + ";' data-key='" + key + "' value='" + (obj[key][i] || "") + "' /> ";			
					} else if (value.indexOf("pattern") > -1) {
						list[i] = "<input type='text' class='input pattern-picker picker-value' style='width:150px;background:" + obj[key][i] + ";' data-key='" + key + "' value='" + (obj[key][i] || "") + "' /> ";			
					} else {
						list[i] = "<input type='text' class='input color-picker picker-value' style='width:80px;background:" + obj[key][i] + ";' data-key='" + key + "' value='" + (obj[key][i] || "") + "' /> ";
					}

				}

				table_2.append({ key: key + " <a class='btn btn-mini'><i class='icon-plus'></i></a>", value: list.join(" ") });
			} else {
				var str = obj[key];
				if (key.indexOf("Color") > -1)
				{
					str = "<input type='text' class='color-picker input picker-value' style='width:80px;background:" + obj[key] + ";' data-key='" + key + "' value='" + (obj[key] || "") + "' /> ";
				} else if (key.indexOf("FontSize") > -1) {
                    str = "<input type='text' value='"+str+"' class='font-size-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("BorderWidth") > -1) {
                    str = "<input type='text' value='"+str+"' class='border-width-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("BorderSize") > -1) {
                    str = "<input type='text' value='"+str+"' class='border-size-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("Opacity") > -1) {
                    str = "<input type='text' value='"+str+"' class='opacity-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("Padding") > -1) {
                    str = "<input type='text' value='"+str+"' class='padding-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("Radius") > -1) {
                    str = "<input type='text' value='"+str+"' class='radius-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("DashArray") > -1) {
                    str = "<input type='text' value='"+str+"' class='dash-array input picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("FontWeight") > -1) {
                    str = ["<select class='font-weight-range input picker-value' data-key='" + key + "'>",
                            "<option value='normal'>normal</option>",
                            "<option value='bold'>bold</option>",
                            "<option value='bolder'>bolder</option>",
                            "<option value='lighter'>lighter</option>",
                            "<option value='100'>100</option>",
                            "<option value='200'>200</option>",
                            "<option value='300'>300</option>",
                            "<option value='400'>400</option>",
                            "<option value='500'>500</option>",
                            "<option value='600'>600</option>",
                            "<option value='700'>700</option>",
                            "<option value='800'>800</option>",
                            "<option value='900'>900</option>",
                        "</select>"].join("");
                } else {
                    str = "<input type='text' value='"+str+"' class='input picker-value'  data-key='" + key + "'/>";
				}
				table_2.append({ key: key, value: str });
			}
		}

		$(".picker-value").change(function() {
			convertTheme();
		});

        $(".color-picker").colorPicker({
            renderCallback : function ($elem, toggled) {
				convertTheme();
            }
        });

        $(".font-size-range").ionRangeSlider({
            min: 8,
            max : 50,
            hide_min_max : true 
        });

        $(".border-width-range").ionRangeSlider({
            min: 0,
            max : 10,
            hide_min_max : true 
        });
        $(".border-size-range").ionRangeSlider({
            min: 0,
            max : 20,
            hide_min_max : true 
        });
        $(".opacity-range").ionRangeSlider({
            min: 0,
            max : 1,
            step : 0.05,
            hide_min_max : true 
        });
        $(".radius-range").ionRangeSlider({
            min: 0,
            max : 30,
            step : 0.5,
            hide_min_max : true
        });
        
        $(".padding-range").ionRangeSlider({
            min: 0,
            max : 20,
            hide_min_max : true
        });

		convertTheme();
	}

	window.savecode = function savecode() {

		var data = {
			type : 'theme',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $("#name").val(),
			description : $("#description").val(),
			license : $("#license").val(),
			component_code : componentCode.getValue(),
			sample_code : sampleCode.getValue(),
			sample : $("#sample").val()
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

   window.viewAccessMessage = function viewAccessMessage() {
        var access = $("[name=access]:checked").val();
        if (access == 'private') {
            $("#access_message").html('Only you can see this component.').css({
                color : 'red'        
            });
        } else {
            $("#access_message").html("Anyone can see this component.").css({color : 'blue'});
        }
   }

   viewAccessMessage();

	window.viewFullscreen = function viewFullscreen(e) {
		var view = $(e.target).data('view');	
		var $view = $(".view-" + view);

		if ($view.data('clone'))
		{
			var $clone = $view.data('clone');
			$view.data('clone').before($view);
			$view.removeClass('fullscreen');
			$view.data('clone', null);
			$clone.remove();
			$view.css({
				'z-index' : 0
			});

			coderun();
		} else {
			if (view == 'component' || view == 'sample') return;


			var $clone = $view.clone();
			$clone.removeClass('view-' + view).addClass('clone');
			$clone.empty();

			$view.after($clone);
			$clone.css({ opacity : 0.8 });

			$view.addClass('fullscreen').appendTo('body');
			$view.data('clone', $clone);
			$view.css({
				'z-index' : 999999
			});

			if (view == 'result') {
				coderun();
			}
		}
	}


	$("a.label").css({
		'cursor' : 'pointer',
		'-webkit-user-select' : 'none'
	}).on('dblclick', viewFullscreen);

	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
                $("[value=" + data.access + "]").attr('checked', true);
				$("#title").val(data.title);
				$("#name").val(data.name);
				$("#description").val(data.description);
				$("#license").val(data.license || "None");
				componentCode.setValue(data.component_code);
				sampleCode.setValue(data.sample_code);

				coderun();

				getThemeObject();


			});
		}

	}


	if ('<?php echo $_GET['id'] ?>')
	{
		loadContent();
		view_type_list(false);
	} else {
		view_type_list(true);
	}



	function load_sample_list() {
		var $sample_list = $("#sample_list");
		$.get("sample.php").success(function(arr) {
			for(var i = 0, len = arr.length; i < len; i++) {
				$sample_list.append("<option value='"+ arr[i] + "'>" + arr[i].replace(".js", "") + "</option>");
			}
		});
	}
	load_sample_list();

	$("#sample_list").on('change', function() {
		var file = $(this).val();
		
		$.get("/sample/" + file).success(function(code) {
			code = code.replace("#chart\-content", "#result");
			code = code.replace("#chart", "#result");
			sampleCode.setValue(code); 	

			coderun();
		});
	});

});
</script>
<?php include_once "footer.php" ?>
