<!--- ui file tree --> 

<!-- Template -->
<div class="menu" id="filetree-menu">
	<div class="items">
		<a class="item" data-action="new_folder">New Folder</a>
		<a class="item" data-action="new_file">New File</a>
		<a class="item" data-action="move_to">Move To</a>
		<a class="item" data-action="rename_input">Rename</a>
		<a class="item" data-action="delete_file">Delete</a>
	</div>
</div>

<div id="new-folder-win" class='window' style='display:none'>
    <div class="head">
        <div class="left"><i class='icon-doc'></i> Make a folder</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
	<div class="body" style="padding:10px;">
		<div style="position:relative;width:100%;height:100%">
			<div>
				<label style="width:120px;display:inline-block;">Parent</label>
				<span class="parent-name"></span>
			</div>
			<div>
				<label style="width:120px;display:inline-block;">Directory Name</label>
				<div style="display:inline-block;width:200px;">
					<input type="text" name="folder-name"  style="width:100%"/>
				</div>
				
			</div>
		</div>
	</div>
	<div class="foot" style="text-align:right;padding:15px;box-sizing:border-box;">
		<a href="#" class="button close">Close</a>
		<a href="#" class="button active apply">Apply</a>
	</div>
</div>

<div id="new-file-win" class='window' style='display:none'>
    <div class="head">
        <div class="left"><i class='icon-doc'></i> Make a  file</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
	<div class="body" style="padding:10px;">
		<div style="position:relative;width:100%;height:100%">
			<div>
				<label style="width:80px;display:inline-block;">Parent</label>
				<span class="parent-name"></span>
			</div>
			<div>
				<label style="width:80px;display:inline-block;">File Name</label>
				<input type="text" name="file-name" />
			</div>
		</div>
	</div>
	<div class="foot" style="text-align:right;padding:15px;box-sizing:border-box;">
		<a href="#" class="button close">Close</a>
		<a href="#" class="button active apply">Apply</a>
	</div>
</div>
<div id="new-blob-win" class='window' style='display:none'>
    <div class="head">
        <div class="left"><i class='icon-doc'></i> Make a  file</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
	<div class="body" style="padding:10px;">
		<div style="position:relative;width:100%;height:100%">
			<div>
				<label style="width:80px;display:inline-block;">Parent</label>
				<span class="parent-name"></span>
			</div>
			<div>
				<label style="width:80px;display:inline-block;">File Name</label>
				<input type="text" name="file-name"  style='width:380px;' />
			</div>
            <div style='height:200px;border:1px solid #ececec;margin-top:10px;'>
                <img src="" style="max-width:100%;max-height:100%;" />
            </div>
		</div>
	</div>
	<div class="foot" style="text-align:right;padding:15px;box-sizing:border-box;">
		<a href="#" class="button close">Close</a>
		<a href="#" class="button active apply">Apply</a>
	</div>
</div>

<div id="move-to-win" class='window' style='display:none'>
    <div class="head">
        <div class="left">Pick a folder</div>
        <div class="right">
            <a href="#" class="close"><i class="icon-exit"></i></a>
        </div>
    </div>
	<div class="body" style="padding:10px;">
		<div style="position:relative;width:100%;height:100%">
				<div class="items">
					<div class="item selected">Project Root</div>
				</div>
		</div>
	</div>
	<div class="foot" style="text-align:right;padding:15px;box-sizing:border-box;">
		<a href="#" class="button close">Close</a>
		<a href="#" class="button active apply">Apply</a>
	</div>
</div>


<!-- Style -->
<style type="text/css">

.file-tree {
	list-style:none;
	margin:0px;
	padding:0px;
}

.file-tree li a {
	display:block;
}

.is-dragover:before {
	content: "Drop to me";
	position:absolute;
	left:30px;
	top:50%;
	-webkit-tansform:translateY(-50%);
	tansform:translateY(-50%);
	display:inline-block;
	vertical-align:middle;
	text-align:center;
	font-size:15px;
	font-weight:bold;
	text-transform:uppercase;
	color:#48cfad;
}

.is-dragover .jqueryFileTree {
	-webkit-filter:blur(1px);
	filter:blur(1px);
}


/* file tree */
UL.jqueryFileTree LI.selected>a {
	background-color: #eee;
	font-weight:300 !important;
	display:inline-block;
	box-sizing:border-box;
	//padding:0px 5px;
}

UL.jqueryFileTree LI.changed > a {
	color: #39c !important;
}

</style>

<!-- Script --> 

