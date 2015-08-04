<script>
$(function() {
	var url = (location.pathname == "/mylist.php") ? "load-my-box.php" : "load-box.php";
	var $container = $('#content-container');
	
	$container.masonry({
	  // options
	  itemSelector: '.summary-box',
	  isFitWidth: true,
	  transitionDuration: '0.8s'
	});

	window.lazyLoadFrame = function() {
		if (window.isLazyLoad) return;

		window.isLazyLoad = true;
		$("iframe[data-src]").each(function(i) {
			var $self = $(this); 

			(function($self, i) {
				setTimeout(function() {
					$self.attr('src', $self.attr('data-src'));
					$self.removeAttr('data-src');
				}, 200*i);
			})($self, i);

		});

		window.isLazyLoad = false;
	}

	lazyLoadFrame();

	window.loadLastList = function loadLastList() {
		var lastId = $(".summary-box:last").data('id');
		var sort = '<?php echo $sort_type ?>';

		$.get(url, { lastId : lastId, sort : sort }, function(data) {
	        var $moreBlocks = jQuery( data );

		    $container.append( $moreBlocks );

			lazyLoadFrame();

	        $container.masonry( 'appended', $moreBlocks );         
		});
	}

	setTimeout(function() {
		loadLastList();

		setTimeout(function() {
			loadLastList();
		},1000);
	}, 1000);

    $(window).scroll(function(e) {
		var height = $(document.body)[0].scrollHeight - $(document.body)[0].scrollTop - $(window).height();

		if (height == 0) {
			loadLastList();
		}
    });
});
</script>
