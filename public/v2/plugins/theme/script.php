<?php include_once V2_INC.'/property.php' ?>

<script type="text/template" id="theme_template">jui.define("chart.theme.<!= name !>", [], function() {
	return <!= json !>;
});
</script>

<?php @include_once V2_PLUGIN."/theme/settings.php" ?>

<script type="text/javascript">

jui.define("property.parser.charttheme", ['jquery'], function ($) {
	var ChartTheme = function () {

		var items = [];

		this.parseItem = function (item) {
			var key = item.key;
			var value = item.value;

			var arr = key.replace(/([A-Z])/g, ' $1').split(" ");
			arr.shift();
			item.title = arr.join(" ");

			if (key == 'colors') {
				item.type = 'colors'; 
			} else if (key.indexOf("Color") > -1) {

				if (value && (value.indexOf("linear") > -1 || value.indexOf("radial") > -1)) {
					item.type = 'text';
				} else {
					item.type = 'color';
				}

			} else if (key.indexOf("FontSize") > -1) {
				item.type = 'range'; 
			} else if (key.indexOf("BorderWidth") > -1) {
				item.type = 'range'; 
				item.max = 10; 
			} else if (key.indexOf("BorderSize") > -1) {
				item.type = 'range'; 
				item.max = 20;
			} else if (key.indexOf("Opacity") > -1) {
				item.type = 'range'; 
				item.max = 1; 
				item.step = 0.05;
			} else if (key.indexOf("Padding") > -1) {
				item.type = 'range'; 
				item.max = 20; 
			} else if (key.indexOf("Radius") > -1) {
				item.type = 'range'; 
				item.max = 30;
				item.step = 0.5;
			} else if (key.indexOf("DashArray") > -1) {
				item.type = 'text';
			} else if (key.indexOf("FontWeight") > -1) {
				item.type = 'select';
				item.items = [
					'normal',
					'bold',
					'bolder',
					'lighter',
					'100',
					'200',
					'300',
					'400',
					'500',
					'600',
					'700',
					'800',
					'900',
				];
			} else {
				item.type = 'text'; 
			}

			return item; 

		}

		this.parse = function (obj) {


			var keys = Object.keys(obj);

			var arr = [];
			for(var i = 0, len = keys.length; i < len; i++) {
				var key = keys[i];
				var value = obj[key];

					arr.push(this.parseItem({
						key : key,
						value : value 
					}));
			}

			return arr; 
		}

		this.grouping = function (arr) {
			var data = [];

			var keyString = '';
			for(var i = 0, len = arr.length; i < len; i++) {
				var item = arr[i];

				if (!item) {
					continue;
				}
				var key = item.key;
				var keyStr = key.split(/[A-Z]/)[0];

				if (keyString != keyStr)
				{
					data.push({ type : 'group', title : keyStr  });
					keyString = keyStr;
				}

				data.push(item);

			}

			return data; 

		}

		this.items = function () {
			return items; 
		}

		this.load = function (obj) {
			items = this.grouping(this.parse(obj)); 
		}

		this.generate = function (values) {
			values = values || {}; 
			var data = {};

			for(var i = 0, len = items.length; i < len; i++) {
				var it = items[i];

				if (!it) continue;

				var key = it.key; 
				if (key == '') continue;
				if (it.type == 'group') continue;

				var value = values[key] || it.value;

				data[key] = value;
			}

			return data;
		}

	};

	return ChartTheme; 
});