<script type="text/javascript">
jui.defineUI("ui.filetree", [], function () {
	var FileTree = function() {

			var self = this; 
			var selected_tree_nodes; 
			var selected_tree_file; 
			var rootId;
			var rootPath;
			var action;
			var $el;
			var $contextMenu;
			var newFolderWin, newFileWin,newBlobWin, moveToWin;
			var DIRECTORY_SEPARATOR = '/';
            var _blob;


			function normalize_for_path(path) {
					var arr = path.split(DIRECTORY_SEPARATOR);
					var result = [];
					for(var i = 0, len = arr.length; i < len; i++) {
						var it = $.trim(arr[i]);

						if (it == '.' || it == '..' || it == '')
						{
							continue;
						}

						result.push(it);
					}

					return result.join(DIRECTORY_SEPARATOR).replace(/\/\//g, DIRECTORY_SEPARATOR);
			}

			

			function select_tree_node (filepath) {

				var is_dir = filepath.lastIndexOf(DIRECTORY_SEPARATOR) == filepath.length-1;
				var path = normalize_for_path(rootPath + DIRECTORY_SEPARATOR + filepath); 

				// how to select tree node 
				var nodes = path.split(DIRECTORY_SEPARATOR);

				nodes.pop(); // 마지막 요소 삭제 

				var result = []
				for(var i = 0; i < nodes.length; i++) {
					result[i] = (i == 0) ? nodes[i] : result[i-1] + DIRECTORY_SEPARATOR + nodes[i];
				}

				result.shift();
				result.shift();
				selected_tree_nodes = result; 
				if (!is_dir) selected_tree_file = path; 
				traverse();
			}

			function add_file(filename, callback) {
				var relativePath = filename.replace(rootPath, '');
				var fullPath = filename;

				$.post(action.addFile, { id : rootId, filename : relativePath }, function (res) {
					if (res.result) {
						callback && callback ();
						select_tree_node(relativePath);	
					} else {
						alert(res.message);
					}
				});
			}

			function make_input ($a) {
					var is_file = $a.parent().hasClass('file');
					var prevPath = $a.attr('rel');
					var arr =  prevPath.split(DIRECTORY_SEPARATOR);
					var nameIndex = (is_file) ? arr.length-1 : arr.length-2;

					var name = arr[nameIndex];

					function restoreInputValue(e) {
						var $input = $(this);
						var currentFileName = $input.val();

						if ($.trim(currentFileName) == '') {
							alert('filename is empty.');
							$input.val(name).focus().select();
							return; 
						}

						arr[nameIndex] = currentFileName;
						var fullpath = arr.join(DIRECTORY_SEPARATOR);

						if ($el.find("a[rel='"+fullpath+"']").length && prevPath  != fullpath )
						{
							var text = (is_file) ? 'file' : 'directory';
							alert(text + ' is already!!');
							//$input.focus().select();
							//return;
						} else if(prevPath  != fullpath) { 
							var currentPath = arr.join(DIRECTORY_SEPARATOR);
							$a.attr('rel', currentPath);
							$a.html(currentFileName);

							rename_file(prevPath, currentFileName, currentPath);
						}

						$a.show();
						$input.remove();

					}

					var $input = $("<input type='text' />")
					.on('blur', function (e) { restoreInputValue.call(this, e); } )
					.on('keyup', function (e) {
						if (e.keyCode == 13) {
							restoreInputValue.call(this, e);
						}
					})
					.val(name);


					$a.after($input);
					$input.focus().select();
					$a.hide();
			}

			function get_selected_node () {
					var $a  = $el.find(".selected a");

					if (!$a.length)
					{
						return { type : 'directory', file : rootPath, parent : '', filename : '' };
					}

					var type = 'file';
					
					if ($a.parent().hasClass('directory')) {
						type = 'directory';
					}

					var filename = '';
					var name = $a.attr('rel').split(DIRECTORY_SEPARATOR); 
					name.shift();
					name.shift();

					if (type == 'file') {
						filename = name.pop();
					} else if (type == 'directory') {
						name.pop();
						filename = name.pop();
					}

					return {
						$a : $a, 
						$li : $a.parent(),
						file : $a.attr('rel'),
						type :  type,
						parent: name.join(DIRECTORY_SEPARATOR),
						filename : filename
					};
			}

			function hide_menu () {
				$contextMenu.hide();
			}
			
			function rename_input () {
				var $a = $el.find(".selected a");
				make_input($a);
			}

			function rename_file (prevPath, currentFileName, currentPath) {
				$.post(self.options.action.renameFile, { id : rootId, prev_file : prevPath, filename : currentFileName}, function (res) {
					self.emit("rename.file", [ currentPath, currentFileName ]);
				});
			}

			function delete_file (currentPath, $li) {
				$.post(self.options.action.deleteFile, { id : rootId, filename : currentPath }, function (res) {
					if (res.result) {
						// 파일 삭제 
						console.log($li);
						$li.remove();
						self.emit("delete.file", [ currentPath ]);
					}
				});
			}


			function select_node (data) {
				$el.find(".selected").removeClass('selected');
				data.li.addClass('selected');
				$el.data('lastFile', data);
				
			}

			function loadTree(dontTraverse) { 
				$el.data('fileTree', null).empty().off('filetreeinitiated filetreeclicked filetreeexpanded filetreecollapsed');
				
						 
				$el.on('filetreeclicked', function(e, data) {
					select_node(data);
				}).on('filetreeexpanded filetreecollapsed ', function(e, data) {
					select_node(data);

					if (dontTraverse !== true) traverse();
			   }).on('contextmenu', 'li a', function (e) {
					   e.preventDefault();
						var $a = $(this);
						var $li = $a.parent();
						var file = $a.attr('rel');

						$el.find(".selected").removeClass('selected');
						$li.addClass('selected');
						$el.data('lastFile', {li : $li, rel : file });

						$contextMenu.css({
							left: e.clientX,
							top: e.clientY + $a.height()
						}).show();
			   });

				function CheckTreeMenuSelect (e) {
						var $list = $(e.target).closest($contextMenu);

						if (!$list.length)
						{
							$contextMenu.hide();
						}
			   }

			   $('body').off('click', CheckTreeMenuSelect).on('click', CheckTreeMenuSelect);

			
				$el.fileTree({
					root: rootPath + DIRECTORY_SEPARATOR, 
					script : self.options.script,
					expandSpeed: 10,
					collapseSpeed: 10
				 }, function(file) {
					loadFile(file);
				 })

				setTimeout(function() {
					if (dontTraverse !== true) traverse();
				}, 500);

			}

			function traverse () {
				if (selected_tree_nodes && selected_tree_nodes.length > 0) { 
					var path = selected_tree_nodes[0];

					var $a = $el.find("a[rel='"+path+"/']");

					if ($a.length) {
						selected_tree_nodes.shift();
						$a.parent().addClass('collapsed');
						$a.click();
					} else { // 대상이 없다면 트리를 다시 로드하고 시작한다. 
						loadTree(true);
					}
				} else if (selected_tree_file) {

					var $a = $el.find("a[rel='"+selected_tree_file+"']");

					if ($a.length) {
						$a.click();
						selected_tree_file = null; 
					} else {
						loadTree();
						selected_tree_file = null;
					}
				} else {
                    loadTree(true);
                }
			}

            function get_time_string() {
                var list = [];
                var date = new Date();
                list.push(date.getFullYear());
                list.push(date.getMonth()+1);
                list.push(date.getDate());
                list.push(date.getHours());
                list.push(date.getMinutes());
                list.push(date.getSeconds());

                return list.join("-");
            }

			function loadFile(file) {
				self.emit("load.file", [ file, file.replace(rootPath + '/', '') ]);
			}

			function upload_files (files, callback) {

				var item = get_selected_node();

				var data = new FormData();
				data.append("id", rootId);

				if (item.type == 'file') {
					var path = rootPath + item.parent + DIRECTORY_SEPARATOR;
				} else {
					var path = item.file + DIRECTORY_SEPARATOR;
				}

				data.append("filepath", path);

				for(var i = 0, len = files.length; i < len; i++) {
					var file = files[i];
					data.append("files[]", file, file.name);
				}

				 $.ajax({
					url: action.uploadFile,
					type: 'post',
					data: data,
					dataType: 'json',
					cache: false,
					contentType: false,
					processData: false,
					complete: function() {
						console.log('complete');
					},
					success: function(data) {
						
                       callback && callback();
						if (data.result) {
							traverse();
						} else {
							alert(data.message);
						}
					},
					error: function() {
					  // Log the error, show an alert, whatever works for you
                       callback && callback();
					}
				  });
		  }



			this.init = function () {
					rootId = this.options.id;
					rootPath = this.options.root;
					action = this.options.action; 
					$el = $(this.root);

					this.initElement();
			}

			this.initContextMenu = function () {
				$contextMenu = $("#filetree-menu");

				$contextMenu.on('click', 'a', function () {
						var actionName= $(this).data('action');

						self['action_' + actionName].call(self);
				});
			}

			this.action_new_folder = function () {
				hide_menu(); 
				newFolderWin.show();
			}

			this.action_new_file = function (file) {
				hide_menu(); 
            
                // 파일 기준으로 작성한다. 
                _blob = file; 

                if (file) {
				    newBlobWin.show();
                } else {
				    newFileWin.show();
                }
			}

			this.action_move_to = function () {
				hide_menu(); 
				moveToWin.show();
			}

			this.action_rename_input = function () {
				hide_menu();
				rename_input();
			}

			this.action_delete_file = function () {
				hide_menu();
				
				if (confirm('Delete a file?')) {
					var item = get_selected_node();
					delete_file(item.file, item.$li);
				}
			}

			this.initWindow = function () {

				newFolderWin = jui.create("ui.window", "#new-folder-win", {
					width : 400,
					height : 200,
					modal : true,
					event : {
						show: function () {
							var item = get_selected_node();

							$(this.root).find(".parent-name").text(DIRECTORY_SEPARATOR + item.parent);
							$(this.root).find("input[name='folder-name']").focus().select();
						},
						apply : function() {

							var name = $(this.root).find("input[name='folder-name']").val();
							new_folder(name, this);
						}
					}
				});


				newFileWin = jui.create("ui.window", "#new-file-win", {
					width : 300,
					height : 200,
					modal : true,
					event : {
						show: function () {
							var item = get_selected_node();

							$(this.root).find(".parent-name").text(DIRECTORY_SEPARATOR + item.parent);
							$(this.root).find("input[name='file-name']").val('').focus().select();
						},
						apply : function() {
							var name = $(this.root).find("input[name='file-name']").val();
							new_file(name, this);
						}
					}
				});

                newBlobWin = jui.create("ui.window", "#new-blob-win", {
					width : 500,
					height : 400,
					modal : true,
					event : {
						show: function () {
							var item = get_selected_node();

							$(this.root).find(".parent-name").text(DIRECTORY_SEPARATOR + item.parent);
							$(this.root).find("input[name='file-name']").focus().val( get_time_string() + '.png');

                            if (_blob) {
                                var url = window.webkitURL || window.URL;
                                $(this.root).find("img").attr('src', url.createObjectURL(_blob));
                            }
						},
						apply : function() {
							var name = $(this.root).find("input[name='file-name']").val();
							new_file(name, this);
						}
					}
				});

				moveToWin = jui.create("ui.window", "#move-to-win", {
					width : 300,
					height : 200,
					modal : true,
					event : {
						apply : function() {
							this.hide();
						}
					}
				});

				$([newFolderWin.root, newFileWin.root, newBlobWin.root, moveToWin.root]).on('click', '.close', function () {
					$(this).closest('.window')[0].jui.hide();
				});

				$([newFolderWin.root, newFileWin.root, newBlobWin.root, moveToWin.root]).on('click', '.apply', function () {
					var win = $(this).closest('.window')[0].jui;
					win.emit('apply');
				});

				$([newFolderWin.root, newFileWin.root, newBlobWin.root]).on('keyup', 'input[type=text]', function (e) {
					if (e.keyCode == 13) {
						var win = $(this).closest('.window')[0].jui;
						win.emit('apply');
					}

				});

                $(newBlobWin.root).find('img').on('load', function () {
                    
                    var url = window.webkitURL || window.URL;
                    url.revokeObjectURL($(this).attr('src'));
                });
			}

			this.initElement = function () {

				this.initContextMenu();
				this.initWindow();

				loadTree();


				$el.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
					e.preventDefault();
					e.stopPropagation();
				  })
				  .on('dragover dragenter', function() {
					$el.addClass('is-dragover');
				  })
				  .on('dragleave dragend drop', function() {
					$el.removeClass('is-dragover');
				  })
				  .on('drop', function(e) {
					  upload_files(e.originalEvent.dataTransfer.files);
				  });

			}

			


			function file_exists(path) {
				return !!$el.find("a[rel='"+path+"']").length;
			}

			function new_folder(name, win) {
				var item = get_selected_node();

				if (item.type == 'file') {
					var path = rootPath + item.parent + DIRECTORY_SEPARATOR + name;
				} else {
					var path = item.file + name;
				}



				if (file_exists(path + DIRECTORY_SEPARATOR)) {
					alert('directory is already exists' );
					return;
				}

				 add_file(path + DIRECTORY_SEPARATOR, function () {
					 win && win.hide();
					 self.emit("success.add.file");
				 });

			}

			function new_file(name, win) {

				var item = get_selected_node();

				if (item.type == 'file') {
					var path = rootPath + item.parent + DIRECTORY_SEPARATOR + name;
				} else {
					var path = item.file + name;
				}

				if (file_exists(path)) {
					alert('File is already exists' );
					return;
				}

                if (_blob) {
                    var file  = _blob;
                    file.name = name;
                    upload_files([file], function () {
                         win && win.hide();
                         _blob = null;
                    });
                } else {

                     add_file(path, function () {
                         win && win.hide();
                     });

                }

			}

			this.getValue = function () {
				var item = get_selected_node();
				return item.file;
			}

			this.setValue = function (file) {
				select_tree_node(file.replace(root, ''));
			}

		  
	}

	FileTree.setup = function () {
		return {
			id : '',
			root : '' ,
			script : '', 
			action : {
				addFile : '',
				deleteFile : '',
				renameFile : '',
				uploadFile : ''
			}
		};
	}
	return FileTree; 
});
</script>


