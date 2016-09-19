<style type="text/css">

/* custom select box */
.select {
	display:inline-block;
	z-index:5;
	position:relative;
	vertical-align: middle;
}

.select .title {
	border: 0px;
    background-image: none;
	background-color:white;
	color: black;
	border-color:#dfdfdf;
	border-radius:0px;
	font-size:13px;
	padding:5px 10px;
	padding-right:30px;
	line-height:17px;
	height: 25px;
	box-sizing:border-box;
	cursor:pointer;
	text-decoration:none;
	vertical-align:middle;
	overflow:hidden;
	min-width:100px;
	z-index:4;
	user-select:none;
	-webkit-user-select:none;
}

.select .items {
	position:absolute;
	border-color:#dfdfdf;
	border-width:1px;
	border-style:solid;
	z-index:1000;
	background-color: #ffffff;
	box-sizing:border-box;
	cursor:pointer;
	min-width:200px;
	max-height: 300px;
	pointer-events:none;
	opacity:0;
	overflow:auto;
	transition:opacity 0.3s ease-in-out;
	-webkit-transition:opacity 0.3s ease-in-out;
	padding-top:5px;
	padding-bottom:5px;
}

.select.open .items
{
	display:inline-block;
	pointer-events:auto;
	opacity:1;
}

.select.right .items {
	right: 0px;
}

.select .items .item {
	height: 25px;
	box-sizing:border-box;
	padding:8px;
	overflow:hidden;
	font-size:13px;
	user-select:none;
	-webkit-user-select:none;
}

.select .items .item:hover
{
	background-color: #9fe6d5;
}

.select .items .item.selected
{
	background-color: #48cfad;
	color:white;
}
</style>

<style type="text/css">

/* property */ 


.property-header {
	color: #48cfad;
	font-weight:400;
	font-size:12px;
	background-color:white;
}

.property-header .expand-btn {
	position:absolute;
	right:30px;
	top:0px;
	height:100%;
	font-size:30px;
	width:30px;
	line-height:45px;
}

.property-table {
	border-bottom:0px;
	width:100%;
}

.property-item {
    position: relative;
    height: 60px;
	box-sizing:border-box;
    border-top: 1px solid #ececec;
    border-bottom: 1px solid #ececec;
	padding:10px 10px;
	overflow:hidden;
	margin-top:-1px;
}

.property-item.expanded {
	z-index:2;
	height:61px;
	border-bottom:1px solid #fff !important;
}

.property-item.multi {
	height: auto;
	overflow:auto;
}

.property-header {
	height:100%;
	padding:10px 30px;
}

.property-table .property-item:last-child {
	border-bottom:0px;
}

.property-item:not(.property-header-item)  { 
		margin-left:30px;
		margin-right:30px;
}

.property-item .property-title {
	position:absolute;
	top:0px;
	left:0px;
	height:100%;
	font-size:12px;
	line-height:24px;
	padding:10px;
	color:#404040;
	font-weight:400;
	box-sizing:border-box;
}

.property-item .property-render {
	position:absolute;
	top:0px;
	right:0px;
	height:100%;
	padding:10px 10px 5px;
	box-sizing:border-box;
	text-align:right;
}

.property-item.multi .property-title {
	position:relative;
	display:block;
	width:100%;
	top:auto;
	left:auto;
	right:auto;
	height:auto;
	text-align:left;
	padding-top:0px;
	padding-bottom:0px;
}

.property-item.multi .property-render {
	position:relative;
	display:block;
	width:100%;
	top:auto;
	left:auto;
	right:auto;
	height:auto;
	text-align:left;
	padding-top:0px;
}

.property-item textarea,
.property-item .html[contenteditable] {
	 width: 100%;
	outline:none;
    border: 1px solid #a4a4a4;
	border-radius: 0px;
	font-size:13px;
	box-sizing:border-box;

}

.property-item textarea:focus,
.property-item .html[contenteditable]:focus
 {
	border-width:2px;
	border-color:#48cfad;
}

.property-item input[type=text]{
    width: 100%;
    border: 0px;
	height:30px;
	outline:none;
    border-bottom: 1px solid #a4a4a4;
	border-radius: 0px;
	font-size:13px;
	box-sizing:border-box;
}