$(function() {

	var ExternalItemTemplate = '<span title="drag me for ordering" class="handle" draggable="true"><i class="icon-dashboardlist"></i></span><input type="text" placeholder="//myhost.com/my.js" class="input" /><a class="btn small"><i class="icon-exit"></i></a>';

	var componentCode = $("#component_code");

	componentCode.setValue = function(str) {
		componentCode.val(str);
	}

	componentCode.getValue = function() {
		return componentCode.val();
	}

	var sampleCode = $("#sample_code");

	sampleCode.setValue = function(str) {
		sampleCode.val(str);
	}

	sampleCode.getValue = function() {
		return sampleCode.val();
	}

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

		var ChartTheme = jui.include("property.parser.charttheme");

		window.chartTheme = new ChartTheme();

	
		// chart theme settings 
		window.themeSettings = jui.create("ui.property", '.theme-property', {
			event : {
				change : function (item, newValue, oldValue) {
					coderun();
				}
			}
		});

		window.setThemeObject = function setThemeObject(obj, isRun) {
			themeSettings.setValue(obj);

			if (isRun) {
				coderun();
			}
		}


		window.propertyCategory  = jui.create('ui.select', '.property-category', {
				items : [
					{ value : '', text : 'Default' } 	
				] ,
				event : {
					change : function () {
						var id = this.getValue();
						themeSettings.expanded(id);
						location.href='#' + id;
					}
				}
		});

		themeSettings.on('load.items', function () {
			var groupList = themeSettings.getGroupList();

			var data = [] 
			for(var i = 0, len = groupList.length; i < len; i++) {
				var it = groupList[i];
				data[i] = { value : it.id, text :  it.name } ;
			}
			propertyCategory.update(data);


		});


		window.loadTheme = function (obj) {
				chartTheme.load( obj);
				themeSettings.loadItems(chartTheme.items());
				if (typeof coderun == 'function')
				{
					coderun();
				}
		}


		window.themeList  = jui.create('ui.select', '.theme-list', {
				items : [
					{ value : '', text : 'THEME' } ,
					{ value : 'jennifer', text : 'Jennifer' } ,
					{ value : 'dark', text : 'Dark' } ,
					{ value : 'pastel', text : 'Pastel' } ,
					{ value : 'pattern', text : 'Pattern' },
					{ value : 'gradient', text : 'Gradient' } 
				] ,
				event : {
					change : function (value) {
						var obj = jui.include("chart.theme." + value);
						loadTheme(obj);
					}
				}
		});
		themeList.setValue('jennifer');

		window.sampleList = jui.create('ui.select', '.sample-list', {
				align: 'right', 
				event : {
					change : function (value) {
						coderun();
					}
				}
		});

		// select sample 
		window.load_sample = function(btn) {

				var data = [ { value : '', text : 'SELECT SAMPLE' } ];

			$.get("/sample.php").success(function(arr) {

				for(var i = 0, len = arr.length; i < len; i++) {
					var item = arr[i];

					if (typeof item == 'string') {
						var path = item.replace(".js", "");
						item = { sample : path, name : path, children : [] };
					}

					if (item.type != 'group')
					{
						data.push({ text : item.sample, value : item.name } );
					}
				}

				sampleList.update(data);
			}).fail(function() {
				//$(win.root).find("body").empty();
			});
		}

		load_sample();


		window.coderun = function coderun () {

			convertContent();

			window.coderun.componentCodeText = componentCode.getValue();

			$("#chart_form [name=component_code]").val(window.coderun.componentCodeText);
			$("#chart_form [name=sample_code]").val(sampleList.getValue());

			$("#chart_form").submit();
		}

		
		function convertContent() {
				var name =  "CustomeTheme";

				var template = jui.include("util.base").template($("#theme_template").html());
				var str = JSON.stringify(themeSettings.getValue(), null, 4);

				str = str.replace(/\n/g, "\n    ");

				componentCode.setValue(template({ name : name, json : str }));
		}

		window.savecode = function savecode() {

			convertContent();

			var componentCodeText = componentCode.getValue();
		
			var data = {
				type : 'theme',
				id : '<?php echo $_GET['id'] ?>',
				access : $("[name=access]:checked").val(),
				title : $("#title").val(),
				name : $.trim(name),
				description : $("#description").val(),
				license : $("#license").val(),
				component_code : componentCodeText,
				sample_code : sampleList.getValue()
			}


			$.post("/v2/save.php", data, function(res) {
				
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


		function loadContent() {
			var id = '<?php echo $_GET['id'] ?>';
			//themeList.setValue('jennifer');

			if (id){
				$.get('/read.php', { id : id }, function(data) {
					  $("[value=" + data.access + "]").attr('checked', true);
					$("#title").val(data.title);
					$("#name").val(data.name);
					$("#description").val(data.description);
					$("#license").val(data.license || "None");

					componentCode.setValue(data.component_code);
					sampleList.setValue(data.sample_code);
				});
			} else {
				//themeList.setValue('jennifer');
				//$("#theme-list").val("jennifer").change();
			}


		}

		loadContent();


	   window.previewSplitter = jui.create('ui.splitter', '.editor-area', {
		   initSize: '50%',
		   items : [ '.editor-left', '.editor-right' ]
	   });

});
</script>
