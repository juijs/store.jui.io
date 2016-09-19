<?php 
$data = $row; 

$title = $data['title'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];

//include V2_PLUGIN."/style/meta.php";

$metaList[] =<<<EOD
	<!-- Facebook -->
	<meta property="og:title" content="{$title}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="//store.jui.io/view.php?id={$id}"/>
	<meta property="og:description" content="{$description}"/>
	<meta property="og:image" content="//store.jui.io/thumbnail.php?id={$id}"/>

	<!-- Twitter -->
	<meta name="twitter:card"           content="summary_large_image">
	<meta name="twitter:title"          content="{$title}">
	<meta name="twitter:site"           content="@easylogic">
	<meta name="twitter:creator"        content="@{$username}">
	<meta name="twitter:image"          content="//store.jui.io/thumbnail.php?id={$id}">
	<meta name="twitter:description"    content="{$description}">
	 
	<!-- Google -->
	<meta itemprop="name" content="{$title}">
	<meta itemprop="description" content="{$description}">
	<meta itemprop="image" content="//store.jui.io/thumbnail.php?id={$id}">
EOD;

$metaList[] = '<link rel="stylesheet" href="/jui-all/jui/dist/ui.min.css" />';
$metaList[] = '<link rel="stylesheet" href="/jui-all/jui/dist/ui-'.$_POST['theme_name'].'.min.css" />';
$metaList[] = '<script type="text/javascript" src="/jui-all/jui-core/dist/core.min.js"></script>';
$metaList[] = '<script type="text/javascript" src="/jui-all/jui/dist/ui.min.js"></script>';

$meta = implode(PHP_EOL, $metaList);

include V2_PLUGIN."/style/header.static.php";

$backgroundColor = 'white';

if ($data['theme_name'] == 'dark') {
	$backgroundColor = 'black';
}

$sample_type = $data['sample_type'];

if (!$sample_type) {
	$sample_type = "buttons";
}

$id = uniqid("temp".rand(0, 100));
$code = $_POST['component_code'];

$defaultTheme = "jui-all/jui/less/theme/jennifer.less";
$filename = "jui-all/jui/less/theme/".$id.".less";

$realCode = str_replace('@import "../theme.less";
', '', file_get_contents($defaultTheme)).PHP_EOL.$code;

file_put_contents(ABSPATH."/".$filename, $realCode);

try {
	$parser = new Less_Parser();
	$parser->parseFile(ABSPATH."/".$filename );
	$result = $parser->getCss();

    $result = str_replace("../img", "/img", $result);
    $result = str_replace("../widget/img", "/img", $result);

} catch (Exception $ex) {

	echo $ex->getMessage();
}

unlink($filename);



?>
<style type="text/css">
html, body {
	background:white;
}


/* Base */
.jui-style section:not(:first-child) {
	margin-top: 50px;
}

.jui-style section:first-child {
	margin-top: -25px;
}

.jui-style section:last-child {
	margin-bottom: 75px;
}

/* Sentense */
.jui-style p {
    font-family: "Noto Sans", "Nanum Gothic", "Dotum", "sans-serif";
	color: #666;
	font-size: 0.9em;
	line-height: 1.6em;
}

.jui-style p.br {
	margin-top: 25px;
}

/* Header */
.jui-style h1 {
	font-size: 32px;
	margin-top: -10px;
	margin-bottom: 0;
	color: #8772bf;
	font-weight: 500;
	border-bottom: 1px solid #eee;
	padding-bottom: 9px;
}

.jui-style h2 {
	font-size: 24px;
	color: #2b3340;
	margin-top: 30px;
	font-weight: 400;
}

.jui-style h3 {
	font-size: 18px;
	color: #666;
	margin-top: 30px;
	margin-bottom: 0px;
	font-weight: 400;
}

/* Tables */
.jui-style table:not(.table):not(.xtable):not(.body) {
    font-family: "Noto Sans", "Nanum Gothic", "Dotum", "sans-serif";
	width: 100%;
	margin: 0px;
	padding: 0px;
	border: 1px solid #dcdcdc;
	 -webkit-border-radius: 2px;
	 -moz-border-radius: 2px;
	      border-radius: 2px;
	border-spacing: 0;
	border-collapse: collapse;
}
.jui-style table:not(.table):not(.xtable):not(.body) {
	width: 100%;
	height: 100%;
	margin: 0px;
	padding: 0px;
}
.jui-style table:not(.table):not(.xtable):not(.body) tr:nth-child(odd) {
	background-color:#fafafa;
}
.jui-style table:not(.table):not(.xtable):not(.body) tr:nth-child(even) {
	background-color:#ffffff;
}
.jui-style table:not(.table):not(.xtable):not(.body) td {
	vertical-align: middle;
	border: 1px solid #dcdcdc;
	border-width: 0px 1px 1px 0px;
	text-align: left;
	padding: 10px;
	font-weight: normal;
	font-size: 12px;
	color: #000000;
}
.jui-style table:not(.table):not(.xtable):not(.body) tr:last-child td{
	border-width: 0px 1px 0px 0px;
}
.jui-style table:not(.table):not(.xtable):not(.body) tr td:last-child{
	border-width: 0px 0px 1px 0px;
}
.jui-style table:not(.table):not(.xtable):not(.body) tr:last-child td:last-child{
	border-width: 0px 0px 0px 0px;
}
.jui-style table:not(.table):not(.xtable):not(.body) tr:first-child td{
	background-color: #f5f5f5;
	text-align: center;
	font-size: 13px;
	font-weight: bold;
}


.jui-style.dark {
    background-color: #1c1c1c;
}
.jui-style.dark h2 {
    color: #d5d5d5 !important;
}

.jui-style.dark table:not(.table):not(.xtable):not(.body) tr:first-child td {
    color: #fff !important;
}

.jui-style.dark table:not(.table):not(.xtable):not(.body) td {
    color: #666 !important;
    border: 1px solid #666 !important;
}

.jui-style.dark table:not(.table):not(.xtable):not(.body) tr:nth-child(odd),
.jui-style.dark table:not(.table):not(.xtable):not(.body) tr:first-child td {
    background-color: #2c2c2c !important;
}

.jui-style.dark table:not(.table):not(.xtable):not(.body) tr:nth-child(even) {
    background-color: #262626 !important;
}

.jui-style.dark .col {
    color: #666 !important;
} 


</style>
<div id="result">
<div style="padding:10px" class="jui-style <?php echo $data['theme_name'] ?>">
		<?php include_once ABSPATH."/sample/ui/{$sample_type}.html" ?>
	</div>
</div>

</body>
</html>
