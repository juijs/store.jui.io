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
    padding: 0 12px;
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
			<a class="large nav-btn" data-target="component" onclick="select(this)"><?php echo $first ?></a>
			<?php if ($row['type']) {  ?>
			<a class="large nav-btn" data-target="sample" onclick="select(this)">JavaScript</a>
			<a class="large nav-btn" data-target="html" onclick="select(this)">HTML</a>
			<?php } else { ?>
            <a class="large nav-btn" data-target="sample" onclick="select(this)">Sample</a>
			<?php } ?>


            <!--<a class="btn-large nav-btn" data-target="information" onclick="select(this)">Information</a> -->
        </span> 

        <span style="float:right">
			<?php $link = ($row['access'] == 'share') ? "/" : "view.php?id=".$_GET['id'] ?>
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
					$sample_type = 'button';
				}
				?>
					<div style="padding:10px">
						<?php include __DIR__."/sample/ui/{$sample_type}.html" ?>
					</div>
			<?php
			} else if ($type == 'component' || $type == 'map') {
						
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


	function loadContent() {
		var id = '<?php echo $_GET['id'] ?>';

		if (id){
			$.get('/read.php', { id : id }, function(data) {
				componentCode.setValue(data.component_code || "");
				sampleCode.setValue(data.sample_code || "");
				htmlCode.setValue(data.html_code || "");
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
