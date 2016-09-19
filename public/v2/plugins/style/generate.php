<?php $page_id = "generate"; ?>
<?php 
error_reporting(E_ALL);

include_once "../../../../bootstrap.php";

header('X-XSS-Protection: 0');

$data = $_POST;

$metaList[] = '<link rel="stylesheet" href="/jui-all/jui/dist/ui.min.css" />';
$metaList[] = '<link rel="stylesheet" href="/jui-all/jui/dist/ui-'.$_POST['theme_name'].'.min.css" />';
$metaList[] = '<script type="text/javascript" src="/jui-all/jui-core/dist/core.min.js"></script>';
$metaList[] = '<script type="text/javascript" src="/jui-all/jui/dist/ui.min.js"></script>';
$meta = implode(PHP_EOL, $metaList);

$backgroundColor = 'white';

if ($_POST['theme_name'] == 'dark') {
	$backgroundColor = 'black';
}

$sample_type = $_POST['sample_type'];

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
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>Embeded - Store for JUI</title>
	<link rel="shortcut icon" href="//store.jui.io/favicon.ico" type="image/x-icon">
	<link rel="icon" href="//store.jui.io/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="//store.jui.io/bower_components/jquery/dist/jquery.min.js"></script>
	<?php echo $meta ?>
	<style type="text/css">
	<?php echo $result ?> 
	</style>
</head>
<body class="jui">
<?php include_once V2_INC."/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:<?php echo $backgroundColor ?>;
    margin:0px;
    padding:0px;
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
<div style="padding:10px" class="jui-style <?php echo $_POST['theme_name'] ?>">
		<?php include_once ABSPATH."/sample/ui/{$sample_type}.html" ?>
	</div>
</div>

</body>
</html>