<!-- image editor --> 
<!-- Template --> 
<!-- Style -->
<style type="text/css">

.image-editor-container {
	position:relative;
	width:100%;
	height:100%;
}

.image-editor-menu {
	position:absolute;
	display:none;
	top:0px;
	left:6px;
	right:0px;
	height:40px;
	padding:12px 5px;
	box-sizing:border-box;
	border-bottom:1px solid #ececec;
}

.image-editor .image-editor-menu .menu-title {
	display:inline-block;
	right:10px;
	position:absolute;
	top:50%;
	-webkit-transform:translateY(-50%);
	transform:translateY(-50%);

}
.image-editor .image-editor-menu .button {
	letter-spacing:0px;
	text-transform:none;
	padding:5px 10px;
}
.image-editor .image-editor-menu .button:not(:last-child) {
	border-right:0px;
}
.image-editor .image-editor-toolbar {
	position:absolute;
	top:40px;
	left:6px;
	bottom:0px;
	width:150px;
	padding:5px;
	box-sizing:border-box;
	overflow-y:auto;
	border-right:1px solid #ececec;
}


.image-editor .image-editor-toolbar .button {
	display:block;
	letter-spacing:0px;
	text-transform:none;
	padding:5px 10px;
}
.image-editor .image-editor-toolbar .button:not(:last-child) {
	border-bottom:0px;
}

