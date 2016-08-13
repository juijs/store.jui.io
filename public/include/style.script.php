<script type="text/template" id="style_template"><!= style_doc !>

@import "../theme.less";
</script>

<script type="text/javascript">
$(function() {
	var componentCode = window.componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "css",
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

				getStyleObject();
			}

			reader.readAsText(blob, "utf-8");
		}

	});


	var $component_list = $("#component_list"); 

	window.view_type_list = function view_type_list(themeName, isChange) {
		if (isChange)
		{
			select_theme_list(themeName || "jennifer");
		}
	}

	window.select_theme_list = function(value) {
		var path = value.replace(/\./g, '/');
		$.get("/jui-all/jui/less/theme/" + path + ".less").success(function(code) {

			$.get("/jui-all/jui-grid/less/theme/" + path + ".less").success(function(code2) {

				$("#theme_name").val(path);

				componentCode.setValue(code + "\r\n" + code2); 	

				getStyleObject();

				coderun();
			});
		});
	}


	window.select_theme = function(btn) {
		var win = jui.create('ui.window', "#theme_select_win", {
			width: 280,
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



	window.coderun = function coderun () {
		window.coderun.componentCodeText = componentCode.getValue();
		window.coderun.sampleCodeText = sampleCode.getValue();

        $("#result_form [name=component_code]").val(window.coderun.componentCodeText);
        $("#result_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#result_form [name=sample_type]").val($("#sample_list").val());
        $("#result_form [name=name]").val($("#name").val());
        $("#result_form [name=theme_name]").val($("#theme_name").val());

        $("#result_form").submit();
	}

	window.getStyleObject = function getStyleObject () {
		var text = componentCode.getValue();

		var list = text.split("\n");
		var arr = [];
		for(var i = 0, len = list.length; i < len; i++) {
			var item = list[i];

			if ($.trim(item) != "" && item.indexOf(":") > -1) {
				var temp = item.split(":");

				var key = temp.shift();
				var value = $.trim(temp.join(":"));

				if (value.lastIndexOf(";") == value.length - 1)
				{
					value = value.substr(0, value.length-1)
				}

				arr.push({
					key : key,
					value : value 
				});
			}
		}

		setStyleObject(arr);
	}

	var timer2 = null;
	function convertContent() {
		clearTimeout(timer2);

		timer2 = setTimeout(function() { 

			var style = [];

			$(".property-item .picker-value").each(function() {
				var key = $.trim($(this).attr('data-key'));
				var postfix = $(this).attr('data-postfix') || "";
				var value = $(this).val();
				
				style.push([key, value + postfix + ";"].join(" : "));
			});

			var name = $("#name").val();
			var template = jui.include("util.base").template($("#style_template").html());

			//console.log( style.join("\r\n"));

			var result = template({ style_doc : style.join("\r\n") });
			componentCode.setValue(result);
			componentCode.refresh();


			coderun();
			
		}, 1000);
	}

	window.rp = function (value, postfix) {
		postfix = postfix || "px";
		return value.replace(postfix, "");
	}

	function getKeyStringColor(key, keyString) {
		return "<span class='key-str'>" + keyString + "</span><span class='key-name'>" + key.split(keyString)[1] + "</span>";
	}

	window.setStyleObject = function setStyleObject(arr) {
		if(jui.include("util.base").browser.msie) return;

		var $p = $(".property").empty();

		/*
		window.table_2 = jui.create("uix.table", "#table_style", {
			fields: [ "key", "value" ],
            scroll : true,
			scrollHeight : $(".view-component").height() - 84,
			resize: true,
			tpl: {
				row: "<tr><td style='font-size:15px;'><!= key !></td><td><!= value !></td></tr>"
			}
		});

		// 테이블 초기화
		table_2.reset();
		*/

		var keyString = '';
		var keyList = [];
		for(var i = 0, len = arr.length; i < len; i++) {
			var item = arr[i];

			if (!item) {
				continue;
			}
			var key = item.key;
			var value = item.value; 
			var keyStr = key.split(/[A-Z]/)[0];

			if (keyString != keyStr)
			{
				$p.append("<div class='property-header' id='"+keyStr+"'>" + keyStr+"</div>");
				keyString = keyStr;
				keyList.push(keyString);
			}

			var str = value; 

			if (value.indexOf("darken") > -1 || value.indexOf('solid') > -1) {
				str = "<input type='text' value='"+str+"' class='input picker-value' style='width:100%'  data-key='" + key + "'/>";

			} else if (key.indexOf("Color") > -1) {
				str = "<input type='text' class='color-picker input picker-value' style='width:80px;background:" + value + ";' data-key='" + key + "' value='" + (value || "") + "' /> ";
			} else if (key.indexOf("FontSize") > -1) {
				str = "<input type='text' value='"+rp(str)+"' class='font-size-range picker-value'  data-key='" + key + "' data-postfix='px' />";
			} else if (key.indexOf("BorderWidth") > -1) {
				str = "<input type='text' value='"+rp(str)+"' class='border-width-range picker-value'  data-key='" + key + "' data-postfix='px' />";
			} else if (key.indexOf("BorderSize") > -1) {
				str = "<input type='text' value='"+rp(str)+"' class='border-size-range picker-value'  data-key='" + key + "' data-postfix='px' />";
			} else if (key.indexOf("HandleSize") > -1) {
				str = "<input type='text' value='"+rp(str)+"' class='handle-size-range picker-value'  data-key='" + key + "' data-postfix='px' />";
			} else if (key.indexOf("Opacity") > -1) {
				str = "<input type='text' value='"+str+"' class='opacity-range picker-value'  data-key='" + key + "'/>";
			} else if (key.indexOf("Padding") > -1) {
				str = "<input type='text' value='"+rp(str)+"' class='padding-range picker-value'  data-key='" + key + "' data-postfix='px' />";
			} else if (key.indexOf("Radius") > -1) {
				str = "<input type='text' value='"+rp(str)+"' class='radius-range picker-value'  data-key='" + key + "' data-postfix='px' />";
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
				str = "<input type='text' value='"+str+"' class='input picker-value' style='width:100%'  data-key='" + key + "'/>";
			}
			//table_2.append({ key: key, value: str });

			
			$p.append("<div class='property-item'><div class='property-key'>"  + getKeyStringColor(key, keyString) + "</div> <div class='property-value'>" +  str + "</div></div>");
		}

		var $keyList = $("#key-list").empty();
		keyList.sort();
		for(var i = 0, len = keyList.length; i < len; i++) {
			$keyList.append("<option value='"  + keyList[i] + "'>"  + keyList[i] + "</option>");
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

        $(".handle-size-range").ionRangeSlider({
            min: 0,
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

        $(".darken-range").ionRangeSlider({
            min: 0,
            max : 100,
            step : 0.5,
            hide_min_max : true,
			postfix : "%"
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
			type : 'style',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val(),
			component_code : componentCode.getValue(),
			sample_code : sampleCode.getValue(),
			sample_type : $("#sample_list").val(),
			theme_name : $("#theme_name").val(),
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

			// 기본 속성 모두 로드 

			$.get('/read.php', { id : id }, function(data) {
                $("[value=" + data.access + "]").attr('checked', true);
				$("#title").val(data.title);
				$("#name").val(data.name);
				$("#description").val(data.description);
				$("#license").val(data.license || "None");
				componentCode.setValue(data.component_code);
				sampleCode.setValue(data.sample_code);
				$("#sample_list").val(data.sample_type);
				$("#theme_name").val(data.theme_name);

				coderun();

				getStyleObject();


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
		$.get("sample.ui.php").success(function(arr) {
			for(var i = 0, len = arr.length; i < len; i++) {
				$sample_list.append("<option value='"+ arr[i] + "'>" + arr[i] + "</option>");
			}
		});
	}
	load_sample_list();

	$("#sample_list").on('change', function() {
		var type = $(this).val();

		coderun();

	});

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

<div id="theme_select_win" class="window" style="display:none;">
    <div class="head">
        <div class="left">Select UI Theme</div>
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
    </div>
	<div class="foot">
		<a class="btn select-btn">Select</a>
	</div>

</div>


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
						<input type="hidden" id="theme_name" name="theme_name" value="" />
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
