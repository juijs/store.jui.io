function login(type) {
	window.open("/auth/" + type + ".php", "social_login", "width=600,height=500,scrolling=no");
} 

// jui 객체 확장
var lastTheme = null;
function define(key, juiObject) {
	if (key.indexOf("chart.theme") > -1)
	{
		lastTheme = juiObject;
	}
}

define.amd = true; 

function good(id) {
	$.post('/good.php', { id : id}, function(res) {
		if (res.result)
		{
			$("#good_count_" + id).html(res.good);
		} else {
			alert(res.message ? res.message : "Failed to good count");
		}
	});
}

function toggleSns(sns) {
	$(".share-buttons").not(sns).hide();
	$(sns).next().toggle();
}

$(function() {
	$(document).click(function(e) {
					console.log(e.target);
		if (e.target.tagName == 'A' && $(e.target).hasClass('share-button'))
		{
			console.log(e.target);
		} else {
			$(".share-buttons").hide();
		}

	});
})