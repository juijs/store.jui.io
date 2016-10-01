
<?php include_once V2_INC.'/property.php' ?>
<?php @include_once V2_PLUGIN."/file/ui.php" ?>
<script type="text/javascript">
$(function() {

	var baseCode = window.baseCode = CodeMirror.fromTextArea($("#base_code")[0], {
	  lineNumbers : true
	});

	$(".add-file-btn").on('click', function () {
		filetree.action_new_file();
	});

	$(".add-directory-btn").on('click', function () {
		filetree.action_new_folder();
	});

	$(".file-name").on("click", function () {
		if (baseCode.file) {
			window.open('/v2/file' + baseCode.file, 'file-' + baseCode.file);
		}
	});

	// file editor mode settings 
	CodeMirror.modeURL = "/bower_components/codemirror/mode/%N/%N.js";
	window.change_edit_mode = function () {
		var val = baseCode.file, m, mode, spec;

		if (val.indexOf('.color') > -1) {
			val = val.replace('.color', '.css');
		}

		if (m = /.+\.([^.]+)$/.exec(val)) {
			var info = CodeMirror.findModeByExtension(m[1]);
			if (info) {
		        mode = info.mode;
		      spec = info.mime;
		    }
	    } else if (/\//.test(val)) {
		    var info = CodeMirror.findModeByMIME(val);
		    if (info) {
			    mode = info.mode;
		        spec = val;
	        }
		} else {
		    mode = spec = val;
		}

		if (mode) {
		    baseCode.setOption("mode", spec);
	        CodeMirror.autoLoadMode(baseCode, mode);
		}
	}

	window.save_current_file = function save_current_file( content, callback ) {
		var file = baseCode.file;

		$.post('<?php echo V2_PLUGIN_URL ?>/file/save_file.php', { file : file, content : content || baseCode.getValue() }, function (res) {
			if (callback) {
				callback();	
			}
		});	
	}

	window.is_image = function (file) {
		var ext = file.split('.').pop();
		return (ext == 'png' || ext == 'jpg' || ext == 'gif' || ext == 'bmp') ;
	}

	window.update_file_name = function (name) {
		$(".code-toolbar .file-name").html(name);
	}

	window.show_editor = function (file, relativePath) {
		if (is_image(file)) 	{		// image editor 의 경우 
			$(".code-content .editor").hide();
			$(".code-content .image-editor").show();
			$(".image-editor-menu").show();
			$(".editor-area.view-only").addClass('no-preview');
			baseCode.file = file;
			imageeditor.setImage('/v2/file' + file);
			imageeditor.render();

			load_type_tools();
			update_file_name(relativePath);
			


		} else {			// text editor 일 경우 
			$(".code-content .editor").hide();
			$(".image-editor-menu").hide();
			$(".code-content .text-editor").show();
			$(".editor-area.view-only").removeClass('no-preview');
			$.post('/v2/file' + file, function (res, result, xhr) {
				baseCode.file = file;
				baseCode.setValue(xhr.responseText);
				baseCode.refresh();
				baseCode.focus();

				load_type_tools();
				update_file_name(relativePath);

				change_edit_mode();
			});
		}

	}

	window.load_type_tools = function () {
		var $tools = $(".file-type-tools");

		$tools.html(generate_tool_buttons(get_type_tools()));
	}

	window.generate_tool_buttons = function (arr) {
		var temp = [];

		for(var i = 0, len = arr.length; i < len; i++) {
			var item = arr[i];

			if (item == 'sample') {
				temp.push($("<a class='sample-download'><i class='icon-download download-sample'></i> Sample Code</a>")[0]);
			}
		}

		return temp;
	}

	window.get_type_tools = function () {
		if (!baseCode.file) return [];

		var ext = baseCode.file.split(".").pop();

		switch(ext) {
		//	case 'ts': return ['sample'];  
		}

		return []
	}

	$(".file-type-tools").on("click", "a", function () {
		var $it = $(this);

		if ($it.hasClass('sample-download')) {
			sample_download();
		}
	});

	window.sample_download = function () {
		var ext = baseCode.file.split('.').pop();

		$.get('<?php echo V2_PLUGIN_URL?>/file/sample_list.php', { ext : ext }, function (list) {
			console.log(list);
		});
	}

	window.check_change = function (callback) {
		$.post("<?php echo V2_PLUGIN_URL ?>/file/check_change.php", { 
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
	window.savefile = function savefile() {

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

	window.commit_file = function commit_file(commit_message) {
		show_loading();
		$.post('<?php echo V2_PLUGIN_URL?>/file/commit.php',  { commit_message : commit_message, id : '<?php echo $_GET['id'] ?>' }, function (res)  {
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
	
	  window.filetree = jui.create("ui.filetree", ".file-content", {
			id : '<?php echo $_GET['id'] ?>',
			root : '/<?php echo $_GET['id'] ?>' ,
			script : '<?php echo V2_PLUGIN_URL?>/file/fileTree.php', 
			action : {
				addFile : '<?php echo V2_PLUGIN_URL ?>/file/add_file.php',
				deleteFile : '<?php echo V2_PLUGIN_URL ?>/file/delete_file.php',
				renameFile : '<?php echo V2_PLUGIN_URL ?>/file/rename_file.php',
				uploadFile : '<?php echo V2_PLUGIN_URL ?>/file/upload_file.php'
			},
			event : {
				'load.file' : function (file, relativePath) {
						show_editor(file, relativePath);	
				}
			}
	  });

	window.imageeditor = jui.create('ui.imageeditor', '.image-editor', {
		saveCallback  : function (data, file) {
			save_current_file(data, function () {
				// 데이타 저장 이후 로직 
			});
		}
	});


	  window.fileSplitter = jui.create('ui.splitter', '.type-editor-container', {
			initSize: 200,
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
			$.post("<?php echo V2_PLUGIN_URL ?>/file/show_history.php", { 
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

<?php if ($isMy) { ?>
	 $(".save-btn").on("click", function () {

        // 파일을 먼저 저장하고 
        save_current_file(null, function () {
            // 변경 체크 하고 
    		check_change(function (is_changed, changed_files) {
                // 변경 되었으면 commit 메세지를 작성한다. 
				if (is_changed) {
					show_commit_modal(changed_files);
				}
			});

        });

	 });

    $('body').on('paste', function (e) {
        var items = e.originalEvent.clipboardData.items;

        for(var i = 0, len = items.length; i < len; i++) {
            var item = items[i];
            
            if (item.type.indexOf('image/') > -1) {
		        filetree.action_new_file(item.getAsFile());
            }
        }
    });
<?php } ?>

});
</script>
