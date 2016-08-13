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

	/* @Deprecated */
	window.viewFullscreen = function viewFullscreen(e) {
		var view = $(e.target).data('view');	
		var $view = $(".view-" + view);

		if ($view.hasClass('fullscreen'))
		{
			$view.removeClass('fullscreen');
			$view.css({
				'z-index' : 0
			});

			$view.find(".editor-tool .close").hide();

			coderun();
		} else {
			if ($view.hasClass('.editor-panel-pull')) return;

			$view.addClass('fullscreen');
			$view.css({
				'z-index' : 999999
			});

			if (view == 'component') {
				componentCode.refresh();
			} else if (view == 'sample') {

			} else if (view == 'result') {
				coderun();
			}

			$close = $view.find(".editor-tool .close");

			$close.show();

			$close.one('click', function() {
				$(this).parent().find("a.h2").dblclick();
			});
		}
	}


	$("a[data-view]").css({
		'cursor' : 'pointer',
		'-webkit-user-select' : 'none'
	}).on('click', viewFullscreen).attr('title', 'Click for fullscreen' );

	window.more_info_license = function() {
		var key = $("#license").val();
		var url = 'http://opensource.org/licenses/' + key;

		window.open(url, "_license");
	}

	var $splitter = $("<div class='editor-splitter' />").css({
		'position':'absolute',
		'top':'0px',
		'left' : ($(".editor-left").width()) + 'px',
		'width':'4px',
		'bottom':'0px',
		'background-color':'#ececec',
		'border-right':'1px solid #ddd',
		'cursor':'ew-resize'
	});
	$(".editor-area").append($splitter);


	$splitter.on('mousedown', function (e) {
		var $self = $(this);
		var $parent = $self.parent();
		var splitterWidth = $self.width();

		var offset = $parent.offset();

		var maxWidth = $parent.width();
		var $left = $(".editor-left");
		var $right = $(".editor-right");

		$left.css('user-select', 'none');
		$right.css('user-select', 'none');

		$left.find("iframe").css('pointer-events', 'none');
		$right.find("iframe").css('pointer-events', 'none');

		var prevClientX = e.clientX;

		function mouseMove(e) {
	
			var distX = e.clientX - prevClientX;
			var posX = parseFloat($self.css('left')) + distX;

			
			if (posX < 0) {
				posX = 0;
			} else if (posX > maxWidth) {
				posX = maxWidth; 
			}

			$self.css('left' , posX + 'px');
			$right.css('left' , (posX + splitterWidth) + 'px');

			$left.width(maxWidth - (maxWidth - $right.position().left) - splitterWidth);

			prevClientX = e.clientX; 
		}

		function mouseUp() {
			$(document).off('mousemove', mouseMove);
			$(document).off('mouseup', mouseUp);

			$left.css('user-select', '');
			$right.css('user-select', '');
			$left.find("iframe").css('pointer-events', 'auto');
			$right.find("iframe").css('pointer-events', 'auto');

			if (window.splitter_move_done)
			{
				window.splitter_move_done();
			}
		}

		$(document).on('mousemove', mouseMove);
		$(document).on('mouseup', mouseUp);

	})


});
</script>
