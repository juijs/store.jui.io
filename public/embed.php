<?php 
include_once '../bootstrap.php';

// connect
$m = new MongoClient();

// select a rowbase
$db = $m->store;

$components = $db->components;

$row = $components->findOne(array(
	'_id' => new MongoId($_GET['id'])
));

$isMy = true;
if ($_GET['id'] && ($row['login_type'] != $_SESSION['login_type'] || $row['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}



if ($row['access'] == 'private' && !$isMy) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$only = $_GET['only'];

?>

<?php 

$title = $row['title'];
$id = (string)$row['_id'];
$description = str_replace("\r\n", "\\r\\n", $row['description']);
$username = $row['username'];

if (!$row['type']) $row['type'] = 'component';


$meta =<<<EOD
	<!-- Facebook -->
	<meta property="og:title" content="{$title}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="http://store.jui.io/share.php?id={$id}"/>
	<meta property="og:description" content="{$description}"/>
	<meta property="og:image" content="http://store.jui.io/thumbnail.php?id={$id}"/>

	<!-- Twitter -->
	<meta name="twitter:card"           content="summary_large_image">
	<meta name="twitter:title"          content="{$title}">
	<meta name="twitter:site"           content="@easylogic">
	<meta name="twitter:creator"        content="@{$username}">
	<meta name="twitter:image"          content="http://store.jui.io/thumbnail.php?id={$id}">
	<meta name="twitter:description"    content="{$description}">
	 
	<!-- Google -->
	<meta itemprop="name" content="{$title}">
	<meta itemprop="description" content="{$description}">
	<meta itemprop="image" content="http://store.jui.io/thumbnail.php?id={$id}">
EOD;


include_once "header.embed.php";


$type = $row['type'];
$first = $type_text[$type];

$color = $type_colors[$first];

?>
<style type="text/css">
html, body {
	background:white;
}

.CodeMirror {
	height: 100%;
}
</style>
<div id="content-container">
    <div class='nav <?php echo $only ? "result-only" : "" ?>'>
        <span style="float:left">
            <a class="btn-large nav-btn active" data-target="result" onclick="select(this)">Result</a>
			<a class="btn-large nav-btn" data-target="component" onclick="select(this)"><?php echo $first ?></a>
            <a class="btn-large nav-btn" data-target="sample" onclick="select(this)">Sample</a>
            <!--<a class="btn-large nav-btn" data-target="information" onclick="select(this)">Information</a> -->
        </span> 

        <span style="float:right">
            <a class='btn-large nav-btn nav-edit' href="/view.php?id=<?php echo $_GET['id'] ?>" target="_blank">Edit in JUI Store</a>
        </span>
    </div>
	<div class='nav-container <?php echo $only ? "result-only" : "" ?>' >
        <div id='result' class='nav-content active' style="overflow:<?php echo $only ? "hidden" : "auto" ?>" >
			<?php
			$type = $row['type'];
			$sample_type = $row['sample_type'];

			if ($type == 'style') {

				if (!$sample_type) {
					$sample_type = 'button';
				}
				?>
					<div style="padding:10px">
						<?php include __DIR__."/sample/ui/implements/{$sample_type}.html" ?>
					</div>
			<?php
			}
						
			?>		
		
		</div>
        <div id='sample' class='nav-content' >
            <textarea id="sample_code"></textarea>
        </div>
        <div id='component' class='nav-content'>
            <textarea id="component_code"></textarea>
        </div>
        <div id='information' class='nav-content' >
			<div class="title" style='margin-bottom:5px'><span class="simbol simbol-<?php echo $type ?>"><?php echo $first ?></span> <?php echo $row['title'] ? $row['title'] : '&nbsp;' ?></div>
			<div class="content" style="border:1px solid #ddd"><?php echo nl2br($row['description']) ?></div>
		</div>

	</div>

</div>

<script type="text/javascript">
$(function() {
   window.select = function(btn) {
        var target = $(btn).data('target');
        $(".nav-btn.active").removeClass("active");
        $(btn).addClass('active');

        $(".nav-content.active").removeClass("active");
        $("#" + target).addClass('active');

        componentCode.refresh();
        sampleCode.refresh();
   }

	var componentCode = window.componentCode = CodeMirror.fromTextArea($("#component_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
      readOnly : true
	});

	var sampleCode = window.sampleCode = CodeMirror.fromTextArea($("#sample_code")[0], {
	  mode:  "javascript",
	  lineNumbers : true,
      readOnly : true
	});


	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
				componentCode.setValue(data.component_code || "");
				sampleCode.setValue(data.sample_code || "");
			});
		}


	}

	loadContent();


});
</script>

<?php if ($row['type'] == 'style') { ?>
<link rel="stylesheet" href="sample/ui/css/jui.css" />
<link rel="stylesheet" href="generate.css.php?id=<?php echo $_GET['id'] ?>" />

<?php } else if ($row['type'] == 'map') { 

$map_link = "/generate.js.php?id=".$_GET['id'];
$sample_code = str_replace("@path", "'".$map_link."'", $row['sample_code']) ;

?>
<script type="text/javascript">
<?php echo $sample_code ?>
</script>

<?php  } else { ?>

<script type="text/javascript" src="generate.js.php?id=<?php echo $_GET['id'] ?>"></script>
<script type="text/javascript" src="generate.js.php?id=<?php echo $_GET['id'] ?>&code=sample"></script>
<script type="text/javascript">

jui.ready(function() { 

	// 테마 설정 
	var theme = '<?php echo $row['name'] ?>';
	if (theme.indexOf("chart.theme.") > -1) {
		var obj = $("#result")[0].jui;
		if (obj) {
			obj.setTheme(theme.replace("chart.theme.", ""));
		}
	}
});

</script>

<?php } ?>
<script type="text/javascript">
$(function() {

	setTimeout(function() {
		if (parent && parent.setContentHeight)
		{
			parent.setContentHeight($("#result")[0].scrollHeight);
		}
	}, 100);
});
</script>
</body>
</html>