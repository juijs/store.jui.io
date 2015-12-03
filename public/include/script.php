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




});
</script>