.image-editor .image-editor-content {
	position:absolute;
	left:150px;
	right:0px;
	bottom:0px;
	top:40px;
	padding:20px 20px;
	box-sizing:border-box;
}

.image-editor .image-editor-content canvas {
	width:100%;
	height:auto;
    box-sizing: inherit;
}

.image-editor .image-editor-content .loading-message {
	position:absolute;
	left:0px;
	top:0px;
	right:0px;
	bottom:0px;
	background-color:rgba(0, 0, 0, 0.8);
	display:none;
	z-index:2;
}

.image-editor .image-editor-content .loading-message .message {
	position:absolute;
	left:0px;
	top:50%;
	right:0px;
	display:inline-block;
	-webkit-transform:translateY(-50%);
	transform:translateY(-50%);
	color:rgba(255, 255, 255, 0.8);
	text-align:center;
	font-size:20px;
	
}


.image-editor .image-editor-content.loading .loading-message {
	display:block;
}
</style>
<!-- Script -->
<script type="text/javascript">
jui.defineUI("ui.imageeditor", [], function () {
	// camanjs 를 이용한 bitmap image editor 
	var ImageEditor = function () {
			var $el, $container, $menu, $toolbar, $content, $canvas, $loading_message; 
			var _caman;
			var self = this; 
			var preset_filters = [
				{ filter : 'original', title : 'original'}, 
				{ filter : 'vintage', title : 'vintage'}, 
				{ filter : 'lomo',title : 'lomo'},
				{ filter : 'clarity',title : 'clarity'},
				{ filter : 'sinCity',title : 'sinCity'},
				{ filter : 'sunrise',title : 'sunrise'},
				{ filter : 'crossProcess',title : 'crossProcess'},
				{ filter : 'orangePeel',title : 'orangePeel'},
				{ filter : 'love',title : 'love'},
				{ filter : 'grungy',title : 'grungy'},
				{ filter : 'jarques',title : 'jarques'},
				{ filter : 'pinhole',title : 'pinhole'},
				{ filter : 'oldBoot',title : 'oldBoot'},
				{ filter : 'glowingSun',title : 'glowingSun'},
				{ filter : 'hazyDays',title : 'hazyDays'},
				{ filter : 'herMajesty',title : 'herMajesty'},
				{ filter : 'nostalgia',title : 'nostalgia'},
				{ filter : 'hemingway',title : 'hemingway'},
				{ filter : 'concentrate', title : 'concentrate'}
			];

			var menuItems = [
				{ 	title : 'Filter', action : 'filter' },
				//{ 	title : 'ADJUST', action : 'adjust' },
				//{ 	title : 'CROP', action : 'crop' },
				//{ 	title : 'RESIZE', action : 'resize' },
				//{ 	title : 'ROTATE', action : 'rotate' },
				{	title : 'Image Save', action : 'save' } 
			];

			function action (actionName) {
				// action 을 정의하세요. 
				if (actionName == 'filter')
				{
					showFilter();
				} else if (actionName == 'save') {
					self.save();
				}
			}

			function showFilter () {
				$toolbar.empty();
				for(var i = 0, len = preset_filters.length; i < len; i++) {
					var pf = preset_filters[i];

					 var $a = $("<a class='button button-common' />").html(pf.title).attr('data-filter', pf.filter);

					 $toolbar.append($a);
				}
			}

			function loadImage ( callback) {

				self.initContent();
				_caman = Caman($canvas[0], self.options.src, callback || function () { this.render(); });
			}

			this.init = function () {
				self = this; 
				$el = $(this.root);

				this.initElement();
				this.initEvent();
			}

			this.initElement = function () {
				$container = $("<div class='image-editor-container' />");
				$menu = $("<div class='image-editor-menu' />");
				$toolbar = $("<div class='image-editor-toolbar' />");
				$content = $("<div class='image-editor-content' />");
				$loading_message = $("<div class='loading-message' />");

				$loading_message.append("<div class='message'></div>");

				this.initMenu();
				this.initToolbar();
				this.initContent();

				$container.append([$menu, $toolbar, $content]);

				$el.html($container);

				loadImage();
			}

			this.initMenu = function () {

				$menu.append($("<span class='menu-title'>Image Editor</span>"));

				var len = menuItems.length; 

				for(var i = 0; i < len; i++) {
					var item = menuItems[i];
					var $a = $("<a class='button button-common'  />").html(item.title).attr('data-action', item.action);

					if (i == 0) {
						$a.addClass('active');
					}

					$menu.append($a);
				}

			}

			this.initToolbar = function () {
				showFilter();
			}

			this.recreateCanvas = function () {
					$canvas = $('<canvas class="image-editor-canvas" />');
					$content.html($canvas);
			}

			this.initContent = function () {

				this.recreateCanvas();
				$content.append($loading_message);
				//_caman = Caman($canvas[0]);
			}

			this.initEvent = function () {
				$menu.on('click', 'a', function () {
					var $a = $(this);
					$a.parent().find(".active").removeClass('active');
					$a.addClass('active');
					action($a.attr('data-action'));
				});

				$toolbar.on('click', '[data-filter]', function () {
					var $a = $(this);
					self.filter($a.attr('data-filter'));
				});

				this.on('filter.done', function (filterName) {
					console.log('filter done ' + filterName);
				});

			}

			this.setImage = function (image) {
				this.options.src = image;
			}

			this.getImage = function () {
				return this.options.src;
			}

			this.download = function () {

			}

			this.toDataURL = function (contentType) {
				contentType = contentType || "image/png";
			}

			this.save = function () {
				var path = this.options.src;

				var data = _caman.toBase64();

				if (this.options.saveCallback) {
					this.options.saveCallback(data, path);
				}
				
			}

			this.filter = function (filterName) {
				if (_caman)
				{
					this.showLoading('Filtering....');
					_caman.revert(false);

					if (_caman[filterName]) { 
						_caman[filterName].call(_caman);
					}
					_caman.render(function() {
						self.hideLoading();
						self.emit('filter.done', [filterName]);
					});
				}

			}

			this.showLoading = function (message) {
				$loading_message.find('.message').html(message || '');

				$content.addClass('loading');
			}

			this.hideLoading = function () {
				$content.removeClass('loading');
			}		

			this.render = function (callback) {
				loadImage(callback);
			}
	};

	ImageEditor.setup = function () {
		return {
			src : '',  // image src 
			saveCallback : function () {}
		};
	}

	return ImageEditor;
});
</script>

