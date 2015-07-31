<script type="text/javascript">
$(function() {
   window.viewAccessMessage = function viewAccessMessage() {
        var access = $("[name=access]:checked").val();
        if (access == 'private') {
            $("#access_message").html('Only you can see this component.').css({
                color : 'red'        
            });
        } else {
            $("#access_message").html("Anyone can see this component.").css({color : 'blue'});
        }
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