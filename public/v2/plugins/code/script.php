
<?php include_once V2_INC.'/property.php' ?>
<?php @include_once V2_PLUGIN."/code/ui.php" ?>
<?php @include_once V2_PLUGIN."/code/settings.php" ?>
<?php 
$id = $_GET['id'];
$dir = REPOSITORY.'/'.$id. '/';

use Cz\Git\GitRepository;
$repo = new GitRepository($dir);

$branches = $repo->getBranches();
$current_branch = $repo->getCurrentBranchName();

$branch_list = array();
foreach($branches as $branch) {

    if (strpos($branch, 'HEAD') !== false) {
        continue;
    }

    if (strpos($branch, 'remotes/') !== false) {
        $arr = explode("/", $branch);

        $branch_type = array_shift($arr);
        $origin = array_shift($arr);
        $branch_name = implode("/", $arr);

        $branch_list[] = array(
            'value' => $branch_name,
            'text' => $branch_name,
            'type' => 'remote',
            'origin' => $origin,
            'selected' => ($branch_name == $current_branch)
        );

    }
}

if (sizeof ($branch_list) == 0) {
    $branches = $repo->getLocalBranches();
    $branch_list = array();
    foreach($branches as $branch) {

        $branch_list[] = array(
            'value' => $branch,
            'text' => $branch,
            'type' => 'local',
            'selected' => ($branch == $current_branch)
        );
    }
   
}