<!-- History List -->
<style type="text/css">
.commit-list {
	box-sizing:border-box;
	padding-left: 40px;
	padding-bottom:20px;
	margin-bottom:15px;
}

.commit-list:before {
	position: absolute;
    top: 0;
    bottom: 0;
    left: 24px;
    z-index: -1;
    //display: none;
    width: 2px;
    content: "";
    background-color: #f7f7f7;
}

.commit-group {
	margin-top: 15px;
    margin-left: -22px;
	color: #767676;
	font-size:13px;
	box-sizing:border-box;
}
.commit-icon {
	margin-right: 17px;
    color: #ccc;
	background: #fff;
	vertical-align:text-bottom;
}

.commit-icon svg path {
	fill: #48cfad;
}

.commit-icon svg {
	vertical-align:middle;
}

.commit-items {
	display: block;
    width: 100%;
    color: #999;
	margin-top:10px;
	box-sizing:border-box;
	padding-left:0px;
	padding-right: 10px;
	list-style-type: none; 
}

.commit-items .item {
	position:relative;
	list-style:none;
	border-bottom: 1px solid #e5e5e5;
	border-left: 1px solid #e5e5e5;
	border-right: 1px solid #e5e5e5;
	padding:10px;
}


.commit-items .item .title {
	padding:2px;
	font-weight:bold;
}

