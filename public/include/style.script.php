<script type="text/template" id="style_template"><!= style_doc !>

@import "../theme.less";
</script>

<script type="text/javascript">
$(function() {
	var componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
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

	window.view_type_list = function view_type_list(themeName, isChange) {
		var arr = ['jennifer', 'dark'];
		var type = "";
		$component_list.empty();
		$component_list.append("<option value=''>Select Theme</option>");
		for(var i = 0, len = arr.length; i < len; i++) {
			$component_list.append("<option value='" + arr[i] + "'>" + arr[i] + "</option>");
		}


		if (isChange)
		{
			//$component_list.val("chart.theme.jennifer");
			$component_list.change();
		}

	}

	var $component_list = $("#component_list"); 


	$component_list.on('change', function(e) {
		var value = $(this).val();

		if (value.length > 0) {
            var path = value.replace(/\./g, '/');
			$.get("/sample/ui/theme/" + path + ".less").success(function(code) {
				componentCode.setValue(code); 	

				getStyleObject();
			});
		}
	});

	window.coderun = function coderun () {
		window.coderun.componentCodeText = componentCode.getValue();
		window.coderun.sampleCodeText = sampleCode.getValue();

        $("#result_form [name=component_code]").val(window.coderun.componentCodeText);
        $("#result_form [name=sample_code]").val(window.coderun.sampleCodeText);
        $("#result_form [name=sample_type]").val($("#sample_list").val());
        $("#result_form [name=name]").val($("#name").val());

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
	function convertStyle() {
		clearTimeout(timer2);

		timer2 = setTimeout(function() { 

			var style = [];

			$("#table_style tbody .picker-value").each(function() {
				var key = $(this).attr('data-key');
				var value = $(this).val();
				
				style.push([key, value + ";"].join(" : "));
			});

			var name = $("#name").val();
			var template = jui.include("util.base").template($("#style_template").html());


			var result = template({ style_doc : style.join("\r\n") });
			componentCode.setValue(result);
			componentCode.refresh();

			coderun();
			
		}, 1000);
	}

	window.setStyleObject = function setStyleObject(arr) {
		if(jui.include("util.base").browser.msie) return;

		window.table_2 = jui.create("uix.table", "#table_style", {
			fields: [ "key", "value" ],
            scroll : true,
			scrollHeight : $(".view-component").height() - 132,
			resize: true,
			tpl: {
				row: "<tr><td style='font-size:15px;'><!= key !></td><td><!= value !></td></tr>"
			}
		});

		// 테이블 초기화
		table_2.reset();

		for(var i = 0, len = arr.length; i < len; i++) {
			var item = arr[i];

			if (!item) {
				continue;
			}
			var key = item.key;
			var value = item.value; 

			var str = value; 

			if (value.indexOf("darken") > -1) {
				str = "<input type='text' value='"+str+"' class='input picker-value' style='width:100%'  data-key='" + key + "'/>";

			} else if (key.indexOf("Color") > -1) {
				str = "<input type='text' class='color-picker input picker-value' style='width:80px;background:" + value + ";' data-key='" + key + "' value='" + (value || "") + "' /> ";
			} else if (key.indexOf("FontSize") > -1) {
				str = "<input type='text' value='"+str+"' class='font-size-range picker-value'  data-key='" + key + "'/>";
			} else if (key.indexOf("BorderWidth") > -1) {
				str = "<input type='text' value='"+str+"' class='border-width-range picker-value'  data-key='" + key + "'/>";
			} else if (key.indexOf("BorderSize") > -1) {
				str = "<input type='text' value='"+str+"' class='border-size-range picker-value'  data-key='" + key + "'/>";
			} else if (key.indexOf("HandleSize") > -1) {
				str = "<input type='text' value='"+str+"' class='handle-size-range picker-value'  data-key='" + key + "'/>";
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
				str = "<input type='text' value='"+str+"' class='input picker-value' style='width:100%'  data-key='" + key + "'/>";
			}
			table_2.append({ key: key, value: str });
		}

		$(".picker-value").change(function() {
			convertStyle();
		});

        $(".color-picker").colorPicker({
			customBG: '#222',
			margin: '5px 0 0',
			doRender: 'div div',
			colorNames: {
				'191970': 'midnightblue',
				'696969': 'dimgrey',
				'708090': 'slategrey',
				'778899': 'lightslategrey',
				'800000': 'maroon',
				'800080': 'purple',
				'808000': 'olive',
				'808080': 'grey',
				'F0F8FF': 'aliceblue',
				'FAEBD7': 'antiquewhite',
				'00FFFF': 'cyan',
				'7FFFD4': 'aquamarine',
				'F0FFFF': 'azure',
				'F5F5DC': 'beige',
				'FFE4C4': 'bisque',
				'000000': 'black',
				'FFEBCD': 'blanchedalmond',
				'0000FF': 'blue',
				'8A2BE2': 'blueviolet',
				'A52A2A': 'brown',
				'DEB887': 'burlywood',
				'5F9EA0': 'cadetblue',
				'7FFF00': 'chartreuse',
				'D2691E': 'chocolate',
				'FF7F50': 'coral',
				'6495ED': 'cornflowerblue',
				'FFF8DC': 'cornsilk',
				'DC143C': 'crimson',
				'00008B': 'darkblue',
				'008B8B': 'darkcyan',
				'B8860B': 'darkgoldenrod',
				'A9A9A9': 'darkgrey',
				'006400': 'darkgreen',
				'BDB76B': 'darkkhaki',
				'8B008B': 'darkmagenta',
				'556B2F': 'darkolivegreen',
				'FF8C00': 'darkorange',
				'9932CC': 'darkorchid',
				'8B0000': 'darkred',
				'E9967A': 'darksalmon',
				'8FBC8F': 'darkseagreen',
				'483D8B': 'darkslateblue',
				'2F4F4F': 'darkslategrey',
				'00CED1': 'darkturquoise',
				'9400D3': 'darkviolet',
				'FF1493': 'deeppink',
				'00BFFF': 'deepskyblue',
				'1E90FF': 'dodgerblue',
				'B22222': 'firebrick',
				'FFFAF0': 'floralwhite',
				'228B22': 'forestgreen',
				'FF00FF': 'magenta',
				'DCDCDC': 'gainsboro',
				'F8F8FF': 'ghostwhite',
				'FFD700': 'gold',
				'DAA520': 'goldenrod',
				'008000': 'green',
				'ADFF2F': 'greenyellow',
				'F0FFF0': 'honeydew',
				'FF69B4': 'hotpink',
				'CD5C5C': 'indianred',
				'4B0082': 'indigo',
				'FFFFF0': 'ivory',
				'F0E68C': 'khaki',
				'E6E6FA': 'lavender',
				'FFF0F5': 'lavenderblush',
				'7CFC00': 'lawngreen',
				'FFFACD': 'lemonchiffon',
				'ADD8E6': 'lightblue',
				'F08080': 'lightcoral',
				'E0FFFF': 'lightcyan',
				'FAFAD2': 'lightgoldenrodyellow',
				'D3D3D3': 'lightgrey',
				'90EE90': 'lightgreen',
				'FFB6C1': 'lightpink',
				'FFA07A': 'lightsalmon',
				'20B2AA': 'lightseagreen',
				'87CEFA': 'lightskyblue',
				'B0C4DE': 'lightsteelblue',
				'FFFFE0': 'lightyellow',
				'00FF00': 'lime',
				'32CD32': 'limegreen',
				'FAF0E6': 'linen',
				'66CDAA': 'mediumaquamarine',
				'0000CD': 'mediumblue',
				'BA55D3': 'mediumorchid',
				'9370DB': 'mediumpurple',
				'3CB371': 'mediumseagreen',
				'7B68EE': 'mediumslateblue',
				'00FA9A': 'mediumspringgreen',
				'48D1CC': 'mediumturquoise',
				'C71585': 'mediumvioletred',
				'F5FFFA': 'mintcream',
				'FFE4E1': 'mistyrose',
				'FFE4B5': 'moccasin',
				'FFDEAD': 'navajowhite',
				'000080': 'navy',
				'FDF5E6': 'oldlace',
				'6B8E23': 'olivedrab',
				'FFA500': 'orange',
				'FF4500': 'orangered',
				'DA70D6': 'orchid',
				'EEE8AA': 'palegoldenrod',
				'98FB98': 'palegreen',
				'AFEEEE': 'paleturquoise',
				'DB7093': 'palevioletred',
				'FFEFD5': 'papayawhip',
				'FFDAB9': 'peachpuff',
				'CD853F': 'peru',
				'FFC0CB': 'pink',
				'DDA0DD': 'plum',
				'B0E0E6': 'powderblue',
				'FF0000': 'red',
				'BC8F8F': 'rosybrown',
				'4169E1': 'royalblue',
				'8B4513': 'saddlebrown',
				'FA8072': 'salmon',
				'F4A460': 'sandybrown',
				'2E8B57': 'seagreen',
				'FFF5EE': 'seashell',
				'A0522D': 'sienna',
				'C0C0C0': 'silver',
				'87CEEB': 'skyblue',
				'6A5ACD': 'slateblue',
				'FFFAFA': 'snow',
				'00FF7F': 'springgreen',
				'4682B4': 'steelblue',
				'D2B48C': 'tan',
				'008080': 'teal',
				'D8BFD8': 'thistle',
				'FF6347': 'tomato',
				'40E0D0': 'turquoise',
				'EE82EE': 'violet',
				'F5DEB3': 'wheat',
				'FFFFFF': 'white',
				'F5F5F5': 'whitesmoke',
				'FFFF00': 'yellow',
				'9ACD32': 'yellowgreen'
			},

			buildCallback: function($elm) {
				var that = this;

				$elm.append('<div class="cp-patch"><div></div></div><div class="cp-disp"></div>');
				$('.trigger').parent().on('click', '.trigger', function(e) {
					if (e.target === this && $(this).hasClass('active')) {
						e.cancelBubble = true;
						e.stopPropagation && e.stopPropagation();
						that.toggle();
					}
				});
				// if input type="color"
				$('.color').on('click', function(e){
					e.preventDefault && e.preventDefault();
				});
			},

			cssAddon: // could also be in a css file instead
				'.cp-patch{float:left; margin:9px 0 0;' +
					'height:24px; width: 24px; border:1px solid #aaa;}' +
				'.cp-patch{background-image: url(\'data:image/gif;base64,R0lGODlhDAAMAIABAMzMzP///yH5BAEAAAEALAAAAAAMAAwAAAIWhB+ph5ps3IMyQFBvzVRq3zmfGC5QAQA7\');}' +
				'.cp-patch div{height:24px; width: 24px;}' +
				'.cp-disp{padding:4px 0 4px 4px; margin-top:10px; font-size:12px;' +
					'height:16px; line-height:16px; color:#333;}' +
				'.cp-color-picker{border:1px solid #999; padding:8px; box-shadow:5px 5px 16px rgba(0,0,0,0.4);' +
					'background:#eee; overflow:visible; border-radius:3px;}' +
				'.cp-color-picker:after{content:""; display:block; ' +
					'position:absolute; top:-8px; left:8px; border:8px solid #eee; border-width: 0px 8px 8px;' +
					'border-color: transparent transparent #eee}' +
				// simulate border...
				'.cp-color-picker:before{content:""; display:block; ' +
					'position:absolute; top:-9px; left:8px; border:8px solid #eee; border-width: 0px 8px 8px;' +
					'border-color: transparent transparent #999}' +
				'.cp-xy-slider{border:1px solid #aaa; margin-bottom:10px; width:150px; height:150px;}' +
				'.cp-xy-slider:active {cursor:none;}' +
				'.cp-xy-cursor{width:12px; height:12px; margin:-6px}' +
				'.cp-z-slider{margin-left:8px; border:1px solid #aaa; height:150px; width:24px;}' +
				'.cp-z-cursor{border-width:5px; margin-top:-5px;}' +
				'.cp-color-picker .cp-alpha{width:152px; margin:10px 0 0; height:6px; border-radius:6px;' +
					'overflow:visible; border:1px solid #aaa; box-sizing:border-box;' +
					'background: linear-gradient(to right, rgba(238,238,238,1) 0%,rgba(238,238,238,0) 100%);}' +
				'.cp-alpha-cursor{background: #eee; border-radius: 100%;' +
					'width:14px; height:14px; margin:-5px -7px; border:1px solid #999!important;' +
					'box-shadow:inset -2px -4px 3px #ccc}' +
				'.cp-alpha:after{position:relative; content:"α"; color:#666; font-size:16px;' +
					'font-family:monospace; position:absolute; right:-26px; top:-8px}',

			renderCallback: function($elm, toggled) {
				var colors = this.color.colors,
					rgb = colors.RND.rgb;

				$('.cp-patch div').css({'background-color': $elm[0].style.backgroundColor});
				$('.cp-disp').text(this.color.options.colorNames[colors.HEX] || $elm.val());
				if (toggled === true) {
					// here you can recalculate position after showing the color picker
					// in case it doesn't fit into view.
					$('.trigger').removeClass('active');
					$elm.closest('.trigger').addClass('active');
				} else if (toggled === false) {
					$elm.closest('.trigger').removeClass('active');
				}

				convertStyle();
			}
		});

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

		convertStyle();
	}

	window.savecode = function savecode() {

		var data = {
			type : 'style',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $("#name").val(),
			description : $("#description").val(),
			license : $("#license").val(),
			component_code : componentCode.getValue(),
			sample_code : sampleCode.getValue(),
			sample_type : $("#sample_list").val(),
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
				$sample_list.append("<option value='"+ arr[i] + "'>" + arr[i].replace(".html", "") + "</option>");
			}
		});
	}
	load_sample_list();

	$("#sample_list").on('change', function() {
		var type = $(this).val();

		if ($.trim(type) == "") {
			// view all style 
			$(table_2.root).find("tbody tr").show();
		} else {
			$(table_2.root).find("tbody tr").hide();
			$(table_2.root).find("tbody [data-key*=" + type.replace(".html", "") +"]").each(function() {
				$(this).parent().parent().show();
			});			
		} 

		coderun();

	});

});
</script>