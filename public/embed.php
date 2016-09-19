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


$embed_path = V2_PLUGIN."/{$row['type']}/embed.php";

if (file_exists($embed_path)) {
	include_once $embed_path;
	exit;
}


$metaList = array();
$arr = explode(",", $row['resources']);
foreach($arr as $val) {
	$ext = strtolower(array_pop(explode(".", $val)));

	if ($ext == 'css') {
		$metaList[] = "<link rel='stylesheet' href='".$val."' />";
	} else {
		$metaList[] = "<script type='text/javascript' src='".$val."'></script>";
	}
}

include_once "include/generate.meta.php";


$metaList[] =<<<EOD
	<!-- Facebook -->
	<meta property="og:title" content="{$title}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="http://store.jui.io/view.php?id={$id}"/>
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

$meta = implode(PHP_EOL, $metaList);

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

.nav-btn {
	position: relative;
    display: inline-block;
    vertical-align: middle;
    cursor: pointer;
    text-decoration: none;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 12px;
    height: 28px;
    line-height: 28px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
	width:70px;
	text-align:center;
}

.nav-btn.nav-edit {
	width:inherit;
}

.nav-btn.large {

}
</style>
<div id="content-container">
    <div class='nav <?php echo $only ? "result-only" : "" ?>'>
        <span style="float:left">
            <a class="large nav-btn active" data-target="embedResult" onclick="select(this)">Result</a>
			<?php if ($row['type'] != 'page') {  ?>
			<a class="large nav-btn" data-target="component" onclick="select(this)"><?php echo $first ?></a>
			<?php } ?>
			<?php if ($row['type']) {  ?>
			<a class="large nav-btn" data-target="sample" onclick="select(this)">JavaScript</a>
				<?php if ($row['type'] == 'component' || $row['type'] == 'page') { ?>
					<a class="large nav-btn" data-target="css" onclick="select(this)">CSS</a>
				<?php } ?>
			<a class="large nav-btn" data-target="html" onclick="select(this)">HTML</a>
			<?php } else { ?>
            <a class="large nav-btn" data-target="sample" onclick="select(this)">Sample</a>
			<?php } ?>


            <!--<a class="btn-large nav-btn" data-target="information" onclick="select(this)">Information</a> -->
        </span> 

        <span style="float:right">
			<?php $link = "view.php?id=".$_GET['id'] ?>
            <a class='large nav-btn nav-edit' href="<?php echo $link ?>" target="_blank">Edit in JUI Store</a>
        </span>
    </div>
	<div class='nav-container <?php echo $only ? "result-only" : "" ?>' >
        <div id='embedResult' class='nav-content active' style="overflow:<?php echo $only ? "hidden" : "auto" ?>" >
			<?php
			$type = $row['type'];
			$sample_type = $row['sample_type'];

			if ($type == 'style') {

				if (!$sample_type) {
					$sample_type = 'buttons';
				}
				?>
					<div style="padding:10px">
						<?php include __DIR__."/sample/ui/{$sample_type}.html" ?>
					</div>
			<?php
			} else if (in_array($type, array('component', 'map', 'page'))) {
						
				echo $row['html_code'];
			}
			?>		
		
		</div>
        <div id='sample' class='nav-content' >
            <textarea id="sample_code"></textarea>
        </div>
        <div id='html' class='nav-content' >
            <textarea id="html_code"></textarea>
        </div>
        <div id='css' class='nav-content' >
            <textarea id="css_code"></textarea>
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
        htmlCode.refresh();

		if (cssCode)
		{
		cssCode.refresh();
		}

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


	var htmlCode = window.htmlCode = CodeMirror.fromTextArea($("#html_code")[0], {
	  mode:  "htmlmixed",
	  lineNumbers : true,
      readOnly : true
	});

<?php if ($row['type'] == 'component' || $row['type'] == 'page') { ?>
	var cssCode = window.cssCode = CodeMirror.fromTextArea($("#css_code")[0], {
	  mode:  "css",
	  lineNumbers : true,
      readOnly : true
	});
<?php } ?>
	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
				componentCode.setValue(data.component_code || "");
				sampleCode.setValue(data.sample_code || "");
				htmlCode.setValue(data.html_code || "");
				<?php if ($row['type'] == 'component' || $row['type'] == 'page') { ?>
				cssCode.setValue(data.css_code || "");

				var arr = data.preprocessor.split(",");

				var obj = {
					less : 'LESS',
					scss : 'SCSS',
					css : 'CSS',
					javascript: 'JavaScript',
					html : 'HTML',
					jade : 'Jade',
					markdown : 'Markdown'
				}

				$("[data-target=html]").html(obj[arr[0]]);      // html 
				$("[data-target=sample]").html(obj[arr[1]]);  // javascript 
				$("[data-target=css]").html(obj[arr[2]]);          // css 
					
				if (componentCode.getValue() == '') { $("[data-target=component]").hide(); $("#component").hide(); }
				if (htmlCode.getValue() == '') { $("[data-target=html]").hide(); $("#html").hide(); }
				if (sampleCode.getValue() == '') { $("[data-target=sample]").hide(); $("#sample").hide(); }
				if (cssCode.getValue() == '') { $("[data-target=css]").hide(); $("#css").hide(); }

				<?php } ?>


			});
		}


	}

	loadContent();


});
</script>

<?php if ($row['type'] == 'style') { ?>
<link rel="stylesheet" href="generate.css.php?id=<?php echo $_GET['id'] ?>" />

<?php } else if ($row['type'] == 'map') { 

$map_link = "/generate.js.php?id=".$_GET['id'];
$sample_code = str_replace("@path", "'".$map_link."'", $row['sample_code']) ;

?>
<script type="text/javascript">
<?php echo $sample_code ?>
</script>

<?php  } else { ?>
<link rel="stylesheet" href="generate.js.php?id=<?php echo $_GET['id'] ?>&code=css" />
<script type="text/javascript" src="generate.js.php?id=<?php echo $_GET['id'] ?>"></script>
<script type="text/javascript" src="generate.js.php?id=<?php echo $_GET['id'] ?>&code=sample"></script>
<script type="text/javascript">

jui.ready(function() { 

	// 테마 설정 
	var theme = '<?php echo $row['name'] ?>';
	if ('<?php echo $row['type'] ?>' == 'theme') {
		var obj = $("#embedResult")[0].jui;
		if (obj) {
			obj.setTheme(theme);
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
			parent.setContentHeight($("#embedResult")[0].scrollHeight);
		}
	}, 100);
});
</script>
</body>
</html>
