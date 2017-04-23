
<?php include_once V2_INC.'/property.php' ?>
<?php @include_once V2_PLUGIN."/pr/settings.php" ?>
<script type="text/javascript">
$(function() {

	var ExternalItemTemplate = window.ExternalItemTemplate = '<span title="drag me for ordering" class="handle" draggable="true"><i class="icon-dashboardlist"></i></span><input type="text" placeholder="//myhost.com/my.js" class="input" /><a class="btn small"><i class="icon-exit"></i></a>';

	var slideCode = window.slideCode = CodeMirror.fromTextArea($("#slide_code")[0], {
	  mode:  "markdown",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  },
	  colorpicker : {
		mode : 'edit'
	  }
	});
	var slideStyle = window.slideStyle = CodeMirror.fromTextArea($("#slide_style")[0], {
	  mode:  "css",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  },
	  colorpicker : {
		mode : 'edit'
	  }
	});

	var slideNote = window.slideNote = CodeMirror.fromTextArea($("#slide_note")[0], {
	  mode:  "markdown",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  }
	});
   
    slideCode.on('keyup', function () {
		if (window.isSelectedSlide)
		{
			window.isSelectedSlide = false; 
		}

		var $li = get_item();
		
		$li.html(get_first_line($li.data('content')));
		
	});

    slideStyle.on('keyup', function () {
		if (window.isSelectedSlide)	{
			window.isSelectedSlide = false; 
		}
	});

    slideNote.on('keyup', function () {
		if (window.isSelectedSlide)
		{
			window.isSelectedSlide = false; 
		}
	});

	// change event settings 
	slideCode.on('change', function () {
		get_item().data('content', slideCode.getValue());
		if (!window.isSelectedSlide) { coderun(); }
	  })

	slideNote.on('change', function () {
		get_item().data('note', slideNote.getValue());
		if (!window.isSelectedSlide) coderun();
	})

	slideStyle.on('change', function () {
		get_item().data('style', slideStyle.getValue());
		/*if (!window.isSelectedSlide)*/ coderun();
	})

	// paste event settings 
	slideCode.on('paste', function () {
		get_item().data('content', slideCode.getValue());
		if (!window.isSelectedSlide) { coderun(); }
	  })

	slideNote.on('paste', function () {
		get_item().data('note', slideNote.getValue());
		if (!window.isSelectedSlide) coderun();
	})

	slideStyle.getWrapperElement().addEventListener('paste', function () {
		get_item().data('style', slideStyle.getValue());
		if (!window.isSelectedSlide) coderun();
	})

    jui.create('ui.tab', '#tab_slide_settings', {
		target: ".slider-content",
		event : {
			change : function (obj) {
				if (obj.text == 'NOTE') {
					slideNote.refresh();
				} else if (obj.text == 'STYLE') {
					slideStyle.refresh();
				} else {
					slideCode.refresh();
				}
			}		
		}
	});

	$(".pr-settings").on('click', function () {
		$(this).toggleClass('active');
		$("#pr-settings").toggle();
		$("#pr-slide").toggle();
	});

	window.prSettings = jui.create("ui.property", '#pr-settings', {
		items : [
			{ type : 'group', title : 'Views', description : 'Simple theme select info '},
			{ type : 'select', title : 'Theme', key : 'theme' , value : 'white', items : ['white', 'black', 'league', 'beige', 'sky', 'night', 'serif', 'simple', 'solarized', 'mozila' ] },
			{ type : 'group', title : 'Configure'},
			{ type : 'checkbox', title : 'Controls', key : 'controls' , value : true, description : 'Display controls in the bottom right corner' },
			{ type : 'checkbox', title : 'Progress', key : 'progress' , value : true, description : 'Display a presentation progress bar' },
			{ type : 'checkbox', title : 'Slide Number', key : 'slideNumber' , value : false, description : 'Display the page number of the current slide' },
			{ type : 'checkbox', title : 'History', key : 'history' , value : false, description : 'Push each slide change to the browser history' },
			{ type : 'checkbox', title : 'Keyboard', key : 'keyboard' , value : true, description : 'Enable keyboard shortcuts for navigation' },
			{ type : 'checkbox', title : 'Overview', key : 'overview' , value : true, description : 'Enable the slide overview mode' },
			{ type : 'checkbox', title : 'Center', key : 'center' , value : true, description : 'Vertical centering of slides' },
			{ type : 'checkbox', title : 'Touch', key : 'touch' , value : true, description : 'Enables touch navigation on devices with touch input' },
			{ type : 'checkbox', title : 'Loop', key : 'loop' , value : false, description : 'Loop the presentation' },
			{ type : 'checkbox', title : 'RTL', key : 'rtl' , value : false, description : 'Change the presentation direction to be RTL' },
			{ type : 'checkbox', title : 'Shuffle', key : 'shuffle' , value : false, description : 'Randomizes the order of slides each time the presentation loads' },
			{ type : 'checkbox', title : 'Fragments', key : 'fragments' , value : true, description : 'Turns fragments on and off globally' },
			{ type : 'checkbox', title : 'Embedded', key : 'embedded' , value : false, description : 'Flags if the presentation is running in an embedded mode,\ni.e. contained within a limited portion of the screen ' },
			{ type : 'checkbox', title : 'Help', key : 'help' , value : true, description : 'Flags if we should show a help overlay when the questionmark, key is pressed' },
			{ type : 'checkbox', title : 'Show Notes', key : 'showNotes' , value : false, description : '' },
			{ type : 'number', title : 'Auto Slide', key : 'autoSlide' , value : 0, description : '' },
			{ type : 'checkbox', title : 'Auto Slide Stoppable', key : 'autoSlideStoppable' , value : true, description : 'Stop auto-sliding after user input' },
			{ type : 'checkbox', title : 'Auto Slide Method', key : 'autoSlideMethod' , value : 'Reveal.navigateNext', description : 'Use this method for navigation when auto-sliding' },
			{ type : 'checkbox', title : 'Mouse Wheel', key : 'mouseWheel' , value : false, description : 'Enable slide navigation via mouse wheel' },
			{ type : 'checkbox', title : 'Hide Address Bar', key : 'hideAddressBar' , value : true, description : '' },
			{ type : 'checkbox', title : 'Preview Links', key : 'previewLinks' , value : false, description : '' },
			{ type : 'select', title : 'Transition style', key : 'transition' , value : 'default', items : ['default', 'none', 'fade', 'slide', 'convex', 'concave', 'zoom', 'fade-in slide-out', 'slide-in fade-out' ] },
			{ type : 'select', title : 'Transition Speed', key : 'transitionSpeed' , value : 'default', items : ['default', 'fast', 'slow' ] },
			{ type : 'select', title : 'Background Transition', key : 'backgroundTransition' , value : 'default', description : 'Transition style for full page slide backgrounds',  items : ['default', 'none', 'fade', 'slide', 'convex', 'concave', 'zoom' ] },
			{ type : 'number', title : 'View Distance', key : 'viewDistance' , value : 3, description : 'Number of slides away from the current that are visible' },
			{ type : 'group', title : 'Parallax'},
			{ type : 'text', title : 'Background Image', key : 'parallaxBackgroundImage' , value : '', description : 'https://s3.amazonaws.com/hakim-static/reveal-js/reveal-parallax-1.jpg' },
			{ type : 'text', title : 'Background Size', key : 'parallaxBackgroundSize' , value : '', description : 'CSS syntax, e.g. "2100px 900px"' },
			{ type : 'text', title : 'Background Horizontal', key : 'parallaxBackgroundHorizontal' , value : '', description : '' },
			{ type : 'text', title : 'Background Vertical', key : 'parallaxBackgroundVertical' , value : '', description : ''  }
		],
		event : {
			change : function (item, newValue, oldValue) {
				coderun();
			}
		}
	});

	window.sliderSettings = jui.create("ui.property", '#slider-settings', {
		items : [
			{ type : 'group' , title : 'Information' },
			{ title : 'Slide ID', key : 'slide-id'  },
			{ title : 'Character Set', key : 'charset'  },
			{ type : 'group' , title : 'Background' },
			{ type : 'color', title : 'Background Color', key : 'background-color', value : '', description : 'All CSS color formats are supported, like rgba() or hsl().' },
			{ title : 'Background Image', key : 'background-image', value : '', description : 'URL of the image to show. GIFs restart when the slide opens.', media : true  },
			{ title : 'Background Size', key : 'background-size', value : 'cover', description : 'See <a href="https://developer.mozilla.org/docs/Web/CSS/background-size">background-size</a> on MDN.' },
			{ title : 'Background Position', key : 'background-position', value : 'center', description : 'See <a href="https://developer.mozilla.org/docs/Web/CSS/background-position">background-position</a> on MDN.' },
			{ title : 'Background Repeat', key : 'background-repeat', value : 'no-repeat', description : 'See <a href="https://developer.mozilla.org/docs/Web/CSS/background-repeat" target="_blank">background-repeat</a> on MDN.' },
			{ title : 'Background Video', key : 'background-video', value : '', description : 'A single video source, or a comma separated list of video sources.' },
			{ type: 'checkbox', title : 'Background Video Loop', key : 'background-video-loop', value : false,  description : 'Flags if the video should play repeatedly.' },
			{ type: 'checkbox', title : 'Background Video Mutied', key : 'background-video-muted', value: false, description : 'Flags if the audio should be muted.' },
			{ title : 'Background Iframe', key : 'background-iframe', value : '', description : 'Embeds a web page as a background. Note that since the iframe is in the background layer, behind your slides, it is not possible to interact with the embedded page.' },
			{ type : 'select', title : 'Background Transition', key : 'background-transition' , value : 'default', description : 'Transition style for full page slide backgrounds',  items : ['default', 'none', 'fade', 'slide', 'convex', 'concave', 'zoom' ] },
			{ type : 'group', title : 'Transition' }, 
			{ type : 'select', title : 'Transition style', key : 'transition' , value : 'default', items : ['default', 'none', 'fade', 'slide', 'convex', 'concave', 'zoom', 'fade-in slide-out', 'slide-in fade-out'] , description : 'You can override the global transition for a specific slide' },
			{ type : 'select', title : 'Transition Speed', key : 'transition-speed', value: 'fast', description : 'Choose from three transition speeds: default, fast or slow!', items : [ 'default', 'fast', 'slow' ] }
		],
		event : {
			change : function (item, newValue, oldValue) {
				var $li = get_item();
                $li.data('settings', this.getValue());
                update_slide_view();
				coderun();
			}
		}
	});

	

	$(".add-btn").on('click', function () {
		new_slide_item();
		$(".slider-items > ul").sortable({ });
		update_slide_number();
		coderun();
	});

	function get_first_line(content) {
		var arr = content.split('\n');

		for(var i = 0, len = arr.length; i < len; i++) {
			if ($.trim(arr[i]) != '') {
				return arr[i].replace(/\#/g, '');
			}
		}
	}

    function update_slide_view ($li) {
		$li =  $li || get_item();
        var settings = $li.data('settings');
        $li.css('background-color', settings['background-color']) ;
        $li.css('background-image', 'url(' + settings['background-image'] + ')') ;
        $li.css('background-size', 'cover') ;
    }

	function new_slide_item(obj, isNoneActive) {
		obj = obj || {};
		$selected = get_item();

		var content = typeof obj.content == 'undefined' ? 'welcome' : obj.content;
		var $li = $("<li ></li>").data({
			name : obj.name || 'new slider',
			content: content,
			note : obj.note || '', 
			style: obj.style || '',
			secondary : obj.secondary || false,
			settings : obj.settings || sliderSettings.getDefaultValue()   // slider 기본 설정 추가 
		}).html(get_first_line(content));

        update_slide_view($li);
    
		if (obj.secondary)
		{
			$li.addClass('secondary icon-chevron-right');
		}

		if ($selected.length) {
			$selected.after($li);
		} else {
			$(".slider-items ul").append($li);
		}

		if (isNoneActive !== true) {
			active_item($li);
		}

	}

	$(".slider-items").on('click', 'li',  function () {
		var $li = $(this);
		active_item($li);
		
		view_slide_item($li)
	});

	$(".delete-slide-btn").on("click", function (e) {
		remove_item();
	});

	function get_item() {
		return $(".slider-items .selected");
	}

	$(".check-slide-secondary").on("click", function (e) {
		var $item = get_item();
		
		$item.toggleClass('secondary icon-chevron-right');

		$item.data('secondary', $item.hasClass('secondary'));

		coderun();
	});

	window.view_slide_item = function ($li) {
		var win = $("iframe[name=chart_frame]")[0].contentWindow;
		
		if (typeof win.setSelectedNum == 'function' )
		{
			win.setSelectedNum(get_slide_index($li));
		}
	}

	// 슬라이드 드래그 이후 실행 
	$(".slider-items > ul").on('sortupdate', function (e, ui) { 
		update_slide_number();
		coderun();
	});

	$("#slide_code").on('change', function () {

		get_item().data('content', slideCode.getValue());
		//coderun();
	});

	window.$prev_li = null;
	function active_item ($li) {

		if (!$li) return; 

		$('.slider-items .selected').removeClass('selected');
		$li.addClass('selected');


		if ($prev_li != $li[0]) {
			$prev_li = $li[0];
			refresh_content($li.data());
		}
	}

	function remove_item ($li) {
		$li = $li || $('.slider-items .selected');

		var $activeItem = $li.next();
		var $activeItemPrev = $li.prev();

		$li.remove();

		if ($activeItem.length)
		{
			active_item($activeItem);
		} else if ($activeItemPrev.length) 	{
			active_item($activeItemPrev);
		}

		update_slide_number();
		coderun();

	}



	function refresh_content(data) {
		var $selected = get_item();
		slideCode.setValue($selected.data('content') || "");
		slideNote.setValue($selected.data('note') || "");
		slideStyle.setValue($selected.data('style') ||  "");

		slideCode.refresh();
		slideNote.refresh();
		slideStyle.refresh();

		slideCode.clearHistory();
		slideNote.clearHistory();
		slideStyle.clearHistory();


        var settings = $selected.data('settings');
		sliderSettings.initValue(settings);
        
	}

	window.slide_coderun_timer = null;
	window.coderun = function coderun () {

		if (slide_coderun_timer) {
			clearTimeout(slide_coderun_timer);
		}

		slide_coderun_timer = setTimeout(function () { 
			update_slide_number();

			$("#chart_form").attr('action', '<?php echo V2_PLUGIN_URL ?>/pr/generate.php');
			$("#chart_form").attr('target', 'chart_frame');
			$("#chart_form [name=resources]").val(getResourceList());
			//$("#chart_form [name=preprocessor]").val(getPreProcessorList());

		
			$("#chart_form [name=pr_settings]").val(getPrSettingsView());
			$("#chart_form [name=slide_code]").val(getSliderView());
			$("#chart_form [name=selected_num]").val(getSliderSelectedNum());
			$("#chart_form").submit();
		}, 1000);	
	}

	window.export_to_pdf = function () {
		$("#chart_form").attr('action', '<?php echo V2_PLUGIN_URL ?>/pr/generate.php?print-pdf');
		$("#chart_form").attr('target', 'export_frame');
		$("#chart_form [name=resources]").val(getResourceList());
	//$("#chart_form [name=preprocessor]").val(getPreProcessorList());

		$("#chart_form [name=pr_settings]").val(getPrSettingsView());
		$("#chart_form [name=slide_code]").val(getSliderView());
		$("#chart_form [name=selected_num]").val("");
		$("#chart_form").submit();
	
	}

	$(".export-btn").on("click", function () {
		export_to_pdf();

		setTimeout(function() {
			$("iframe[name=export_frame]")[0].contentWindow.print();		
		}, 500);
	});

	window.update_slide_number = function () {

		var lastIndex = 1; 
		$(".slider-items > ul li").each(function () {
			var isSecond = $(this).hasClass('secondary');

			if (!isSecond) {
				$(this).attr('data-index-text', lastIndex++);
			}
		});
	}

	window.getPrSettingsView = function getPrSettingsView() {
		return JSON.stringify(prSettings.getValue());
	}

	window.getSliderView = function getSliderView() {
		return JSON.stringify($(".slider-items li").map(function() {
			return $(this).data();
		}).toArray());
	}

	window.getSliderSelectedNum = function getSliderSelectedNum() {
		return get_slide_index($(".slider-items li.selected")).join("/");
	}

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
			license : $("#license").val(),
			pr_settings : getPrSettingsView(),
			slide_code: getSliderView(), 
			selected_num : getSliderSelectedNum(),
			resources : getResourceList(),
			preprocessor : getPreProcessorList()
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
		if (confirm("Delete this slide?\r\ngood count is also deleted.")) {
			$.post("/v2/delete.php", { id : '<?php echo $_GET['id'] ?>' }, function(res) {
				if (res.result) {
					location.href = '/v2/dashboard.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}
<?php } ?>
	window.setSlideCode = function (list) {
		list = list || [];

		for(var i = 0, len = list.length; i < len; i++) {
			var item = list[i];
			new_slide_item(item, true);
		}
	}

	window.setPrSettings = function (obj) {
		obj = obj || {};

		prSettings.setValue(obj);

	}

	window.get_slide_item = function (num, index) {
		var $li = $(".slider-items > ul >  li:not(.secondary)").eq(num); 

		var max = index;
		for(var i = max; i > 0; i--) {
			$li = $li.next();
		}

		return $li;
	}

	// find main num 
	window.get_slide_num = function ($li) {
			var num = 0;
			$(".slider-items > ul > li:not(.secondary)").each(function(i) {
					if ($(this)[0] == $li[0]) {
						num = i; 
					}
			});

			return num;
	}

	// find sub index 
	window.get_slide_index = function ($li) {
		var num = 0, index = 0;
		if ($li.hasClass('secondary')) {

			var count = 0;
			do
			{
				var $temp = $li.prev();

				if ($temp.hasClass('secondary'))
				{
					$li = $temp;
					count++;
				} else {
					$li = $temp;		// 시작지점 찾기 
					break; 
				}
			}
			while (true);

			num = get_slide_num($li);
			index = count + 1; 
		} else {
			num = get_slide_num($li);
		}

		return [num, index];
	}

	window.setSelectedSlide = function (num, index) {
		num = num || 0; 
		index = index || 0; 
		window.isSelectedSlide = true; 

		active_item(get_slide_item(num, index));
	}

	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
                $("[value=" + data.access + "]").attr('checked', true);
				$("#title").val(data.title);
				$("#name").val(data.name);
				$("#description").val(data.description);
				$("#license").val(data.license || "None");
				//slideCode.setValue(data.slide_code || "");

				setPrSettings(JSON.parse(data.pr_settings));
				setSlideCode(JSON.parse(data.slide_code));

				var arr = data.selected_num.split("/");
				setSelectedSlide(+arr[0], +arr[1]);

				setResourceList(data.resources);
				setPreProcessorList(data.preprocessor);
				$(".slider-items > ul").sortable({ items : 'li'});
				coderun();
			});
		} else {
			updatePreProcessorList();
			
			new_slide_item();
		}


	}

	loadContent();

	
	$("#library").click(function() {

		fileListWin.show();

	});



	   window.previewSplitter = jui.create('ui.splitter', '.editor-area', {
		   initSize: '50%',
		   items : [ '.editor-left', '.editor-right' ]
	   });

	   $(".slider-items").on('drop', function (e) {
			e.preventDefault();
			console.log(e);

	   });

      window.fileDrop = jui.create("ui.filedrop", ".slider-items", {
            uploadParams : {
                id : '<?php echo $id ?>'
            },
            event : {
                success : function (data) {

                }
            }
      });

});
</script>

<div class='blockUI'>
	<div class='message'>Saving...</div>
</div>
