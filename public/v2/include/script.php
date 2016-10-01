<script type="text/javascript">
jui.defineUI('ui.splitter', [], function () {
	var Splitter = function () {
		var self, $el, $splitter, $items, barSize;
		var $list = [];
		var maxSize, direction, initSize;

		this.init = function () {
			self = this;
			$el = $(this.root);
			barSize = this.options.barSize; 
			direction = this.options.direction;
			initSize = this.options.initSize;

			var temp = [];
			for(var i = 0, len = this.options.items.length; i < len; i++) {
				$list[i] = $(this.options.items[i]);
				$list[i].css({
					width : 'auto',
					height: 'auto',
					top : 0,
					right: 0,
					bottom: 0,
					left: 0 
				});
				temp[i] = $list[i][0];
			}

			$items = $(temp);

			this.initElement();
			this.initEvent();
		}

		function is_vertical() {
			return direction == 'vertical';
		}

		this.initElement = function () {

			if ($splitter && $splitter.length) $splitter.remove();

			if (is_vertical()) {
				$splitter = $("<div  />").css({
					'position':'absolute',
					'top':'0px',
					'width': barSize,
					'bottom':'0px',
					'cursor':'ew-resize'
				});

			} else {
				$splitter = $("<div />").css({
					'position':'absolute',
					'left':'0px',
					'height': barSize,
					'right':'0px',
					'cursor':'ns-resize'
				});

			}

			$splitter.addClass(this.options.splitterClass);
			$splitter.css('z-index', 9999999);
			$splitter.css(this.options.barStyle);

			$el.append($splitter);

			this.initResize();
		}

		this.getSize = function (size, maxSize) {
			if (typeof size == 'string' && size.indexOf('%') > -1) {
				return maxSize * (parseFloat(size.replace('%','')) /100);
			}

			return size; 
		}

		this.getShowList = function () {
			var list = [];
			for(var i = 0, len = $items.length; i < len; i++) {
				var $it = $($items[i]);
				if ($it.hasClass(self.options.hideClass)) {
					continue;
				}
				list.push($items[i]);

			}

			return $(list);
		}

		this.initResize = function () {

			var $showList = this.getShowList();

			if ($showList.length == 1)
			{
					$showList.css({ 'left': '0px', width: 'auto', height: 'auto', right: 0, top : 0, bottom : 0 });
					$splitter.hide();
			} else {
				if (is_vertical()) {
					var maxWidth = $el.width();
					var centerPos = this.getSize(initSize, maxWidth);
					$list[0].css({ 'left': '0px', 'width' : centerPos + 'px', top : 0, bottom : 0 });
					$list[1].css({ 'left': centerPos + 'px', 'right' : '0px', top : 0, bottom : 0  });
					$splitter.css({ 'left': centerPos + 'px' });
				} else {
					var maxHeight = $el.height();
					var centerPos = this.getSize(initSize, maxHeight);
					$list[0].css({ 'top': '0px', 'height' :  centerPos + 'px', left : 0, right : 0 });
					$list[1].css({ 'top': centerPos + 'px', 'bottom' : '0px', left : 0, right : 0  });
					$splitter.css({ 'top': centerPos + 'px' });
				}
				$splitter.show();
			}
		}

		function mouseMove(e) {

			if (is_vertical()) {
				var distX = e.clientX - $splitter.data('prevClientX');
				var posX = parseFloat($splitter.css('left')) + distX;;

				if (posX < 0) {
					posX = 0;
				} else if (posX > maxSize) {
					posX = maxSize; 
				}

				$splitter.css('left' , posX + 'px');
				$list[1].css('left' , (posX) + 'px');
				$list[0].css('width',  posX + 'px');

				initSize = posX;
				$splitter.data('prevClientX', e.clientX); 

			} else {
				var distY = e.clientY - $splitter.data('prevClientY');
				var posY = parseFloat($splitter.css('top')) + distY;

				if (posY < 0) {
					posY = 0;
				} else if (posY > maxSize) {
					posY = maxSize; 
				}

				$splitter.css('top' , posY + 'px');
				$list[1].css('top' , (posY) + 'px');
				$list[0].css('height', posY + 'px');
				initSize = posY;

				$splitter.data('prevClientY', e.clientY); 
			}

		}

		function mouseUp() {
			$(document).off('mousemove', mouseMove);
			$(document).off('mouseup', mouseUp);

			$items.css('user-select', '');
			$items.find('iframe').css('pointer-events', 'auto');

			self.emit('move.done', [$splitter]);
		}

		this.initEvent = function () {

			$el.on('mousedown', '> .' + this.options.splitterClass,  function (e) {

				$items.css('user-select', 'none');
				$items.find('iframe').css('pointer-events', 'none');

				if (is_vertical()) {
					maxSize = $el.width();
					$splitter.data('prevClientX', e.clientX);
				} else {
					maxSize = $el.height();
					$splitter.data('prevClientY', e.clientY);
				}

				$(document).on('mousemove', mouseMove);
				$(document).on('mouseup', mouseUp);

			});
		}

		this.setDirection = function (d) {
			direction = d;

			this.initElement();
		}

		this.setInitSize = function (size) {
			initSize = size; 

			this.initResize();
		}

		this.setHide = function (index) {
			$($items[index]).hide().addClass(self.options.hideClass);

			this.initResize();
		}

		this.setShow = function (index) {
			$($items[index]).show().removeClass(self.options.hideClass);

			this.initResize();
		}

		this.toggle = function (index) {
			if($($items[index]).hasClass(self.options.hideClass)) {
				this.setShow(index);
			} else {
				this.setHide(index);
			}
		}

	}

	Splitter.setup = function () {
		return {
			splitterClass : 'ui-splitter', 
			hideClass: 'hide',
			barSize : 4,
			barStyle : {
				'background-color': '#f6f6f6',
				'border-right': '1px solid #e4e4e4'
			},	
			direction : 'vertical',
			initSize : '50%', 
			items : []
		}
	};

	return Splitter;
});
</script>

