<?php @include_once PLUGIN."/$type/settings.php" ?>
<script type="text/javascript">


jui.defineUI("ui.property", ['jquery', 'util.base'], function ($, _) {
	var PropertyView = function () {

		var $root, $propertyContainer; 
		var items = [];
		var self;

		var renderer = {};

		function each(callback) {
			for(var i = 0, len = items.length; i < len; i++) {
				callback.call(self, items[i], i);
			}
		}

		// refer to underscore.js 
		function debounce(func, wait, context) {
			var timeout;
			return function() {
				var args = arguments;
				var later = function() {
					timeout = null;
					func.apply(context, args);
				};
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
			};
		};


		this.init = function () {
			self = this; 
			$root = $(this.root); 
			items = _.clone(this.options.items);

			$propertyContainer = $("<table class='table simple' />");

			$root.append($propertyContainer);

			this.initProperty();
			
		}

		this.initProperty = function () {

			// 정렬 방식에 따라 그리는 방법이 다르다. 
			$propertyContainer.empty();

			each(function (item, index) {
				$propertyContainer.append(this.renderItem(item, index));
			});
		}

		this.addItem = function (item) {


			if (!_.typeCheck('array', item)) {
				item = [item];
			}
			items = items.concat(item);

			// 정렬에 따라 렌더링이 달라짐 
			// add 하면 전체를 새로 그려야겠다. 

			this.initProperty();
		}

		// remove item by key or title
		this.removeItem = function (item) {
			var result = [];
			for(var i = 0, len = items.length; i < len; i++) {
				var it = items[i];

				if (it.key == item.key || it.title == item.title ) {
					result.push(it);
				}
			}

			items = result; 
		}

		this.renderItem = function (item, index) {

			var $dom = $("<tr class='property-item' />").attr('data-index', index);
			if (item.type == 'group') {
				var $name = $("<th class='property-header' colspan='2'  />").html(item.title).css({
					'text-align': 'left',
					'font-size' : '13px'	
				});;
				$dom.append($name);

			} else {
				$dom.attr('data-key', item.key);	

				var $name = $("<th class='property-title'  />").html(item.title).css({
					'text-align' : 'right',
					'padding-right' : 20
				});;
				var $input = $("<td class='property-render'  />");
				$input.append($("<div class='item' />").html(this.render($dom, item)));


				if (item.description)
				{
					$input.append("<div class='description' >"+item.description+"</div>");
				}

				$dom.append($name);
				$dom.append($input);
			}

			return $dom; 
		}

		this.render = function ($dom, item) {

			var type = item.type || 'text';
			var render = item.render || renderer[type] || renderer.defaultRenderer;

			return render($dom, item);
		}

		this.getValue = function (key) {
			if (key) {
				return this.getItem(key).value;
			} else {
				return this.getAllValue();
			}
		}

		this.getDefaultValue = function () {
			var result = {};
			for(var i = 0, len = this.options.items; i < len; i++) {
				var it = this.options.items[i];

				if (typeof it.value != 'undefined') {
					result[it.key] = it.value;
				}
			}

			return result; 
		}

		this.initValue = function (obj) {
			each(function (item, index) {
				item.value = '';
			});

			this.initProperty();

			if (obj) {
				this.setValue(obj);
			}
		}

		this.setValue = function (obj) {
			obj = obj || {};
			if (Object.keys(obj).length) {
				for(var key in obj) {
					this.updateValue(key, obj[key]);
				}
			}
		}

		this.findRender = function (key) {
			return this.findItem(key).find(".property-render .item");
		}
		this.findItem = function (key) {
			return $propertyContainer.find("[data-key='"+key+"']");
		}
		this.getItem = function ($item) {
			var item;

			if (_.typeCheck("number", $item)) {
				item = items[$item];
			} else if (_.typeCheck('string', $item)) {
				item = items[parseInt(this.findItem($item).attr('data-index'))];
			} else {
				item = items[parseInt($item.attr('data-index'))];
			}

			return item; 
		}

		this.updateValue = function (key, value) {
			var $item = this.findItem(key);
			var it = this.getItem(key);
			it.value = value; 

			var $render = this.findRender(key);

			$render.empty();
			$render.html(this.render($item, it));
		}

		this.getAllValue = function (key) {
			var results = {};
			each(function (item, index) { 
				if (item.type !== 'group') {
					results[item.key] = item.value; 
				}
			});

			return results; 
		}

		this.refreshValue = function ($dom, newValue) {
			var item = this.getItem($dom);

			var oldValue = item.value;
			item.value = newValue;

			this.emit("change", [ item, newValue, oldValue ] );
		}

		// implements renderer for type 
		renderer.defaultRenderer = function ($dom, item) {
			return $("<span >").html(item.value);
		}

		renderer.select = function ($dom, item) {
			var $input = $("<select class='input' />").css({
				width: '100%'	
			});

			var list = item.items || [];

			for(var i = 0, len = list.length; i < len; i++) {
				var it = list[i];

				if (typeof it == 'string') {
					it = { text : it, value : it } 
				}

				$input.append($("<option >").val(it.value).text(it.text));
			}

			$input.val(item.value);

			$input.on('change', debounce(function () {
				self.refreshValue($(this).closest('.property-item'), $(this).val());
			}, 250, $input));

			return $input; 
		}		

		renderer.text = function ($dom, item) {
			var $input = $("<input type='text' class='input' />").css({
				width: '100%'	
			});
			$input.val(item.value);

			$input.on('input', debounce(function () {
				self.refreshValue($(this).closest('.property-item'), $(this).val());
			}, 250, $input));

			return $input; 
		}

		renderer.number = function ($dom, item) {
			var $input = $("<input type='number' class='input' />").css({
				width: '100%'	
			});
			$input.val(item.value);

			$input.on('input', debounce(function () {
				self.refreshValue($(this).closest('.property-item'), +$(this)[0].value);
			}, 250, $input));

			return $input; 
		}

		renderer.checkbox = function ($dom, item) {
			var $input = $("<input type='checkbox' />");

			$input[0].checked = (item.value == 'true' || item.value === true) ? true : false ;

			$input.on('click', debounce(function () {
				self.refreshValue($(this).closest('.property-item'), $(this)[0].checked);
			}, 250, $input));

			return $input; 
		}
	}

	PropertyView.setup = function () {
		return {
			sort : 'group', // name, group, type 
			viewport : 'default',
			items : []
		}
	}

	return PropertyView;

});

