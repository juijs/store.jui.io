<script>
$(function() {
	var url = (location.pathname == "/mylist.php") ? "load-my-box.php" : "load-box.php";
	var $container = $('#content-container');
	
	$container.masonry({
	  // options
	  itemSelector: '.summary-box',
		isFitWidth : true 
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

		var h = Math.floor($(window).height() / 303);
		var w = Math.floor($(window).width() / 238);
		var max = h * w + 1;

		$.get(url, { lastId : lastId, sort : sort, max : max }, function(data) {
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
		},2000);
	}, 1000);

    $(window).scroll(function(e) {
		var height = $(document.body)[0].scrollHeight - $(document.body)[0].scrollTop - $(window).height();

		if (height == 0) {
			loadLastList();
		}
    });
});
</script>