?>
<script type="text/javascript">
$(function() {

	var baseCode = window.baseCode = CodeMirror.fromTextArea($("#base_code")[0], {
	  lineNumbers : true,
	  colorpicker : {
		mode : 'edit'
	  }
	});

    baseCode.on('keyup', function () {
		auto_save();
	});
   
	$(".add-file-btn").on('click', function () {
		filetree.action_new_file();
	});

	$(".add-directory-btn").on('click', function () {
		filetree.action_new_folder();
	});

	$(".file-name").on("click", function () {
		if (baseCode.file) {
			window.open('/v2/code' + baseCode.file, 'file-' + baseCode.file);
		}
	});

	// code editor mode settings 
	CodeMirror.modeURL = "/bower_components/codemirror/mode/%N/%N.js";
	window.change_edit_mode = function () {
		var val = baseCode.file, m, mode, spec;

		if (val.indexOf('.color') > -1) {
			val = val.replace('.color', '.css');
		} else if (val.indexOf('.tex') > -1) {
            val = val.replace('.tex', '.stex');
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

    window.update_branch = function update_branch() {

		show_loading("Updating codes...");
        $.post('<?php echo V2_PLUGIN_URL ?>/code/update_branch.php', { id : '<?php echo $_GET['id'] ?>' }, function (res) {
            alert('Updated codes');
            location.reload();
        });
    }

    window.change_branch = function change_branch(branch_name) {
        $.post('<?php echo V2_PLUGIN_URL ?>/code/change_branch.php', { id : '<?php echo $_GET['id'] ?>', branch_name : branch_name }, function (res) {
            if (res.result) {
                alert('Checkout ' + branch_name + ' branch');
                location.reload();
            } else {
                alert(res.message);
            }
        });
    }

    window.branches  = jui.create('ui.select', '.branches', {
            items : <?php echo json_encode($branch_list) ?> ,
            event : {
                change : function (value) {
                    change_branch(value);
                }
            }
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

	window.save_current_code = function save_current_code( content, callback ) {
		var file = baseCode.file;

		if (!file)
		{
			return;
		}
        
        if (file.indexOf('.ipynb') > -1) {
            return;
        }

		$.post('<?php echo V2_PLUGIN_URL ?>/code/save_code.php', { file : file, content : content || baseCode.getValue() }, function (res) {
			if (callback) {
				callback();	
			} else {
				preview_code();
			}
		});	
	}

	window.preview_timer = null;
	window.preview_code = function preview_code() {
		if (preview_timer) {
			clearTimeout(preview_timer);
		}

		preview_timer = setTimeout(function() {

            if (baseCode.file.indexOf('.ipynb') > -1) {

			    $("iframe[name=chart_frame]").attr('src', 'https://store.jui.io:8888/notebooks' + baseCode.file);
            } else {

			    $("iframe[name=chart_frame]").attr('src', '/v2/code' + baseCode.file + '?t=' + (+new Date()));
            }

		}, 1000);
	}

	$(".preview-btn").on("click", function (e) {
			preview_code();
	});

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
            baseCode.relativePath = relativePath;
			imageeditor.setImage('/v2/code' + file);
			imageeditor.render();

			update_file_name(relativePath);
			
			load_type_tools();
			//preview_code();			

            fileSplitter.setShow(1);
			previewSplitter.setHide(1);
		} else {			// text editor 일 경우 
			$(".code-content .editor").hide();
			$(".image-editor-menu").hide();
			$(".code-content .text-editor").show();
			$(".editor-area.view-only").removeClass('no-preview');

            $.post('<?php echo V2_PLUGIN_URL ?>/code/fileRead.php', { file : file } , function (res) {
                baseCode.file = file;
                baseCode.relativePath = relativePath;
                baseCode.setValue(res.replace(/http\:\/\//g, '//'));
                baseCode.refresh();
                baseCode.focus();
				baseCode.clearHistory();		// 로드 이후에  history 를 없애야 마지막 시점(공백)으로 돌아가지 않는다. 

                load_type_tools();
                update_file_name(relativePath);

                change_edit_mode();
                preview_code();
            });
            fileSplitter.setShow(1);
            previewSplitter.setShow(1);
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
			} else if (item == 'gist') { 
				temp.push($("<a class='store-gist'><i class='icon-link'></i></a>")[0]);
				temp.push($("<input type='text' class='gist-input' value='<script src=\"http://store.jui.io/v2/gist"+baseCode.file+"\" > <\/script>'>").hide()[0]);
			} else if (item == 'startpage') {
                temp.push($("<a class='startpage'><i class='icon-checkbox'></i> Start Page</a>")[0]);
            }
		}

		return temp;
	}

	window.get_type_tools = function () {
		if (!baseCode.file) return [];

		var ext = baseCode.file.split(".").pop();

		var list = ['gist', 'startpage'];

		switch(ext) {
		//	case 'ts': return ['sample'];  
		}

		return list
	}

	$(".file-type-tools").on("click", "a", function (e) {

		e.preventDefault();

		var $it = $(this);

		if ($it.hasClass('sample-download')) {
			sample_download();
		} else if ($it.hasClass('store-gist')) {
			store_gist();
        } else if ($it.hasClass('startpage')) {
            set_start_page();
    
		}
	});

	window.sample_download = function () {
		var ext = baseCode.file.split('.').pop();

		$.get('<?php echo V2_PLUGIN_URL?>/code/sample_list.php', { ext : ext }, function (list) {
			console.log(list);
		});
	}

	window.store_gist = function () {
        $(".gist-input").toggle();
	}

    window.set_start_page = function () {
        savecode(baseCode.relativePath);
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

	/*
	window.getCodeSettingsView = function getCodeSettingsView() {
		//return JSON.stringify(codeSettings.getValue());
		return "";
	}

	window.getCodeView = function getCodeView() {
		return JSON.stringify($(".code-items li").map(function() {
			return $(this).data();
		}).toArray());
}
	*/

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

    $('.branch-title').on('click', function () {
        update_branch();
    });

	window.savecode = function savecode(start_page) {
		show_loading("Saving...");
		
		var data = {
			type : '<?php echo $type ?>',
			id : '<?php echo $_GET['id'] ?>',
            access : $("[name=access]:checked").val(),
			title : $("#title").val(),
			name : $.trim($("#name").val()),
			description : $("#description").val(),
			license : $("#license").val(),
            start_page : start_page || ''
		}

		$.post("/v2/save.php", data, function(res) {
			hide_loading();

			if (res.result)
			{
				//location.href = '?id=' + res.id; 	
			} else {
				alert(res.message ? res.message : 'Failed to save');
			}
		});

	}

	window.deletecode = function deletecode () {
		if (confirm("Delete this code project?\r\ngood count is also deleted.")) {
			$.post("/v2/delete.php", { id : '<?php echo $_GET['id'] ?>' }, function(res) {
				if (res.result) {
					location.href = '/v2/dashboard.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}

	window.commit_code = function commit_code(commit_message) {
		show_loading();
		$.post('<?php echo V2_PLUGIN_URL?>/code/commit.php',  { commit_message : commit_message, id : '<?php echo $_GET['id'] ?>' }, function (res)  {
			hide_loading();
			if (res.result)
			{
				// 커밋하면 새로고침한다. 
				//location.reload();
				close_commit_modal();
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

                if (data.support_notebook) {
                    fileSplitter.setHide(1);
                    previewSplitter.setInitSize(250);
                    // 기본 preview size 설정 
                }
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
				deleteFile : '<?php echo V2_PLUGIN_URL ?>/code/delete_file.php',
				renameFile : '<?php echo V2_PLUGIN_URL ?>/code/rename_file.php',
				uploadFile : '<?php echo V2_PLUGIN_URL ?>/code/upload_file.php'
			},
			event : {
				'load.file' : function (file, relativePath) {
						show_editor(file, relativePath);	
				}
			}
	  });

	window.imageeditor = jui.create('ui.imageeditor', '.image-editor', {
		saveCallback  : function (data, file) {
			save_current_code(data, function () {
				// 데이타 저장 이후 로직 
			});
		}
	});


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

    $(".notebook-btn").on('click', function () {
        previewSplitter.setHide(0);
		$("iframe[name=chart_frame]").attr('src', 'https://store.jui.io:8888/notebooks/<?php echo $_GET['id'] ?>/');
    });
<?php } ?>

});
</script>