$(function() {

	var ExternalItemTemplate = window.ExternalItemTemplate = '<span title="drag me for ordering" class="handle" draggable="true"><i class="icon-dashboardlist"></i></span><input type="text" placeholder="//myhost.com/my.js" class="input" /><a class="btn small"><i class="icon-exit"></i></a>';

	/*
	var sampleCode = window.sampleCode = CodeMirror.fromTextArea($("#sample_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  }
	});

	var htmlCode = window.htmlCode = CodeMirror.fromTextArea($("#html_code")[0], {
	  mode:  "htmlmixed",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
	  }
	});
	 */
	var slideCode = window.slideCode = CodeMirror.fromTextArea($("#slide_code")[0], {
	  mode:  "markdown",
	  lineNumbers : true,
	  extraKeys: {
		  "Ctrl-S":  function () {  savecode(); },
		  "Ctrl-R":  function () {  coderun(); }
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
	});

    slideNote.on('keyup', function () {
		if (window.isSelectedSlide)
		{
			window.isSelectedSlide = false; 
		}
	});

	slideCode.on('change', function () {
		get_item().data('content', slideCode.getValue());
		if (!window.isSelectedSlide) {
			coderun();
		}
	  })

	slideNote.on('change', function () {
		get_item().data('note', slideNote.getValue());
		if (!window.isSelectedSlide) coderun();
	  })

    jui.create('ui.tab', '#tab_slide_settings', {
		target: ".slider-content"
	});

    jui.create('ui.tab', '#tab_pr_settings', {
		target: ".pr-content"
	});

	window.prSettings = jui.create("ui.property", '#pr-settings', {
		items : [
			{ type : 'group', title : 'Views'},
			{ type : 'select', title : 'Theme', key : 'theme' , value : 'white', items : ['white', 'black', 'league', 'beige', 'sky', 'night', 'serif', 'simple', 'solarized' ] },
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
			{ type : 'select', title : 'Transition style', key : 'transition' , value : 'default', items : ['default', 'none', 'fade', 'slide', 'convex', 'concave', 'zoom' ] },
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
			{ title : 'Background Color', key : 'background-color' },
			{ title : 'Background Image', key : 'background-image' },
			{ title : 'Background Size', key : 'background-size' },
			{ title : 'Background Position', key : 'background-position' },
			{ title : 'Background Repeat', key : 'background-repeat' },
			{ title : 'Background Video', key : 'background-video' },
			{ title : 'Background Video Loop', key : 'background-video-loop' },
			{ title : 'Background Video Mutied', key : 'background-video-muted' },
			{ title : 'Background Iframe', key : 'background-iframe' },
			{ type : 'group', title : 'Transition' }, 
			{ title : 'Transition Type', key : 'transition', value : 'slide-in fade-out' },
			{ title : 'Transition Speed', key : 'transition-speed', value: 'fast' }
		],
		event : {
			change : function (item, newValue, oldValue) {
				get_item().data('settings', this.getValue());
				coderun();
			}
		}
	});

	

	$(".add-btn").on('click', function () {
		new_slide_item();
		$(".slider-items > ul").sortable({ });
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

	function new_slide_item(obj, isNoneActive) {
		obj = obj || {};
		$selected = get_item();

		var content = obj.content || 'welcome';
		var $li = $("<li ></li>").data({
			name : obj.name || 'new slider',
			content: content,
			note : obj.note || '', 
			secondary : obj.secondary || false,
			settings : obj.settings || sliderSettings.getDefaultValue()   // slider 기본 설정 추가 
		}).html(get_first_line(content));

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
		$("iframe[name=chart_frame]")[0].contentWindow.setSelectedNum(get_slide_index($li));
	}

	// 슬라이드 드래그 이후 실행 
	$(".slider-items > ul").on('sortupdate', function (e, ui) { 
		coderun();
	});

	$("#slide_code").on('change', function () {

		get_item().data('content', slideCode.getValue());
		//coderun();
	});

	function active_item ($li) {

		if (!$li) return; 

		$('.slider-items .selected').removeClass('selected');
		$li.addClass('selected');


		refresh_content($li.data());
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

		coderun();

	}



	function refresh_content(data) {
		var $selected = get_item();
		slideCode.setValue($selected.data('content') + "");
		slideNote.setValue($selected.data('note') + "");

		sliderSettings.setValue($selected.data('settings'));
	}

	window.code_name_list = {
		// html 
		"markdown": "Markdown",
		"html": "HTML",
		"haml": "Haml",
		"jade": "Jade",

		// script 
		"javascript": "Javascript",
		"coffeescript": "Coffescript",
		"typescript": "Typescript",

		// css 
		"css": "CSS",
		"stylus": "stylus",
		"less": "LESS",
		"scss": "SCSS"

	};

	window.slide_coderun_timer = null;
	window.coderun = function coderun () {

		if (slide_coderun_timer) {
			clearTimeout(slide_coderun_timer);
		}

		slide_coderun_timer = setTimeout(function () { 

			removeError();

			$("#chart_form [name=resources]").val(getResourceList());
			//$("#chart_form [name=preprocessor]").val(getPreProcessorList());

		
			$("#chart_form [name=pr_settings]").val(getPrSettingsView());
			$("#chart_form [name=slide_code]").val(getSliderView());
			$("#chart_form [name=selected_num]").val(getSliderSelectedNum());
			$("#chart_form").submit();
		}, 1000);	
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

	<?php include_once INC."/error.view.php" ?>

	window.savecode = function savecode() {

		$(".blockUI").show();

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

		$.post("/save.php", data, function(res) {
			$(".blockUI").hide();

			if (res.result)
			{
				location.href = '?id=' + res.id; 	
			} else {
				alert(res.message ? res.message : 'Failed to save');
			}
		});
	}

	window.deletecode = function deletecode () {
		if (confirm("Delete this slide?\r\ngood count is also deleted.")) {
			$.post("/delete.php", { id : '<?php echo $_GET['id'] ?>' }, function(res) {
				if (res.result) {
					location.href = '/mylist.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}

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

});
</script>

<div class='blockUI'>
	<div class='message'>Saving...</div>
</div>
