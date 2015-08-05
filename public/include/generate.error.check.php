<script>
window.onerror = function(message, path, line, column, error) {

	var $noti = $('<div class="notify danger"><div class="image"><i class="icon-caution" style="font-size: 18px;"></i></div><div class="title"></div><div class="message"><pre></pre></div></div>');

	$noti.width($(window).width()-200);
	$noti.find(".title").html([message, path, line, column].join('-'));
	$noti.find(".message pre").html(error.stack);
	$noti.css({
		position: 'absolute',
		right : 10,
		bottom : 10
	});

	$("body").append($noti);

	var fulltext = $('html').html();
	var arr = fulltext.split("\n");

	var pos = {
		component : { start : -1, end : -1 },
		sample : { start : -1, end : -1 }
	}
	for(var i = 0, len = arr.length; i < len; i++) {
		if (arr[i].indexOf("component - start") > -1) { 
			pos.component.start = i; 
		}
		else if (arr[i].indexOf("component - end") > -1) { 
			pos.component.end = i; 
		}
		else if (arr[i].indexOf("sample - start") > -1) { 
			pos.sample.start = i; 
		}
		else if (arr[i].indexOf("sample - end") > -1) { 
			pos.sample.end = i; 
		}
	}

	if (pos.component.start > -1 && pos.component.end < 0) 	{
		var real_error_line = line-3-pos.component.start;
		var real_error_column = column;
		var real_error_message = message;

		parent.setError && parent.setError('component', real_error_line, real_error_column, real_error_message);
	} else if (pos.sample.start > -1 && pos.sample.end < 0) 	{
		var real_error_line = line - 3 - pos.sample.start;
		var real_error_column = column;
		var real_error_message = message;

		parent.setError && parent.setError('sample', real_error_line, real_error_column, real_error_message);
	} else if (pos.component.start < line-3 && line-3 < pos.component.end ) {

		var real_error_line = line-3-pos.component.start;
		var real_error_column = column;
		var real_error_message = message;

		parent.setError && parent.setError('component', real_error_line, real_error_column, real_error_message);
	} else if (pos.sample.start < line-3 && line-3 < pos.sample.end ) {
		var real_error_line = line - 3 - pos.sample.start;
		var real_error_column = column;
		var real_error_message = message;

		parent.setError && parent.setError('sample', real_error_line, real_error_column, real_error_message);
	}

	return true;
}
</script>