.property-item input[type=text]:focus {
	border-width:2px;
	border-color:#48cfad;
}

.property-item input[type=number] {
    width:100px;
    border: 0px;
	height:30px;
	outline:none;
    border-bottom: 1px solid #48cfad;
    border-radius: 0px;
	text-align:right;
}

/* color */
.property-item .color-input {
	cursor:pointer;
	display: inline-block;
	box-sizing:border-box;
	margin-bottom : 5px;
	margin-right:5px;
}
.property-item .color-input > span:first-child {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.2);
    margin-right: 5px;
    vertical-align: middle;
}

.property-item .color-input > span:last-child {
	display: inline-block;
    height: 20px;
    font-size: 12px;
    line-height: 26px;
	font-weight:300;
    text-transform: uppercase;
}


/* range */
.property-item input[type=range] {
  -webkit-appearance: none;
  margin: 10px 0;
  width: 100%;
  position:relative;
  z-index:300;
}

.property-item input[type=range] + .range-progress {
	background: #48cfad;
	position: absolute;
    height: 2px;
    left: 0px;
    top: 50%;
    z-index: 2;
    margin-top: -1px;
    pointer-events: none;
    width: 30px;
}

.property-item input[type=range] + .range-progress + span {
	display: inline-block;
    vertical-align: middle;
	font-size:13px;
	margin-left:10px;
}

.property-item input[type=range]:focus {
  outline: none;
}
.property-item input[type=range]::-webkit-slider-runnable-track {
  width: 10%;
  height: 2px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
  background: rgba(67, 73, 84, 0.2);
  border: 0px solid rgba(67, 73, 84, 0.2);
}
.property-item input[type=range]::-webkit-slider-thumb {
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
  border: 0px solid #000000;
  height: 12px;
  width: 12px;
  border-radius: 50%;
  background: #48cfad;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -5px;
  z-index:3;
}
.property-item input[type=range]:focus::-webkit-slider-runnable-track {
  width:10%;
  /*background: #48cfad; */
}
.property-item input[type=range]::-moz-range-track {
  width: 100%;
  height: 2px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
  background: rgba(67, 73, 84, 0.1);
  border: 0px solid rgba(67, 73, 84, 0.1);

}
.property-item input[type=range]::-moz-range-thumb {
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
  border: 0px solid #000000;
  height: 12px;
  width: 12px;
  border-radius: 50%;
  background: #48cfad;
  cursor: pointer;
}
.property-item input[type=range]::-ms-track {
  width: 100%;
  height: 2px;
  cursor: pointer;
  animate: 0.2s;
  background: transparent;
  border-color: transparent;
  border-width: 2px 0;
  color: transparent;
}
.property-item input[type=range]::-ms-fill-lower {
  background:rgba(67, 73, 84, 0.1);
  border: 0px solid #000101;
  border-radius: 50px;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
}
.property-item input[type=range]::-ms-fill-upper {
  background: rgba(67, 73, 84, 0.1);
  border: 0px solid #000101;
  border-radius: 50px;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
}
.property-item input[type=range]::-ms-thumb {
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
  border: 0px solid #000000;
  height: 12px;
  width: 12px;
  border-radius: 50%;
  background: #48cfad;
  cursor: pointer;
}
.property-item input[type=range]:focus::-ms-fill-lower {
  background: #48cfad;
}
.property-item input[type=range]:focus::-ms-fill-upper {
  background: #48cfad;
}


</style>