.commit-items .item .info span {
	margin-right:5px;
	position:relative;
}

.commit-items .item .info span.ago {
	margin-left:5px;
}
.commit-items .item .info span.ago:before {
	content:"";
	position:absolute;
	width:50%;
	height:50%;
	top:50%;
	margin-left:-5px;
	transform:translateY(-50%);
	border-left:1px solid #d8d8d8;
}
.commit-items .item:first-child {
	border-top: 1px solid #e5e5e5;
}

.commit-items .item .commit-info {
	position:absolute;
	display:inline-block;
	right:10px;
	top:50%;
	-webkit-transform:translateY(-50%);
	transform:translateY(-50%);
	padding:5px;
	border: 1px solid #48cfad;
	border-radius: 1px;
	color:#48cfad;
}
</style>
<script type="text/javascript">
jui.defineUI("ui.historyview", ["jquery"], function ($) {
	var HistoryView = function () {

		var self, $root;

		this.init = function () {
			self = this;

			this.initElement();
			this.update(this.options.logs);
			
		}

		this.initElement = function () {
			$root = $("<div class='commit-list' />");

			$(this.root).html($root);
		}

		this.show = function () {
			$(this.root).show();
		}

		this.hide = function () {
			$(this.root).hide();
		}

		this.update = function (originalLogs, isEmpty) {
			logs = originalLogs;

			this.render(isEmpty);
		}

		this.getGroupedLogs = function () {
			var group = '';
			var group_list = [];
			var group_obj = {};

			for(var i = 0, len = logs.length; i < len; i++) {
				var log = logs[i];

				if (group != log.commit_day) {
					group = log.commit_day; 
					group_list.push(group);
				}

				if (!group_obj[group]) {
					group_obj[group] = [];
				}	

				group_obj[group].push(log);
			}

			return group_obj;

		}

		this.render = function (isEmpty) {
			if (isEmpty) $root.empty();

			this.renderLogs();
		}

		this.renderLogs = function () {
			var group = this.getGroupedLogs();
			
			var keys = Object.keys(group);

			for(var i = 0, len = keys.length; i < len; i++) {
				var key = keys[i];

				this.renderGroup(key, group[key]);

			}

		}

		this.renderGroup = function (text, group) {
			var $g = $("<div class='commit-group' />");

			var $icon = $("<span class='commit-icon' />");
			$icon.append('<svg aria-hidden="true" class="octicon octicon-git-commit" height="16" version="1.1" viewBox="0 0 14 16" width="14"><path d="M10.86 7c-.45-1.72-2-3-3.86-3-1.86 0-3.41 1.28-3.86 3H0v2h3.14c.45 1.72 2 3 3.86 3 1.86 0 3.41-1.28 3.86-3H14V7h-3.14zM7 10.2c-1.22 0-2.2-.98-2.2-2.2 0-1.22.98-2.2 2.2-2.2 1.22 0 2.2.98 2.2 2.2 0 1.22-.98 2.2-2.2 2.2z"></path></svg>');
			$g.append($icon);

			var $commit_date = $('<span class="commit-date" />');
			$commit_date.html("Commits on " + text);
			$g.append($commit_date);
			
			var $items = $("<ul class='commit-items' />");
			var log_list = group; 
			for(var index = 0, size = log_list.length ; index < size; index++) {
				var item = log_list[index];
				var $li = $(['<li class="item">',
						'<span class="author-icon"></span>',
						'<div class="title">'+item.message+'</div>',
						'<div class="info">',
							'<span class="author">'+item.author+'</span>',
							'<span class="ago">'+item.ago+'</span>',
						'</div>',
						'<div class="commit-info">',
							'<span class="commit-history-id">'+item.commitId+'</span>',
							'<span class="commit-diff-code"></span>',
						'</div>',
				'</li>'].join(''));

				$items.append($li);
			}

			$root.append([$g[0], $items[0]]);
		}

	};

	HistoryView.setup = function () {
		return { 
			logs : []
		}
	};

	return HistoryView;
});
</script>

