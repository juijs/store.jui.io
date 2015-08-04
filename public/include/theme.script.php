<script type="text/template" id="theme_template">jui.define("chart.theme.<!= name !>", [], function() {
	return <!= json !>;
});
</script>

<script type="text/javascript">
$(function() {
	var componentCode = window.componentCode =  CodeMirror.fromTextArea($("#component_code")[0], {
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
		if (isChange)
		{
			select_theme_list("chart.theme.jennifer");
		}
	}

	window.select_theme_list = function(value) {

		var path = value.replace(/\./g, '/');
		$.get("/jui/js/chart/theme/" + path + ".js").success(function(code) {
			componentCode.setValue(code); 	

			window.selected_theme_name = value; 

			getThemeObject();

			coderun();
		});
	}


	window.select_theme = function(btn) {
		var win = jui.create('uix.window', "#theme_select_win", {
			width: 650,
			height: 260,
			modal : true
		});

		$(win.root).find(".body .window-item").off().on('click', function() {
			$(this).parent().find(".window-item.select").removeClass("select");
			$(this).addClass('select');
		});

		$(win.root).find(".foot .select-btn").off().on('click', function() {
			win.hide();
			var file = $(win.root).find(".window-item.select").attr('data-theme');
			select_theme_list(file);

			$(btn).html("Select Theme : " + file);
		});

		win.show();
	}

	window.select_sample = function(btn) {
		var win = jui.create('uix.window', "#sample_select_win", {
			width: 650,
			height: 500,
			modal : true
		});

		$.get("sample.php").success(function(arr) {
            var $body = $(win.root).find(".body");
            $body.empty();
			for(var i = 0, len = arr.length; i < len; i++) {
                var item = arr[i];

                if (typeof item == 'string') {
                    var path = item.replace(".js", "");
                    item = { sample : path, name : path, children : [] };
                }

                if (item.type == 'group') {
                    var $item = $("<div class='window-item-group' />");
                    $item.append("<p >" + item.name + "</p>");
                    $body.append($item);
                } else {
                    var $item = $("<div class='window-item' />");

					var chart_type = "";
					if (item.sample.indexOf("gauge") > -1) {
						chart_type = "-gauge";
					} else if (item.sample.indexOf("bar") > -1) 
					{
						chart_type = "-bar";
					} else if (item.sample.indexOf("column") > -1 || item.sample.indexOf("equalizer") > -1 || item.sample.indexOf("cylinder") > -1 || item.sample.indexOf("fullstack") > -1 || item.sample.indexOf("waterfall") > -1) {
						chart_type = "-column";
					} else if (item.sample.indexOf("line") > -1) {
						chart_type = "-line";
					} else if (item.sample.indexOf("radar") > -1) {
						chart_type = "-radar";
					} else if (item.sample.indexOf("area") > -1) {
						chart_type = "-area";
					} else if (item.sample.indexOf("scatter") > -1 || item.sample.indexOf("bubble") > -1) {
						chart_type = "-scatter";
					}

                    //$item.append("<img width='100px' height='100px' />");
                    $item.append("<i class='icon-chart" + chart_type +"'></i>");
                    $item.append("<a />");

                    $item.data('sample', item.sample);
                    $item.find("a").attr('title', item.name).append(item.name);

                    $body.append($item);
                }

			}
  
    		$(win.root).find(".body .window-item").off().on('click', function() {
	    		$(this).parent().find(".window-item.select").removeClass("select");
		    	$(this).addClass('select');
	    	});

		    $(win.root).find(".foot .select-btn").off().on('click', function() {
		    	win.hide();
		    	var file = $(win.root).find(".window-item.select").data('sample');
		    	select_sample_list(file);

		    	$(btn).html("Select Sample : " + file);
		    });

     		win.show();

		}).fail(function() {
            //$(win.root).find("body").empty();
        });
	}

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

		window.coderun.componentCodeText = 	window.coderun.componentCodeText.replace(window.selected_theme_name, "chart.theme." + $("#name").val());

        $("#theme_form [name=component_code]").val(window.coderun.componentCodeText);

        $("#theme_form").submit();
	}

	var timer = null;
	function convertContent() {
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
			scrollHeight : $(window).height() - 144,
			resize: true,
			tpl: {
				row: "<tr><td style='font-size:15px;'><!= key !></td><td><!= value !></td></tr>"
			}
		});

		// 테이블 초기화
		table_2.reset();

		var lastGroup = "";

		for(var key in obj) {

			if (lastGroup != obj[key].group)
			{
				//table_2.append({ key: " " , value: "", background : 'yellow'});
				lastGroup = obj[key].group;
			}

			if(key == "colors") {

				var list = [];
				for(var i = 0, len = obj[key].value.length; i < len; i++) {
					var value = obj[key].value[i];
					if (value.indexOf("linear") > -1 || value.indexOf("radial") > -1) {
						list[i] = "<input type='text' class='input gradient-picker picker-value' style='width:200px;background:" + value + ";' data-key='" + key + "' value='" + (value || "") + "' /> ";			
					} else if (value.indexOf("pattern") > -1) {
						list[i] = "<input type='text' class='input pattern-picker picker-value' style='width:150px;background:" + value + ";' data-key='" + key + "' value='" + (value || "") + "' /> ";			
					} else {
						list[i] = "<input type='text' class='input color-picker picker-value' style='width:80px;background:" + value + ";' data-key='" + key + "' value='" + (value || "") + "' /> ";
					}

				}

				table_2.append({ key: key, value: list.join(" ") });
			} else {
				var str = obj[key].value;
				if (key.indexOf("Color") > -1)
				{
					str = "<input type='text' class='color-picker input picker-value' style='width:80px;background:" + obj[key].value + ";' data-key='" + key + "' value='" + (obj[key].value || "") + "' /> ";
				} else if (key.indexOf("FontSize") > -1) {
                    str = "<input type='text' value='"+str+"' class='font-size-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("BorderWidth") > -1) {
                    str = "<input type='text' value='"+str+"' class='border-width-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("BorderSize") > -1) {
                    str = "<input type='text' value='"+str+"' class='border-size-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("Opacity") > -1) {
                    str = "<input type='text' value='"+str+"' class='opacity-range picker-value'  data-key='" + key + "'/>";
				} else if (key.indexOf("Padding") > -1) {
                    str = "<input type='text' value='"+str+"' class='padding-range picker-value'  style='width:100%'   data-key='" + key + "'/>";
				} else if (key.indexOf("Radius") > -1) {
                    str = "<input type='text' value='"+str+"' class='radius-range picker-value'  style='width:100%'   data-key='" + key + "'/>";
				} else if (key.indexOf("DashArray") > -1) {
                    str = "<input type='text' value='"+str+"' class='dash-array input picker-value'  style='width:100%'   data-key='" + key + "'/>";
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
                    str = "<input type='text' value='"+str+"' class='input picker-value' style='width:100%'  data-key='" + key + "'/>";
				}
				table_2.append({ key: key, value: str });
			}
		}

		$(".picker-value").change(function() {
			convertContent();
		});

   		<?php include_once __DIR__."/color.picker.php" ?>

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

		convertContent();
	}

	window.savecode = function savecode() {

		var data = {
			type : 'theme',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val(),
			component_code : componentCode.getValue(),
			sample_code : sampleCode.getValue(),
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
				componentCode.setValue(data.component_code);
				sampleCode.setValue(data.sample_code);

				coderun();

				getThemeObject();


			});
		}

	}


	if ('<?php echo $_GET['id'] ?>')
	{
		view_type_list(false);
		loadContent();
	} else {
		view_type_list(true);
	}

	$("#sample_list").on('change', function() {
		var file = $(this).val();
		
		if (file == '') {
			return;
		}
		select_sample_list(file);
	});

	window.select_sample_list = function(file) {
		$.get("/sample/" + file + ".js").success(function(code) {
			code = code.replace("#chart\-content", "#embedResult");
			code = code.replace("#chart", "#embedResult");
			sampleCode.setValue(code); 	

			coderun();
		});
	}


});
</script>

