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
			scrollHeight : $(window).height() - 229,
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