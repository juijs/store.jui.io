
<?php include_once V2_INC.'/property.php' ?>
<?php @include_once V2_PLUGIN."/code/ui.php" ?>
<?php @include_once V2_PLUGIN."/code/settings.php" ?>
<script type="text/javascript">
$(function() {

	var ExternalItemTemplate = window.ExternalItemTemplate = '<span title="drag me for ordering" class="handle" draggable="true"><i class="icon-dashboardlist"></i></span><input type="text" placeholder="//myhost.com/my.js" class="input" /><a class="btn small"><i class="icon-exit"></i></a>';

	var baseCode = window.baseCode = CodeMirror.fromTextArea($("#base_code")[0], {
	  mode:  "markdown",
	  lineNumbers : true
	});

    baseCode.on('keyup', function () {
		auto_save();
	});
   
	$(".add-btn").on('click', function () {
		newFileWin.show();
	});

	var auto_save_timer = null;
	window.auto_save = function auto_save() {
		if (auto_save_timer) {
			clearTimeout(auto_save_timer);
		}

		auto_save_timer = setTimeout(function () {
			save_current_code();
		}, 1000);
	}

	window.save_current_code = function save_current_code( ) {
		var file = baseCode.file;

		$.post('<?php echo V2_PLUGIN_URL ?>/code/save_code.php', { file : file, content : baseCode.getValue() }, function (res) {
			preview_code();
		});	
	}

	window.preview_code = function preview_code() {
		$("iframe[name=chart_frame]").attr('src', '/v2/code' + baseCode.file);
	}

	$(".preview-btn").on("click", function (e) {
			preview_code();
	});

	window.is_image = function (file) {
		var ext = file.split('.').pop();
		return (ext == 'png' || ext == 'jpg' || ext == 'gif' || ext == 'bmp') ;
	}

	window.show_editor = function (file, relativePath) {
		if (is_image(file)) 	{		// image editor 의 경우 
			$(".code-content .editor").hide();
			$(".code-content .image-editor").show();
			$(".image-editor-menu").show();
			$(".editor-area.view-only").addClass('no-preview');
			baseCode.file = file;
			imageeditor.setImage('/v2/code' + file);
			imageeditor.render();

			$(".code-toolbar .file-name").html(relativePath);
			
			preview_code();			

		} else {			// text editor 일 경우 
			$(".code-content .editor").hide();
			$(".image-editor-menu").hide();
			$(".code-content .text-editor").show();
			$(".editor-area.view-only").removeClass('no-preview');
			$.post('<?php echo V2_PLUGIN_URL ?>/code/fileRead.php', { file : file } , function (res) {
				baseCode.file = file;
				baseCode.setValue(res);
				baseCode.refresh();
				baseCode.focus();

				$(".code-toolbar .file-name").html(relativePath);

				var ext = file.split(".").pop();
				var mode = ext; 

				if (mode == 'html') {
					mode = 'htmlmixed'
				} else if (mode == 'css' || mode == 'less' || mode == 'scss' || mode == 'styl') {
					mode = 'css';
				} else if (mode == 'json' || mode == 'js' || mode == 'dart' || mode == 'ts' ) { 
					mode = 'javascript';
				} else if (mode == 'markdown' || mode == 'md' ) {
					mode = 'markdown';
				} else {
					mode = 'text';
				}	

				baseCode.setOption('mode', mode);
				preview_code();
			});
		}
	}

	/*
	window.loadFile = function loadFile(file) {
		show_editor(file);
	}
	*/

	window.coderun = function coderun () {
		if (base_coderun_timer) {
			clearTimeout(base_coderun_timer);
		}
	}

	window.getCodeSettingsView = function getCodeSettingsView() {
		//return JSON.stringify(codeSettings.getValue());
		return "";
	}

	window.getCodeView = function getCodeView() {
		return JSON.stringify($(".code-items li").map(function() {
			return $(this).data();
		}).toArray());
	}

	window.check_change = function (callback) {
		$.post("<?php echo V2_PLUGIN_URL ?>/code/check_change.php", { 
			id : '<?php echo $_GET['id'] ?>'
		}, function (res) {
			if (res.result)
			{
				callback(true, res.changed_files);
				return;
			}
			callback(false);
		});

	};

<?php if ($isMy && !$is_viewer) { ?>
	window.savecode = function savecode() {

		show_loading("Saving...");
		
		var data = {
			type : '<?php echo $type ?>',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val()
		}

		$.post("/v2/save.php", data, function(res) {
			hide_loading();

			if (res.result)
			{
				location.href = '?id=' + res.id; 	
			} else {
				alert(res.message ? res.message : 'Failed to save');
			}
		});

	}

	window.commit_code = function commit_code(commit_message) {
		show_loading();
		$.post('<?php echo V2_PLUGIN_URL?>/code/commit.php',  { commit_message : commit_message, id : '<?php echo $_GET['id'] ?>' }, function (res)  {
			hide_loading();
			if (res.result)
			{
				// 커밋하면 새로고침한다. 
				location.reload();
				//close_commit_modal();
			} else {
				alert(res.message);
			}

		});
	}
<?php } ?>
	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
                $("[value=" + data.access + "]").attr('checked', true);
				$("#title").val(data.title);
				$("#name").val(data.name);
				$("#description").val(data.description);
				$("#license").val(data.license || "None");
			});
		} else {
		}


	}

	loadContent();

	
	$("#library").click(function() {
		fileListWin.show();
	});

	   window.previewSplitter = jui.create('ui.splitter', '.editor-area', {
		   initSize: '70%',
		   items : [ '.editor-left', '.editor-right' ]
	   });
	

	  window.filetree = jui.create("ui.filetree", ".file-content", {
			id : '<?php echo $_GET['id'] ?>',
			root : '/<?php echo $_GET['id'] ?>' ,
			script : '<?php echo V2_PLUGIN_URL?>/code/fileTree.php', 
			action : {
				addFile : '<?php echo V2_PLUGIN_URL ?>/code/add_file.php',
				renameFile : '<?php echo V2_PLUGIN_URL ?>/code/rename_file.php',
				uploadFile : '<?php echo V2_PLUGIN_URL ?>/code/upload_file.php'
			},
			event : {
				'load.file' : function (file, relativePath) {
						show_editor(file, relativePath);	
				}
			}
	  });

	  window.imageeditor = jui.create('ui.imageeditor', '.image-editor');


	  window.fileSplitter = jui.create('ui.splitter', '.type-editor-container', {
			initSize: '30%',
			items : ['.file-container', '.code-container']
	  });

	  window.historyView = jui.create('ui.historyview', '.history-viewer');

	 $(".show-history.toolbar-button").on("click", function () {
		 $(this).toggleClass('on');

		 if ($(this).hasClass('on')) {
			 showHistory(true);
		 } else {
			historyView.hide();
		 }
	 });
	
	 window.showHistory = function (isFile) {
			var file = filetree.getValue();
			$.post("<?php echo V2_PLUGIN_URL ?>/code/show_history.php", { 
				id : '<?php echo $_GET['id'] ?>',  
				file : file, 
				isFile : (isFile || false) 
			}, function (res) {
					if (res.result)
					{
						if (res.logs.length > 0) {
							historyView.update(res.logs, true);
							historyView.show();
						} else {
							historyView.show();
						}
					}
			});
	 }

<?php if ($isMy && !$is_viewer) { ?>
	 $("#commit-btn").on("click", function () {
			check_change(function (is_changed, changed_files) {
				if (is_changed) {
					show_commit_modal(changed_files);
				} else {
					alert("No Changes");	
				}
			});
	 });
<?php } ?>

});
</script>