<div id="theme_select_win" class="window" style="display:none;">
    <div class="head">
        <div class="left">Select Theme</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
    <div class="body" style="text-align:center">
		<div class="window-item" data-theme="jennifer" >
			<!--<img src="" width="100px" height="100px" />-->
			<i class='icon-textcolor'></i>
			<a >Jennifer</a>
		</div>
		<div class="window-item" data-theme="dark" >
			<!--<img src="" width="100px" height="100px" />-->
			<i class='icon-textcolor'></i>
			<a >Dark</a>
		</div>
		<div class="window-item" data-theme="pastel" >
			<!--<img src="" width="100px" height="100px" />-->
			<i class='icon-textcolor'></i>
			<a >Pastel</a>
		</div>
		<div class="window-item" data-theme="gradient" >
			<!--<img src="" width="100px" height="100px" />-->
			<i class='icon-textcolor'></i>
			<a >Gradient</a>
		</div>
		<div class="window-item" data-theme="pattern" >
			<!--<img src="" width="100px" height="100px" />-->
			<i class='icon-textcolor'></i>
			<a >Pattern</a>
		</div>
    </div>
	<div class="foot">
		<a class="btn select-btn">Select</a>
	</div>

</div>

<div id="sample_select_win" class="window" style="display:none;">
    <div class="head">
        <div class="left">Select Sample</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
    <div class="body" style="text-align:center">
    </div>
	<div class="foot">
		<a class="btn select-btn">Select</a>
	</div>

</div>


<?php include __DIR__."/script.php" ?>
