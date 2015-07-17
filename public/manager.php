<?php $page_id = 'manager'; 

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
				<div class="editor-data view-component" style="background:#ffffff">

					<div class="editor-tool" style="font-size:13px;">
						<a class="label" data-view="component">Component</a>
						<span style="padding-left:20px">Load <input type="file" accept=".js" id="component_load" style="width:200px;"/></span>


						<select id="component_list" style="float:right;margin-left:2px;" class="input">
							<option value="">Select component</option>
							<option value="">--------------</option>
						</select>
						<select id="type_list" style="float:right" class="input" onChange="view_type_list()">
							<option value="chart.brush" selected>Chart Brush</option>
							<option value="chart.theme">Chart Theme</option>
						</select>
						
					</div>

					<div id="tab_contents_1" class="tab-contents editor-codemirror">
						<textarea id="component_code"></textarea>
					</div>

					<div class="editor-statusbar">
						Component Access 
						<label><input type="radio" name="source" value="source" checked /> Source </label>
						<label><input type="radio" name="source" value="minify" /> Minify</label>

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
				<div class="editor-component view-sample" style="background:#ffffff">
					<div class="editor-tool" style="font-size:13px;">
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
						</div>
					</div>

					<div id="tab_contents_2" class="tab-contents editor-codemirror">
						<textarea id="sample_code">

var data = [
   { x : 'sample1', y : 200 } , 
   { x : 'sample2', y : 300 },
   { x : 'sample4', y : 2100 } ,
   { x : 'sample3', y : 5000 }
];
/**
	Sample Code For My component 
*/
var chart = jui.create("chart.builder", '#result', {
	axis : {
		x : { 
          type : 'block', domain : 'x'
		},
		y : {
          type : 'range', domain : 'y'
		},
		data : data 
	},
	brush : {
		type : 'line',
		target : "y"
	}
});

</textarea>
					</div>

					<div class="editor-statusbar">

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
					    <form style="padding:10px">
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
                        </form>
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
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function() {
	var componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true 
	});

	var sampleCode = CodeMirror.fromTextArea($("#sample_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true
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

	window.view_type_list = function view_type_list() {
		var type = $("#type_list").val();
		var arr = [];

		if (type == 'chart.brush')
		{
			arr = ['area','bar','bar3d','bargauge','bubble','candlestick','circlegauge','clock','clusterbar3d','clustercolumn3d','clustercylinder3d','column','column3d','cylinder3d','donut','equalizer','fillgauge','focus','fullgauge','fullstackbar','fullstackbar3d','fullstackcolumn','fullstackcolumn3d','fullstackcylinder3d','gauge','imagebar','imagecolumn','line','map.bubble','map.comparebubble','map.core','map.flightroute','map.marker','map.note','map.selector','map.weather','ohlc','path','patternbar','patterncolumn','pie','pin','rangebar','rangecolumn','scatter','scatterpath','splitarea','splitline','stackarea','stackbar','stackbar3d','stackcolumn','stackcolumn3d','stackcylinder3d','stackgauge','stackline','stackscatter','topologynode','waterfall'];


		} else if (type == 'chart.theme') 
		{
			arr = ['jennifer', 'dark', 'pastel', 'pattern', 'gradient'];
		}

		$component_list.empty();
		$component_list.append("<option value=''>Select " + type + "</option>");
		for(var i = 0, len = arr.length; i < len; i++) {
			$component_list.append("<option value='" + type + "." + arr[i] + "'>" + type + "." + arr[i] + "</option>");
		}
	}

	var $component_list = $("#component_list"); 


	$component_list.on('change', function(e) {
		var value = $(this).val();

		if (value.length > 0) {
            var path = value.replace(/\./g, '/');
			$.get("/bower_components/jui/js/" + path + ".js").success(function(code) {
				componentCode.setValue(code); 	
			}).fail(function(code){
				componentCode.setValue(code.responseText); 
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


	window.savecode = function savecode() {

		var data = {
			type : 'component',
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
			});
		}


	}

	loadContent();

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
		} else {
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

			if (view == 'component')
			{
				componentCode.refresh();
			} else if (view == 'sample' ) {
				sampleCode.refresh();
			} else if (view == 'result') {
				coderun();
			}
		}
	}


	$("a.label").css({
		'cursor' : 'pointer',
		'-webkit-user-select' : 'none'
	}).on('dblclick', viewFullscreen);

	view_type_list();


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
