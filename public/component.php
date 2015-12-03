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

	if (window.localStorage)
	{
		window.localStorage.setItem("component.layout", type);
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

<div class="editor-container view-all <?php echo $isMy ? 'my' : '' ?>">
	<div class="editor-content has-toolbar has-statusbar">
		<div class="editor-toolbar">
			<div style='float:left;padding:10px;'>
				<a class="btn" id="library"><i class='icon-gear'></i> Setting</a>
			</div>
			<div style="float:right;padding:10px;padding-top:15px;">
				<?php if ($_GET['id']) { ?>
				<a class='btn' href="/view.php?id=<?php echo $_GET['id'] ?>">View</a>
				<?php } ?>			

				<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>

				<?php if ($isMy) { ?>
				<div class='group'>
					<a class="btn" onclick="savecode()">Save</a>
					<?php if ($_GET['id']) { ?>
					<a class="btn" onclick="deletecode()">Delete</a>
					<?php } ?>
				</div>
				<?php } else { ?>

				<?php } ?>

			</div>

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

		<div class="editor-statusbar">
			<div class="group" style="float:left; padding:10px;">

				<label style='margin-right:10px'><small><strong>Ctrl-F / Cmd-F</strong> Start searching</small> </label>
				<label style='margin-right:10px'><small><strong>Ctrl-G / Cmd-G</strong> Find Next</small> </label>
				<label style='margin-right:10px'><small><strong>Shift-Ctrl-F / Cmd-Option-F</strong> Replace</small> </label>
				
				<i class="icon-help"></i>
<!--
				<dl>
					<dt>Ctrl-F / Cmd-F</dt>
					<dd>Start searching</dd>
					<dt>Ctrl-G / Cmd-G</dt>
					<dd>Find next</dd>
					<dt>Shift-Ctrl-G / Shift-Cmd-G</dt>
					<dd>Find previous</dd>
					<dt>Shift-Ctrl-F / Cmd-Option-F</dt>
					<dd>Replace</dd>
					<dt>Shift-Ctrl-R / Shift-Cmd-Option-F</dt>
					<dd>Replace all</dd>
					<dt>Alt-F</dt>
					<dd>Persistent search (dialog doesn't autoclose, enter to find next, shift-enter to find previous)</dd>
				</dl>
-->
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
			<div style="float:right;padding:10px;padding-top:15px;">Layout : </div>

		</div>
</div>
<?php include_once "include/component.script.php" ?>
<?php include_once "footer.php" ?>