<script type="text/javascript">
jui.defineUI("ui.select", ['jquery', 'util.base'], function ($, _) {
	var SelectView = function () {
			var self, $root, $selectView;
			var $title, $items; 
			var items = [];

			this.init = function () {
				self = this; 
				$root = $(this.root); 
				items = _.clone(this.options.items);

				if (!$root.hasClass('select')) {
					$root.addClass('select');
				}

				this.initSelect();
				this.update(items);
				this.setSelectedIndex(this.options.selectedIndex);

				this.initEvent();
			}

			this.initEvent = function () {
				// title 클릭 
				$root.on('click', '.title', function () {
					$root.toggleClass('open');
				});

				// item 클릭 
				$root.on('click', '.item.option', function () {
					self.setSelectedIndex(+$(this).data('index'));
				});

				$('body').on('click', function (e) {
					var $list = $(e.target).closest($root);

					if (!$list.length)
					{
						$root.removeClass('open');
					}


				});
			}

			this.initSelect = function () {
				$title = $("<div class='title' />");
				$items = $("<div class='items' />");


				$title.append($("<span />").addClass('title-content'));
				$title.append($("<img />").attr('src', '/v2/images/main/arrow1.svg').css({
					position: 'absolute',
					right: '10px',
					width: '8px',
					height: '8px',
					top: "50%",
					'margin-top': '-4px'				
				}));

				$root.addClass(this.options.align);

				$root.append($title).append($items);
			}

			this.render = function () {
				$title.find(".title-content").empty();
				$items.empty();
				
				for(var i = 0, len = items.length; i < len; i++) {
					var it = items[i];	

					var $item = $('<div class="item option " />');

					$item.attr('data-index', i);
					$item.attr('value', it.value);
					$item.text(it.text);

					if (it.selected)
					{
						$item.addClass('selected');
					}

					$items.append($item);
				}
			}

			this.setValue = function (value) {
				var i = 0;
				for(len = items.length; i < len; i++) {
					var it = items[i];

					if (it.value == value)
					{
						break; 
					}
				}

				this.setSelectedIndex(i);
			}

			this.getValue = function () {
				return this.getSelectedItem().value;
			}

			this.setSelectedIndex = function (index) {
				var prevItem = $items.find(".selected");

				if (+prevItem.data('index') == +index)
				{
					return;
				}

				if (!items[index]) return; 
				$root.removeClass('open');
				var $item = $items.find("[data-index=" + index + "]");

				$items.find(".selected").removeClass('selected');
				$item.addClass('selected');

				this.setTitle();

				this.emit("change", [ items[index].value, index, +prevItem.data('index') ] );

			}

			this.getSelectedIndex = function () {
				var index = +$items.find(".selected").data('index');

				return index; 
			}

			this.getSelectedItem = function () {
				var index = this.getSelectedIndex();

				if (items[index])
				{
					var it = items[index];
					return it; 
				}

				return items[0] || { text : '', value : '' } ;
			}


			this.setTitle = function () {
				$title.find(".title-content").text(this.getSelectedItem().text);
			}

			this.update = function (data) {
				items = _.clone(data);

				for(var i = 0, len = items.length; i < len; i++) {
					var it = items[i];

					if (typeof it == 'string') {
						items[i] = { text : it , value : it } 
					}
				}

				this.render();
				this.setTitle();
			}
	}

	SelectView.setup = function () {
		return {
			items : [],
			selectedIndex : 0,
			align: 'left'
		}
	}

	return SelectView;
});
</script>
<script type="text/javascript">
jui.defineUI("ui.property", ['jquery', 'util.base'], function ($, _) {
	var PropertyView = function () {

		var $root, $propertyContainer; 
		var items = [];
		var self;

		var renderer = {};

		function removeJuiComponent (ui) {
				var list = jui.getAll();

				var i = 0;
				for(len = list.length; i < len; i++) { 
					if (list[i][0] == ui) {
						break; 
					}
				}

				jui.remove(i);
		}

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

			$propertyContainer = $("<div class='property-table' />").css({
					'position' : 'relative'
			});

			$root.append($propertyContainer);

			this.loadItems(this.options.items);
		}

		this.loadItems = function (newItems) {
			items = _.clone(newItems);

			this.initProperty();

			this.emit("load.items");
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

		this.getGroupList = function () {
			var result = [];
			$propertyContainer.find(".property-header-item").each(function() {
				var it = $(this).data('item');
				result.push({  
					name : 	it.title,
					id : $(this).attr('id')
				});
			});

			return result ;
		}

		this.collapsed = function (id) {
			var $dom  = $root.find('#' + id);
			$dom.addClass('collapsed').removeClass('expanded');

			$dom.find('.expand-btn img').attr('src', '/v2/images/main/plus.svg');

			var $next = $dom.next();

			while($next.length && !$next.hasClass('property-header-item')) {
				$next.hide();
				$next = $next.next();	
			}
		}

		this.expanded = function (id) {
			// 접기 
			var $dom  = $root.find('#' + id);
			$dom.removeClass('collapsed').addClass('expanded');

			$dom.find('.expand-btn img').attr('src', '/v2/images/main/minus.svg');

			var $next = $dom.next();

			while($next.length && !$next.hasClass('property-header-item')) {
				$next.show();
				$next = $next.next();	
			}
		}

		this.renderItem = function (item, index) {

			var $dom = $("<div class='property-item' />").attr('data-index', index);

			if (item.type == 'group') {
				$dom.addClass('property-header-item collapsed');
				$dom.attr('id', 'property-header-item-' + index);
				$dom.data('item', item);
				var $name = $("<div class='property-header' />").html(item.title).css({
					'text-align': 'left',
					'font-size' : '13px'	,
					'cursor': 'pointer'
				});

				$name.append("<a class='expand-btn'><img src='/v2/images/main/plus.svg' /></a>");

				$dom.on('click', function (e) {
					if ($(this).hasClass('collapsed')) {
						self.expanded($dom.attr('id'));
					} else {
						self.collapsed($dom.attr('id'));
					}
				});

				$dom.append($name);

			} else {

				if (_.typeCheck("array", item.value) || item.multi)
				{
					$dom.addClass('multi');
				}

				$dom.attr('data-key', item.key).hide();	

				var $name = $("<div class='property-title'  />").html(item.title);
				var $input = $("<div class='property-render'  />");
				$input.append(
					$("<div class='item' />").html( this.render($dom, item) ) );


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

			if (!it) return; 

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

		/* Implements Item Renderer */ 
		renderer.str2array = function (value, splitter) {
			splitter = splitter || ",";
			if (typeof value == 'string')  {
				return value.split(splitter);
			}

			return value; 
		}

		renderer.defaultRenderer = function ($dom, item) {
			return renderer.text($dom, item);
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
				var value = $(this).val();
				value = (_.typeCheck('array', item.value)) ? renderer.str2array(value) : value; 

				self.refreshValue($(this).closest('.property-item'), value);
			}, 250, $input));

			return $input; 
		}		

		renderer.text = function ($dom, item) {
			var $input = $("<input type='text' />").css({
				width: '100%'	
			}).attr({
				placeholder : 'Type here'
			});
			$input.val(item.value);

			

			$input.on('input', debounce(function () {
				var value = $(this).val();
				value = (_.typeCheck('array', item.value)) ? renderer.str2array(value) : value; 

				self.refreshValue($(this).closest('.property-item'), value);
			}, 250, $input));

			return $input; 
		}

		renderer.textarea = function ($dom, item) {
			var $input = $("<textarea />").css({
				width: '100%',
				height: item.height || 100	
			}).attr({
				placeholder : 'Type here'
			});
			$input.val(item.value);

			$input.on('input', debounce(function () {
				var value = $(this).val();
				value = (_.typeCheck('array', item.value)) ? renderer.str2array(value) : value; 

				self.refreshValue($(this).closest('.property-item'), value);
			}, 250, $input));

			return $input; 
		}

		renderer.html = function ($dom, item) {
			var $input = $("<div class='html' contenteditable=true />").css({
				width: '100%',
				height: item.height || 100	
			}).attr({
				placeholder : 'Type here'
			});
			$input.val(item.value);

			$input.on('input', debounce(function () {
				var value = $(this).html();

				self.refreshValue($(this).closest('.property-item'), value);
			}, 250, $input));

			return $input; 
		}

		renderer.number = function ($dom, item) {
			var $input = $("<input type='number' />").css({
				'text-align' : 'center'
			});

			$input.attr('max', item.max || 100);
			$input.attr('min', item.min || 0);
			$input.attr('step', item.step || 1);
			$input.val(item.value);

			$input.on('input', debounce(function () {
				self.refreshValue($(this).closest('.property-item'), +$(this)[0].value);
			}, 250, $input));

			return $input; 
		}

		renderer.range = function ($dom, item) {

			var $group = $("<div />").css({
					position: 'relative'
			});

			var $input = $("<input type='range' />").css({
					width: '100px',
					'z-index' : 1 
			});

			value = item.value; 

			var postfix = item.postfix || "";

			if (item.postfix)
			{
				value = value.replace(postfix, "");
			}

			$input.attr('max', item.max || 100);
			$input.attr('min', item.min || 0);
			$input.attr('step', item.step || 1);
			$input.val(+value);

			var $progress = $("<div class='range-progress' />");
			$progress.width((value / (+$input.attr('max') - +$input.attr('min'))) * $input.width());

			var $inputText = $("<span />");
			$inputText.text(value +postfix);

			$input.on('input', function () {
				var $el = $(this);
				var value = +$el[0].value;
				var width = (value / (+$el.attr('max') - +$el.attr('min'))) * $(this).width();
				$progress.width(width);
				$inputText.text(value + postfix);
			});

			$input.on('input', debounce(function () {
				var $el = $(this);
				var value = +$el[0].value;
				self.refreshValue($el.closest('.property-item'), value + postfix);

			}, 250, $input));

			$group.append([ $input, $progress, $inputText ]);

			return $group; 
		}

		renderer.checkbox = function ($dom, item) {
			var $input = $("<input type='checkbox' />");

			$input[0].checked = (item.value == 'true' || item.value === true) ? true : false ;

			$input.on('click', debounce(function () {
				self.refreshValue($(this).closest('.property-item'), $(this)[0].checked);
			}, 250, $input));

			return $input; 
		}

		renderer.colors = function ($dom, item) {
			
			var colors = item.value;

			var arr = [];
			for(var i = 0, len = colors.length; i < len; i++) {
				var $input = renderer.color($dom, item, i);

				arr.push($input[0]);
			}

			return $(arr);
		}

		renderer.color = function ($dom, item, index) {
			index = typeof index == 'undefined' ? -1 : index;
			var $input = $("<a  class='color-input' />");

			var $container = $propertyContainer;
			var colorValue = index == -1 ? item.value : item.value[index];
			var $colorPanel = $("<span />").css({
				'background-color': colorValue,
			}).html('&nbsp;');

			var $colorCode = $("<span />").html(colorValue || '');

			$input.append($colorPanel);
			$input.append($colorCode);

			$input.on('click', function() {
				var offset = $(this).offset();

				var $colorPicker = $container.next('.colorpicker');

				if (!$colorPicker.length) {
					$colorPicker = $('<div class="colorpicker" />');

					$container.after($colorPicker);

					var colorpicker = jui.create('ui.colorpicker', $colorPicker, {
						color: colorValue,
						event: {
							 change: debounce(function() {
								 var color = colorpicker.getColor('hex');

								 if (color.indexOf('NAN') > -1)
								 {
									 return;
								 }
			
								 $colorPanel.css('background-color', color);
								 $colorCode.html(color);
						
								if (index == -1) {
									self.refreshValue($input.closest('.property-item'), color);
								}  else {
									var colors = item.value; 
									colors[index] = color; 
									self.refreshValue($input.closest('.property-item'), colors);
								}


							}, 100, colorpicker)
						}
					});


					$('body').on('click', function (e) {
						var $c = $(e.target).closest('.colorpicker');
						var $c2 = $(e.target).closest($input);
						if (!$c.length && !$c2.length) {

							removeJuiComponent(colorpicker);
							$colorPicker.remove();
						}
					});
				} else {
					$colorPicker[0].jui.setColor(colorValue || "");
				}

				var containerOffset = $container.offset()
				var maxWidth = $container.outerWidth();
				var maxHeight = $container.outerHeight();

				var left = offset.left - containerOffset.left;

				if (left + $colorPicker.outerWidth() >= maxWidth)
				{
					left = maxWidth - $colorPicker.outerWidth() - 20;
				}

				var top = offset.top -  containerOffset.top + 50;

				if (top + $colorPicker.outerHeight() >= maxHeight)
				{
					top = maxHeight - $colorPicker.outerHeight() - 20;
				}


				$colorPicker.css({
						position: 'absolute',
						'z-index' : 100000,
						left: left,
						top: top
				});
				$colorPicker.show();
	
			});

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


</script>
