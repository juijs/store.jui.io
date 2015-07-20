<?php $page_id = "generate"; ?>
<?php include_once "header.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

#result {
    position:absolute;
    width:100%;
    height:100%;
    
}
</style>
<div id="result"></div>
<script type="text/javascript">
<?php echo $_POST['component_code'] ?>

<?php echo $_POST['sample_code'] ?>

function createSampleImage() {
	var obj = $("#result")[0].jui;

	if (!obj) return;

	var svg = obj.svg;

    if (!svg) return;

	var img = new Image(),
		size = svg.size();

	var uri = svg.toDataURI()
		.replace('width="100%"', 'width="' + size.width + '"')
		.replace('height="100%"', 'height="' + size.height + '"');

	img.onload = function() {
		var canvas = document.createElement("canvas");
		canvas.width = img.width;
		canvas.height = img.height;

		var context = canvas.getContext('2d');
		context.drawImage(img, 0, 0);

		parent.setSampleImage && parent.setSampleImage(canvas.toDataURL("image/png"));
	}

	img.src = uri;
}

jui.ready(function() { 

	// 테마 설정 
	var theme = '<?php echo $_POST['name'] ?>';
	if (theme.indexOf("chart.theme.") > -1) {
		var obj = $("#result")[0].jui;
		if (obj) {
			obj.setTheme(theme.replace("chart.theme.", ""));
		}
	}


	setTimeout(function() { 
		createSampleImage();
	}, 2000);


});

</script>


<?php include_once "footer.php" ?>