<script type="text/javascript">
jui.defineUI("ui.filedrop", ['jquery'], function ($) {
    var FileDrop = function () {
        var self, $root, _files;

        this.init = function () {
            self = this;
            $root = $(this.root);

            this.initEvent();
        }

        this.initEvent = function () {
            $root.on('drop', function (e) {
                e.preventDefault();

                self.setFiles(e);
                self.processFile();
           });
        }

        this.setFiles = function (e) {
            _files = e.originalEvent.dataTransfer.files;

        }

        this.getFiles = function () {
            return _files; 
        }

        this.getFile = function (i) {
            return _files[i];
        }

        this.processFile = function (e) {
            var files = _files;
            
            if (this.options.uploadUrl != '') {

                if (this.options.isMultiFileUpload) {

                    this.uploadFilesForSingleUpload(files);
                } else {

                    this.uploadFiles(files);
                }

            } else {
                // upload 안하고 바로 파일을 사용할 경우 
                this.emit("dropped.files");
            }
        }

        
        this.uploadOneFile = function (file, index, callback) {
            var formData = new FormData();
            
            for(var k in this.options.uploadParams) {
                var key = k;
                var value = this.options.uploadParams[k];

                if (typeof value == 'function') {
                    formData.append(key, value.call(this));
                } else {
                    formData.append(key, value);
                }   
            }
           
            formData.append(this.options.uploadParamName, file);

        	 $.ajax({
                url: this.options.uploadFile,
                type: 'post',
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();

                    // set upload progress event 
                    xhr.upload.addEventListener("progress", function(evt) {
                      if (evt.lengthComputable) {

                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        self.emit('file.progress.update', [index,  percentComplete, evt.loaded , evt.total] );

                        if (percentComplete === 100) {
                            self.emit('file.progress.complete', [ index ] );
                        }

                      }
                    }, false);

                    return xhr;
                },
                complete: function() {
                    self.emit("file.complete", [index]);
                },
                success: function(data) {
                    callback && callback (index, data);
                    self.emit("file.success", [index, data]);
                },
                error: function() {
                    self.emit("file.error", [index]);
                  // Log the error, show an alert, whatever works for you
                }
            });

        }


        this.uploadFilesForSingleUpload = function (files) {

            // TODO: 업로드 코드 
            var max = files.length;
            var count = 0;

            
            var totalFileSize  = 0;
            for(var i = 0, len = max; i < len; i++) {
                totalFileSize += files[i].size;
            }

            var uploadedFileSize = 0;
            for(var i = 0, len = max; i < len; i++) {

                this.uploadOneFile(files[i], i, max, function (index) {
                    count++;
                    uploadedFileSize += files[index].size;
                  
                    var percentComplete = parseInt((uploadedFileSize/  totalFileSize) * 100);
                    self.emit('progress.update', [ , uploadedFileSize, totalFileSize] );


                    if (percentComplete == 100) {
                        self.emit("progress.complete");
                    } 
                    if (count == max) {
                        self.emit("complete");
                    }
                });
            }
        }


        this.uploadFiles = function (files) {

            var formData = new FormData();
            
            for(var k in this.options.uploadParams) {
                var key = k;
                var value = this.options.uploadParams[k];

                if (typeof value == 'function') {
                    formData.append(key, value.call(this));
                } else {
                    formData.append(key, value);
                }   
            }

            // TODO: 업로드 코드 
            for(var i = 0, len = files.length; i < len; i++) {
                var file = files[i];
                formData.append(this.options.uploadParamName, file);
            }

           	 $.ajax({
                url: this.options.uploadFile,
                type: 'post',
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();

                    // set upload progress event 
                    xhr.upload.addEventListener("progress", function(evt) {
                      if (evt.lengthComputable) {

                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        self.emit('progress.update', [ percentComplete, evt.loaded , evt.total] );

                        if (percentComplete === 100) {
                            self.emit('progress.complete');
                        }

                      }
                    }, false);

                    return xhr;
                },
                complete: function() {
                    self.emit("complete");
                },
                success: function(data) {
                    self.emit("success", [data]);
                },
                error: function() {
                    self.emit("error", []);
                  // Log the error, show an alert, whatever works for you
                }
            });
        }
        
    };

    FileDrop.setup = function () {
        return {
            uploadName : 'files[]',
            uploadUrl : '<?php echo V2_PLUGIN_URL ?>/file/upload_file.php',
            uploadParams : {},
            isMultiFileUpload : true      // true 면 파일 업로드 개별로 함. 
        }
    }

    return FileDrop;
});
</script>


<script type="text/javascript">
$(function() {
   window.viewAccessMessage = function viewAccessMessage() {
        var access = $("[name=access]:checked").val();
		var html = "";
		var color = "blue";

        if (access == 'private') {
            html = 'only you can see this component.';
			color = 'red';
        } else if (access == 'share') {
            html = 'It\'s like private. but result view can be share.';
			color = 'green';
        } else {
            html = 'Anyone can see this component.';
			color = 'blue';
        }

		$("#access_message").html(html).css({
			border : '1px solid ' + color,
			color : color ,
				padding: " 3px 10px"
        });

   }

   viewAccessMessage();

	window.more_info_license = function() {
		var key = $("#license").val();
		var url = 'http://opensource.org/licenses/' + key;

		window.open(url, "_license");
	}



	$("[data-splitter]").on('click', function() {
		var splitterName = $(this).data('splitter');
		var index = + ($(this).data('splitter-toggle') || 0);

		$(this).toggleClass('toggle');

		window[splitterName].toggle(index);
	});

});
</script>
