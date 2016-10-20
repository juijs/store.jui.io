<?php $page_id = 'file';

include_once '../../bootstrap.php';
include_once 'common.php';

use Cz\Git\GitRepository;
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

$type = 'file'; // plugin name

include_once V2_PLUGIN."/$type/meta.php";
$meta = implode(PHP_EOL, $metaList);

// 레파지토리 자동 생성 
// 파일 디렉토리 생성
$repository = REPOSITORY.'/'.$_GET['id']. '/';
if (!is_dir("$repository/.git")) {
    @mkdir($repository, 0777, true);
	GitRepository::init($repository);
}




?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> -->
<title>JENNIFER UI: Store</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<?php echo $meta ?>
<link href="css/flat.css" rel="stylesheet" />
<link href="css/flat-responsive.css" rel="stylesheet" />
<link href="css/edit.css" rel="stylesheet" />
<link href="css/edit-responsive.css" rel="stylesheet" />
<link href="<?php echo V2_PLUGIN_URL ?>/<?php echo $type ?>/resource/editor.css" rel="stylesheet" />
<style type="text/css">
.CodeMirror {
	height: 100%;
}
.lint-error {font-family: arial; font-size: 70%; background: #ffa; color: #a00; padding: 2px 5px 3px; }
.lint-error-icon {color: white; background-color: red; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 7px;}
body { overflow: hidden; }
</style>

</head>
<body class="jui flat">
<div class="<?php echo $type ?>-editor editor-container view-all <?php echo $isMy ? 'my' : '' ?>">
    <div class="editor-content">
		<div class="editor-area view-only">
            <div class="editor-media-tab">
                <a class='tab add-directory-btn'><i class='icon-add-dir'></i> +Folder</a>
                <a class='tab add-file-btn'><i class='icon-report'></i> +File</a>
                <?php if (IS_HTTPS) { ?><a class='tab add-camera-btn'><i class='icon-screenshot'></i> Camera</a><?php } ?>
                <a class='tab add-url-btn'><i class='icon-clip'></i> URL</a>
                <!-- social media upload -->
            </div>
			<div class="editor-left">
				<?php include_once V2_PLUGIN."/$type/editor.php" ?>
			</div>
		</div>
        <div class='file-select'>
            <div class='file-list'>
            </div>
            <div class='select-button-area'>
                <a class='button select-send-btn'>Select</a>
            </div>
        </div>    
	</div>
</div>
<?php @include_once V2_PLUGIN."/$type/script.php" ?>
<?php include_once "include/script.php" ?>
<?php include_once "footer.php" ?>
<?php include_once "modals.php" ?>
</body>
</html>