<!-- Commit Message Alert -->
<style type='text/css'>
/* commit modal */ 

.commit-modal {
	position:fixed;
	left:0px;
	right:0px;
	bottom:0px;
	top:0px;
	background-blend-mode: overlay;
	background-color: rgba(38, 46, 57, 0.9);
	z-index:100;
	display:none;
	opacity:0;
	transition:opacity 0.3s linear;
}

.commit-modal .close {
	position:absolute;
	top:20px;
	right:20px;
	color:white;
	padding:0px;
	margin:0px;
	cursor:pointer;
}

.commit-modal .close svg {
	width:30px;
	height:30px;
}

.commit-modal .close svg polygon {
	transition:all 0.1s linear;
	fill-opacity: 0.5;
	fill:white;
}

.commit-modal .close:hover svg polygon {
	fill-opacity: 1;
	fill:#48cfad;
}

.commit-modal .content {
	position:absolute;
	top:50%;
	left:50%;
	-webkit-transform: translateY(-50%) translateX(-50%);
	transform: translateY(-50%) translateX(-50%);
	margin:0;
	padding:0px;
	box-sizing:border-box;
	text-align:center;
	color:white;
}

.commit-modal .content .commit-container {
	text-align:left;
}

.commit-modal .content .commit-container label { 
	text-transform: uppercase;
	margin-bottom:10px;
	display:block;
	font-size:14px;
	letter-spacing:1px;
}

