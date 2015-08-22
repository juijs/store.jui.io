<?php $page_id = 'component'; 

?>

<?php include_once "header.php";



// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$data = $components->findOne(array('_id' => new MongoId($_GET['id'])));

$isMy = true;
if ($_GET['id'] && ($data['login_type'] != $_SESSION['login_type'] || $data['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}



?>

<script type="text/javascript">

function showFullscreen(type) {
	$(".view-" + type).addClass('fullscreen').css({ 'z-index' : 99999 })
	componentCode.refresh();
	sampleCode.refresh();
}

function closeFullscreen(type) {
	$(".view-" + type).removeClass('fullscreen').css({ 'z-index' : 0 })
	componentCode.refresh();
	sampleCode.refresh();
}

function changeLayout (type) {
	if (type == 'module+result') {
		showFullscreen('component');
		showFullscreen('result');
		closeFullscreen('sample');
		closeFullscreen('information');
	} else if (type == 'sample+result') {
		closeFullscreen('component');
		showFullscreen('result');
		showFullscreen('sample');
		closeFullscreen('information');
	} else if (type == 'module+sample+result') {
		closeFullscreen('component');
		showFullscreen('result');
		closeFullscreen('sample');
		closeFullscreen('information');
	} else if (type == 'all') {
		closeFullscreen('component');
		closeFullscreen('result');
		closeFullscreen('sample');
		closeFullscreen('information');
	}
}
</script>

<style type="text/css">
.CodeMirror {
	height: 100%;
}
.lint-error {font-family: arial; font-size: 70%; background: #ffa; color: #a00; padding: 2px 5px 3px; }
.lint-error-icon {color: white; background-color: red; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 7px;}
body { overflow: hidden; }
</style>

<div class="editor-container view-all">
	<div class="editor-content has-toolbar">
		<div class="editor-toolbar">
			<div style='float:left;padding:10px;'>
				<a class="btn" id="library"><i class='icon-search'></i> Import</a>
			</div>
			<div class="group" style="float:right;padding:10px;">
				<a href="javascript:void(changeLayout('module+result'))" class="btn" title="module + result">
					<svg width="30" height="20" style="margin-top:3px;">
						<rect x="0" y="0" width="15" height="20" stroke="black" stroke-width="1" fill="white" />
						<rect x="15" y="0" width="15" height="20" stroke="black" stroke-width="1" fill="white" />
						<text x="7.5" y="15" text-anchor="middle">M</text>
						<text x="22.5" y="15" text-anchor="middle">R</text>
					</svg>
				</a>
				<a href="javascript:void(changeLayout('sample+result'))" class="btn" title="sample + result">
					<svg width="30" height="20" style="margin-top:3px;">
						<rect x="0" y="0" width="15" height="20" stroke="black" stroke-width="1" fill="white" />
						<rect x="15" y="0" width="15" height="20" stroke="black" stroke-width="1" fill="white" />
						<text x="7.5" y="15" text-anchor="middle">S</text>
						<text x="22.5" y="15" text-anchor="middle">R</text>
					</svg>
				</a>
				<a href="javascript:void(changeLayout('module+sample+result'))" class="btn" title="module + sample + result">
					<svg width="30" height="20" style="margin-top:3px;">
						<rect x="0" y="0" width="15" height="10" stroke="black" stroke-width="1" fill="white" />
						<rect x="15" y="0" width="15" height="20" stroke="black" stroke-width="1" fill="white" />
						<rect x="0" y="10" width="15" height="10" stroke="black" stroke-width="1" fill="white" />
						<text x="7.5" y="7.5" text-anchor="middle" font-size="5">M</text>
						<text x="7.5" y="18" text-anchor="middle" font-size="5">S</text>
						<text x="22.5" y="15" text-anchor="middle">R</text>
					</svg>								
				</a>
				<a href="javascript:void(changeLayout('all'))" class="btn" title="all">
					<svg width="20" height="20" style="margin-top:3px;">
						<rect x="0" y="0" width="10" height="10" stroke="black" stroke-width="1" fill="white" />
						<rect x="10" y="0" width="10" height="10" stroke="black" stroke-width="1" fill="white" />
						<rect x="0" y="10" width="10" height="10" stroke="black" stroke-width="1" fill="white" />
						<rect x="10" y="10" width="10" height="10" stroke="black" stroke-width="1" fill="white" />
					</svg>				
				</a>
			</div>
			<div style="float:right;padding:10px;padding-top:20px;">Layout : </div>

		</div>
		<div class="editor-area">
			<div class="editor-left">

				<?php include_once "include/component.editor.php" ?>

				<?php include_once "include/component.sample.php" ?>

			</div>

			<div class="editor-right">
                
				<?php include_once "include/component.information.php" ?>


				<?php include_once "include/component.result.php" ?>
			</div>
		</div>
	</div>
</div>
<?php include_once "include/component.script.php" ?>
<?php include_once "footer.php" ?>