.commit-modal .content .commit-container .commit-message
{
	font-size:13px;
	margin-top:20px;
}

.commit-modal .content .commit-container .commit-buttons
{
	font-size:13px;
	margin-top:20px;
}

.commit-modal .content .commit-container textarea {
	width:370px; 
	max-width:100%;
	box-sizing:border-box;
	height:70px;
	font-size:12px;
	padding:10px;
	border:0px;
	border-radius:3px;
	cursor:auto;
	background-color:rgba(0, 0, 0, 0.2);
	color:white;
}

.commit-modal .commit-files {
	padding:10px;
	max-height: 200px;
	overflow:auto; 
	background-color:rgba(0, 0, 0, 0.2);
}

.commit-modal .commit-files .file {
	font-size:12px;
	font-weight:300;
	line-height:20px;
	padding:5px;
	cursor:pointer;
	position:relative;
	border-radius:3px;
}

.commit-modal .commit-files .file.selected,
.commit-modal .commit-files .file:hover
{
	background-color: #3e806f;
	color:white;
}

.commit-modal .commit-files .file .tool {
	position:absolute;
	display:none;
	right:5px;
	top:50%;
	-webkit-transform: translateY(-50%);
	transform: translateY(-50%);
}

.commit-modal .commit-files .file .tool a {
	padding:0px;
	margin:0px;
	font-weight:normal;
	cursor:pointer;
	padding:1px 3px;
	border-radius:3px;
	text-transform: lowercase;
}

.commit-modal .commit-files .file.selected .tool {
	display:inline-block;
}


</style>
<script type="text/javascript">
function show_commit_modal (changed_files) {
	$("body").addClass('modal-open');
	$(".commit-modal").show();
	setTimeout(function() {  $(".commit-modal").css('opacity', 1); } , 0);

	var $files = $(".commit-modal .commit-files").empty();
	for(var i = 0, len = changed_files.length; i < len; i++) {
		var it = changed_files[i];

		var $file = $("<div />").addClass("file").html("<span>" + it.file  + "</span>").attr('data-file', it.file);

		var $tool = $("<div class='tool' />");
		$tool.append($("<a class='button button-small button-common danger active' />").html("Diff"));
		$file.append($tool);

		$files.append($file);
	}

    var file_list = $files.children().map(function() {
        return $(this).data('file');
    }).toArray().join(",");
    $(".commit-message textarea").html("Update " + file_list);
}

function close_commit_modal() {
	$("body").removeClass('modal-open');
	$(".commit-modal").hide().css('opacity', 0);
}

$(function () {
	$(".commit-modal .close").on('click', function () {
		close_commit_modal();
	});

	$(".commit-modal .commit-btn").on("click", function () {
		var message = $(".commit-message textarea").val();

		if ($.trim(message) == '') {
			message = 'Update File';
		}
		commit_file(message);
	});

	$(".commit-files").on("click", ".file", function () {
		var $li = $(this);
		
		$li.parent().find(".selected").removeClass('selected');
		$li.addClass('selected');
	});

	$(".commit-files").on("click", ".file .tool", function (e) {
		e.preventDefault();
		e.stopPropagation();
		var $li = $(this).parent();
		var file = $li.data('file');
	});

});

</script>
<div class="commit-modal">
	<div class="block-background"></div>
	<a class="close"><?php echo get_svg_image('close') ?></a>
	<div class="content">
		<div class="commit-container">
			<label>Changed Files</label>
			<div class="commit-files">
			</div>
			<div class="commit-message">
				<label>Commit Message</label>
				<div style='margin-top:5px;'><textarea placeholder="Update File"></textarea> </div>
			</div>

			<div class="commit-buttons">
			<a class="button danger active commit-btn">Commit</a>
			</div>
		</div>
	</div>
</div>
